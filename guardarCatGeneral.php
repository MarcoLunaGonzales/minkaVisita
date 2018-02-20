<?php
require("conexion.inc");
$codMed=$_GET["codMed"];
$cantidadLineas=$_GET["cantidadLineas"];

for($i=0; $i<$cantidadLineas; $i++){
	$codLinea=$_GET["linea$i"];
	$catLinea=$_GET["cat$i"];
	
	//echo $codLinea. " ".$catLinea."<br>";
	$sql="update categorias_lineas set categoria_med='$catLinea' where cod_med='$codMed' and codigo_linea='$codLinea'";
	mysql_query($sql);
	
	$sql_cat_rutero="SELECT rmd.cod_contacto, rmd.orden_visita, rmd.categoria_med from rutero_maestro_detalle rmd, rutero_maestro rm, rutero_maestro_cab rmc where rmd.cod_med='$codMed' and rmd.cod_contacto=rm.cod_contacto and rm.cod_rutero=rmc.cod_rutero and rmc.codigo_linea='$codLinea'";
	$resp_cat_rutero=mysql_query($sql_cat_rutero);
	while($dat_rutero=mysql_fetch_array($resp_cat_rutero)) {	
		$cod_contacto=$dat_rutero[0];
		$orden_visita=$dat_rutero[1];
		$cat_rutero=$dat_rutero[2];
		$sql_actualiza="UPDATE rutero_maestro_detalle set cod_especialidad='$especialidad', categoria_med='$h_categoria'where cod_contacto='$cod_contacto' and orden_visita='$orden_visita'";
		//echo $sql_actualiza;
		$resp_actualiza=mysql_query($sql_actualiza);			
	}
	
}

echo "<script language='Javascript'>
	alert('Los datos se modificaron satisfactoriamente');
	location.href='busquedaMedCat.php';
</script>";

?>