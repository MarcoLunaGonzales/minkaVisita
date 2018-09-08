<script type='text/javascript' language='Javascript'>
    function envia_select(form) {
        form.submit();
        return(true);
    }
    function envia_formulario(f) {
        var gestionCicloRpt=f.gestionCicloRpt.value;
		var rpt_territorio=new Array();
		var j=0;
		for(i=0;i<=f.rpt_territorio.options.length-1;i++)
		{	if(f.rpt_territorio.options[i].selected)
			{	rpt_territorio[j]=f.rpt_territorio.options[i].value;
				j++;
			}
		}
		
//        var linea_rpt=f.linea_rpt.value;
        window.open('rptVerificacionPersonalizada.php?gestionCicloRpt='+gestionCicloRpt+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
        return(true);
    }
</script>
<?php
require("conexion.inc");
require("estilos_cuerpo.inc");

$gestionCicloRpt = $_GET["gestionCicloRpt"];

echo "<h1>Verificacion para la Distribucion de Parrillas Personalizadas <br> Seleccione el ciclo</h1>";

echo "<form metodh='GET'>";
echo "<center><table class='texto' align='center'>";

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

echo "<tr><th align='left'>Territorio</th>
	<td>
	<select name='rpt_territorio' class='texto'  size='12' multiple>";
	$sql="select c.cod_ciudad, c.descripcion from ciudades c order by c.descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
echo "</select></td></tr>";
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


echo "</table>";
echo "<input type='button' name='reporte' value='Verificar' onClick='envia_formulario(this.form)' class='boton'>";
echo "</form></center>";

?>
