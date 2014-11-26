DROP TABLE newprices;
CREATE TEMP TABLE newprices
	AS SELECT orderdetail.orderid, orderdetail.prod_id, 
	round(products.price * 0.9804 ^ (2014 - EXTRACT(YEAR from orders.orderdate))::numeric, 2) AS price,
	orderdetail.quantity
	FROM orderdetail
		INNER JOIN orders ON orderdetail.orderid = orders.orderid
		INNER JOIN products ON orderdetail.prod_id = products.prod_id;

ALTER TABLE newprices ADD constraint newprices_pkey PRIMARY kEY (orderid,prod_id);

UPDATE orderdetail SET price=newprices.price from newprices WHERE orderdetail.orderid = newprices.orderid; 