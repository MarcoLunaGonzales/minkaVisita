<?php
	require("conexion.inc");
	setcookie("global_linea",$linea);
	header("location:inicio_administracion.php");
?>