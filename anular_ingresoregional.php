<?php
require("conexion.inc");
require("estilos_almacenes.inc");
$sql="update ingreso_almacenes set ingreso_anulado=1 where cod_ingreso_almacen='$codigo_registro'";
$resp=mysql_query($sql);
if($grupo_ingreso==1)
{	echo "<script language='Javascript'>
			alert('El registro fue anulado.');
			location.href='navegador_ingresomuestrasregional.php';
			</script>";
}
if($grupo_ingreso==2)
{	echo "<script language='Javascript'>
			alert('El registro fue anulado.');
			location.href='navegador_ingresomaterialesregional.php';
			</script>";
}
?>