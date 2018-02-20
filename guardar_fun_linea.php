<?php
require("conexion.inc");
require("estilos_administracion.inc");
$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql_inserta=mysql_query("insert into funcionarios_lineas values('$j_funcionario','$vector[$i]')");	
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='anadir_funcionario_linea.php?j_funcionario=$j_funcionario';
			</script>";
?>