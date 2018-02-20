<?php
require("conexion.inc");
require("estilos.inc");
$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql_inserta=mysql_query("insert into producto_especialidad values('$cod_espe','$vector[$i]',$global_linea)");	
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='ver_prod_especialidad.php?cod_espe=$cod_espe';
			</script>";
?>