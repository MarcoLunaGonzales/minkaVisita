<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
require("conexion.inc");
require("estilos.inc");
require("funcion_nombres.php");

$sql_ciclos="select distinct(c.cod_ciclo), c.codigo_gestion, g.`nombre_gestion` from ciclos c, gestiones g where c.estado = 'Inactivo' and c.codigo_gestion in (1012) and g.codigo_gestion = c.codigo_gestion order by fecha_ini";
// $sql_ciclos="select distinct(c.cod_ciclo), c.codigo_gestion, (select g.`nombre_gestion` from `gestiones` g where g.`codigo_gestion`=c.codigo_gestion) from ciclos c where  c.codigo_gestion in (1009,1010) order by fecha_ini";
	
echo "<center><table border='0' class='textotit'><tr><td>Parrilla Promocional x Ciclos</td></tr></table></center><br>";
echo "<center><table border='1' width='50%' cellspacing='0' class='texto'><tr><th>Ciclo</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
$resp_ciclos=mysql_query($sql_ciclos);
while($dat=mysql_fetch_array($resp_ciclos))
{	$ciclo=$dat[0];
	$codGestion=$dat[1];
	$nombreGestion=$dat[2];
	echo"<tr><td align='center'>$ciclo - $nombreGestion</td>
		<td align='center'><a href='navegador_parrillas_espe_ciclos.php?ciclo_trabajo=$ciclo&gestion_trabajo=$codGestion'>Ver por Tipo de Cliente</a></td>
		<td align='center'><a href='navegadorParrillasTerritorio.php?ciclo_trabajo=$ciclo&gestion_trabajo=$codGestion'>Ver por Territorio</td>
		<td align='center'><a href='editar_grupoparrillas.php?ciclo_trabajo=$ciclo&gestion_trabajo=$codGestion'>Editar en Conjunto</td>
		<td align='center'><a href='eliminar_productoparrilla.php?ciclo_trabajo=$ciclo&gestion_trabajo=$codGestion'>Eliminar Productos</td>
		</tr>";
}	
echo "</table><br>";
require('home_central1.inc');
?>