<?php

require("conexion.inc");

$sql="select id, cod_medico, cod_visitador, cantidad, codigo_muestra, id_for 
from banco_muestra_cantidad_visitador b group by 
cod_medico, cod_visitador, codigo_muestra";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$id=$dat[0];
	$codMed=$dat[1];
	$codVis=$dat[2];
	$codMM=$dat[4];
	$idFor=$dat[5];
	
	$sqlDel="delete from banco_muestra_cantidad_visitador where id_for=$idFor and
	cod_medico=$codMed and cod_visitador=$codVis and codigo_muestra='$codMM' and id<>$id";
	$respDel=mysql_query($sqlDel);
	
	echo $sqlDel;
}

?>