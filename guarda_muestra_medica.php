<?php
require("conexion.inc");
require("estilos_administracion.inc");

$sql="select count(*)+1 from muestras_medicas";
$resp=mysql_query($sql);
$numFilas=mysql_result($resp,0,0);

if($numFilas==1){	
	$codigo="M1";
}
else{
	$codigo="M$numFilas";
}

$codigo_producto=$codigo;

$txtInserta="insert into muestras_medicas 
	(codigo, descripcion, estado, cod_tipo_muestra, codigo_linea) values 
	('$codigo_producto','$muestra',1,'$tipo_muestra','$linea')";

$sql_inserta=mysql_query($txtInserta);
if($sql_inserta==1)
{
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_muestras_medicas.php';
			</script>";
}
else
{
		echo "<script language='Javascript'>
			alert('Ocurrio un error. Contacte con el administrador.');
			history.back(-1);
			</script>";
}
?>