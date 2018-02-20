<?php

set_time_limit(0);
require("../../conexion.inc");

$ciclo = $_POST['ciclo'];
$gestion = $_POST['gestion'];
$cadena = $_POST['cadena'];
$ciudades = $_POST['ciudades'];
$material_apoyo = $_POST['ma'];
$cadena_explode = explode("@",$cadena);
$especialidad = $cadena_explode[0];
$linea = $cadena_explode[1];
$producto = $cadena_explode[2];
$linea_mkt=$_POST['linea_mkt'];

$material_apoyo_explode = explode("@", $material_apoyo);
$nombre_material_apoyo = $material_apoyo_explode[0];
$codigo_material_apoyo = $material_apoyo_explode[1];

// echo $ciclo."-".$gestion."-".$cadena."-".$nombre_material_apoyo."-".$codigo_material_apoyo;

$ciudades = substr($ciudades, 0, -1);
$ciudades = str_replace("*", ",", $ciudades);

/*Aqui buscados el codigo del la linea de visita de los visitadores*/

/*$especialidad_linea =  $especialidad." ".$linea;

$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio where nom_generico = '$especialidad_linea'");
$codigo_l_visita = mysql_result($sql_codigo_l_visita, 0, 0);

$sql_visitadores = mysql_query("SELECT DISTINCT lvv.codigo_funcionario from lineas_visita_visitadores lvv, lineas_visita lv, funcionarios f where lv.codigo_l_visita = lvv.codigo_l_visita and f.codigo_funcionario = lvv.codigo_funcionario and lvv.codigo_ciclo = $ciclo and lvv.codigo_gestion = $gestion and lv.codigo_l_visita = $codigo_l_visita and f.cod_ciudad in ($ciudades)");
// echo("SELECT DISTINCT lvv.codigo_funcionario from lineas_visita_visitadores lvv, lineas_visita lv, funcionarios f where lv.codigo_l_visita = lvv.codigo_l_visita and f.codigo_funcionario = lvv.codigo_funcionario and lvv.codigo_ciclo = $ciclo and lvv.codigo_gestion = $gestion and lv.codigo_l_visita = $codigo_l_visita and f.cod_ciudad in ($ciudades)");

while ($row_vi = mysql_fetch_array($sql_visitadores)) {
	$codigos_funcionarios .= $row_vi[0].","; 
}

$codigos_funcionarios_final = substr($codigos_funcionarios, 0, -1);

*/
/*FIN*/

/*PARTE NUEVA LINEAS DOLOR - NEURO*/
$txt_visitadores="SELECT DISTINCT f.codigo_funcionario from funcionarios f, funcionarios_lineas fli 
	where f.codigo_funcionario = fli.codigo_funcionario and fli.codigo_linea in ($linea_mkt) 
	and f.cod_ciudad in ($ciudades)";

	//echo $txt_visitadores;

//SELECT DISTINCT lvv.codigo_funcionario from lineas_visita_visitadores lvv, lineas_visita lv, funcionarios f 
//where lv.codigo_l_visita = lvv.codigo_l_visita and f.codigo_funcionario = lvv.codigo_funcionario and 
//lvv.codigo_ciclo = $ciclo and lvv.codigo_gestion = $gestion and lv.codigo_l_visita = $codigo_l_visita and f.cod_ciudad in ($ciudades)

$sql_visitadores = mysql_query($txt_visitadores);

while ($row_vi = mysql_fetch_array($sql_visitadores)) {
	$codigos_funcionarios .= $row_vi[0].","; 
}
$codigos_funcionarios_final = substr($codigos_funcionarios, 0, -1);
/*fin parte nueva*/

//$sql_cantidad_a = mysql_query("SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion and rmc.codigo_ciclo = $ciclo and rmd.categoria_med = 'A' and rmd.cod_especialidad = '$especialidad' and rmd.cod_visitador in ($codigos_funcionarios_final) and m.cod_ciudad in ($ciudades) and rmc.codigo_linea in ($linea_mkt)");
$sql_cantidad_a = mysql_query("SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion and rmc.codigo_ciclo = $ciclo and rmd.categoria_med = 'A' and rmd.cod_especialidad = '$especialidad' and rmc.codigo_linea in ($linea_mkt)");
$cantidad_final_a = mysql_result($sql_cantidad_a, 0,0);

//$sql_cantidad_b = mysql_query("SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion and rmc.codigo_ciclo = $ciclo and rmd.categoria_med = 'B' and rmd.cod_especialidad = '$especialidad' and rmd.cod_visitador in ($codigos_funcionarios_final) and m.cod_ciudad in ($ciudades) and rmc.codigo_linea in ($linea_mkt)");
$sql_cantidad_b = mysql_query("SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion and rmc.codigo_ciclo = $ciclo and rmd.categoria_med = 'B' and rmd.cod_especialidad = '$especialidad' and rmc.codigo_linea in ($linea_mkt)");
$cantidad_final_b = mysql_result($sql_cantidad_b, 0,0);

//$sql_cantidad_c = mysql_query("SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion and rmc.codigo_ciclo = $ciclo and rmd.categoria_med = 'C' and rmd.cod_especialidad = '$especialidad' and rmd.cod_visitador in ($codigos_funcionarios_final) and m.cod_ciudad in ($ciudades) and rmc.codigo_linea in ($linea_mkt)");
$sql_cantidad_c = mysql_query("SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion and rmc.codigo_ciclo = $ciclo and rmd.categoria_med = 'C' and rmd.cod_especialidad = '$especialidad' and rmc.codigo_linea in ($linea_mkt)");
$cantidad_final_c = mysql_result($sql_cantidad_c, 0,0);


/*Existencias segun Kardex*/

$sql_fechas_ingresos="SELECT sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='1000' and 
i.grupo_ingreso=2 and i.ingreso_anulado=0 and id.cod_material='$codigo_material_apoyo'";
// echo $sql_fechas_ingresos;
$resp_fechas_ingresos=mysql_query($sql_fechas_ingresos);
$dat_kardex_ingresos=mysql_fetch_array($resp_fechas_ingresos);
$cantidad_ingreso_kardex=$dat_kardex_ingresos[0];

$sql_fechas_salidas="SELECT sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='1000' and 
s.grupo_salida=2 and s.salida_anulada=0 and sd.cod_material='$codigo_material_apoyo'";
// echo $sql_fechas_salidas;
$resp_fechas_salidas=mysql_query($sql_fechas_salidas);
$dat_kardex_salidas=mysql_fetch_array($resp_fechas_salidas);
$cantidad_salida_kardex=$dat_kardex_salidas[0];

$existencia_final=$cantidad_ingreso_kardex-$cantidad_salida_kardex;

/*Fin Existencias*/

//$cantidad_final_a=4; $cantidad_final_b=9; $cantidad_final_c=11;
$arr = array("cantidadd_a" => $cantidad_final_a, "cantidadd_b" => $cantidad_final_b,  "cantidadd_c" => $cantidad_final_c, "existencia" => $existencia_final);
echo json_encode($arr);

?>