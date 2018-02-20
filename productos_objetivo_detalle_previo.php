<?php
error_reporting(0);
require("conexion.inc");
$ciclo = $_GET['ciclo'];
$fecha = $_GET["fecha"];
$cuantos = $_GET["cuantos"];
$desde = $_GET["desde"];
$territorios = $_GET["territorios"];
$tipo = $_GET["tipo"];
$explode_ciclo = explode("|", $ciclo);
$codigo_ciclo = $explode_ciclo[0];
$codigo_gestion = $explode_ciclo[1];

$sql_relegados = mysql_query("SELECT pod.cod_muestra_medica, CONCAT(m.descripcion, ' ', m.presentacion) FROM productos_objetivo_relegados pod, muestras_medicas m, productos_objetivo_cabecera poc where pod.cod_muestra_medica = m.codigo and poc.id = pod.id_cabecera and poc.ciclo = $codigo_ciclo and poc.gestion = $codigo_gestion");
while($row_relegados = mysql_fetch_array($sql_relegados)){
    $tags_relegados .= $row_relegados[1]."@".$row_relegados[0].",";
}

$sql = mysql_query("SELECT count(*) from productos_objetivo_cabecera where ciclo = $codigo_ciclo and gestion = $codigo_gestion");
if(mysql_result($sql, 0, 0) > 0){
    header('Location: productos_objetivo_detalle_editar.php?ciclo='.$ciclo.'&fecha='.$fecha.'&territorios='.$territorios.'&cuantos='.$cuantos.'&desde='.$desde.'&tipo='.$tipo.'&tags='.$tags_relegados, true);
}else{
    header('Location: productos_objetivo_detalle.php?ciclo='.$ciclo.'&fecha='.$fecha.'&territorios='.$territorios.'&cuantos='.$cuantos.'&desde='.$desde.'&tipo='.$tipo, true);
}

?>