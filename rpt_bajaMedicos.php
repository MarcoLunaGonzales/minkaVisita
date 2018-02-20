<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("function_formatofecha.php");

$rpt_territorio = $_GET["rpt_territorio"];
$rpt_inicio     = $_GET["rpt_inicio"];
$rpt_final      = $_GET["rpt_final"];

$rpt_inicio = cambia_formatofecha($rpt_inicio);
$rpt_final  = cambia_formatofecha($rpt_final);

$sql_gestion       = mysql_query("SELECT codigo_gestion, nombre_gestion FROM gestiones WHERE estado = 'Activo'");
$dat_gestion       = mysql_fetch_array($sql_gestion);
$codigo_gestion    = $dat_gestion[0];
$gestion           = $dat_gestion[1];

$sql_nombreGestion = mysql_query("SELECT nombre_gestion FROM gestiones WHERE codigo_gestion = $codigo_gestion");
$dat_nombreGestion = mysql_fetch_array($sql_nombreGestion);
$nombreGestion     = $dat_nombreGestion[0];

echo "<center><table border='0' class='textotit' align='center'><tr><th>Baja de Medicos<br />Fecha Inicio: $rpt_inicio  Fecha Final: $rpt_final </th></tr></table></center><br>";

echo "<table border=1 class='texto' cellspacing='0' id='main' align='center'>";
echo "<tr><th>Territorio</th><th>M&acute;dico</th><th>Motivo</th><th>Inicio</th><th>Fin</th></tr>";
$sql = "SELECT c.descripcion, concat(m.ap_pat_med, ' ', m.nom_med) as medico, mo.descripcion_motivo, b.inicio, b.fin FROM baja_medicos b, medicos m, ciudades c, motivos_baja mo WHERE b.cod_med = m.cod_med AND m.cod_ciudad = c.cod_ciudad AND b.codigo_motivo = mo.codigo_motivo AND b.inicio BETWEEN '$rpt_inicio' AND '$rpt_final' AND m.cod_ciudad in ($rpt_territorio) ORDER BY c.descripcion, b.inicio";
echo $sql;
$resp=mysql_query($sql);

while( $dat = mysql_fetch_array($resp) ){
	$nombreTerritorio = $dat[0];
	$nombreMedico     = $dat[1];
	$motivo           = $dat[2];
	$fechaInicio      = $dat[3];
	$fechaFinal       = $dat[4];
	echo "<tr><td>$nombreTerritorio</td><td>$nombreMedico</td><td>$motivo</td><td>$fechaInicio</td><td>$fechaFinal</td></tr>";
}
echo "</table>";
?>