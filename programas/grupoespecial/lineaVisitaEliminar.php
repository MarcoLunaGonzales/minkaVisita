<html>
<head>
    <title>Lineas de visita</title>
    <link type="text/css" rel="stylesheet" href="../../stilos.css">
</head>
<body background="../../imagenes/fondo_pagina.jpg" >
    <center>
<?php

require("../../conexion.inc");

$cod_grupo    = $_GET["cod_grupo"];
$codigo_linea = $_GET["codigo_linea"];
$cod_ciudad   = $_GET["cod_ciudad"];

$lstCodLineaVisita = $_GET["lstcodlinvis"];

echo "<br>";
echo "<div class='textotit'>";
//echo "Edicion de Linea de visita en Grupo Especial";
echo "</div>";
echo "<br>";
$consulta = "DELETE FROM grupoespecial_lineavisita WHERE cod_grupo=$cod_grupo AND cod_l_visita IN ($lstCodLineaVisita) ";
//echo "$consulta";
$sw = mysql_query($consulta);
if($sw==1) {
    $cadenaMensaje="Se han eliminado las lineas de visita seleccionadas en el grupo especial.";
}else{
    $cadenaMensaje="No se pudo eliminar las lineas de visita en el grupo especial.";
}
echo "
<script type='text/javascript' language='javascript'>
    alert('$cadenaMensaje');
    location.href='navegadorLineasVisita.php?cod_grupo=$cod_grupo&codigo_linea=$codigo_linea&cod_ciudad=$cod_ciudad';
    //history.back(2);
</script>";

?>
    </center>
</body>
</html>
