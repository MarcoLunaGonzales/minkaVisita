<?php

require("../../conexion.inc");

$origen = $_GET['origen'];
$destino = $_GET['destino'];
$linea = $_GET['linea'];
$gestion = $_GET['gestion'];

$sql = "SELECT f.codigo_funcionario from funcionarios f, lineas_visita_visitadores fl, ciudades c where f.cod_cargo=1011 and f.estado=1 and f.codigo_funcionario=fl.codigo_funcionario and f.cod_ciudad=c.cod_ciudad and fl.codigo_l_visita='$linea' and fl.codigo_ciclo = $origen and codigo_gestion = $gestion order by 1";
$resp = mysql_query($sql);

while ($row = mysql_fetch_array($resp)) {
//    echo("Insert into lineas_visita_visitadores values($linea,$row[0],$destino,$gestion)");
    mysql_query("INSERT into lineas_visita_visitadores values($linea,$row[0],$destino,$gestion)");
}

echo json_encode("Contactos Replicados satisfactoriamente");
?>