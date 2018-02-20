<?php

require("conexion.inc");
require("estilos_gerencia.inc");

$cod_ingreso =$_GET['cod_ingreso'];
$nro_empaques=$_GET['nro_empaques'];

for($ii=1;$ii<=$nro_empaques;$ii++){
	$codMaterial=$_GET['material'.$ii];
	$cantMaterial=$_GET['canti'.$ii];
	$indice=$_GET['indice'.$ii];
	//echo $codMaterial." ".$cantMaterial."<br>";
	$sql="update ingreso_etiquetas set cantidad='$cantMaterial' where
	cod_ingreso='$cod_ingreso' and etiqueta='$indice' and cod_material='$codMaterial'";
	echo "$sql<br>";
	$resp=mysql_query($sql);
}

echo "<script>
	alert('Se modificaron los datos.');
	window.close();
</script>";
?>