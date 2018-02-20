<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql_upd=mysql_query("update gestiones set nombre_gestion='$nombre' where codigo_gestion='$codigo'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_gestiones.php';
			</script>";
?>