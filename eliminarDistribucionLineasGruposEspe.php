<?php
require("conexion.inc");
	require("estilos_inicio_adm.inc");

	$gestionDistribucion=$_GET['codGestion'];
	$cicloDistribucion=$_GET['codCiclo'];

$sql="select * from distribucion_grupos_especiales where cod_ciclo=$cicloDistribucion and codigo_gestion=$gestionDistribucion 
	and cantidad_sacadaalmacen<>0";
$resp=mysql_query($sql);

$numFilas=mysql_num_rows($resp);

if($numFilas==0){
	$sqlDel="delete from distribucion_grupos_especiales where cod_ciclo=$cicloDistribucion and codigo_gestion=$gestionDistribucion";
	$respDel=mysql_query($sqlDel);
	echo "<script language='JavaScript'>
		alert('Los datos fueron eliminados.')
		location.href='navegadorDistribucionGruposCiclos.php';
		</script>";
}else{
	echo "<script language='JavaScript'>
		alert('Los datos no pueden ser eliminados porque ya se realizaron salidas para este Ciclo.')
		location.href='navegadorDistribucionGruposCiclos.php';
	</script>";

}	
	
?>