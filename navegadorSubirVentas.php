<?php

require("conexion.inc");
require("estilos_regional_pri.inc");

?>
<html>
	<form enctype="multipart/form-data" method="post" action="uploadDatosVentas.php">
		<h1>Cargar Archivos Base de Ventas</h1>
		
		<center>
			<input name="archivo" id="archivo" required type="file" class="boton2"/><br/><br/>
			<input type="submit" value="Cargar Archivo" class="boton"/>
		</center>
		
		
		<h5>Nota Importante:<br>
			El formato correcto para procesar los archivos de Ventas es: <br>
			1. El archivo .csv deben esta contenidos en una carpeta llamada datosventas.zip <br>
		</h5>

	</form>
</html>