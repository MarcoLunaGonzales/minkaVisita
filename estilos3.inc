<?php
require("conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$sql_gestion=mysql_query("select codigo_gestion, nombre_gestion from gestiones where estado='Activo'");
$dat_gestion=mysql_fetch_array($sql_gestion);
$codigo_gestion=$dat_gestion[0];
$gestion=$dat_gestion[1];
$global_gestion=$dat_gestion[0];
$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo'" );
$dat_ciclo=mysql_fetch_array($sql_ciclo);
$ciclo_global=$dat_ciclo[0];
$sql="select nombre_linea from lineas where codigo_linea=1021";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$linea=$dat[0];
$sql="select paterno, materno, nombres from funcionarios where codigo_funcionario=$global_usuario";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$paterno_usu=$dat[0];
$materno_usu=$dat[1];
$nombre_usu=$dat[2];
$nombre_completo="$paterno_usu $materno_usu $nombre_usu";

//echo "<center><table border=1 cellspacing='0' width='100%' class='linea'><tr><th colspan='4'>Modulo de Gesti&oacute;n Central</th></tr><tr><th>L&iacute;nea $linea</th><th>Usuario: $nombre_completo</th><th>Gesti&oacute;n: $gestion</th><th>Ciclo: $ciclo_global</th></tr></table></center>";

?>