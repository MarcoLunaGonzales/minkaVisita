<?php
	
require("conexion.inc");
require("estilos_administracion.inc");

echo "<form action='guardarCambiarMesPres.php' method='post'>";

echo "<h1>Cambiar Mes Vista de Presupuestos</h1>";

echo "<center><table class='texto'>";

echo "<tr><th>Año</th><th>Mes</th></tr>";

echo "<tr>

<td align='center'><select name='cod_anio' id='cod_anio' class='textograndenegro'>";
for($i=2018; $i<=2030; $i++){
	echo "<option value='$i'>$i</option>";
}
echo "</select></td>";

echo "<td align='center'><select name='cod_mes' id='cod_mes' class='textograndenegro'>";
echo "<option value='1'>Enero</option>
		<option value='2'>Febrero</option>
		<option value='3'>Marzo</option>
		<option value='4'>Abril</option>
		<option value='5'>Mayo</option>
		<option value='6'>Junio</option>
		<option value='7'>Julio</option>
		<option value='8'>Agosto</option>
		<option value='9'>Septiembre</option>
		<option value='10'>Octubre</option>
		<option value='11'>Noviembre</option>
		<option value='12'>Diciembre</option>
		";
echo "</select></td>";


echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>";

echo "</form>";
?>