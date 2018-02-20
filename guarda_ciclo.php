<?php
require("estilos.inc");
require("conexion.inc");
$fecha=$exafinicial;
$fecha_real_ini=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$fecha=$exafvalidez;
$fecha_real_fin=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$cod_ciclo=$cod_ciclo;
//verificamos que no se crucen las fechas de los ciclos
$ban=0;
$sql_aux=mysql_query("select fecha_ini,fecha_fin from ciclos where codigo_linea='$global_linea'");
while($dat_aux=mysql_fetch_array($sql_aux))
{	$fecha_inicio=$dat_aux[0];
	$fecha_final=$dat_aux[1];
	if($fecha_real_ini>=$fecha_inicio && $fecha_real_ini<=$fecha_final)
	{	$ban=1;
	}
	if($fecha_real_fin>=$fecha_inicio && $fecha_real_fin<=$fecha_final)
	{	$ban=1;
	}
}
//fin verificacion
//si $ban es 0 se insertan en caso contrario se rebotan los datos
if($ban==0)
{	$sql="insert into ciclos values('$cod_ciclo','$fecha_real_ini','$fecha_real_fin','Inactivo',$global_linea,'$codigo_gestion')";
	$resp=mysql_query($sql);
	echo "<script language='Javascript'>
			alert('Los datos se guardaron correctamente.');
			location.href='navegador_ciclos.php';
			</script>";
}
else
{	echo "<script language='Javascript'>
			alert('Las fechas de Inicio y Fin de Ciclo no pueden cruzarse con la de los Ciclos que ya existen.');
			history.back(-1);
			</script>";
}
?>