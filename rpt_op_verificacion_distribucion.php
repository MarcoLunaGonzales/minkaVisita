<script type='text/javascript' language='Javascript'>
    function envia_select(form) {
        form.submit();
        return(true);
    }
    function envia_formulario(f) {
        var gestionCicloRpt=f.gestionCicloRpt.value;
//        var linea_rpt=f.linea_rpt.value;
        window.open('rptVerificacionDistribucion.php?gestionCicloRpt='+gestionCicloRpt+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
        return(true);
    }
</script>
<?php
require("conexion.inc");
require("estilos_cuerpo.inc");

$gestionCicloRpt = $_GET["gestionCicloRpt"];

echo "<center>";
echo "<table class='textotit'><tr><th>Seleccione el ciclo</th></tr></table><br>";
echo "<form metodh='GET'>";
echo "<table class='texto' border='1' align='center' cellSpacing='0'>";

echo "<tr><th align='left'>Ciclo - Gestion</th>";
echo "<td><select name='gestionCicloRpt' class='texto'>";
$sql = "select distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g
				where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,11";
$resp = mysql_query($sql);
while ($dat = mysql_fetch_array($resp)) {
    $codCiclo = $dat[0];
    $codGestion = $dat[1];
    $nombreGestion = $dat[2];
    echo "<option value='$codCiclo|$codGestion'>$codCiclo $nombreGestion</option>";
}
echo "</select></td>";
echo "</tr>";
//echo "<tr><th>L&iacute;nea</th>";
//$sql_linea = "select codigo_linea, nombre_linea from lineas where linea_promocion=1 order by nombre_linea";
//$resp_linea = mysql_query($sql_linea);
//echo "<td><select name='linea_rpt' class='texto'>";
//while ($datos_linea = mysql_fetch_array($resp_linea)) {
//    $cod_linea_rpt = $datos_linea[0];
//    $nom_linea_rpt = $datos_linea[1];
//    echo "<option value='$cod_linea_rpt'>$nom_linea_rpt</option>";
//}
//echo "</select></td>";
//echo "</tr>";


echo "</table><br>";
echo "<input type='button' name='reporte' value='Verificar' onClick='envia_formulario(this.form)' class='boton'>";
echo "</form>";
echo "</center>";
?>
