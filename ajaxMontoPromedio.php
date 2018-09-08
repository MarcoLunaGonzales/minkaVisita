<?php
require('conexion.inc');
require('funciones.php');

$codCliente=$_GET['codCliente'];
$nroMeses=$_GET['nroMeses'];

$primerDiaMesActual=funcionPrimerDiaMes();
$ultimoDiaCalculo=date('Y-m-d',strtotime($primerDiaMesActual.'-1 day'));
$primerDiaCalculo=date('Y-m-d',strtotime($primerDiaMesActual.'-'.$nroMeses.' month'));

$sqlMontoVenta="select ifnull(sum(monto_venta),0) from ventas v where 
v.cod_cliente='$codCliente' and 
v.fecha_venta BETWEEN '$primerDiaCalculo' and '$ultimoDiaCalculo'";
$respMontoVenta=mysql_query($sqlMontoVenta);
$montoVentaCliente=mysql_result($respMontoVenta,0,0);
if($montoVentaCliente>0){
	$promedioVentaCliente=$montoVentaCliente/$nroMeses;
}else{
	$promedioVentaCliente=0;
}

echo "<input type='hidden' name='promedio_venta' id='promedio_venta' value='$promedioVentaCliente'>";
echo formatonumero($promedioVentaCliente);


?>