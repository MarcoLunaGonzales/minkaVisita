<?php

require("../../conexion.inc");

$origen = $_GET['origen'];
$destino = $_GET['destino'];
$gestion = $_GET['gestion'];

$sql1 = "SELECT codigo_l_visita from lineas_visita where codigo_linea='1021' and codigo_l_visita <> 0 order by nombre_l_visita";
$resp1 = mysql_query($sql1);
while ($row_l = mysql_fetch_array($resp1)) {
    $codigo_linea_v = $row_l[0];
    $sql = "SELECT f.codigo_funcionario from funcionarios f, lineas_visita_visitadores fl, ciudades c where f.cod_cargo=1011 and f.estado=1 and f.codigo_funcionario=fl.codigo_funcionario and f.cod_ciudad=c.cod_ciudad and fl.codigo_l_visita='$codigo_linea_v' and fl.codigo_ciclo = $origen and codigo_gestion = $gestion order by 1";
    $resp = mysql_query($sql);
    while ($row = mysql_fetch_array($resp)) {
//    echo("Insert into lineas_visita_visitadores values($linea,$row[0],$destino,$gestion)");
        mysql_query("INSERT into lineas_visita_visitadores values($codigo_linea_v,$row[0],$destino,$gestion)");
    }
}

echo json_encode("Contactos Replicados satisfactoriamente");
?>