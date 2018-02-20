<?php

require("conexion.inc");
require("estilos_administracion.inc");


$vector=explode(",",$datos);
$n=sizeof($vector);


for($i=0;$i<$n;$i++){
         
	$sql="delete from medicos where cod_med=$vector[$i]";
	$resp=mysql_query($sql);
	$sql1="delete from especialidades_medicos where cod_med=$vector[$i]";
	$resp1=mysql_query($sql1);
	$sql2="delete from direcciones_medicos where cod_med=$vector[$i]";
	$resp2=mysql_query($sql2);
	$sql3="delete from medico_asignado_visitador where cod_med=$vector[$i]";
	$resp3=mysql_query($sql3);
	$sql4="delete from categorias_lineas where cod_med=$vector[$i]";
	$resp4=mysql_query($sql4);
	$consulta="DELETE FROM rutero_detalle WHERE cod_med=$vector[$i]";
	$rs=mysql_query($consulta);
	$consulta="DELETE FROM rutero_detalle_utilizado WHERE cod_med=$vector[$i]";
	$rs=mysql_query($consulta);
	$consulta="DELETE FROM rutero_maestro_detalle WHERE cod_med=$vector[$i]";
	$rs=mysql_query($consulta);
	$consulta="DELETE FROM rutero_maestro_detalle_aprobado WHERE cod_med=$vector[$i]";
	$rs=mysql_query($consulta);
}

echo "<script type='text/javascript' language='Javascript'>
	alert('Se eliminaron los datos seleccionados.');
	location.href='navegador_medicos1.php';
</script>";

?>