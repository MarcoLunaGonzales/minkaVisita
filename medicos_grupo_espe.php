<?php
require("conexion.inc");
echo"<head><link href='stilos.css' rel='stylesheet' type='text/css'></head>";  
echo "<body background='imagenes/fondo_pagina.png'>";
$sql_grupo=mysql_query("select nombre_grupo_especial from grupo_especial where codigo_grupo_especial='$cod_grupo'");
$dat_grupo=mysql_fetch_array($sql_grupo);
$grupo_espe=$dat_grupo[0];
echo "<center><table border='0' class='textotit'><tr><td>Medicos $grupo_espe</td></tr></table></center><br>";
$sql_det="select m.ap_pat_med,m.ap_mat_med,m.nom_med from medicos m, grupo_especial_detalle g
		where m.cod_med=g.cod_med and codigo_grupo_especial='$cod_grupo'";
$resp_det=mysql_query($sql_det);
echo"<center><table class='texto' border='1' cellspacing='0' width='50%'>";
echo "<tr><th>Medico</th>";
while($dat_det=mysql_fetch_array($resp_det))
{	$pat=$dat_det[0];
	$mat=$dat_det[1];
	$nom=$dat_det[2];
	$nombre_medico="$pat $mat $nom";
	echo "<tr><td>$nombre_medico</td></tr>";
}	
echo "</table>";
?>