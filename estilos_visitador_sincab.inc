<?php
echo"<head><title>Minka VM++</title>
<link href='stilos.css' rel='stylesheet' type='text/css'></head>";
?>

<?php
require("conexion.inc");
$sql_gestion=mysql_query("select codigo_gestion,nombre_gestion from gestiones where estado='Activo'");
$dat_gestion=mysql_fetch_array($sql_gestion);
$codigo_gestion=$dat_gestion[0];
$gestion=$dat_gestion[1];
$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo'");
$dat_ciclo=mysql_fetch_array($sql_ciclo);
$ciclo_global=$dat_ciclo[0];

$sql="select nombre_linea from lineas where codigo_linea='$global_linea'";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$linea=$dat[0];
$sql="select paterno, materno, nombres, cod_ciudad from funcionarios where codigo_funcionario=$global_visitador";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$paterno=$dat[0];
$materno=$dat[1];
$nombre=$dat[2];

	/*$codCiudadXXX=$dat[3];
	if($codCiudadXXX==116 || $codCiudadXXX==122 || $codCiudadXXX==124 || $codCiudadXXX==118 || $codCiudadXXX==119 || $codCiudadXXX==109 || $codCiudadXXX==113){
		$ciclo_global = 2;
	}*/

$nombre_completo="$paterno $materno $nombre";
$sql="select descripcion from ciudades where cod_ciudad=$global_agencia";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$agencia=$dat[0];
$fecha_sistema=date("d-m-Y");
$hora_sistema=date("H:i");
?>