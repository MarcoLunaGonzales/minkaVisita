<?php
	require("conexion.inc");
	setcookie("global_linea",$linea);
	echo "<script>window.top.location.href='indexSupervision.php';</script>";
?>