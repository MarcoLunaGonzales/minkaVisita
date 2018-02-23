<html>
<head>
    <title>Lineas de visita</title>
    <link type="text/css" rel="stylesheet" href="../../stilos.css">
    <script type='text/javascript' language='javascript'>
function guardarAdiLineaVisita(codGrupo,codLinea,codCiudad)
{
    var tag=document.getElementById("cmbcodlinvis");
    var linvis=tag.value;
    location.href='lineaVisitaAdicionarGuardar.php?cod_grupo='+codGrupo+'&codigo_linea='+codLinea+'&cod_ciudad='+codCiudad+'&codlinvis='+linvis+'';
}
function cancelarAdiLineaVisita(codGrupo,codLinea,codCiudad)
{
    location.href='navegadorLineasVisita.php?cod_grupo='+codGrupo+'&codigo_linea='+codLinea+'&cod_ciudad='+codCiudad+'';
}
    </script>
</head>
<body background="../../imagenes/fondo_pagina.jpg" >
    <center>
<?php

require("../../conexion.inc");

$cod_grupo    = $_GET["cod_grupo"];
$codigo_linea = $_GET["codigo_linea"];
$cod_ciudad   = $_GET["cod_ciudad"];

echo "<br>";
echo "<div class='textotit'>";
echo "Adicion de Linea de visita en Grupo Especial";
echo "</div>";
echo "<br>";
$consulta = "
    SELECT lv.codigo_l_visita, lv.nombre_l_visita
    FROM lineas_visita AS lv
    WHERE lv.codigo_linea = $codigo_linea
    AND lv.codigo_l_visita NOT IN (SELECT gelv.cod_l_visita FROM grupoespecial_lineavisita AS gelv WHERE gelv.cod_grupo=$cod_grupo)
    ORDER BY lv.nombre_l_visita ASC ";
//echo "$consulta";
$rs1 = mysql_query($consulta);
echo "<table border='0'>";
echo "<tr><td>";
echo "<select id='cmbcodlinvis'>";
while ($reg = mysql_fetch_array($rs1)) {
    $contador++;
    $codLineaVisita = $reg[0];
    $nomLineaVisita = $reg[1];
    echo "<option value='$codLineaVisita'>$nomLineaVisita</option>";
}
echo "</select>";
echo "</td></tr>";
echo "</table>";
echo "<br>";

echo "<table border='0' class='texto'><tr>";
echo "<td><td><a href='javascript:history.back(1);'><img  border='0'src='../../imagenes/volver.gif' width='15' height='8'></a></td>";
echo "</tr></table>";

echo "<table border='0' class='texto'><tr>";
echo "<td><input type='button' value='Guardar' class='boton' onclick='javascript:guardarAdiLineaVisita($cod_grupo,$codigo_linea,$cod_ciudad)'></td>";
echo "<td><input type='button' value='Cancelar' class='boton' onclick='javascript:cancelarAdiLineaVisita($cod_grupo,$codigo_linea,$cod_ciudad)'></td>";
echo "</tr></table>";

?>
    </center>
</body>
</html>
