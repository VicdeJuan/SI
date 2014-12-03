#!/bin/python
export PGHOST=localhost && export PGUSER=alumnodb && export PGPASSWORD=alumnodb

psql si1 -c "DROP INDEX idx_totalamount;"

echo "no INDEX" > results_listaClientesMes
echo -n "prepare on: " >> results_listaClientesMes
wget "http://localhost/?mes=04&anio=2012&minimo=300&intervalo=5&iter=1000&prepare=on&fecha=Enviar" -O aux
cat aux | awk -v FS="Tiempo: " '{print $2}' | awk -v FS="</p>" '{print $1}' | tr -d "\n" >> results_listaClientesMes
echo " " >> results_listaClientesMes
echo -n "prepare off: " >> results_listaClientesMes
wget "http://localhost/?mes=04&anio=2012&minimo=300&intervalo=5&iter=1000&fecha=Enviar" -O aux
cat aux | awk -v FS="Tiempo: " '{print $2}' | awk -v FS="</p>" '{print $1}' | tr -d "\n" >> results_listaClientesMes
echo " " >> results_listaClientesMes


psql si1 -c "CREATE INDEX idx_totalamount ON orders(totalamount);"


echo "\n\nINDEX" >> results_listaClientesMes
echo -n "prepare on: " >> results_listaClientesMes
wget "http://localhost/?mes=04&anio=2012&minimo=300&intervalo=5&iter=1000&prepare=on&fecha=Enviar" -O aux
cat aux | awk -v FS="Tiempo: " '{print $2}' | awk -v FS="</p>" '{print $1}' | tr -d "\n" >> results_listaClientesMes
echo " " >> results_listaClientesMes

echo -n "prepare off: " >> results_listaClientesMes
wget "http://localhost/?mes=04&anio=2012&minimo=300&intervalo=5&iter=1000&fecha=Enviar" -O aux
cat aux | awk -v FS="Tiempo: " '{print $2}' | awk -v FS="</p>" '{print $1}' | tr -d "\n" >> results_listaClientesMes

rm aux