<?php

require("conexion.inc");
require("estilos_gerencia.inc");

 
$cod_grupo= $_GET['cod_grupo'];

echo "
<script type='text/javascript' language='javascript'>
    function enviar_nav() {  
        location.href='anadir_medico_grupoespe.php?cod_grupo=$cod_grupo';
    }
    function eliminar_nav(f) {   
        var i;
        var j=0;
        datos=new Array();
        for(i=0;i<=f.length-1;i++) {   
            if(f.elements[i].type=='checkbox') {   
                if(f.elements[i].checked==true) {   
                    datos[j]=f.elements[i].value;
                    j=j+1;
                }
            }
        }
        if(j==0) {   
            alert('Debe seleccionar al menos un M&eacute;dico para eliminarlo del Grupo.');
        }
        else {   
            if(confirm('Esta seguro de eliminar los datos.')) {   
                location.href='eliminar_med_grupo_espe.php?datos='+datos+'&cod_grupo=$cod_grupo';
            } else {   
                return(false);
            }
        }
    }
</script>
";

echo "<form>";
$sql_cab = "SELECT g.nombre_grupo_especial FROM grupo_especial g where g.codigo_grupo_especial='$cod_grupo'";
//echo $sql_cab;
$resp_cab = mysql_query($sql_cab);
$dat_cab = mysql_fetch_array($resp_cab);
$nombre_grupo_espe = $dat_cab[0];

echo "<h1>Registro de Grupos Especiales<br>Grupo Especial: $nombre_grupo_espe</h1>";

$sql = " SELECT concat(m.ap_pat_med, ' ',m.ap_mat_med,' ',m.nom_med) as nom, c.cod_especialidad,c.categoria_med,m.cod_med from 
grupo_especial g, grupo_especial_detalle gd, medicos m, categorias_lineas c where g.codigo_grupo_especial = gd.codigo_grupo_especial 
and gd.cod_med = m.cod_med and c.codigo_linea = g.codigo_linea and c.cod_med = gd.cod_med AND 
g.codigo_grupo_especial = $cod_grupo ORDER BY g.nombre_grupo_especial, nom, c.cod_especialidad ";

$resp = mysql_query($sql);
echo "<center><table border='0' class='textotit'><tr><td>M&eacute;dicos Pertenecientes al Grupo</td></tr></table></center><br>";
$indice_tabla = 1;

echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidades</th></tr>"; 
while ($dat = mysql_fetch_array($resp)) {
    $nombre_completo = $dat[0];
    $especialidad    = $dat[1];
    $categoria       = $dat[2];
    $codMed          = $dat[3];

    $validacion = mysql_query(" SELECT MAX(c.cod_ciclo) from ciclos c where c.codigo_gestion in (SELECT codigo_gestion from gestiones where estado = 'Activo')and estado = 'Activo'  "); $codCiclo = mysql_result($validacion, 0, 0);
    $sql_gestion = mysql_query(" SELECT codigo_gestion from gestiones where estado = 'Activo' ");
    $codGestion = mysql_result($sql_gestion, 0, 0);

    /*$sqlVis = "SELECT DISTINCT gd.cod_visitador, CONCAT(f.nombres, ' ',f.paterno, ' ', f.materno) as nombre from funcionarios f, 
    grupo_especial g, grupo_especial_detalle gd where g.codigo_grupo_especial = gd.codigo_grupo_especial 
	and f.codigo_funcionario = gd.cod_visitador and 
	gd.cod_med = $codMed and g.codigo_grupo_especial = $cod_grupo LIMIT 0, 1 ";
    // echo $sqlVis."<br />";
    $respVis = mysql_query($sqlVis);
    $cadenaVisitador = "<table border='0' cellpadding='0' cellspacing='0' class='textomini' >";
    while ($datVis = mysql_fetch_array($respVis)) {
        $codVisitador     = $datVis[0];
        $nomVisitador     = $datVis[1];
        $cadenaVisitador .= "<tr>";
        $cadenaVisitador .= "<td>&nbsp;</td>"; 
        $cadenaVisitador .= "<td>$nomVisitador</td>";
        $cadenaVisitador .= "</tr>";
    }
    $cadenaVisitador .= "</table>";*/
    echo "<tr>";
    echo "<td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigos_ciclos' value='$codMed'></td>
    <td align='center'>$codMed</td><td align='left' class='texto'>$nombre_completo</td>
    <td align='center'>$especialidad $categoria</td>";
    echo "</tr>";
    $indice_tabla++;
}
echo "</table></center>";

echo "<table align='center'>";
echo "<tr>";
echo "<td><a href='javascript:history.back(1);'><img  border='0'src='imagenes/back.png' width='40'></a></td>";
echo "</tr>";
echo "</table>";

echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	
echo "</tr>";
echo "</table>";

echo "</form>";
?>
