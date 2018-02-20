<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2005 
 */
require("conexion.inc");
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
error_reporting(0);
require("estilos_almacenes.inc");
$tipo = 2;
$cod_ciclo = $_GET['cod_ciclo'];
$cod_gestion = $_GET['cod_gestion'];

if ($global_usuario == 1100) {
    $sqlVis = "select d.codigo_devolucion, f.codigo_funcionario, f.paterno, f.materno, f.nombres, d.tipo_devolucion from devoluciones_ciclo d, funcionarios f where d.codigo_ciclo=$cod_ciclo and d.codigo_gestion='$cod_gestion' and d.codigo_visitador=f.codigo_funcionario and f.cod_ciudad in (122,116,124)  and d.tipo_devolucion in (1,2)";
} else {
    $sqlVis = "select d.codigo_devolucion, f.codigo_funcionario, f.paterno, f.materno, f.nombres, d.tipo_devolucion from devoluciones_ciclo d, 
				funcionarios f where d.codigo_ciclo=$cod_ciclo and 
				d.codigo_gestion='$cod_gestion' and d.codigo_visitador=f.codigo_funcionario 
				and f.cod_ciudad='$global_agencia' and d.tipo_devolucion in (1,2)";
}

// echo $sqlVis;

$respVis = mysql_query($sqlVis);
echo "<center><table border='0' class='textotit'><tr><th>Devolucion de MM y MA por Visitador<br>Ciclo: $cod_ciclo</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
echo "<tr><th>Visitador</th><th>Tipo de Devolucion</th><th>Ver>></th></tr>";
while ($datVis = mysql_fetch_array($respVis)) {
    $codDevolucion = $datVis[0];
    $codVisitador = $datVis[1];
    $nombreVis = "$datVis[2] $datVis[3] $datVis[4]";
    $tipoDevolucion = $datVis[5];

    if ($tipoDevolucion == 1) {
        $nombreTipoDevolucion = "Muestras Medicas";
        $link = "registro_devolucion_almacen.php?cod_ciclo=$cod_ciclo&cod_gestion=$cod_gestion&cod_visitador=$codVisitador&cod_devolucion=$codDevolucion&tipo=$tipo";
    }
    if ($tipoDevolucion == 2) {
        $nombreTipoDevolucion = "Material de Apoyo";
        $link = "registro_devolucion_almacenMA.php?cod_ciclo=$cod_ciclo&cod_gestion=$cod_gestion&cod_visitador=$codVisitador&cod_devolucion=$codDevolucion&tipo=$tipo";
    }

    echo "<tr>
		<td>$nombreVis</td>
		<td>$nombreTipoDevolucion</td>";

    echo"<td align='center'>
		<a href='$link'>
		Ver>></a></td>
		</tr>";
}
echo "</table></center><br>";
echo "</form>";
?>