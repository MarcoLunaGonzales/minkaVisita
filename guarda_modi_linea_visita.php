<?php
require("conexion.inc");
require("estilos.inc");
$sql_inserta=mysql_query("update lineas_visita set nombre_l_visita='$nombre_linea' where codigo_l_visita='$cod_l_visita'");
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_lineas_visita.php';
			</script>";		

?>