<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$ciclo_de = $_GET['ciclo_de'];
$ciclo_para = $_GET['ciclo_para'];
$funcionarios = $_GET['funcionarios'];

$ciclo_de_f = explode("-", $ciclo_de);
$ciclo_de_final = $ciclo_de_f[0];
$gestion_de_final = $ciclo_de_f[1];

$ciclo_para_f = explode("-", $ciclo_para);
$ciclo_para_final = $ciclo_para_f[0];
$gestion_para_final = $ciclo_para_f[1];

foreach ($funcionarios as $funcionario) {
    $funcionario_final .= $funcionario . ",";
}
$funcionario_final_sub = substr($funcionario_final, 0, -1);

$results = mysql_query("SELECT * from lineas_visita_visitadores where codigo_funcionario in ($funcionario_final_sub) and codigo_ciclo = $ciclo_de_final and codigo_gestion = $gestion_de_final");

while($row_i =  mysql_fetch_array($results)){
    mysql_query("INSERT into lineas_visita_visitadores values($row_i[0],$row_i[1],$ciclo_para_final,$gestion_para_final)");
}

echo json_encode("Datos replicados satisfactoriamente.");
?>