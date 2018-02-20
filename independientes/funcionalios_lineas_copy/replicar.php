<?php  
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");

$ciclo_gestion = $_GET['ciclo_gestion'];
$ciclo_gestion = explode("-", $ciclo_gestion);

$ciclo = $ciclo_gestion[0];
$gestion = $ciclo_gestion[1];


$sql_funcionarios = mysql_query("SELECT * from lineas_visita_visitadores where codigo_ciclo = $ciclo and codigo_gestion = $gestion");
$sql_copy_delete  = mysql_query("DELETE from lineas_visita_visitadores_copy where codigo_ciclo = $ciclo and codigo_gestion = $gestion");

while ($row_funcionarios = mysql_fetch_array($sql_funcionarios)) {
	$sql1 = mysql_query("INSERT into lineas_visita_visitadores_copy values($row_funcionarios[0],$row_funcionarios[1],$ciclo,$gestion,1021)");
}


$txt_lineas="SELECT rc.cod_visitador, rc.codigo_linea, rd.cod_especialidad, lv.codigo_l_visita FROM 
	rutero_maestro_aprobado r, rutero_maestro_cab_aprobado rc, rutero_maestro_detalle_aprobado rd, 
	lineas_visita_visitadores lv, lineas_visita_especialidad le, lineas_visita ll 
	WHERE r.cod_rutero = rc.cod_rutero AND 
	r.cod_contacto = rd.cod_contacto AND le.codigo_l_visita = lv.codigo_l_visita  AND le.cod_especialidad = rd.cod_especialidad 
	AND rc.codigo_ciclo = $ciclo AND rc.codigo_gestion = $gestion AND rc.codigo_linea in (1009,1022,1023,1031) 
	and  ll.codigo_l_visita=lv.codigo_l_visita and ll.unica=1
	GROUP BY rc.cod_visitador, rc.codigo_linea, rd.cod_especialidad ORDER BY 3;";

$sql_lineas = mysql_query($txt_lineas);
// echo("SELECT rc.cod_visitador, rc.codigo_linea, rd.cod_especialidad, lv.codigo_l_visita FROM rutero_maestro_aprobado r, rutero_maestro_cab_aprobado rc, rutero_maestro_detalle_aprobado rd, lineas_visita_visitadores lv, lineas_visita_especialidad le WHERE r.cod_rutero = rc.cod_rutero AND r.cod_contacto = rd.cod_contacto AND le.codigo_l_visita = lv.codigo_l_visita  AND le.cod_especialidad = rd.cod_especialidad AND rc.codigo_ciclo = $ciclo AND rc.codigo_gestion = $gestion AND rc.codigo_linea in (1009,1022,1023,1031) GROUP BY rc.cod_visitador, rc.codigo_linea, rd.cod_especialidad ORDER BY 3;");
while ($row_lineas = mysql_fetch_array($sql_lineas)) {
	$sql2 = mysql_query("INSERT into lineas_visita_visitadores_copy values($row_lineas[3],$row_lineas[0],$ciclo, $gestion, $row_lineas[1])");
}
if($sql1){
	echo "Guardado satisfactoriamente";
}

?>	