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

$codLineaVisita    = $_GET["codlinvis"];
$codLineaVisitaNew = $_GET["codlinvisnew"];

echo "<br>";
echo "<div class='textotit'>";
echo "Edicion de Linea de visita en Grupo Especial";
echo "</div>";
echo "<br>";
if($codLineaVisita!=$codLineaVisitaNew) {
    $consulta = "UPDATE grupoespecial_lineavisita SET cod_l_visita=$codLineaVisitaNew WHERE cod_grupo=$cod_grupo AND cod_l_visita=$codLineaVisita ";
    //echo "$consulta";
    $sw = mysql_query($consulta);
    if($sw==1) {
        $cadenaMensaje="Se ha modificado la linea visita en el grupo especial.";
    }else{
        $cadenaMensaje="No se pudo modificar la linea visita en el grupo especial.";
    }
}else{
    $cadenaMensaje="Las lineas de visita son las mismas.";
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
