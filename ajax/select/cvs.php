<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$territorio = $_GET['territorio'];
$territorio = implode(",", $territorio);

if($territorio==0){
	echo json_encode("<option value='0'>Escoga un funcionario por favor</option>");
}else{

	$results = mysql_query("SELECT DISTINCT codigo_funcionario, CONCAT(paterno,' ' ,materno, ' ',nombres) as nombre from funcionarios where cod_ciudad in ($territorio) and cod_cargo = 1022 ORDER BY 2");
	while (is_resource($results) && $row = mysql_fetch_object($results)) {
	    $response .= "<option value='$row->codigo_funcionario'>" .  $row->nombre . "</option>";
	}
	echo json_encode($response);	
}

?>