<?php
require("conexion.inc");
require("estilos_regional_pri.inc");
$sql_cab = "select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
$resp_cab = mysql_query($sql_cab);
$dat_cab = mysql_fetch_array($resp_cab);
$nombre_funcionario = "$dat_cab[0] $dat_cab[1] $dat_cab[2]";
echo "<form method='post' action=''>";
echo "<center><table border='0' class='textotit'><tr><th>Ruteros Medicos Maestro Aprobados x Ciclo<br>
							Visitador: $nombre_funcionario</th></tr></table></center><br>";
echo "<center><table border='1' width='50%' cellspacing='0' class='texto'><tr><th>&nbsp;</th>
<th>Nombre de Rutero</th><th>Ciclo Asociado</th><th>Estado</th><th>&nbsp;</th></tr>";
$sql = "select cod_rutero, nombre_rutero, estado_aprobado, codigo_ciclo, codigo_gestion from rutero_maestro_cab_aprobado 
				where cod_visitador='$visitador' and codigo_linea='$global_linea' order by codigo_gestion desc, codigo_ciclo desc";
$resp = mysql_query($sql);
$filas_ruteros = mysql_num_rows($resp);
while ($dat = mysql_fetch_array($resp)) {
    $cod_rutero = $dat[0];
    $nombre_rutero = $dat[1];
    $estado = $dat[2];
    $codCiclo = $dat[3];
    $codigoGestion = $dat[4];

    $sqlGestion = "select nombre_gestion from gestiones where codigo_gestion=$codigoGestion";
    $respGestion = mysql_query($sqlGestion);
    $datGestion = mysql_fetch_array($respGestion);
    $nombreGestion = $datGestion[0];

    if ($estado == 0) {
        $estado_desc = "No Aprobado";
    } 
    if ($estado == 1) {
        $estado_desc = "Aprobado";
    } 
    if ($estado == 3) {
        $estado_desc = "Aprobado Individualmente";
    } 
    echo"<tr><td>&nbsp;</td>
    <td align='center'>$nombre_rutero</td><td>$codCiclo/$nombreGestion</td>
    <td align='center'>$estado_desc</td>
    <td align='center'>
	<a href='funcionario_rutero_maestro_todo.php?rutero=$cod_rutero&visitador=$visitador&vista=2'>Ver</a>--- 
	<a href='ruteroConsolidado.php?codCiclo=$codCiclo&codGestion=$codigoGestion&visitador=$visitador&vista=1'>Consolidado</a>
	</td></tr>";
} 
echo "</table><br>";
echo "</form>";

?>