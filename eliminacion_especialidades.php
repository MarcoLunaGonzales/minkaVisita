<?php
require("conexion.inc");
//echo $codigos_especialidades;

$codigos=explode("|",$codigos_especialidades);
$numcodigos=sizeof($codigos);

$numcodigos=$numcodigos-2;
for($i=0;$i<=$numcodigos;$i++)
	{
		$codespecialidad=$codigos[$i];
		
		$sql="delete from especialidades where cod_especialidad='$codespecialidad'";
		$resp=mysql_query($sql);
	}
header("location:navegador_especialidades.php");

?>