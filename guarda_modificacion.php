<?php
require("estilos.inc");
require("conexion.inc");
$fecha_real_ini=$_POST['exafinicial'];
$fecha_real_fin=$_POST['exafvalidez'];
$codCiclo=$_POST['cod_ciclo'];
$codGestion=$_POST['cod_gestion'];

//verificamos que no se crucen las fechas de los ciclos

$sql="update ciclos set fecha_ini='$fecha_real_ini', fecha_fin='$fecha_real_fin' where cod_ciclo='$codCiclo' and codigo_gestion='$codGestion'";
$resp=mysql_query($sql);
echo "<script language='Javascript'>
		alert('Los datos se modificaron satisfactoriamente.');
		location.href='navegador_activar_ciclos.php';
	</script>";
		
?>