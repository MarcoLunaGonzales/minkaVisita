<?php

require("conexion.inc");
$mesTrabajo=$_POST['cod_mes'];
$anioTrabajo=$_POST['cod_anio'];
$nombreMesTrabajo=$cod_mes.".".$anioTrabajo;

$_SESSION['mesTrabajo']=$mesTrabajo;
$_SESSION['anioTrabajo']=$anioTrabajo;
$_SESSION['mesTrabajoDesc']=$nombreMesTrabajo;	
 
echo "<script>
	alert('El mes de Vista de presupuestos fue cambiado.');
	location.href='reporteGraficoPresRegional.php';
</script>";
?>