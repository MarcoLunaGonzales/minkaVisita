<?php
echo"<head><link href='stilos.css' rel='stylesheet' type='text/css'></head>";
//echo "<div id='Layer1' style='position:absolute; left:0px; top:0px; width:1000px; height:80px; z-index:1; background-color: #000000; layer-background-color: #000000; border: 1px none #000000;'><img src='imagenes/cab_peque.jpg'>";
//echo "<center><script src='xaramenu.js'></script><script Webstyle4 src='imagenes/menu_regional.js'></script></center></div>";
echo "<body background='imagenes/fondo_pagina.jpg'>";
//echo "<div style='position:absolute; left:0px; top:90px; width:1000px; border: 1px none #000000;'>";
/*require("conexion.inc");
$sql_gestion = mysql_query("select codigo_gestion,nombre_gestion from gestiones where estado='Activo' and codigo_linea='$global_linea'");
$dat_gestion = mysql_fetch_array($sql_gestion);
$codigo_gestion = $dat_gestion[0];
$gestion = $dat_gestion[1];
$sql_ciclo = mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
$dat_ciclo = mysql_fetch_array($sql_ciclo);
$ciclo_global = $dat_ciclo[0];
$sql = "select nombre_linea from lineas where codigo_linea=$global_linea";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$linea = $dat[0];
$sql = "select paterno, materno, nombres from funcionarios where codigo_funcionario=$global_usuario";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$paterno = $dat[0];
$materno = $dat[1];
$nombre = $dat[2];
$nombre_completo = "$paterno $materno $nombre";
$sql = "select descripcion from ciudades where cod_ciudad=$global_agencia";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$agencia = $dat[0];
echo "<center><table border=1 cellspacing='0' width='100%' class='linea'><tr><th colspan='5'>Modulo de Gesti�n Regional</th></tr><tr><th>L�nea $linea</th><th>Territorio $agencia</th><th>Usuario: $nombre_completo</th><th>Gesti�n:$gestion</th><th>Ciclo en Curso:$ciclo_global</th></tr></table></center><br>";
*/
?>