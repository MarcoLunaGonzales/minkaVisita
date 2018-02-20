<?php
require("conexion.inc");
require("estilos.inc");
$sql="update categorias_medicos set visitas_minimo=$visitas_min,visitas_maximo=$visitas_max where categoria_med='$categoria'";
$resp=mysql_query($sql);
	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_categorias.php';
			</script>";		
?>