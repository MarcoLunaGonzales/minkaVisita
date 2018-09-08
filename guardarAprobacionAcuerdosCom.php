<?php
require("conexion.inc");
require("estilos_administracion.inc");
require("funciones.php");

$idAcuerdo=$_GET['codAcuerdoCom'];
$estado=$_GET['estado'];
$observacion=$_GET['observacion'];

echo $idAcuerdo." ".$estado;

/*$sql_inserta="insert into acuerdos_comerciales (id_acuerdos, cod_ciudad, cod_cliente, cod_funcionario, promedio_ventas, porcentaje_crecimiento, promedio_ventasnuevo, porcentaje_rebate,
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
}*/
			
?>