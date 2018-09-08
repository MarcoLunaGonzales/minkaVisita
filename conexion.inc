<?php

session_start();
if(isset($_SESSION['logueado'])){
	
	date_default_timezone_set('America/La_Paz');
	if(!function_exists('register_globals')){
		include('register_globals.php');
		register_globals();
	}else{
	}

	//$conexion  = mysql_connect("localhost","crespal_minka","Marquexmar30");
	//$bd        = mysql_select_db("crespal_minkavisita", $conexion);

	$conexion  = mysql_connect("localhost","root","4868422Marco");
	//$bd        = mysql_select_db("minkafarma", $conexion);
	$bd        = mysql_select_db("minkaventas", $conexion);

}else{
	header("location:noConectado.php");
}




?>