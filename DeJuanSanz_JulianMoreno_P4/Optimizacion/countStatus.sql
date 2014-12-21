
/*Consulta a ejecutar:*/
select customerid from customers where customerid not in (  select customerid  from orders  where status='Paid' );
/*Planificación: 
                            QUERY PLAN                             
-------------------------------------------------------------------
 Seq Scan on customers  (cost=3961.65..4490.81 rows=7046 width=4)
   Filter: (NOT (hashed SubPlan 1))
   SubPlan 1
     ->  Seq Scan on orders  (cost=0.00..3959.38 rows=909 width=4)
           Filter: ((status)::text = 'Paid'::text)
(5 rows)

*/
/*Consulta a ejecutar:*/
select customerid from (	select customerid  from customers 	union all 			select customerid from orders where status='Paid'	) as A	group by customerid	having count(*) =1;
/*Planificación: 
                                QUERY PLAN                                 
---------------------------------------------------------------------------
 HashAggregate  (cost=4537.41..4539.91 rows=200 width=4)
   Filter: (count(*) = 1)
   ->  Append  (cost=0.00..4462.40 rows=15002 width=4)
         ->  Seq Scan on customers  (cost=0.00..493.93 rows=14093 width=4)
         ->  Seq Scan on orders  (cost=0.00..3959.38 rows=909 width=4)
               Filter: ((status)::text = 'Paid'::text)
(6 rows)

*/
/*Consulta a ejecutar:*/
select customerid from customers except  select customerid  from orders  where status='Paid'; 
/*Planificación: 
                                    QUERY PLAN                                     
-----------------------------------------------------------------------------------
 HashSetOp Except  (cost=0.00..4640.83 rows=14093 width=4)
   ->  Append  (cost=0.00..4603.32 rows=15002 width=4)
         ->  Subquery Scan on "*SELECT* 1"  (cost=0.00..634.86 rows=14093 width=4)
               ->  Seq Scan on customers  (cost=0.00..493.93 rows=14093 width=4)
         ->  Subquery Scan on "*SELECT* 2"  (cost=0.00..3968.47 rows=909 width=4)
               ->  Seq Scan on orders  (cost=0.00..3959.38 rows=909 width=4)
                     Filter: ((status)::text = 'Paid'::text)
(7 rows)

*/
create index idx_status on orders(status)
/*Consulta a ejecutar:*/
select customerid from customers where customerid not in (  select customerid  from orders  where status='Paid' );
/*Planificación: 
                                     QUERY PLAN                                     
------------------------------------------------------------------------------------
 Seq Scan on customers  (cost=1498.79..2027.96 rows=7046 width=4)
   Filter: (NOT (hashed SubPlan 1))
   SubPlan 1
     ->  Bitmap Heap Scan on orders  (cost=19.46..1496.52 rows=909 width=4)
           Recheck Cond: ((status)::text = 'Paid'::text)
           ->  Bitmap Index Scan on idx_status  (cost=0.00..19.24 rows=909 width=0)
                 Index Cond: ((status)::text = 'Paid'::text)
(7 rows)

*/
/*Consulta a ejecutar:*/
select customerid from (	select customerid  from customers 	union all 			select customerid from orders where status='Paid'	) as A	group by customerid	having count(*) =1;
/*Planificación: 
                                       QUERY PLAN                                       
----------------------------------------------------------------------------------------
 HashAggregate  (cost=2074.55..2077.05 rows=200 width=4)
   Filter: (count(*) = 1)
   ->  Append  (cost=0.00..1999.54 rows=15002 width=4)
         ->  Seq Scan on customers  (cost=0.00..493.93 rows=14093 width=4)
         ->  Bitmap Heap Scan on orders  (cost=19.46..1496.52 rows=909 width=4)
               Recheck Cond: ((status)::text = 'Paid'::text)
               ->  Bitmap Index Scan on idx_status  (cost=0.00..19.24 rows=909 width=0)
                     Index Cond: ((status)::text = 'Paid'::text)
(8 rows)

*/
/*Consulta a ejecutar:*/
select customerid from customers except  select customerid  from orders  where status='Paid'; 
/*Planificación: 
                                          QUERY PLAN                                          
----------------------------------------------------------------------------------------------
 HashSetOp Except  (cost=0.00..2177.98 rows=14093 width=4)
   ->  Append  (cost=0.00..2140.47 rows=15002 width=4)
         ->  Subquery Scan on "*SELECT* 1"  (cost=0.00..634.86 rows=14093 width=4)
               ->  Seq Scan on customers  (cost=0.00..493.93 rows=14093 width=4)
         ->  Subquery Scan on "*SELECT* 2"  (cost=19.46..1505.61 rows=909 width=4)
               ->  Bitmap Heap Scan on orders  (cost=19.46..1496.52 rows=909 width=4)
                     Recheck Cond: ((status)::text = 'Paid'::text)
                     ->  Bitmap Index Scan on idx_status  (cost=0.00..19.24 rows=909 width=0)
                           Index Cond: ((status)::text = 'Paid'::text)
(9 rows)

*/
ANALYZE orders;
/*Consulta a ejecutar:*/
select customerid from customers where customerid not in (  select customerid  from orders  where status='Paid' );
/*Planificación: 
                                      QUERY PLAN                                       
---------------------------------------------------------------------------------------
 Seq Scan on customers  (cost=2331.34..2860.50 rows=7046 width=4)
   Filter: (NOT (hashed SubPlan 1))
   SubPlan 1
     ->  Bitmap Heap Scan on orders  (cost=367.47..2285.19 rows=18458 width=4)
           Recheck Cond: ((status)::text = 'Paid'::text)
           ->  Bitmap Index Scan on idx_status  (cost=0.00..362.86 rows=18458 width=0)
                 Index Cond: ((status)::text = 'Paid'::text)
(7 rows)

*/
/*Consulta a ejecutar:*/
select customerid from (	select customerid  from customers 	union all 			select customerid from orders where status='Paid'	) as A	group by customerid	having count(*) =1;
/*Planificación: 
                                        QUERY PLAN                                         
-------------------------------------------------------------------------------------------
 HashAggregate  (cost=3126.46..3128.96 rows=200 width=4)
   Filter: (count(*) = 1)
   ->  Append  (cost=0.00..2963.70 rows=32551 width=4)
         ->  Seq Scan on customers  (cost=0.00..493.93 rows=14093 width=4)
         ->  Bitmap Heap Scan on orders  (cost=367.47..2285.19 rows=18458 width=4)
               Recheck Cond: ((status)::text = 'Paid'::text)
               ->  Bitmap Index Scan on idx_status  (cost=0.00..362.86 rows=18458 width=0)
                     Index Cond: ((status)::text = 'Paid'::text)
(8 rows)

*/
/*Consulta a ejecutar:*/
select customerid from customers except  select customerid  from orders  where status='Paid'; 
/*Planificación: 
                                           QUERY PLAN                                            
-------------------------------------------------------------------------------------------------
 HashSetOp Except  (cost=0.00..3186.01 rows=14093 width=4)
   ->  Append  (cost=0.00..3104.63 rows=32551 width=4)
         ->  Subquery Scan on "*SELECT* 1"  (cost=0.00..634.86 rows=14093 width=4)
               ->  Seq Scan on customers  (cost=0.00..493.93 rows=14093 width=4)
         ->  Subquery Scan on "*SELECT* 2"  (cost=367.47..2469.77 rows=18458 width=4)
               ->  Bitmap Heap Scan on orders  (cost=367.47..2285.19 rows=18458 width=4)
                     Recheck Cond: ((status)::text = 'Paid'::text)
                     ->  Bitmap Index Scan on idx_status  (cost=0.00..362.86 rows=18458 width=0)
                           Index Cond: ((status)::text = 'Paid'::text)
(9 rows)

*/
