<style>
td { font-size: 12px !important;}
</style>
<?php
set_time_limit(0);
error_reporting(0);
require("conexion.inc");
require("estilos_reportes_central.inc");

//echo "$rpt_linea $rpt_territorio $grupo_especial";
$cod_ge = $_GET['cod_ge'];
$territorio = $_GET['territorio'];
$linea = $_GET['linea'];
$valores_c_g = $_GET['ciclo'];
$valores_c_g_explode = explode("|", $valores_c_g);
$ciclo = $valores_c_g_explode[0];
$gestion = $valores_c_g_explode[1];

/*$validacion = mysql_query(" select MAX(c.cod_ciclo) from ciclos c where c.codigo_gestion in (select codigo_gestion from gestiones where estado = 'Activo')and estado = 'Activo'  ");
$codCiclo = mysql_result($validacion, 0, 0);
$sql_gestion = mysql_query(" select codigo_gestion from gestiones where estado = 'Activo' ");
$codGestion = mysql_result($sql_gestion, 0, 0);*/
$territorio = explode(",", $territorio);
//print_r($territorio);
?>
<h2 align='center'>Reporte Grupos Especiales</h2>
<table width="70%" border='1' cellspacing='0' cellpadding='0' align='center'>
    <tr>
        <th>Agencia</th>
        <th>Regional</th>
        <th>Nombre del m&eacute;dico</th>
        <th>Especialidad</th>
        <th>Grupo especial</th>
        <!-- <th>L&iacute;nea de Visita</th> -->
        <th>Visitador M&eacute;dico asignado</th>
    </tr>
    <?php
    foreach ($territorio as $region) {
        $sql_territorio = mysql_query(" select cod_ciudad,descripcion from ciudades where cod_ciudad='$region' ");
        $cod_ciudad = mysql_result($sql_territorio, 0, 0);
        $desc_ciudad = mysql_result($sql_territorio, 0, 1);

        $txtGrupoEspecial="select g.codigo_grupo_especial, g.nombre_grupo_especial, concat(m.ap_pat_med, ' ',m.ap_mat_med,' ',m.nom_med) as nom, 
			c.cod_especialidad,c.categoria_med,m.cod_med,m.cod_catcloseup, c.frecuencia_linea, 
			(select l.nombre_linea from lineas l where l.codigo_linea=g.codigo_linea)
            from grupo_especial g, grupo_especial_detalle gd, medicos m, categorias_lineas c where g.codigo_grupo_especial = gd.codigo_grupo_especial and gd.cod_med = m.cod_med and c.codigo_linea = g.codigo_linea
            and c.cod_med = gd.cod_med and g.codigo_linea = '$linea' and g.agencia = '$cod_ciudad' AND g.codigo_grupo_especial IN ($cod_ge) 
			ORDER BY g.nombre_grupo_especial, nom, c.cod_especialidad ";
		$sql_grupo_especial = mysql_query($txtGrupoEspecial);
       
		//echo $txtGrupoEspecial;
		
	   while ($row_datos_ge = mysql_fetch_array($sql_grupo_especial)) {
            $codGrupo = $row_datos_ge[0];
            $nombreGrupo = $row_datos_ge[1];
            $nombreMedico = $row_datos_ge[2];
            $especialidad = $row_datos_ge[3];
            $categoria = $row_datos_ge[4];
            $codMedico = $row_datos_ge[5];
            $catcloseup = $row_datos_ge[6];
            $frecuencia = $row_datos_ge[7];
			$nombreLinea= $row_datos_ge[8];


            $sqlVis = "select DISTINCT gd.cod_visitador, CONCAT(f.nombres, ' ',f.paterno, ' ', f.materno) as nombre 
			from funcionarios f, 
            grupos_especiales g, grupos_especiales_detalle gd where g.id = gd.id and f.codigo_funcionario = 
			gd.cod_visitador and gd.cod_med = $codMedico and g.ciclo = $ciclo and g.gestion = $gestion and g.codigo_grupo_especial=$codGrupo LIMIT 0, 1 ";
            
			//echo $sqlVis."<br />";

            $respVis = mysql_query($sqlVis);
            $cadenaVisitador = "<table border='0' cellpadding='0' cellspacing='0' class='textomini' >";
            while ($datVis = mysql_fetch_array($respVis)) {
                $codVisitador = $datVis[0];
                $nomVisitador = $datVis[1];
                $cadenaVisitador.="<tr>";
                $cadenaVisitador.="<td>&nbsp;</td>"; //"<td>medicos:</td>"
                $cadenaVisitador.="<td>$nomVisitador</td>";
                $cadenaVisitador.="</tr>";
            }
            $cadenaVisitador.="</table>";
            ?>


            <tr>
                <td><?php echo $nombreLinea ?></td>
                <td><?php echo $desc_ciudad ?></td>
                <td><?php echo $nombreMedico ?></td>
                <td><?php echo $especialidad ?></td>
                <td><?php echo $nombreGrupo ?></td>
                <!-- <td><?php echo $cadLineaVisita1 ?></td> -->
                <td><?php echo $cadenaVisitador ?></td>
            </tr>    
            <?php
        }
    }
    ?>
</table>