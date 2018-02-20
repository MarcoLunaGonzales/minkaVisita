<?php
require("conexion.inc");
$sql="update ingreso_almacenes set observaciones='$observaciones',ingreso_anulado=1 where cod_ingreso_almacen='$codigo_registro'";
$resp=mysql_query($sql);
if($grupo_ingreso==1)
{	echo "<script language='Javascript'>
			alert('El registro fue anulado.');
			opener.location.reload();
			window.close();
			</script>";
}
if($grupo_ingreso==2)
{	echo "<script language='Javascript'>
			alert('El registro fue anulado.');
			opener.location.reload();
			window.close();
			</script>";
}

?>