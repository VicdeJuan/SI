DROP TABLE newprices;
CREATE TEMP TABLE newprices
	AS SELECT orderdetail.orderid, orderdetail.prod_id, 
	round(products.price * 0.9804 ^ (2014 - EXTRACT(YEAR from orders.orderdate))::numeric, 2) AS price,
	orderdetail.quantity
	FROM orderdetail
		INNER JOIN orders ON orderdetail.orderid = orders.orderid
		INNER JOIN products ON orderdetail.prod_id = products.prod_id;

DROP TABLE orderdetail;
CREATE TABLE orderdetail AS SELECT * FROM newprices;
ALTER TABLE orderdetail ADD CONSTRAINT orders_fkey FOREIGN KEY (orderid)
      REFERENCES orders (orderid) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE orderdetail ADD CONSTRAINT product_fkey FOREIGN KEY (prod_id)
      REFERENCES inventory (prod_id) MATCH SIMPLE
      ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE orderdetail
  OWNER TO alumnodb;
COMMENT ON COLUMN orderdetail.price IS 'price without taxes when the order was paid';
