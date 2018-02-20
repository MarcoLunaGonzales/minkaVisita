<?php
require('conexion.inc');
$codigo_parrilla = $_GET['parrilla'];
$prioridad = $_GET['prioridad'];
$codigo = $_GET['codigo'];
$producto = $_GET['producto'];

    $sqlUpd = "update parrilla_detalle set codigo_muestra='$codigo' where codigo_parrilla='$codigo_parrilla' and prioridad=$prioridad";
    $respUpd = mysql_query($sqlUpd);

$sqlVerifica = "select count(codigo_muestra) from parrilla_detalle where codigo_parrilla='$codigo_parrilla' and codigo_muestra='$codigo'";
$respVerifica = mysql_query($sqlVerifica);
$datVerifica = mysql_fetch_array($respVerifica);
$filas = $datVerifica[0];

if ($filas > 1) {
    echo "<strong>NO PUEDE REPETIR EL MISMO PRODUCTO EN LA PARRILLA!!!</strong>";
} else {
	echo $producto;
}

?>