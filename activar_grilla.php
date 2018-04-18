<?php
	require("conexion.inc");
	require("estilos.inc");
	$sql_update="update grilla set estado='0' where agencia='$cod_ciudad' and codigo_linea='$codigo_linea' and 
	cod_distrito='$cod_distrito'";
	
	echo $sql_update;
	
	$resp_update=mysql_query($sql_update);
	$sql_update2="update grilla set estado='1' where codigo_grilla='$j_codigo' and agencia='$cod_ciudad'";
	
	//echo $sql_update2;
	$resp_update2=mysql_query($sql_update2);

	echo "<script language='Javascript'>
		  alert('Los Grilla se activo satisfactoriamente.');
		  location.href='navegador_grillas.php?cod_ciudad=$cod_ciudad&codigo_linea=$codigo_linea';
	      </script>";
?>