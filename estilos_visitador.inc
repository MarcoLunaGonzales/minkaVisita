<?php
date_default_timezone_set('America/La_Paz');

echo"<link href='stilos.css' rel='stylesheet' type='text/css'>";

	require("conexion.inc");
	$sql_gestion = mysql_query( "select codigo_gestion,nombre_gestion from gestiones where estado='Activo'" );
	$dat_gestion = mysql_fetch_array( $sql_gestion );
	$codigo_gestion = $dat_gestion[ 0 ];
	$global_gestion = $codigo_gestion;
	$gestion = $dat_gestion[ 1 ];
	
	$sql_ciclo = mysql_query( "select cod_ciclo from ciclos where estado='Activo'" );
	$dat_ciclo = mysql_fetch_array( $sql_ciclo );
	$ciclo_global = $dat_ciclo[ 0 ];
	
	$sql = "select nombre_linea from lineas where codigo_linea='$global_linea'";
	$resp = mysql_query( $sql );
	$dat = mysql_fetch_array( $resp );
	$linea = $dat[ 0 ];
	$sql = "select paterno, materno, nombres, cod_ciudad from funcionarios where codigo_funcionario=$global_visitador";
	$resp = mysql_query( $sql );
	$dat = mysql_fetch_array( $resp );
	$paterno = $dat[ 0 ];
	$materno = $dat[ 1 ];
	$nombre = $dat[ 2 ];

	
	$nombre_completo = "$paterno $materno $nombre";
	$sql = "select descripcion from ciudades where cod_ciudad=$global_agencia";
	$resp = mysql_query( $sql );
	$dat = mysql_fetch_array( $resp );
	$agencia = $dat[ 0 ];

	$fecha_sistema = date( "d-m-Y" );
	$hora_sistema = date( "H:i" );

?>
