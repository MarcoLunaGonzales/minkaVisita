<?php
require('conexion.inc');
$sql="select f.codigo_funcionario, f.cod_ciudad from funcionarios f 
	where f.estado=1 and f.cod_cargo=1011";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codFuncionario=$dat[0];
	$codCiudad=$dat[1];
	
	mysql_query("insert into funcionarios_agencias values($codFuncionario, $codCiudad)");

}

echo "termino";
?>