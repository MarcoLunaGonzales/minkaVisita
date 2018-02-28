<?php
require("conexion.inc");
$sql_gestion=mysql_query("select codigo_gestion, nombre_gestion from gestiones where estado='Activo'");
$dat_gestion=mysql_fetch_array($sql_gestion);
$codigo_gestion=$dat_gestion[0];
$gestion=$dat_gestion[1];
$global_gestion=$dat_gestion[0];
$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
$dat_ciclo=mysql_fetch_array($sql_ciclo);
$ciclo_global=$dat_ciclo[0];
$sql="select paterno, materno, nombres from funcionarios where codigo_funcionario=$global_usuario";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$paterno_usu=$dat[0];
$materno_usu=$dat[1];
$nombre_usu=$dat[2];
$nombre_completo="$paterno_usu $materno_usu $nombre_usu";

?>