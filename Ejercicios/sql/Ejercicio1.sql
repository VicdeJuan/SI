/* Creación de la vista: */
create or replace view top10protas as select actor_id,sum(ord) as num_protagonista 
	from reparto,actor,pelicula
	where pelicula.id = reparto.pelicula_id AND 
	 	reparto.actor_id = actor.id AND
	 	 reparto.ord = 1 AND
	 	 pelicula.agno > 1980 
	group by actor_id
	order by num_protagonista
	desc limit 10;


/* Creación de la tabla auxiliar */
drop table TOP10;
CREATE TABLE  TOP10 as select actor_id,sum(ord) as num_protagonista 
	from reparto,actor,pelicula
	where pelicula.id = reparto.pelicula_id AND 
	 	reparto.actor_id = actor.id AND
	 	 reparto.ord = 1 AND
	 	 pelicula.agno > 1980 
	group by actor_id
	order by num_protagonista
	desc limit 10;

/* Trigger. */



DROP FUNCTION modificacion2() cascade;
CREATE FUNCTION modificacion2() RETURNS TRIGGER AS '
DECLARE 
	boolaux boolean;
  	iterator record;
BEGIN
	boolaux = FALSE;
	FOR iterator IN SELECT * FROM TOP10 LOOP
		if (iterator.actor_id = NEW.actor_id) THEN
			boolaux = TRUE;
		end if;
	END LOOP;
	if boolaux = true THEN
		UPDATE TOP10 SET num_protagonista = num_protagonista + 1 where actor_id = NEW.actor_id;
	else
		/**
		 * Sentencias de recrear la tabla.
		 */
		drop table top10;
		CREATE TABLE  TOP10 as select actor_id,sum(ord) as num_protagonista  from reparto,actor,pelicula where pelicula.id = reparto.pelicula_id AND   	reparto.actor_id = actor.id AND  	 reparto.ord = 1 AND  	 pelicula.agno > 1980  group by actor_id order by num_protagonista desc limit 10;
	end if;
RETURN NEW; END;'LANGUAGE 'plpgsql';


DROP TRIGGER t_modificacion2 on reparto;
CREATE TRIGGER t_modificacion2 AFTER INSERT ON reparto
FOR EACH ROW EXECUTE PROCEDURE modificacion2();
