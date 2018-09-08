<?php

require("conexion.inc");
$mesTrabajo=$_POST['cod_mes'];
$anioTrabajo=$_POST['cod_anio'];
$nombreMesTrabajo=$cod_mes."."$anioTrabajo;

$_SESSION['mesTrabajo']=$mesTrabajo;
$_SESSION['anioTrabajo']=$anioTrabajo;
$_SESSION['mesTrabajoDesc']=$nombreMesTrabajo;	
  
?>