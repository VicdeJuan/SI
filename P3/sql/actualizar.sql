
/* Borrar los registros repetidos para poder añadir una clave primaria */
DELETE FROM orderdetail WHERE 	(orderid,prod_id) IN (SELECT orderid,prod_id FROM orderdetail T2 GROUP BY orderid,prod_id HAVING count(*) > 1); 


/*  Rellenamos  esos huecos, para poder añadir claves foráneas que referencien a filas que existan. */
INSERT INTO inventory (select prod_id,0,0 from products outter left join inventory using (prod_id) where stock is null);



ALTER TABLE orderdetail ADD CONSTRAINT orderdetail_pkey PRIMARY KEY (orderid, prod_id);
ALTER TABLE orderdetail
  ADD CONSTRAINT orders_fkey FOREIGN KEY (orderid) REFERENCES orders (orderid)
  ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE orderdetail
   ADD CONSTRAINT product_fkey FOREIGN KEY (prod_id) REFERENCES products (prod_id)
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




ALTER TABLE customers ADD CONSTRAINT unique_email UNIQUE (email);

CREATE OR REPLACE FUNCTION alter_sequences () RETURNS void
AS $$
DECLARE 
  order_seq orders.orderid%TYPE;
  customer_seq customers.customerid%TYPE;
BEGIN
  select orderid into order_seq from orders order by orderid desc limit 1;
  execute 'alter sequence orders_orderid_seq restart with  ' || order_seq +1;

  select customerid into customer_seq from customers order by customerid desc limit 1;
  execute 'alter sequence customers_customerid_seq restart with  ' || customer_seq +1;

  END;
$$ LANGUAGE 'plpgsql';

SELECT * FROM alter_sequences();




ALTER TABLE imdb_movies ADD COLUMN url_to_img character varying(255);
UPDATE imdb_movies SET url_to_img="http://img2.wikia.nocookie.net/__cb20110130000348/tarzan/images/5/50/Tarzan.jpg";




/************************** Separación de tablas de géneros, idiomas y países ****************/

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

/************************************ Funciones y procedimientos  *******************************/

CREATE OR REPLACE FUNCTION getTopVentas(int) RETURNS TABLE(Año int,Pelicula character varying(255), venta bigint) AS
$$
DECLARE 
year_var record;
BEGIN
FOR year_var in (select distinct(EXTRACT(year from orders.orderdate)) as year from orders where EXTRACT(year from orders.orderdate) >= $1 order by year) LOOP
  return query select distinct on (year,quantity) EXTRACT(year from orders.orderdate)::int as year,movietitle,sum(quantity) as quantity from orders join orderdetail using(orderid) join products using (prod_id) join imdb_movies using (movieid) where EXTRACT(year from orders.orderdate)::int = year_var.year group by EXTRACT(year from orders.orderdate)::int, movietitle order by quantity desc limit 1;
END LOOP;
END;
$$
language 'plpgsql';

/* Para solo la película más vendida:
select distinct on (year,quantity) EXTRACT(year from orders.orderdate)::int as year,sum(quantity) as quantity, movietitle 
  from orders
      join orderdetail using(orderid) 
      join products using (prod_id) 
      join imdb_movies using (movieid) 
  where EXTRACT(year from orders.orderdate)::int = 2006 
  group by EXTRACT(year from orders.orderdate)::int, movietitle 
  order by quantity desc limit 1; */



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
CREATE TRIGGER updInventory BEFORE UPDATE OF status ON orders 
  FOR EACH ROW EXECUTE PROCEDURE updInventory();
        
