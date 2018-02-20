<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
require("conexion.inc");
require("estilos_visitador.inc");
$sql_ciclos="select * from ciclos where codigo_linea='$global_linea' and estado<>'Cerrado' and estado<>'Activo' order by fecha_ini";
echo "<center><table border='0' class='textotit'><tr><td>Registro de Rutero Medico x Ciclos</td></tr></table></center><br>";
echo "<center><table border='1' width='50%' cellspacing='0' class='texto'><tr><th>Ciclo</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
$resp_ciclos=mysql_query($sql_ciclos);
while($dat=mysql_fetch_array($resp_ciclos))
{	$ciclo=$dat[0];
	echo"<tr><td align='center'>$ciclo</td><td align='center'><a href='navegador_contactos_ciclos_todo.php?ciclo_trabajo=$ciclo'>Ver Todo >></a></td><td align='center'><a href='navegador_contactos_ciclos_dia.php?ciclo_trabajo=$ciclo'>Ver por Dia >></a></td></tr>";
}	
echo "</table>"
?>