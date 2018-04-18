<?php

session_start();
if(isset($_SESSION['logueado'])){
	
	date_default_timezone_set('America/La_Paz');
	if(!function_exists('register_globals')){
		include('register_globals.php');
		register_globals();
	}else{
	}

	//$conexion  = mysql_connect("localhost","root","4868422Marco");
	//$bd        = mysql_select_db("minkavisita", $conexion);

	$conexion  = mysql_connect("localhost","root","4868422Marco");
	$bd        = mysql_select_db("minkavisita2", $conexion);
}else{
	header("location:noConectado.php");
}




?>