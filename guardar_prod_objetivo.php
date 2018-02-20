<?php
require("conexion.inc");
require("estilos_regional_pri.inc");
$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql_inserta=mysql_query("insert into productos_objetivo values($j_cod_med,'$vector[$i]',$global_linea)");	
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='producto_objetivo.php?j_cod_med=$j_cod_med';
			</script>";
?>