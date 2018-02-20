<?php
echo"<head><title>HERMES</title><link href='stilos.css' rel='stylesheet' type='text/css'></head>";
echo "<div id='Layer1' style='position:absolute; left:0px; top:0px; width:1000px; height:80px; z-index:1; background-color: #000000; layer-background-color: #000000; border: 1px none #000000;'><img src='imagenes/cab_peque.jpg'>";
echo "<center><script src='xaramenu.js'></script><script Webstyle4 src='imagenes/menu_inicio_rrhh.js'></script></center></div>";
echo "<body background='imagenes/fondo_pagina.jpg'>";
//echo "<center><img src='imagenes/cab4.png'><br>";
echo "<div style='position:absolute; left:0px; top:100px; width:1000px;  border: 1px none #000000;'>";
require("conexion.inc");
$sql="select paterno, materno, nombres from funcionarios where codigo_funcionario='$global_usuario'";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$paterno_usu=$dat[0];
$materno_usu=$dat[1];
$nombre_usu=$dat[2];
$nombre_completo="$paterno_usu $materno_usu $nombre_usu";
echo "<center><table border=1 cellspacing='0' width='100%' class='linea'><tr><th colspan='4'>Modulo de Recursos Humanos</th></tr><tr><th>Usuario: $nombre_completo</th></tr></table></center><br>";

?>