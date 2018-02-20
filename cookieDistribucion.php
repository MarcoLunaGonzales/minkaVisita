<?php
require("conexion.inc");
setcookie("global_ciclo_distribucion",$codigo_ciclo);
setcookie("global_gestion_distribucion",$codigo_gestion);
//header("location:navegador_distribucion_lineas.php");
echo "<script language='JavaScript'>
		location.href='insertaDistribucionGruposEspeciales2.php';
</script>";
?>