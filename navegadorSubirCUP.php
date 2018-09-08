<?php

require("conexion.inc");
require("estilos_regional_pri.inc");

?>
<html>
	<form enctype="multipart/form-data" method="post" action="uploadCUPBatch.php">
		<h1>Cargar Archivos de CloseUp</h1>
		
		<center>
			<input name="archivo" id="archivo" required type="file" class="boton2"/><br/><br/>
			<input type="submit" value="Cargar Archivo" class="boton"/>
		</center>
		
		
		<h5>Nota Importante:<br>
			El formato correcto para procesar los archivos CLOSEUP es: <br>
			1. Los archivos .txt deben estar contenidos en una carpeta llamada TARGET <br>
			2. La carpeta TARGET debe estar comprimida en .zip
		</h5>

	</form>
</html>