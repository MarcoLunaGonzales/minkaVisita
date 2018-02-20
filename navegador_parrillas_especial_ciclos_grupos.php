<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
require("conexion.inc");
require("estilos.inc");
$sql="select g.codigo_grupo_especial, g.nombre_grupo_especial, c.descripcion from grupo_especial g, ciudades c
	 where g.agencia=c.cod_ciudad and g.codigo_linea='$global_linea' order by c.descripcion, g.nombre_grupo_especial";
	 // echo $sql;
$resp=mysql_query($sql);
echo "<center><table border='0' class='textotit'><tr><td>Registro de Grupos Especiales</td></tr></table></center><br>";
$indice_tabla=1;
echo "<center><table border='1' class='texto' cellspacing='0' width='50%'>";
echo "<tr><td>&nbsp;</td><th>Territorio</th><th>Nombre Grupo Especial</th>
<th>Cantidad Parrillas</th><th>&nbsp;</th></tr>";
while($dat=mysql_fetch_array($resp))
{
	$cod_grupo=$dat[0];
	$nombre_grupo=$dat[1];
	$nombre_ciudad=$dat[2];
	echo "<tr><td align='center'>$indice_tabla</td><td>$nombre_ciudad</td><td align='center'>$nombre_grupo</td>
	<td align='center'><a href='navegador_parrilla_especial_ciclos.php?ciclo_trabajo=$ciclo_trabajo&grupo_especial=$cod_grupo'>Ver Parrillas >></a></td>
	</tr>";
	$indice_tabla++;
}
echo "</table></center><br>";
	
require('home_central1.inc');
?>