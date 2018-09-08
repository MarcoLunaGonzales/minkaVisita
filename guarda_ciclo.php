<?php
require("estilos.inc");
require("conexion.inc");
$fecha_real_ini=$_POST['exafinicial'];
//$fecha_real_ini=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$fecha_real_fin=$_POST['exafvalidez'];
//$fecha_real_fin=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$cod_ciclo=$_POST['cod_ciclo'];
//verificamos que no se crucen las fechas de los ciclos
$sql="insert into ciclos values('$cod_ciclo','$fecha_real_ini','$fecha_real_fin','Inactivo',1032,'$codigo_gestion')";
echo $sql;
$resp=mysql_query($sql);

/*echo "<script language='Javascript'>
		alert('Los datos se guardaron correctamente.');
		location.href='navegador_activar_ciclos.php';
		</script>";*/

?>