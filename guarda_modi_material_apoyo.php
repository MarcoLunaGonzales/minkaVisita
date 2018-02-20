<?php
require("conexion.inc");
require("estilos_administracion.inc");
$fecha=$exafinicial;
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$sql_upd=mysql_query("update material_apoyo set descripcion_material='$material', estado='$estado', cod_tipo_material='$tipo_material', codigo_linea='$linea',
						fecha_expiracion='$fecha_real' where codigo_material='$codigo'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_material.php';
			</script>";
?>