<?php
	require("conexion.inc");
			
	$sql = "select paterno, materno, nombres, cod_ciudad from funcionarios where codigo_funcionario=$global_usuario";
	//echo $sql;
	$resp = mysql_query( $sql );
	$dat = mysql_fetch_array( $resp );
	$paterno = $dat[ 0 ];
	$materno = $dat[ 1 ];
	$nombre = $dat[ 2 ];	
	$nombreUsuarioSesion = "$paterno $nombre";

	$sql = "select nombre_ciudad from ciudades where cod_ciudad=$global_agencia";
	$resp = mysql_query( $sql );
	$dat = mysql_fetch_array( $resp );
	$nombreAgenciaSesion = $dat[ 0 ];

	date_default_timezone_set('America/La_Paz');
	$fechaSistemaSesion = date( "d-m-Y" );
	$horaSistemaSesion = date( "H:i" );
	
	$sqlDatosAct="select DATE_FORMAT(max(v.fecha_venta), '%d/%m/%Y') from ventas v";
	$respDatosAct=mysql_query($sqlDatosAct);
	$fechaAct="";
	$numFilasAct=mysql_num_rows($respDatosAct);
	if($numFilasAct>0){
		$fechaAct=mysql_result($respDatosAct,0,0);
	}
?>
