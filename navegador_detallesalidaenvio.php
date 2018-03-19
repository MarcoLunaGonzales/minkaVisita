<?php

require("conexion.inc");
require('estilos_almacenes_sincab.inc');


$sql="SELECT s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones, s.grupo_salida, s.nro_correlativo, s.territorio_destino, s.almacen_destino FROM salida_almacenes s, tipos_salida ts where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and s.cod_salida_almacenes='$codigo_salida'";
$resp=mysql_query($sql);
echo "<h1>Detalle de despacho</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Nro. Salida</th><th>Fecha</th><th>Tipo de Salida</th><th>Observaciones</th></tr>";
$dat                  = mysql_fetch_array($resp);
$codigo               = $dat[0];
$fecha_salida         = $dat[1];
$fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
$nombre_tiposalida    = $dat[2];
$obs_salida           = $dat[3];
$grupo_salida         = $dat[4];
$nro_correlativo      = $dat[5];
$territorio_destino   = $dat[6];
$almacen_destino      = $dat[7];

$sql_nombre_territorio  = "SELECT descripcion from ciudades where cod_ciudad='$territorio_destino'";
$resp_nombre_territorio = mysql_query($sql_nombre_territorio);
$dat_nombre_territorio  = mysql_fetch_array($resp_nombre_territorio);
$nombre_territorio      = $dat_nombre_territorio[0];

$sql_nombre_func_destino = "SELECT f.paterno, f.materno, f.nombres from funcionarios f, salida_detalle_visitador s where f.codigo_funcionario=s.codigo_funcionario and s.cod_salida_almacen='$codigo'";
$resp_func_destino       = mysql_query($sql_nombre_func_destino);
$dat_func_destino        = mysql_fetch_array($resp_func_destino);
$nombre_func_destino     = "$dat_func_destino[0] $dat_func_destino[1] $dat_func_destino[2]";

$sql_nombre_almacen_destino = "SELECT nombre_almacen from almacenes where cod_almacen='$almacen_destino'";
$resp_nombre_almacen        = mysql_query($sql_nombre_almacen_destino);
$dat_nombre_almacen_destino = mysql_fetch_array($resp_nombre_almacen);
$nombre_almacen_destino     = $dat_nombre_almacen_destino[0];

echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>&nbsp;$obs_salida</td></tr>";
echo "<tr><th>Territorio Destino</th><th>Almacen Destino</th><th colspan=2>Funcionario Destino</th></tr>";
echo "<tr><td>$nombre_territorio</td><td>&nbsp;$nombre_almacen_destino</td><td colspan=2>&nbsp;$nombre_func_destino</td></tr>";
echo "</table><br>";

echo "<table class='texto'>";
echo "<form method='post' action=''>";

$sql_detalle = "SELECT s.despacho_fecha, s.despacho_nroguia, t.nombre_tipotransporte, s.despacho_nrocajas, s.despacho_monto, s.despacho_peso, s.despacho_obs from salida_almacenes s, tipos_transporte t where s.cod_salida_almacenes='$codigo_salida' and s.cod_almacen='$global_almacen' and t.cod_tipotransporte=s.despacho_codtipotransporte";
	// echo $sql_detalle;
$resp_detalle = mysql_query($sql_detalle);
$indice       = 1;
while($dat_detalle=mysql_fetch_array($resp_detalle)) {	
	$fecha_despacho  = $dat_detalle[0];
	$numero_guia     = $dat_detalle[1];
	$tipo_transporte = $dat_detalle[2];
	$nrocajas        = $dat_detalle[3];
	$monto           = $dat_detalle[4];
	$peso            = $dat_detalle[5];
	$obs             = $dat_detalle[6];

	echo "<tr><th align='left'>Fecha Despacho</th><td align='left'>$fecha_despacho</td></tr>";
	echo "<tr><th align='left'>Nro. Guia</th><td align='left'>$numero_guia</td></tr>";
	echo "<tr><th align='left'>Tipo de Transporte</th><td align='left'>$tipo_transporte</td></tr>";
	echo "<tr><th align='left'>Nro. de Cajas</th><td align='left'>$nrocajas</td></tr>";
	echo "<tr><th align='left'>Monto [Bs]</th><td align='left'>&nbsp;$monto</td></tr>";
	echo "<tr><th align='left'>Peso [Kg]</th><td align='left'>&nbsp;$peso</td></tr>";
	echo "<tr><th align='left'>Observaciones</th><td align='left'>&nbsp;$obs</td></tr>";
}
echo "</table></center>";

require("imprimirInc.php");

?>