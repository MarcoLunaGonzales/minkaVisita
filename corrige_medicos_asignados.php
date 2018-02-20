<?php
echo $global_visitador;
echo $global_linea;
require("conexion.inc");
$sql_num_medicos="select * from medico_asignado_visitador where codigo_visitador='$global_visitador' and codigo_linea='$global_linea'";
//echo $sql_num_medicos;
$resp_num_medicos=mysql_query($sql_num_medicos);
$num_filas=mysql_num_rows($resp_num_medicos);
//echo $num_filas;
while($datos=mysql_fetch_array($resp_num_medicos))
{	$codigo_medico=$datos[0];
	echo"$codigo_medico<br>";
	$sql_comprobacion1="select * from categorias_lineas where codigo_linea='$global_linea' and cod_med='$codigo_medico'";
	echo $sql_comprobacion1;
	$resp_comprobacion1=mysql_query($sql_comprobacion1);
	$num_filas=mysql_num_rows($resp_comprobacion1);
	if($num_filas==0)
	{	$sql_elimina=mysql_query("delete from medico_asignado_visitador where codigo_visitador='$global_visitador' and codigo_linea='$global_linea'");
		echo "$num_filas <br>";
	}
	$sql_comprobacion2="select * from medicos where cod_med='$codigo_medico'";
	echo $sql_comprobacion2;
	$resp_comprobacion2=mysql_query($sql_comprobacion2);
	$num_filas=mysql_num_rows($resp_comprobacion2);
	if($num_filas==0)
	{	$sql_elimina=mysql_query("delete from medico_asignado_visitador where codigo_visitador='$global_visitador' and codigo_linea='$global_linea'");
		echo "$num_filas <br>";
	}
}

?>