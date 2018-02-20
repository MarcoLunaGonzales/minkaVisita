<script language='Javascript'>
function validar(f){
	if(f.semana1.value==f.semana2.value){
		alert("No puede intercambiar una misma semana.")
		return(false);
	}
	f.submit();
}
</script>

<?php
require("conexion.inc");
require("estilos_visitador.inc");
require("funcion_nombres.php");

echo "<form method='post' action='guardaIntercambiarSemanas.php'>";

$cod_rutero=$rutero;
$nombreRutero=nombreRutero($cod_rutero);
echo "<input type='hidden' name='cod_rutero' value='$cod_rutero'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Intercambiar Semanas en Rutero Maestro<br>Rutero: $nombreRutero</th></tr></table><br>";
echo "<table border='1' width='80%' cellspacing='0' class='texto' align='center'><tr><th>Semana</th><th>Semana</th></tr>";
echo "<tr><td align='center'><select name='semana1' class='texto'>
			<option value='1'>Semana 1</option>
			<option value='2'>Semana 2</option>
			<option value='3'>Semana 3</option>
			<option value='4'>Semana 4</option>
			</select></td>";
			
echo "<td align='center'><select name='semana2' class='texto'>
			<option value='1'>Semana 1</option>
			<option value='2'>Semana 2</option>
			<option value='3'>Semana 3</option>
			<option value='4'>Semana 4</option>
			</select></td></tr>";
echo "</table><br>";
echo "<center><input type='button' value='Guardar' class='boton' onclick='validar(this.form);'></center>";
echo "</form>";
?>