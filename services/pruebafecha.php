<?php
	require("funcionScaner.php");
	$cadena="Oct 10, 2016 23:00:00";
	
	$nuevaCad=convierteFecha($cadena);
	
	echo $cadena."    ".$nuevaCad;
	
	
?>
