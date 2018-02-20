<?php
require("conexion.inc");
require("estilos_regional_pri.inc");

$date = date('Y-m-d');

$sql_pre="UPDATE rutero_maestro_cab set estado_aprobado='0' where cod_visitador='$visitador' and codigo_linea='$global_linea'";
$resp_pre=mysql_query($sql_pre);
$sql_aprueba="UPDATE rutero_maestro_cab set estado_aprobado='1', fecha_aprobacion = '$date' where cod_visitador='$visitador' and cod_rutero='$cod_rutero'";
$resp_aprueba=mysql_query($sql_aprueba);
//ACA INSERTAMOS EN LA TABLA DE RUTEROS MAESTROS APROBADOS
//verifica si hay un rutero para el ciclo
$sqlVeri="SELECT codigo_ciclo, codigo_gestion, cod_visitador from rutero_maestro_cab where cod_rutero='$cod_rutero'";
$respVeri=mysql_query($sqlVeri);
$codigoCiclo=mysql_result($respVeri,0,"codigo_ciclo");
$codigoGestion=mysql_result($respVeri,0,"codigo_gestion");
$codVisitador=mysql_result($respVeri,0,"cod_visitador");

$sqlVeri="SELECT cod_rutero from rutero_maestro_cab_aprobado where codigo_gestion='$codigoGestion' and codigo_ciclo='$codigoCiclo'and cod_visitador='$codVisitador' and codigo_linea='$global_linea'";
$respVeri=mysql_query($sqlVeri);
$numFilas=mysql_num_rows($respVeri);
if($numFilas>0){
	$codRuteroBorrar=mysql_result($respVeri,0,0);
	$cod_rutero_maestro=$codRuteroBorrar;
	$sql_rutero_maestro="SELECT cod_contacto from rutero_maestro_aprobado where cod_rutero='$cod_rutero_maestro'";
	$resp_rutero_maestro=mysql_query($sql_rutero_maestro);
	while($dat_rutero_maestro=mysql_fetch_array($resp_rutero_maestro)) {	
		$cod_contacto=$dat_rutero_maestro[0];
		$sql_rutero_detalle="DELETE from rutero_maestro_detalle_aprobado where cod_contacto='$cod_contacto'";
		$resp_rutero_detalle=mysql_query($sql_rutero_detalle); 
	}
	$sql_delete_rutero_maestro="DELETE from rutero_maestro_aprobado where cod_rutero='$cod_rutero_maestro' and cod_visitador='$codVisitador'";
	$resp_delete_rutero_maestro=mysql_query($sql_delete_rutero_maestro);
	$sql_delete_rutero="DELETE from rutero_maestro_cab_aprobado where cod_rutero='$cod_rutero_maestro' and cod_visitador='$codVisitador'";
	$resp_delete_rutero=mysql_query($sql_delete_rutero);

}
$sql="INSERT into rutero_maestro_cab_aprobado (cod_rutero, nombre_rutero, cod_visitador, estado_aprobado, codigo_linea, codigo_ciclo, codigo_gestion,fecha_aprobacion, cvs) SELECT cod_rutero, nombre_rutero, cod_visitador, estado_aprobado, codigo_linea, codigo_ciclo, codigo_gestion, fecha_aprobacion, cvs from rutero_maestro_cab where cod_rutero='$cod_rutero'";

echo $sql;

$resp=mysql_query($sql);
$sql="INSERT into rutero_maestro_aprobado (cod_contacto, cod_rutero, cod_visitador, dia_contacto, turno, zona_viaje) SELECT cod_contacto, cod_rutero, cod_visitador, dia_contacto, turno, zona_viaje from rutero_maestro where cod_rutero='$cod_rutero'";
echo $sql;

$resp=mysql_query($sql);

$sql="INSERT into rutero_maestro_detalle_aprobado(cod_contacto, orden_visita, cod_visitador, cod_med, cod_especialidad, categoria_med, cod_zona, estado, tipo) SELECT r.cod_contacto, r.orden_visita, r.cod_visitador, r.cod_med, r.cod_especialidad, r.categoria_med, r.cod_zona, r.estado, r.tipo from rutero_maestro_detalle r where r.cod_contacto in (SELECT rm.cod_contacto from rutero_maestro rm where rm.cod_rutero = '$cod_rutero')";

echo $sql;	   
$resp=mysql_query($sql);
echo "<script language='Javascript'>
alert('El rutero medico se aprobo individualmente.');
location.href='rutero_funcionario.php?visitador=$visitador';
</script>";
?>