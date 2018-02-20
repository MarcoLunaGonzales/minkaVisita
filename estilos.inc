<?php
echo"<head><title>Modulo de Gestion Central</title><link href='stilos.css' rel='stylesheet' type='text/css'></head>";  
//echo "<div id='Layer1' style='position:absolute; left:0px; top:0px; width:1000px; height:50 px; z-index:1; background-color: #000000; layer-background-color: #000000; border: 1px none #000000;'><img src='imagenes/cab_peque.jpg'>";
//echo "<center><script src='xaramenu.js'></script><script Webstyle4 src='imagenes/menu_administrador.js'></script></center>";
//echo "</div>";
echo "<body background='imagenes/fondo_pagina.jpg'>";
//echo "<table><tr><td bgcolor='#000000'><center><img src='imagenes/cab_cofar.png'></td></tr></table>";
//echo "<div style='position:absolute; left:0px; top:100px; width:1000px; border: 1px none #000000;'>";
require("conexion.inc");
$sql_gestion=mysql_query("select codigo_gestion, nombre_gestion from gestiones where estado='Activo'");
$dat_gestion=mysql_fetch_array($sql_gestion);
$codigo_gestion=$dat_gestion[0];
$gestion=$dat_gestion[1];
$global_gestion=$dat_gestion[0];
$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
$dat_ciclo=mysql_fetch_array($sql_ciclo);
$ciclo_global=$dat_ciclo[0];
$sql="select nombre_linea from lineas where codigo_linea=$global_linea";
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
echo "<center><table border=1 cellspacing='0' width='100%' class='linea'><tr><th colspan='4'>Modulo de Gesti&oacute;n Central</th></tr><tr><th>L&iacute;nea $linea</th><th>Usuario: $nombre_completo</th><th>Gesti&oacute;n: $gestion</th><th>Ciclo: $ciclo_global</th></tr></table></center><br>";

?>