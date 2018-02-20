<?php
	require("conexion.inc");
	setcookie("global_linea",$linea);
	header("location:principal_administracion.php");
?>