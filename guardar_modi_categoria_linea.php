<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
require("conexion.inc");
require("estilos_regional_pri.inc");
for($i=0;$i<=$numero_medicos;$i++) {
	$medico="cod_med$i";
	$espe="especialidad$i";
	$categoria="h_categoria$i";
	$frecuenciaPermitida="frecuencia_permitida$i";
	$cod_med=$$medico;
	$especialidad=$$espe;
	$h_categoria=$$categoria;
	$frecuenciaPermitida1=$$frecuenciaPermitida;
	
	$sql="UPDATE categorias_lineas set categoria_med='$h_categoria', cod_especialidad='$especialidad', frecuencia_permitida='$frecuenciaPermitida1'where cod_med='$cod_med' and codigo_linea='$global_linea'";
//echo $sql;
	$resp=mysql_query($sql);
	$categoria_corregir=$h_categoria;
//esta parte actualiza los ruteros maestro que contengan a este medicos en esta especialidad
	$sql_cat_rutero="SELECT rmd.cod_contacto, rmd.orden_visita, rmd.categoria_med from rutero_maestro_detalle rmd, rutero_maestro rm, rutero_maestro_cab rmc where rmd.cod_med='$cod_med' and rmd.cod_contacto=rm.cod_contacto and rm.cod_rutero=rmc.cod_rutero and rmc.codigo_linea='$global_linea'";
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
alert('Los datos se modificaron correctamente.');
location.href='navegador_medicos_lineas.php';
</script>";
?>