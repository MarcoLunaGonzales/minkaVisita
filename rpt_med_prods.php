<?php
require("conexion.inc");
require("estilos_regional_pri.inc");
echo "<form action='reporte_med_prods.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Reporte de Productos Objetivo y Filtrados x Medico</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Sacar reporte por:</th></tr>";
echo "<tr><td align='center'><select name='ver_por' class='texto'>
		<option value='todo'>Todos los Medicos</option>
		<option value='todo'>Especialidad</option></select></td></tr>";
$sql_espe="select cod_especialidad,desc_especialidad from especialidades order by desc_especialidad";
$resp_espe=mysql_query($sql_espe);
echo "<tr><td align='center'><select name='especialidad' class='texto'>";
while($dat_espe=mysql_fetch_array($resp_espe))
{	$cod_espe=$dat_espe[0];
	$espe=$dat_espe[1];
	echo "<option value='$cod_espe'>$espe</option>";
}
echo "</select></td></tr>";
echo "</table><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th colspan='3'>Ordenar por:</th></tr>";
echo "<tr>";
echo "<td><select name='orden1' class='texto'>
	  <option value='nombre'>Nombre</option>
	  <option value='categoria'>Categoria</option>
	  <option value='zona'>Zona</option></td>";
echo "<td><select name='orden2' class='texto'>
	  <option value='categoria'>Categoria</option>
	  <option value='zona'>Zona</option>
	  <option value='nombre'>Nombre</option></td>";
echo "<td><select name='orden3' class='texto'>
	  <option value='zona'>Zona</option>
	  <option value='nombre'>Nombre</option>
	  <option value='categoria'>Categoria</option></td>";
	  
echo "</tr>";
echo "</table><br>";
echo "<input type='submit' class='boton' value='Ver Reporte'></center>";
echo "</form>";

?>