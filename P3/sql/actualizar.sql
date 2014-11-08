
/* Contar los registros repetidos para poder añadir una clave primaria */

DELETE FROM orderdetail WHERE 	(orderid,prod_id) IN (SELECT orderid,prod_id FROM orderdetail T2 GROUP BY orderid,prod_id HAVING count(*) > 1); 



/*  Obtenemos los huecos en la secuencia de id's de inventory */
CREATE or replace VIEW faltan_id_inventory AS (SELECT  prod_id + 1 as id FROM inventory mo 
	WHERE NOT EXISTS (SELECT  NULL FROM    inventory mi	WHERE   mi.prod_id = mo.prod_id + 1)
	ORDER BY prod_id);

/*  Rellenamos  esos huecos, para poder añadir claves foráneas que referencien a filas que existan. */
INSERT INTO inventory (select *,0,0 from faltan_id_inventory where  faltan_id_inventory.id <= 6656);

/* Es necesario hacerlo 2 veces, porque la vista esta no obtiene todos los que debería. */
CREATE or replace VIEW faltan_id_inventory AS (SELECT  prod_id + 1 as id FROM inventory mo 
	WHERE NOT EXISTS (SELECT  NULL FROM    inventory mi	WHERE   mi.prod_id = mo.prod_id + 1)
	ORDER BY prod_id);
INSERT INTO inventory (select *,0,0 from faltan_id_inventory where  faltan_id_inventory.id <= 6656);


/* contar id's que no existen en inventory y si en orderdetail (323) inicialmente, ahora debería ser 0 */
select count(*) from (select orderdetail.prod_id,orderdetail.prod_id from faltan_id_inventory, orderdetail where orderdetail.prod_id = faltan_id_inventory.id group by orderdetail.prod_id) as foo;


/*  Sacadas de PGAdmin */


/* Añadidas las claves primarias y secundarias*/
ALTER TABLE orderdetail ADD CONSTRAINT orderdetail_pkey PRIMARY KEY (orderid, prod_id);


ALTER TABLE orderdetail
  ADD CONSTRAINT orders_fkey FOREIGN KEY (orderid) REFERENCES orders (orderid)
  ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE orderdetail
   ADD CONSTRAINT product_fkey FOREIGN KEY (prod_id) REFERENCES inventory (prod_id)
    ON UPDATE CASCADE ON DELETE CASCADE;



/*  Actor que está fatal: ¿¿numpartitipation wtf?? */

ALTER TABLE imdb_actormovies
  ADD CONSTRAINT movie_fkey FOREIGN KEY (movieid) REFERENCES imdb_movies (movieid)
   ON UPDATE CASCADE ON DELETE NO ACTION;

ALTER TABLE imdb_actormovies
  ADD CONSTRAINT actor_fkey FOREIGN KEY (actorid) REFERENCES imdb_actors (actorid)
   ON UPDATE CASCADE ON DELETE NO ACTION;


/* El id del comprador en el producto. */

ALTER TABLE orders
  ADD CONSTRAINT customer_fkey FOREIGN KEY (customerid) REFERENCES customers (customerid)
   ON UPDATE CASCADE ON DELETE NO ACTION;


/* Invetory y order detail clave foránea de products. */


ALTER TABLE inventory
	ADD CONSTRAINT product_fkey FOREIGN KEY (prod_id) REFERENCES products (prod_id)
	ON UPDATE CASCADE ON DELETE NO ACTION;