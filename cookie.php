<?php

require("conexionInicial.inc");
require("lib/phpmailer/class.phpmailer.php");

$usuario=$_POST['usuario'];
$contrasena=$_POST['contrasena'];


$sql = "SELECT f.cod_cargo, f.cod_ciudad, f.codigo_funcionario, f.cod_zeus from funcionarios f, usuarios_sistema u 
where u.codigo_funcionario=f.codigo_funcionario 
and u.nombre_usuario='$usuario' and u.contrasena='$contrasena'";
//echo $sql;
$resp = mysql_query($sql);
$num_filas = mysql_num_rows($resp);

if ($num_filas != 0) {
	
	session_start();
	
    $dat = mysql_fetch_array($resp);
    $cod_cargo = $dat[0];
    $cod_ciudad = $dat[1];
	$usuario=$dat[2];
	$codExterno=$dat[3];
	
	//obtenemos el mes actual
	$mesTrabajo=date("n");
	$anioTrabajo=date("Y");
	$nombreMesTrabajo=$mesTrabajo.".".$anioTrabajo;

	
	//solo para pruebas
	$mesTrabajo="5";
	$anioTrabajo="2018";
	$nombreMesTrabajo="05.2018";
	
	$_SESSION['mesTrabajo']=$mesTrabajo;
	$_SESSION['anioTrabajo']=$anioTrabajo;
	$_SESSION['mesTrabajoDesc']=$nombreMesTrabajo;
	$_SESSION['codRegionalTrabajo']=$cod_ciudad;
	$_SESSION['promotorTrabajo']=$codExterno;
	
	setcookie("global_usuario", $usuario);
	setcookie("global_agencia", $cod_ciudad);
	
    if ($cod_cargo == 1000) {
		header("location:indexAdmin.php");
	}	
	if ($cod_cargo == 1012) {
		header("location:indexPromotor.php");
	}	 
	if ($cod_cargo == 1001 or $cod_cargo == 1002) {
		header("location:indexSupervision.php");
	}

} 
else {
	
    echo "<h1>Usuario No Registrado!</h1>";
}
