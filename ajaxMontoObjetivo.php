<?php
require('conexion.inc');
require('funciones.php');

$codCliente=$_GET['codCliente'];
$crecimiento=$_GET['crecimiento'];
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
	$montoVentaCliente=$montoVentaCliente+($montoVentaCliente*0.13);
	$montoObjetivoCliente=($montoVentaCliente/$nroMeses)+($montoVentaCliente/$nroMeses)*($crecimiento/100);
}else{
	$montoObjetivoCliente=0;
}
echo "<input type='hidden' name='monto_objetivo' id='monto_objetivo' value='$montoObjetivoCliente' class='textogranderojo'>";
echo formatonumero($montoObjetivoCliente);

?>