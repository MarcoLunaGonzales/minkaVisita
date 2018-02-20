<script language='JavaScript'>
    function envia_formulario(f)
    {	var visitador,rutero_maestro;
        var tipoRuteroRpt=f.tipoRuteroRpt.value;
        var gestionCicloRpt=f.gestionCicloRpt.value;
        var rpt_visitador=new Array();	
        var rpt_zona=new Array();	
        var j=0,z=0;
        for(var i=0;i<=f.rpt_visitador.options.length-1;i++)
        {	if(f.rpt_visitador.options[i].selected)
            {	rpt_visitador[j]=f.rpt_visitador.options[i].value;
                j++;
            }
        }
        for(var s=0;s<=f.rpt_zona.options.length-1;s++)
        {	if(f.rpt_zona.options[s].selected)
            {	rpt_zona[z]=f.rpt_zona.options[s].value;
                z++;
            }
        }
        window.open('rpt_medicos_rutero_maestro1.php?rpt_visitador='+rpt_visitador+'&tipoRuteroRpt='+tipoRuteroRpt+'&gestionCicloRpt='+gestionCicloRpt+'&zonas='+rpt_zona+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
        return(true);
    }
    function nuevoAjax()
    {	var xmlhttp=false;
        try {
            xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e) {
            try {
                xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
            } catch (E) {
                xmlhttp = false;
            }
        }
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
            xmlhttp = new XMLHttpRequest();
        }
        return xmlhttp;
    }
    function ajaxVisitadores(codigo){
        var codTerritorio=new Array();
        var j=0;
        for(var i=0;i<=codigo.options.length-1;i++)
        {	if(codigo.options[i].selected)
            {	codTerritorio[j]=codigo.options[i].value;
                j++;
            }
        }
        var contenedor,contenedor2;
        contenedor = document.getElementById('divVisitadores');
        contenedor2 = document.getElementById('divDistrito');
        ajax=nuevoAjax();
        ajax1=nuevoAjax();
        ajax.open('GET', 'ajaxVisitadores.php?codTerritorio='+codTerritorio+'',true);
        ajax.onreadystatechange=function() {
            if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
            }
        }
        ajax.send(null)
        ajax1.open('GET', 'ajaxDistrito.php?codTerritorio='+codTerritorio+'',true)
        ajax1.onreadystatechange=function() {
            if (ajax1.readyState==4) {
                contenedor2.innerHTML = ajax1.responseText
            }
        }
        ajax1.send(null)
    }

    function ajaxZona(codigo){
        var codTerritorio=new Array();
        var j=0;
        for(var i=0;i<=codigo.options.length-1;i++)
        {	if(codigo.options[i].selected)
            {	codTerritorio[j]=codigo.options[i].value;
                j++;
            }
        }
        var contenedor;
        contenedor = document.getElementById('divZona');
        ajax=nuevoAjax();
        ajax.open('GET', 'ajaxZona.php?codTerritorio='+codTerritorio+'',true);
        ajax.onreadystatechange=function() {
            if (ajax.readyState==4) {
                contenedor.innerHTML = ajax.responseText
            }
        }
        ajax.send(null)
    }

</script>

<?php
require("conexion.inc");
require('estilos_gerencia.inc');
echo "<center><table class='textotit'><tr><th>M&eacute;dicos en Rutero Maestro Resumido x Visitador</th></tr></table><br>";
echo"<form method='post'>";
$sql = "select c.cod_ciudad, c.descripcion from ciudades c, `funcionarios_agencias` f where 
                        f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.descripcion";
//                  echo $sql;
echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='40%'>\n";
echo "<tr><th align='left'>Territorio</th>
	<td>
        
	<select name='rpt_territorio' class='texto' onChange='ajaxVisitadores(this)' size='9' multiple>";

$resp = mysql_query($sql);
while ($dat = mysql_fetch_array($resp)) {
    $codigo_ciudad = $dat[0];
    $nombre_ciudad = $dat[1];
    echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
}
echo "</select></td></tr>";
echo "<tr><th align='left'>Distrito</th>";
echo "<td><div id='divDistrito'></div></td></tr>";
echo "<tr><th align='left'>Zona</th>";
echo "<td><div id='divZona'></div></td></tr>";
echo "<tr><th align='left'>Visitador</th>";
echo "<td><div id='divVisitadores'></td></tr>";
echo "<tr><th align='left'>Ver:</th><td>
	<select name='tipoRuteroRpt' class='texto'>
		<option value='0'>Rutero Maestro</option>
		<option value='1'>Rutero Maestro Aprobado</option>
	</select></td></tr>";

echo "<tr><th align='left'>Gestion - Ciclo</th><td>
	<select name='gestionCicloRpt' class='texto'>";
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
echo "</td></tr>";

echo"\n </table><br>";
echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
echo"</form>";
?>