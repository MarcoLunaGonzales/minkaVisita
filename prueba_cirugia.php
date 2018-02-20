<?php

require("conexion.inc");
$sql_inserta=mysql_query("update estado_civil set nombre_estadocivil='Cojudo' where cod_estadocivil=2");
$numDatos=mysql_affected_rows();
if($numDatos==0){
	echo "no se afectaron datos";
}else{
	echo "se afectaron: ".$numDatos;
}

?>