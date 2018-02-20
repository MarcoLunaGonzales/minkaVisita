<?php
require("estilos_regional.inc");
require("conexion.inc");
$fecha=$exafinicial;
$fecha_real_ini=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$fecha=$exafvalidez;
$fecha_real_fin=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
echo "$fecha_real_ini $fecha_real_fin $medico $motivo";
$sql="update baja_medicos set inicio='$fecha_real_ini', fin='$fecha_real_fin', codigo_motivo='$motivo'
where cod_med='$medico'";
$resp=mysql_query($sql);
echo "<script language='Javascript'>
		alert('Los datos se guardaron correctamente.');
		location.href='navegador_bajas_medicos.php';
		</script>";
?>