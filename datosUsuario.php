<?php
	require("conexion.inc");
	
	$sql_gestion = mysql_query( "select codigo_gestion,nombre_gestion from gestiones where estado='Activo'" );
	$dat_gestion = mysql_fetch_array( $sql_gestion );
	$codigo_gestion = $dat_gestion[ 0 ];
	$global_gestion = $codigo_gestion;
	$nombreGestionSesion = $dat_gestion[ 1 ];
	
	$sql_ciclo = mysql_query( "select cod_ciclo from ciclos where estado='Activo'" );
	$dat_ciclo = mysql_fetch_array( $sql_ciclo );
	$nombreCicloSesion = $dat_ciclo[ 0 ];
	
	
	$sql = "select nombre_linea from lineas where codigo_linea='$global_linea'";
	$resp = mysql_query( $sql );
	$dat = mysql_fetch_array( $resp );
	$nombreLineaSesion = $dat[ 0 ];
	if($nombreLineaSesion==""){
		$nombreLineaSesion="------";
	}
	
	
	$sql = "select paterno, materno, nombres, cod_ciudad from funcionarios where codigo_funcionario=$global_usuario";
	$resp = mysql_query( $sql );
	$dat = mysql_fetch_array( $resp );
	$paterno = $dat[ 0 ];
	$materno = $dat[ 1 ];
	$nombre = $dat[ 2 ];	
	$nombreUsuarioSesion = "$paterno $nombre";

	$sql = "select descripcion from ciudades where cod_ciudad=$global_agencia";
	$resp = mysql_query( $sql );
	$dat = mysql_fetch_array( $resp );
	$nombreAgenciaSesion = $dat[ 0 ];

	date_default_timezone_set('America/La_Paz');
	$fechaSistemaSesion = date( "d-m-Y" );
	$horaSistemaSesion = date( "H:i" );
?>
