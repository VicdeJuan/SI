
/* Borrar los registros repetidos para poder añadir una clave primaria */
DELETE FROM orderdetail WHERE 	(orderid,prod_id) IN (SELECT orderid,prod_id FROM orderdetail T2 GROUP BY orderid,prod_id HAVING count(*) > 1); 


/*  Obtenemos los huecos en la secuencia de id's de inventory */
/*  Rellenamos  esos huecos, para poder añadir claves foráneas que referencien a filas que existan. */
INSERT INTO inventory (select prod_id,0,0 from products outter left join inventory using (prod_id) where stock is null);



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
/* Procedimiento normal. Juntado para la función:


create or replace view orders_years as (
  select orderid, EXTRACT(year from orders.orderdate)::int as year 
  from orders 
  where EXTRACT(year from orders.orderdate)::int >= 2009);

create or replace view prod_years as (
  select orderid,prod_id,year,quantity 
  from orders_years join orderdetail using (orderid));

create or replace view topventas_ids as (
  select distinct on (year,quantity) year_max.year as year,prod_id,quantity 
    from 
      (
        select year,max(quantity) as max from prod_years group by year)
      as year_max 
      join prod_years on max = quantity and year_max.year = prod_years.year);

select topventas_ids.year,movietitle,quantity from 
  topventas_ids join 
  products using (prod_id) join 
  imdb_movies using(movieid);

*/
CREATE OR REPLACE FUNCTION getTopVentas(int) RETURNS TABLE(Año int,Pelicula text, venta integer) AS
'

select topventas_ids.year,movietitle,quantity 
  from
    (
      select distinct on (year,quantity) year_max.year as year,prod_id,quantity 
      from 
        (
        select year,max(quantity) as max 
        from 
          (
          select orderid,prod_id,year,quantity 
          from 
            (
              select orderid, EXTRACT(year from orders.orderdate)::int as year 
                from orders where EXTRACT(year from orders.orderdate)::int >= $1
            ) as orders_years 
            join orderdetail using (orderid)
        ) as prod_years group by year
      ) as year_max 
      join 
        (
          select orderid,prod_id,year,quantity 
          from 
            (
              select orderid, EXTRACT(year from orders.orderdate)::int as year 
              from orders 
              where EXTRACT(year from orders.orderdate)::int >= $1
            ) as orders_years
          join orderdetail using (orderid)
        ) as prod_years 
      on max = quantity and year_max.year = prod_years.year
    ) as topventas_ids join products using (prod_id) join imdb_movies using(movieid) order by topventas_ids.year desc;
' 
language 'sql';

/* Para solo la peli más vendida:

create or replace view orders_years as (select orderid, EXTRACT(year from orders.orderdate)::int as year from orders where EXTRACT(year from orders.orderdate)::int = 2011);

select 2011 as year,movietitle,max from (select prod_id,max(quantity) as max from (select * from orders_years join orderdetail using (orderid)) as foo group by prod_id order by max desc limit 1) as topventa join products using (prod_id) join imdb_movies using (movieid);

 */



/** setOrderAmount */

CREATE OR REPLACE FUNCTION setOrderAmount() RETURNS void
AS '
DECLARE 
  tmp record;

BEGIN

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

/** getTopMonths **/

CREATE OR REPLACE FUNCTION getTopMonths(bigint, numeric)
  RETURNS TABLE(year int, month int, sales bigint, revenue numeric) AS
  '
    SELECT 
      EXTRACT(year FROM orders.orderdate)::int as year, 
      EXTRACT(month FROM orders.orderdate)::int as month,
      SUM(quantity) AS sales, SUM(totalamount) AS revenue 

      FROM orders 
        LEFT JOIN orderdetail ON orderdetail.orderid = orders.orderid
      GROUP BY EXTRACT(year FROM orders.orderdate), EXTRACT(month FROM orders.orderdate)
      HAVING SUM(quantity) >= $1 OR sum(totalamount) >= $2;
  '
language 'sql';

/** updInventory **/

CREATE OR REPLACE FUNCTION updInventory() RETURNS trigger as $updInventory$
  DECLARE 
    orderItem record;
    itemStock integer;
  BEGIN
    FOR orderItem IN SELECT prod_id, quantity FROM orderdetail WHERE orderdetail.orderid = NEW.orderID 
    LOOP  
      SELECT inventory.stock INTO itemStock::int FROM inventory WHERE inventory.prod_id = orderItem.prod_id;

      IF itemStock - orderItem.quantity < 0 THEN
        RAISE EXCEPTION 'Not enough items of product %', orderItem.prod_id;
      ELSIF itemStock - orderItem.quantity = 0 THEN
        INSERT INTO alerts VALUES (DEFAULT, 'stock zero', orderItem.prod_id);
      END IF;
      
      UPDATE inventory SET 
        stock = stock - orderItem.quantity,
        sales = sales + orderItem.quantity
        WHERE inventory.prod_id = orderItem.prod_id;
    END LOOP;
    NEW.orderdate := current_timestamp;
    RETURN NEW;
  END;
$updInventory$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS updInventory ON orders;
CREATE TRIGGER updInventory BEFORE INSERT ON orders 
  FOR EACH ROW EXECUTE PROCEDURE updInventory();
        


