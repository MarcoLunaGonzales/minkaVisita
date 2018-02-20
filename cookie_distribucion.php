<?php

$codigo_ciclo=$_GET['codigo_ciclo'];
$codigo_gestion=$_GET['codigo_gestion'];

setcookie("global_ciclo_distribucion",$codigo_ciclo);
setcookie("global_gestion_distribucion",$codigo_gestion);

//header("location:navegador_distribucion_lineas.php");
echo "<script language='JavaScript'>
		location.href='navegador_lineas_distribucion.php';
</script>";
?>