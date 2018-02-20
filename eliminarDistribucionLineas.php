<?php
require("conexion.inc");
	require("estilos_inicio_adm.inc");
	$global_linea=$global_linea_distribucion;
	$gestionDistribucion=$global_gestion_distribucion;
	$cicloDistribucion=$global_ciclo_distribucion;

$sql="select * from distribucion_productos_visitadores where cod_ciclo=$cicloDistribucion and codigo_gestion=$gestionDistribucion and 
codigo_linea=$global_linea and cantidad_sacadaalmacen<>0";
$resp=mysql_query($sql);

$numFilas=mysql_num_rows($resp);

if($numFilas==0){
	$sqlDel="delete from distribucion_productos_visitadores where cod_ciclo=$cicloDistribucion and codigo_gestion=$gestionDistribucion and 
codigo_linea=$global_linea";
	$respDel=mysql_query($sqlDel);
	echo "<script language='JavaScript'>
		alert('Los datos fueron eliminados.')
		location.href='navegador_lineas_distribucion.php?codigo_ciclo=$cicloDistribucion&codigo_gestion=$gestionDistribucion';
	</script>";
}else{
	echo "<script language='JavaScript'>
		alert('Los datos no pueden ser eliminados porque ya se realizaron salidas para este Ciclo y Linea.')
		location.href='navegador_lineas_distribucion.php?codigo_ciclo=$cicloDistribucion&codigo_gestion=$gestionDistribucion';
	</script>";

}	
	
?>