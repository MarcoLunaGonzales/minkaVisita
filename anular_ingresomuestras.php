<?php
require("conexion.inc");
require("estilos_almacenes_central.inc");
echo $cod_registro;
$sql="update ingreso_almacenes set anulado=1 where cod_ingreso_almacen='$cod_registro'";
$resp=mysql_query($sql);
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_ingresomuestras.php';
			</script>";
?>