<?php
require("conexion.inc");
require("estilos_administracion.inc");

$sql_inserta=mysql_query("update motivos_baja set tipo_motivo='$tipoBaja', descripcion_motivo='$baja' where codigo_motivo='$codigo'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_tiposbaja.php';
			</script>";
?>