<?php
require("conexion.inc");
require("estilos_administracion.inc");
require("funciones.php");

$codFuncionario=$_COOKIE['global_usuario'];
$codCiudad=$_COOKIE['global_agencia'];
$codCliente=$_POST['cod_cliente'];
$nroMeses=$_POST['nro_meses'];
$promedioVenta=$_POST['promedio_venta'];
$porcentajeCrecimiento=$_POST['porcentaje_crecimiento'];
$montoObjetivo=$_POST['monto_objetivo'];
$montoObjetivoTotal=$_POST['monto_objetivototal'];
$porcentajeRebate=$_POST['porcentaje_rebate'];
$montoRebate=$_POST['monto_rebate'];
$detalleRebate=$_POST['detalle_rebate'];
$fechaInicio=$_POST['fecha_inicio'];
$fechaFinal=$_POST['fecha_final'];

$fechaRegistro=date("Y-m-d H:i:s");

$sqlCodAcuerdos="select IFNULL(max(a.id_acuerdos)+1,1) from acuerdos_comerciales a";
$respCodAcuerdos=mysql_query($sqlCodAcuerdos);
$idAcuerdos=mysql_result($respCodAcuerdos,0,0);

$sql_inserta="insert into acuerdos_comerciales (id_acuerdos, cod_ciudad, cod_cliente, cod_funcionario, promedio_ventas, porcentaje_crecimiento, promedio_ventasnuevo, porcentaje_rebate,
monto_objetivo, monto_rebate, numero_meses, fecha_inicio, fecha_final, detalle, cod_estadoacuerdo, fecha_registro) values ('$idAcuerdos','$codCiudad','$codCliente',
'$codFuncionario','$promedioVenta','$porcentajeCrecimiento','$montoObjetivo','$porcentajeRebate','$montoObjetivoTotal','$montoRebate','$nroMeses','$fechaInicio',
'$fechaFinal','$detalleRebate','1','$fechaRegistro')";
//echo $sql_inserta;
$respInserta=mysql_query($sql_inserta);
if($respInserta==1){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegadorAcuerdosCom.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR. Contactarse con el administrador.');
			location.href='navegadorAcuerdosCom.php';
			</script>";
}
			
?>