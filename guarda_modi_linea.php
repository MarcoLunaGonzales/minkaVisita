<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql_upd=mysql_query("update lineas set nombre_linea='$linea' where codigo_linea='$codigo'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_lineas.php';
			</script>";
?>