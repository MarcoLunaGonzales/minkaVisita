<?php
require('conexion.inc');
$codigo_parrilla = $_GET['parrilla'];
$prioridad = $_GET['prioridad'];
$codigo = $_GET['codigo'];
$producto = $_GET['producto'];

    $sqlUpd = "update parrilla_detalle set codigo_material='$codigo' where codigo_parrilla='$codigo_parrilla' and prioridad=$prioridad";
    $respUpd = mysql_query($sqlUpd);

$sqlVerifica = "select count(codigo_material) from parrilla_detalle where codigo_parrilla='$codigo_parrilla' and codigo_material='$codigo'";
$respVerifica = mysql_query($sqlVerifica);
$datVerifica = mysql_fetch_array($respVerifica);
$filas = $datVerifica[0];

if ($filas > 1 && $codigo!=0) {
    echo "<strong>NO PUEDE REPETIR EL MISMO MATERIAL DE APOYO EN LA PARRILLA!!!</strong>";
} else {
	echo $producto;
}

?>