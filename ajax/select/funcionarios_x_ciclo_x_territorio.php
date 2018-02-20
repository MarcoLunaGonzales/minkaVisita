<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$ciudades = $_GET['ciudades'];
$ciclo = $_GET['ciclo'];
$linea = $_GET['linea'];

$ciclos = explode("-", $ciclo);
$ciclos_finales = $ciclos[0];
$gestion = $ciclos[1];

/*echo "ciudad $ciudades";
echo "<br>linea $linea";*/

	foreach ($ciudades as $ciudad) {
		$ciudades_finales .= $ciudad . ",";
	}
	$ciudades_finales_sub = substr($ciudades_finales, 0, -1);
	$txtsql='SELECT DISTINCT f.codigo_funcionario, CONCAT(f.paterno," ", f.materno," ", f.nombres) as nom from funcionarios f, rutero_maestro_cab rc, rutero_maestro rm, rutero_maestro_detalle rd where rc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rd.cod_contacto and rc.cod_visitador = f.codigo_funcionario and rc.codigo_ciclo = "' . $ciclos_finales . '" and rc.codigo_gestion = "' . $gestion . '" and rc.estado_aprobado = 2 and f.cod_ciudad in (' . $ciudades_finales_sub . ') and rc.codigo_linea = "' . $linea . '"';
	//echo $txtsql;
	$results = mysql_query($txtsql);
	while (is_resource($results) && $row = mysql_fetch_object($results)) {
		$response .= "<option value='$row->codigo_funcionario'>" .  $row->nom . "</option>";
	}


echo json_encode($response);
?>