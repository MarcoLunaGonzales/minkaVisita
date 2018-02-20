<?php
require("conexion.inc");
$codigoCiclo=7;
$codigoGestion=1012;

$global_linea=1041;


$sql_medicos="select codigo_linea, cod_med, cod_especialidad, categoria_med from categorias_lineas where codigo_linea='$global_linea'";
$resp_medicos=mysql_query($sql_medicos);
while($dat_medicos=mysql_fetch_array($resp_medicos))
{	$codigo_linea=$dat_medicos[0];
	$cod_med=$dat_medicos[1];
	$cod_espe=$dat_medicos[2];
	$cat_med=$dat_medicos[3];
	$sql_cat_rutero="select rmd.cod_contacto, rmd.orden_visita, rmd.categoria_med
					from rutero_maestro_detalle_aprobado rmd, rutero_maestro_aprobado rm, rutero_maestro_cab_aprobado rmc
					where rmd.cod_med='$cod_med' and rmd.cod_contacto=rm.cod_contacto and rm.cod_rutero=rmc.cod_rutero and 
					rmc.codigo_ciclo='$codigoCiclo' and rmc.codigo_gestion='$codigoGestion' and rmc.codigo_linea='$global_linea'";
	$resp_cat_rutero=mysql_query($sql_cat_rutero);
	while($dat_rutero=mysql_fetch_array($resp_cat_rutero))
	{	$cod_contacto=$dat_rutero[0];
		$orden_visita=$dat_rutero[1];
		$cat_rutero=$dat_rutero[2];
		if($cat_rutero!=$cat_med)
		{	
			$sql_actualiza="update rutero_maestro_detalle_aprobado set categoria_med='$cat_med', cod_especialidad='$cod_espe' 
							where cod_contacto='$cod_contacto' and orden_visita='$orden_visita'";
			echo $sql_actualiza;				
			$resp_actualiza=mysql_query($sql_actualiza);
			if($resp_actualiza==1)
			{	echo "$codigo_linea $cod_med $cod_espe $cat_med $cat_rutero EXITOSO<br>";
			}
			
		}
	}
}

echo "TODO OK";
?>