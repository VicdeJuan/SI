#!/bin/bash

tbold=$(tput bold)
treset=$(tput sgr0)
tred=$(tput setaf 1)
tgreen=$(tput setaf 2)
tyellow=$(tput setaf 3)

host=localhost
path=/Transacciones/borraClienteMal.php

customerId=693

# export PGUSER="alumnodb"
# export PGPASSWORD="alumnodb"
export PGDATABASE="si1"

function show_locks()
{
	echo
	echob "Existing locks: "
	psql <<EOF
SELECT locktype, relation::regclass, mode,
	transactionid AS tid, virtualtransaction AS vtid, pid, granted
	FROM pg_catalog.pg_locks l
		LEFT JOIN pg_catalog.pg_database db
		ON db.oid = l.database
	WHERE (db.datname = '${PGDATABASE}' OR db.datname IS NULL)
		AND NOT pid = pg_backend_pid();
EOF

	echo
	echob "Locked queries: "
	psql <<EOF
SELECT left(query, 60) AS query, state, waiting, pid
	FROM pg_stat_activity
	WHERE datname='${PGDATABASE}'
	AND NOT (state='idle' OR pid=pg_backend_pid());
EOF
}

function echob() {
	echo ${tbold}$@${treset}
}

if [ "$1" == "info" ]; then
	echo "Showing DB locks information"
	show_locks
	exit
fi

read -p "${tbold}Reset database? ${treset}" yesno
if [[ $yesno =~ ^[Yy]$ ]]; then
	dropdb $PGDATABASE &> /dev/null
	createdb $PGDATABASE &> /dev/null
	psql < ../dump_v1.0-P4.sql &> /dev/null
	psql < updPromo.sql &> /dev/null
fi

echob "Requesting customer deletion via PHP script..."
(curl -sS "${host}${path}?enviado=1&PDO=1&id=${customerId}&bien=1&block=1" -o /tmp/siout && echob "CURL request finished" && show_locks) &

sleep 1

echob "Update customers: set customers[${customerId}].promo = 20"
cat > /tmp/query <<EOF
UPDATE customers
	SET promo = 20
	WHERE customerid = $customerId;
EOF
(psql -f /tmp/query && echob "Finished query" && show_locks) &

show_locks

echo
echob "Showing customerid[${customerId}] status: "
psql <<EOF
SELECT customerId, promo
	FROM customers
	WHERE customerId = $customerId;
EOF

echob "${tgreen}Intro to finish..."
read out

