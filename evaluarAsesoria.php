<script>
function validar(f){
	alert('validar');
}
</script>
<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");

	$codAsesoria=$_GET['codAsesoria'];
	$diaContacto=$_GET['diaContacto'];
	$nombreVisitador=$_GET['nombreVisitador'];
	$nombreLinea=$_GET['nombreLinea'];
	$codMed=$_GET['codMed'];
	$nombreMedico=$_GET['nombreMedico'];
	
	echo "<form method='get' action='guardaEvaluarAsesoria.php'>";
	echo "<center><table border='0' class='textotit'><tr><th>Evaluacion de Asesoria en Consultorio
	<br>Visitador $nombreVisitador <br> Dia Contacto: $diaContacto Medico: $nombreMedico</th></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>Observaciones: <input type='text' id='observaciones' name='observaciones' size='80' rows='3'></td></tr>
	</table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
	echo "<tr><th>Area de Evaluacion</th><th>Evaluacion</th></tr>";

	echo "<input type='hidden'  id='codAsesoria' name='codAsesoria' value='$codAsesoria'>";
	echo "<input type='hidden'  id='codMed' name='codMed' value='$codMed'>";
	
	
	$sql1="select cod_pregunta, nombre_pregunta, puntaje from preguntas_evaluacion order by 1";
	$resp1=mysql_query($sql1);
	while($dat1=mysql_fetch_array($resp1))
	{
		$codPregunta=$dat1[0];
		$nombrePregunta=$dat1[1];
		$puntaje=$dat1[2];
		
		echo "<tr><td>$nombrePregunta</td>
		<td align='center'>
		<select name='pregunta$codPregunta' size='4'>
			<option value='-1'></option>
			<option value='0'>---NO---</option>
			<option value='$puntaje'>---SI---</option>
			<option value='-1'></option>
		</select>
		</td>
		</tr>";

	}
	
	echo "</table></center><br>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><td><input type='submit' value='Guardar' name='guardar' class='boton' onSubmit='validar(this.form);'></td></td></tr></table></center>";
	echo "</form>";
?>