<?php

require("conexion.inc");

$sql="LOAD DATA INFILE 'archivosCUP/procesar/TARGET/Laboratorios.txt' INTO TABLE cup_laboratorio2
  FIELDS TERMINATED BY ',' 
  LINES TERMINATED BY '\r\n' 
  (abreviatura,nombre)";
$loaddata=mysql_query($sql);

if(!$loaddata){
	die('no se pudo cargar'.mysql_error());
}  
  
?>