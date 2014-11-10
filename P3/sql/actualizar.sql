
/* Borrar los registros repetidos para poder añadir una clave primaria */
DELETE FROM orderdetail WHERE 	(orderid,prod_id) IN (SELECT orderid,prod_id FROM orderdetail T2 GROUP BY orderid,prod_id HAVING count(*) > 1); 


/*  Obtenemos los huecos en la secuencia de id's de inventory */
/*  Rellenamos  esos huecos, para poder añadir claves foráneas que referencien a filas que existan. */
CREATE or replace VIEW faltan_id_inventory AS (SELECT  prod_id + 1 as id FROM inventory mo 
  WHERE NOT EXISTS (SELECT  NULL FROM    inventory mi WHERE   mi.prod_id = mo.prod_id + 1)
  ORDER BY prod_id);
INSERT INTO inventory (select *,0,0 from faltan_id_inventory where  faltan_id_inventory.id <= 6656);
/* Es necesario hacerlo 2 veces, porque la vista esta no obtiene todos los que debería. */
CREATE or replace VIEW faltan_id_inventory AS (SELECT  prod_id + 1 as id FROM inventory mo 
	WHERE NOT EXISTS (SELECT  NULL FROM    inventory mi	WHERE   mi.prod_id = mo.prod_id + 1)
	ORDER BY prod_id);
INSERT INTO inventory (select *,0,0 from faltan_id_inventory where  faltan_id_inventory.id <= 6656);


/* contar id's que no existen en inventory y si en orderdetail (323) inicialmente, ahora debería ser 0 */
/*select count(*) from (select orderdetail.prod_id,orderdetail.prod_id from faltan_id_inventory, orderdetail where orderdetail.prod_id = faltan_id_inventory.id group by orderdetail.prod_id) as foo;*/



ALTER TABLE orderdetail ADD CONSTRAINT orderdetail_pkey PRIMARY KEY (orderid, prod_id);
ALTER TABLE orderdetail
  ADD CONSTRAINT orders_fkey FOREIGN KEY (orderid) REFERENCES orders (orderid)
  ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE orderdetail
   ADD CONSTRAINT product_fkey FOREIGN KEY (prod_id) REFERENCES inventory (prod_id)
    ON UPDATE CASCADE ON DELETE CASCADE;



ALTER TABLE imdb_actormovies
  ADD CONSTRAINT movie_fkey FOREIGN KEY (movieid) REFERENCES imdb_movies (movieid)
   ON UPDATE CASCADE ON DELETE NO ACTION;
ALTER TABLE imdb_actormovies
  ADD CONSTRAINT actor_fkey FOREIGN KEY (actorid) REFERENCES imdb_actors (actorid)
   ON UPDATE CASCADE ON DELETE NO ACTION;



ALTER TABLE orders
  ADD CONSTRAINT customer_fkey FOREIGN KEY (customerid) REFERENCES customers (customerid)
   ON UPDATE CASCADE ON DELETE NO ACTION;


/* Invetory y order detail clave foránea de products. */


ALTER TABLE inventory
	ADD CONSTRAINT product_fkey FOREIGN KEY (prod_id) REFERENCES products (prod_id)
	ON UPDATE CASCADE ON DELETE NO ACTION;









/*SEPARAR TABLAS */



/** Generos: */
CREATE TABLE genres(
  genreid serial UNIQUE,
  genre character varying NOT NULL);

ALTER TABLE genres ADD CONSTRAINT genre_pkey PRIMARY KEY (genreid);
INSERT INTO genres (genre) SELECT DISTINCT genre FROM imdb_moviegenres;

ALTER TABLE imdb_moviegenres ADD COLUMN genreid Integer;

CREATE OR REPLACE FUNCTION change_genres () RETURNS void
AS ' 
DECLARE 
  temporalgenre record;
BEGIN

FOR temporalgenre IN SELECT * FROM genres LOOP
  UPDATE imdb_moviegenres SET genreid=temporalgenre.genreid WHERE imdb_moviegenres.genre = temporalgenre.genre;
END LOOP;
END;
'LANGUAGE 'plpgsql';

SELECT * FROM change_genres();

ALTER TABLE imdb_moviegenres DROP COLUMN genre;
ALTER TABLE imdb_moviegenres ADD CONSTRAINT moviegenre_fkey FOREIGN KEY (genreid) REFERENCES genres (genreid);


/** Paises: */

CREATE TABLE countries(
  countryid serial UNIQUE,
  country character varying NOT NULL);

ALTER TABLE countries ADD CONSTRAINT country_pkey PRIMARY KEY (countryid);
INSERT INTO countries (country) SELECT DISTINCT country FROM imdb_moviecountries;

ALTER TABLE imdb_moviecountries ADD COLUMN countryid Integer;

CREATE OR REPLACE FUNCTION change_countries () RETURNS void
AS ' 
DECLARE 
  temporalcountry record;
BEGIN

FOR temporalcountry IN SELECT * FROM countries LOOP
  UPDATE imdb_moviecountries SET countryid=temporalcountry.countryid WHERE imdb_moviecountries.country = temporalcountry.country;
END LOOP;
END;
'LANGUAGE 'plpgsql';

SELECT * FROM change_countries();

ALTER TABLE imdb_moviecountries DROP COLUMN country;
ALTER TABLE imdb_moviecountries ADD CONSTRAINT moviecountry_fkey FOREIGN KEY (countryid) REFERENCES countries (countryid);



/** Languages */

CREATE TABLE languages(
  languageid serial UNIQUE,
  language character varying NOT NULL);

ALTER TABLE languages ADD CONSTRAINT language_pkey PRIMARY KEY (languageid);
INSERT INTO languages (language) SELECT DISTINCT language FROM imdb_movielanguages;

ALTER TABLE imdb_movielanguages ADD COLUMN languageid Integer;

CREATE OR REPLACE FUNCTION change_languages () RETURNS void
AS ' 
DECLARE 
  temporallanguage record;
BEGIN

FOR temporallanguage IN SELECT * FROM languages LOOP
  UPDATE imdb_movielanguages SET languageid=temporallanguage.languageid WHERE imdb_movielanguages.language = temporallanguage.language;
END LOOP;
END;
'LANGUAGE 'plpgsql';

SELECT * FROM change_languages();

ALTER TABLE imdb_movielanguages DROP COLUMN language;
ALTER TABLE imdb_movielanguages ADD CONSTRAINT movielanguage_fkey FOREIGN KEY (languageid) REFERENCES languages (languageid);




/*Para psql 9.1 (versión de los laboratorios) */
UPDATE imdb_movies SET year=substr(year,0,5) WHERE length(year) > 4;
ALTER TABLE imdb_movies ALTER COLUMN year SET DATA TYPE Integer USING year::Integer;

/* ¿Pasa algo porque sea sql y no ¿pgpgpsgpspgsl??*/

CREATE OR REPLACE FUNCTION getTopVentas(int) RETURNS TABLE(Año int,Pelicula text, venta bigint) AS
'SELECT movies_filtered.year AS año, movies_filtered.movietitle AS pelicula, movies_filtered.quantity AS venta 
FROM 
  (SELECT movies.year,movies.movietitle,COUNT(orderdetail.quantity) AS quantity 
  FROM 
    imdb_movies AS movies, 
    products,
    orderdetail 
  WHERE products.movieid=movies.movieid AND orderdetail.prod_id = products.prod_id AND movies.year>=$1
  GROUP BY movies.movieid ORDER BY quantity DESC LIMIT 1) 
AS movies_filtered;' 
language 'sql';



/** setOrderAmount */

CREATE OR REPLACE FUNCTION setOrderAmount() RETURNS void
AS '
DECLARE 
  tmp record;

BEGIN
UPDATE orders SET netamount = 0;
FOR tmp IN SELECT orderid,SUM(price * quantity) FROM orderdetail GROUP BY orderid LOOP
  UPDATE orders SET netamount=tmp.sum WHERE orders.orderid = tmp.orderid;
END LOOP;
UPDATE orders SET totalamount = netamount*(tax/100)+netamount;
END;
'LANGUAGE 'plpgsql';

SELECT * FROM setOrderAmount();

/*
Hay 5 orders que no tienen orderdetail....
select * from orders where totalamount=0 limit 10;                                                                                           
 orderid | orderdate  | customerid | netamount | tax |      totalamount       |  status   
---------+------------+------------+-----------+-----+------------------------+-----------
   31502 | 2009-03-14 |       2413 |         0 |  15 | 0.00000000000000000000 | Processed
   41847 | 2007-10-15 |       3179 |         0 |  15 | 0.00000000000000000000 | Shipped
   62797 | 2008-04-30 |       4822 |         0 |  15 | 0.00000000000000000000 | Shipped
   68893 | 2010-03-25 |       5291 |         0 |  15 | 0.00000000000000000000 | Processed
  100924 | 2011-10-03 |       7793 |         0 |  18 | 0.00000000000000000000 | Shipped
(5 rows)
 */


