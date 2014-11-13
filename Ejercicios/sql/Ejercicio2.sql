/* Versión 1: SQL puro

MAL: 
create or replace view top_protagonicos as 
	select agno,actor_id,nombre,count(ord) as totalpro  
		from reparto,actor,pelicula 
		where pelicula.id = reparto.pelicula_id AND 
			reparto.actor_id = actor.id AND 
			ord = 1 
		group by agno, actor_id, nombre 
		order by agno desc; 

create or replace view top_no_protagonicos as 
	select agno,actor_id,nombre,count(ord) as totalnopro 
		from reparto,actor,pelicula 
		where pelicula.id = reparto.pelicula_id AND 
			reparto.actor_id = actor.id AND 
			ord > 1 
		group by agno, actor_id, nombre 
		order by agno desc; 

select top_protagonicos.actor_id,top_protagonicos.nombre,top_protagonicos.agno,totalpro as maximo 
	from top_no_protagonicos join top_protagonicos 
		on top_protagonicos.actor_id = top_no_protagonicos.actor_id 
	where totalpro > totalnopro AND top_protagonicos.agno = top_no_protagonicos.agno;  */

create or replace view masprotas AS
	select menosprotas.nombre as nombre, masprotas.actor_id as actor_id, masprotas.totalpro as maximo
		from 
			(select actor_id,nombre,count(*) as totalpro 
				from reparto join actor on actor_id = id 
				where ord > 1
				group by actor_id,nombre
				order by totalpro desc) as menosprotas 
		join 
			(select actor_id,count(*) as totalpro 
				from reparto join actor on actor_id = id 
				where ord = 1 
				group by actor_id 
				order by totalpro desc) as masprotas
		on masprotas.actor_id = menosprotas.actor_id 
		where masprotas.totalpro > menosprotas.totalpro 
		order by masprotas.totalpro desc;


create or replace view ids_max AS
	select masprotas.actor_id,masprotas.nombre,agno,count(*) as maximo 
		from masprotas, reparto, pelicula 
		where reparto.actor_id = masprotas.actor_id AND
			reparto.pelicula_id = pelicula.id AND 
			ord = 1 
		group by masprotas.actor_id,masprotas.nombre,agno;

/* Hay actores repetidos xk hay empate entre varios años que han sido el mismo número de veces protagónicos y no se eliminarlos... */

select DISTINCT ON (ids_max.actor_id,ids_max.maximo) ids_max.*
		from 
			(select actor_id,max(maximo) from ids_max group by actor_id) 
		as just_ids
		left outer join	ids_max on ids_max.actor_id = just_ids.actor_id 
		where ids_max.maximo = just_ids.max 
		order by ids_max.maximo desc,ids_max.actor_id;

/* Versión 2: Procedimiento almacenado de PostgreSQL */ 

CREATE OR REPLACE FUNCTION Ejercicio2()
  RETURNS TABLE(actor_id int, nombre char(70), agno numeric, maximo bigint) AS
  '
  DECLARE
  	iterator record;
  	temporal record;
  	retvalue record;
	BEGIN
  		FOR iterator IN SELECT * FROM (select ids_max.actor_id,max(ids_max.maximo) from ids_max group by ids_max.actor_id) as just_ids LOOP
			return QUERY select * from ids_max where ids_max.actor_id = iterator.actor_id order by ids_max.maximo desc limit 1;
		END LOOP;
	return ;
	END;'
language 'plpgsql';

SELECT * FROM Ejercicio2() order by maximo desc,actor_id;


/* Versión 3: Script de php */

