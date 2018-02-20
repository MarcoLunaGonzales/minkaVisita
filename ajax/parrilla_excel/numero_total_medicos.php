<?php

set_time_limit(0);
require("../../conexion.inc");

$ciclo = $_POST['ciclo'];
$gestion = $_POST['gestion'];
$cadena = $_POST['candea'];
$ciudades = $_POST['ciudades'];

$ciudades = substr($ciudades, 0, -1);
$ciudades = str_replace("*", ",", $ciudades);

/*Aqui buscados el codigo del la linea de visita de los visitadores*/

$cadena_explode = explode(",",$cadena);
$cadena_fin = '';
foreach ($cadena_explode as $key ) {
	$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio where nom_generico = '$key'");
	$codigo_l_visita = mysql_result($sql_codigo_l_visita, 0, 0);	
	$cadena_fin .= "'".$codigo_l_visita."',";
}
$cadena_fin = substr($cadena_fin, 0, -1);

$sql_visitadores = mysql_query("SELECT DISTINCT lvv.codigo_funcionario, le.cod_especialidad from lineas_visita_visitadores lvv, lineas_visita lv, lineas_visita_especialidad le, funcionarios f where lv.codigo_l_visita = lvv.codigo_l_visita and f.codigo_funcionario = lvv.codigo_funcionario  and lv.codigo_l_visita = le.codigo_l_visita and lvv.codigo_ciclo = $ciclo and lvv.codigo_gestion = $gestion and lv.codigo_l_visita in ($cadena_fin) and f.cod_ciudad in ($ciudades)");

while ($row_vi = mysql_fetch_array($sql_visitadores)) {
	$codigos_funcionarios .= $row_vi[0].","; 
	$codigos_especialidades .= "'".$row_vi[1]."',";
}

$codigos_funcionarios_final = substr($codigos_funcionarios, 0, -1);
$codigos_especialidades_final = substr($codigos_especialidades, 0, -1);


/*FIN*/

$sql_cantidad = mysql_query("SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion and rmc.codigo_ciclo = $ciclo and rmd.categoria_med in ('A','B','C') and rmd.cod_especialidad in ($codigos_especialidades_final) and rmd.cod_visitador in ($codigos_funcionarios_final) and m.cod_ciudad in ($ciudades)");
$cantidad_final = mysql_result($sql_cantidad, 0,0);

$arr = array("cantidadd" => $cantidad_final);
echo json_encode($arr);

?>