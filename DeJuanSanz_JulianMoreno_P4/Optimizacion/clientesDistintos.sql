SELECT count(*) as cc FROM (
	SELECT DISTINCT customerid 
	FROM orderdetail JOIN orders using (orderid) 
	WHERE 
		EXTRACT(year FROM orders.orderdate)::int = 2012 AND 
		EXTRACT(month FROM orders.orderdate)::int = 04 AND 
		totalamount >= 100 GROUP BY customerid
	)AS foo;

/*EXPLAIN SELECT * FROM clientesDistintos;*/

/*
								QUERY PLAN                                                                                                                                
-----------------------------------------------------------------------------------------------------
 Aggregate  (cost=25017.60..25017.61 rows=1 width=0)
   ->  HashAggregate  (cost=25017.57..25017.58 rows=1 width=4)
         ->  HashAggregate  (cost=25017.56..25017.57 rows=1 width=4)
               ->  Hash Join  (cost=6686.25..25017.54 rows=9 width=4)
                     Hash Cond: (orderdetail.orderid = orders.orderid)
                     ->  Seq Scan on orderdetail  (cost=0.00..15321.60 rows=802560 width=4)
                     ->  Hash  (cost=6686.23..6686.23 rows=2 width=8)
                           ->  Seq Scan on orders  (cost=0.00..6686.23 rows=2 width=8)
                                 Filter: ((totalamount >= 100::numeric) AND (date_part('year'::text, (orderdate)::timestamp without time zone) = 2012::double precision) AND (date_part('month'::text, (orderdate)::timestamp without time zone) = 4::double precision))
(9 rows)
*/

CREATE INDEX idx_totalamount ON orders(totalamount);

/*EXPLAIN SELECT * FROM clientesDistintos;*/

/*
								QUERY PLAN                                                                                                               
--------------------------------------------------------------------------------------------------------
 Aggregate  (cost=22811.69..22811.70 rows=1 width=0)
   ->  HashAggregate  (cost=22811.67..22811.68 rows=1 width=4)
         ->  HashAggregate  (cost=22811.65..22811.66 rows=1 width=4)
               ->  Hash Join  (cost=4480.34..22811.63 rows=9 width=4)
                     Hash Cond: (orderdetail.orderid = orders.orderid)
                     ->  Seq Scan on orderdetail  (cost=0.00..15321.60 rows=802560 width=4)
                     ->  Hash  (cost=4480.32..4480.32 rows=2 width=8)
                           ->  Bitmap Heap Scan on orders  (cost=1126.90..4480.32 rows=2 width=8)
                                 Recheck Cond: (totalamount >= 100::numeric)
                                 Filter: ((date_part('year'::text, (orderdate)::timestamp without time zone) = 2012::double precision) AND (date_part('month'::text, (orderdate)::timestamp without time zone) = 4::double precision))
                                 ->  Bitmap Index Scan on idx_totalamount  (cost=0.00..1126.90 rows=60597 width=0)
                                       Index Cond: (totalamount >= 100::numeric)
(12 rows)

*/

CREATE INDEX idx_orderdate ON orders(orderdate);
/*
  								QUERY PLAN                                                                                                               
-----------------------------------------------------------------------------------------------------------
 Aggregate  (cost=22811.69..22811.70 rows=1 width=0)
   ->  HashAggregate  (cost=22811.67..22811.68 rows=1 width=4)
         ->  HashAggregate  (cost=22811.65..22811.66 rows=1 width=4)
               ->  Hash Join  (cost=4480.34..22811.63 rows=9 width=4)
                     Hash Cond: (orderdetail.orderid = orders.orderid)
                     ->  Seq Scan on orderdetail  (cost=0.00..15321.60 rows=802560 width=4)
                     ->  Hash  (cost=4480.32..4480.32 rows=2 width=8)
                           ->  Bitmap Heap Scan on orders  (cost=1126.90..4480.32 rows=2 width=8)
                                 Recheck Cond: (totalamount >= 100::numeric)
                                 Filter: ((date_part('year'::text, (orderdate)::timestamp without time zone) = 2012::double precision) AND (date_part('month'::text, (orderdate)::timestamp without time zone) = 4::double precision))
                                 ->  Bitmap Index Scan on idx_totalamount  (cost=0.00..1126.90 rows=60597 width=0)
                                       Index Cond: (totalamount >= 100::numeric)
(12 rows)

/*No tiene sentido plantearse más índices ya que no se acceden a más campos que fecha, total amount y claves primarias ya indexadas. */

