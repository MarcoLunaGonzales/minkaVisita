<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$ciudades = $_GET['ciudades'];
$especialidades = $_GET['especialidades'];
$ciclo = $_GET['ciclo'];

$ciclos = explode("-", $ciclo);
$ciclos_finales = $ciclos[0];
$gestion = $ciclos[1];
foreach ($ciudades as $ciudad) {
    $ciudades_finales .= $ciudad . ",";
}
$ciudades_finales_sub = substr($ciudades_finales, 0, -1);

foreach ($especialidades as $especialidad) {
    $especialidades_finales .= "'" . $especialidad . "',";
}
$especialidades_finales_sub = substr($especialidades_finales, 0, -1);

/*echo 'select f.codigo_funcionario, CONCAT(f.paterno," ", f.materno," ", f.nombres) as nom, c.descripcion from funcionarios f, ciudades c  where f.cod_ciudad=c.cod_ciudad and f.estado=1  
    and f.cod_cargo=1011 and c.cod_ciudad in (' . $ciudades_finales_sub . ')  and  f.codigo_funcionario  in  (select l.codigo_funcionario from lineas_visita_visitadores l 
    WHERE l.codigo_l_visita in (' . $especialidades_finales_sub . ' )
        and l.codigo_ciclo = "' . $ciclos_finales . '" 
            and l.codigo_gestion = "' . $gestion . '" ) order by nom, c.descripcion;';*/

			/*echo 'select f.codigo_funcionario, CONCAT(f.paterno," ", f.materno," ", f.nombres) as nom, c.descripcion from funcionarios f, ciudades c  where f.cod_ciudad=c.cod_ciudad and f.estado=1  
    and f.cod_cargo=1011 and c.cod_ciudad in (' . $ciudades_finales_sub . ')  and  f.codigo_funcionario  in  (select l.codigo_funcionario from lineas_visita_visitadores l 
    WHERE l.codigo_l_visita in (' . $especialidades_finales_sub . ' )
        and l.codigo_ciclo = "' . $ciclos_finales . '" 
            and l.codigo_gestion = "' . $gestion . '" ) order by nom, c.descripcion;';*/
			
$results = mysql_query('select f.codigo_funcionario, CONCAT(f.paterno," ", f.materno," ", f.nombres) as nom, c.descripcion from funcionarios f, ciudades c  where f.cod_ciudad=c.cod_ciudad and f.estado=1  
    and f.cod_cargo=1011 and c.cod_ciudad in (' . $ciudades_finales_sub . ')  and  f.codigo_funcionario  in  (select l.codigo_funcionario from lineas_visita_visitadores l 
    WHERE l.codigo_l_visita in (' . $especialidades_finales_sub . ' )
        and l.codigo_ciclo = "' . $ciclos_finales . '" 
            and l.codigo_gestion = "' . $gestion . '" ) order by nom, c.descripcion;');
while (is_resource($results) && $row = mysql_fetch_object($results)) {
    $response .= "<option value='$row->codigo_funcionario'>" .  $row->nom . "</option>";
}
echo json_encode($response);
?>