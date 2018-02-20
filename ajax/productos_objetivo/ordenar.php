<?php
set_time_limit(0);
require("../../conexion.inc");

$codigos_productos   = $_POST['datos'];
$cantidades          = $_POST['datos2'];
$visitadores         = $_POST['datos3'];

$codigos_productos  = substr($codigos_productos, 0, -1);
$codigos_productos  = explode(",", $codigos_productos);

$cantidades = substr($cantidades, 0, -1);
$cantidades = explode(",", $cantidades);
$cantidades = array_chunk($cantidades, 3);

$visitadores = substr($visitadores, 0, -1);
$visitadores = explode(",", $visitadores);
$visitadores = array_unique($visitadores);

$contador_codigos = array_count_values($codigos_productos);
$cadena = '';
foreach($visitadores as $visitador){
    foreach ($cantidades as $key => $value) {
        if($visitador == $value[2]){
            $cadena .= "<td>".$value[1]."</td>";
            $cadena .= "<td>".$value[2]."</td>";
        }else{
            $cadena .= "<div>";
            $cadena .= "</div>";
        }
    }
}

echo $cadena;