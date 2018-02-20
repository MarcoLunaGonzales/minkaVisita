<script language='JavaScript'>
    function envia_formulario(f)
    {	var j,i;
        var rpt_territorio=new Array();
        var gestionCicloRpt=f.gestionCicloRpt.value;
        j=0;
        for(i=0;i<=f.rpt_territorio.options.length-1;i++)
        {	if(f.rpt_territorio.options[i].selected)
            {	rpt_territorio[j]=f.rpt_territorio.options[i].value;
                j++;
            }
        }
        window.open('rpt_funcionariosLineasVisita.php?rpt_territorio='+rpt_territorio+'&ciclo='+gestionCicloRpt+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
        return(true);
    }
</script>
<?php
require("conexion.inc");
require("estilos_administracion.inc");
echo "<center><table class='textotit'><tr><th>Funcionarios en Lineas de Visita</th></tr></table><br>";
echo"<form method='post'>";
echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
echo "<tr><th align='left'>Territorio</th>
	<td>
	<select name='rpt_territorio' class='texto'  size='12' multiple>";
$sql = "select c.cod_ciudad, c.descripcion from ciudades c, `funcionarios_agencias` f where 
				f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.descripcion";
$resp = mysql_query($sql);
while ($dat = mysql_fetch_array($resp)) {
    $codigo_ciudad = $dat[0];
    $nombre_ciudad = $dat[1];
    echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
}
echo "</select></td></tr>";
echo "<tr><th align='left'>Ciclo-Gestion</th>";
echo "<td>";
echo "<select name='gestionCicloRpt' class='texto'>";
$sql = "select distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g 
    where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,15";
$resp = mysql_query($sql);
while ($dat = mysql_fetch_array($resp)) {
    $codCiclo = $dat[0];
    $codGestion = $dat[1];
    $nombreGestion = $dat[2];
    echo "<option value='$codCiclo|$codGestion|$nombreGestion'>$codCiclo $nombreGestion</option>";
}
echo "</select>";
echo "</td>";
echo "</tr>";
echo"\n </table><br>";
echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
echo"</form>";
?>