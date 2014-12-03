#/bin/zsh
export PGHOST=localhost && export PGUSER=alumnodb && export PGPASSWORD=alumnodb;

create=$1
if [[ $create == 1 ]]; then
	dropdb si1
	createdb si1
	psql si1 < "../../dump_v1.0-P4.sql"
fi

consulta1="select customerid from customers where customerid not in (  select customerid  from orders  where status='Paid' );"
consulta2="select customerid from (	select customerid  from customers 	union all 			select customerid from orders where status='Paid'	) as A	group by customerid	having count(*) =1;"
consulta3="select customerid from customers except  select customerid  from orders  where status='Paid'; "


function printall {
	echo "/*Consulta a ejecutar:*/" >> ../countStatus.sql
	echo "$consulta1" >> ../countStatus.sql
	echo "/*Planificación: " >> ../countStatus.sql
	psql si1 -c "explain $consulta1" >>../countStatus.sql
	echo "*/" >> ../countStatus.sql

	echo "/*Consulta a ejecutar:*/" >> ../countStatus.sql
	echo "$consulta2" >> ../countStatus.sql
	echo "/*Planificación: " >> ../countStatus.sql
	psql si1 -c "explain $consulta2" >>../countStatus.sql
	echo "*/" >> ../countStatus.sql

	echo "/*Consulta a ejecutar:*/" >> ../countStatus.sql
	echo "$consulta3" >> ../countStatus.sql
	echo "/*Planificación: " >> ../countStatus.sql
	psql si1 -c "explain $consulta3" >>../countStatus.sql
	echo "*/" >> ../countStatus.sql

}

echo "" > ../countStatus.sql

printall

createindex="create index idx_status on orders(status)"

echo "$createindex" >> ../countStatus.sql
psql si1 -c "$createindex"

	
printall

analyze="ANALYZE orders;"
echo "$analyze" >> ../countStatus.sql
psql si1 -c "$analyze"

printall

