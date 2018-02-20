<?php

require("conexion.inc");
require("estilos.inc");

$sql_ciclos="select distinct(cod_ciclo) from ciclos where (codigo_gestion='$global_gestion' or codigo_gestion = 1014)
 and estado<>'Cerrado' and estado<>'Activo' order by fecha_ini";
// echo $sql_ciclos;
echo "<center><table border='0' class='textotit'><tr><td align='center'>Parrillas Especiales x Ciclos</td></tr></table></center><br>";
echo "<center><table border='1' width='40%' cellspacing='0' class='texto'><tr><th>Ciclo</th><th>&nbsp;</th></tr>";
$resp_ciclos=mysql_query($sql_ciclos);
while($dat=mysql_fetch_array($resp_ciclos))
{	$ciclo=$dat[0];
	//echo"<tr><td align='center'>$ciclo</td><td align='center'><a href='navegador_parrilla_especial_ciclos.php?ciclo_trabajo=$ciclo'>Ver >></a></td></tr>";
	echo"<tr><td align='center'>$ciclo</td><td align='center'><a href='navegador_parrillas_especial_ciclos_grupos.php?ciclo_trabajo=$ciclo'>Ver >></a></td></tr>";
}	
echo "</table><br>";
require('home_central1.inc');
?>