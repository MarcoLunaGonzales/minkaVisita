<?php


header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$especialidades = $_GET['especialidades'];
$funcionarios = $_GET['funcionarios'];
$arrayCiclo=explode('-',$ciclos);
$codCiclo=$arrayCiclo[0];
$codGestion=$arrayCiclo[1];
echo $ciclo[0];
foreach ($funcionarios as $funcionario) {
	
	foreach ($especialidades as $especialidad) {
		mysql_query("INSERT into lineas_visita_visitadores values ($especialidad,$funcionario,$codCiclo,$codGestion)");
		// echo("insert into lineas_visita_visitadores values ($especialidad,$funcionario,$codCiclo,$codGestion)");
	}
}

echo json_encode("Datos replicados satisfactoriamente.");
?>