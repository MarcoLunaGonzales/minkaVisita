<?php

require("conexion.inc");
require("estilos_reportes_central.inc");

//echo "$rpt_linea $rpt_territorio $grupo_especial";
$global_linea = $rpt_linea;
$bandera = 0;
$sql_cab = "select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp1 = mysql_query($sql_cab);
$dato = mysql_fetch_array($resp1);
$nombre_territorio = $dato[1];

echo "<center><table border='0' class='textotit'><tr><th>Grupos Especiales</th></tr></table></center><br>";
echo "<center><table border='1' class='textomini' cellspacing='0'>";// width='70%'
echo "<tr>";
echo "<th>Grupo Especial</th><th>M&#233;dico</th><th>Especialidad</th><th>Categor&#237;a</th><th>Lineas de visita</th><th>Visitador asignado</th>";
echo "</tr>";

 $consulta="
     SELECT max(r.codigo_ciclo), r.codigo_gestion FROM rutero_maestro_cab_aprobado r WHERE
     r.codigo_gestion IN (select g.codigo_gestion FROM gestiones g WHERE g.estado='Activo') ";
 $rs1=mysql_query($consulta);
 $nroregs=mysql_num_rows($rs1);
 $reg=mysql_fetch_array($rs1);
 $codCiclo=$reg[0];
 $codGestion=$reg[1];

$sql_grupo_especial = "
    SELECT g.codigo_grupo_especial, g.nombre_grupo_especial,
    concat(m.ap_pat_med, ' ', m.ap_mat_med, ' ', m.nom_med) as nom,
    c.cod_especialidad, c.categoria_med, m.cod_med
    FROM grupo_especial g, grupo_especial_detalle gd, medicos m, categorias_lineas c
    WHERE g.codigo_grupo_especial = gd.codigo_grupo_especial AND gd.cod_med = m.cod_med
    AND c.codigo_linea = g.codigo_linea AND c.cod_med=gd.cod_med
    AND g.codigo_linea='$global_linea' AND g.agencia='$rpt_territorio' AND g.codigo_grupo_especial IN ($grupo_especial) 
    ORDER BY g.nombre_grupo_especial, nom, c.cod_especialidad ";
//echo "xxxx:$sql_grupo_especial<br>";
$resp_grupo_especial = mysql_query($sql_grupo_especial);
while ($datos_grupo_especial = mysql_fetch_array($resp_grupo_especial)) {
    $codGrupo = $datos_grupo_especial[0];
    $nombreGrupo = $datos_grupo_especial[1];
    $nombreMedico = $datos_grupo_especial[2];
    $especialidad = $datos_grupo_especial[3];
    $categoria = $datos_grupo_especial[4];
    $codMedico = $datos_grupo_especial[5];
    //
    $consulta="
        SELECT lv.codigo_l_visita, lv.nombre_l_visita
        FROM grupoespecial_lineavisita glv, lineas_visita lv, lineas_visita_especialidad lve
        WHERE glv.cod_grupo=$codGrupo
        AND glv.cod_l_visita=lv.codigo_l_visita
        AND lv.codigo_linea=$global_linea
        AND lv.codigo_l_visita=lve.codigo_l_visita AND lve.cod_especialidad='$especialidad'
        ORDER BY lv.nombre_l_visita ASC ";
    $rs1 = mysql_query($consulta);
    $cadLineaVisita="";
    //$cadLineaVisita="$consulta<br>";
    $nroRegs = mysql_num_rows($rs1);
    if($nroRegs>0) {
        while($reg = mysql_fetch_array($rs1)) {
            $codLineaVisita=$reg[0];
            $nomLineaVisita=$reg[1];
            $cadLineaVisita.="$nomLineaVisita";
        }
        if($cadLineaVisita=="") {
            $cadLineaVisita="&nbsp;";
        }
    } else {
        $cadLineaVisita.="&nbsp;";
    }
    //
    $cadVisitador="";
    $consulta="
        SELECT distinct(rd.cod_visitador),
            (select concat(paterno,' ',materno,' ',nombres) from funcionarios where codigo_funcionario=rd.cod_visitador) as nom
        FROM rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, rutero_maestro_detalle_aprobado rd
        WHERE rc.cod_rutero=r.cod_rutero AND r.cod_contacto=rd.cod_contacto
        AND rc.codigo_ciclo=$codCiclo AND rc.codigo_gestion=$codGestion
        AND rc.codigo_linea=$global_linea AND rd.cod_med=$codMedico
        ORDER BY nom ASC ";
    $rs1 = mysql_query($consulta);
    $nroRegs=mysql_num_rows($rs1);
    $cadVisitador="";
    if($nroRegs>1) {
        while($reg = mysql_fetch_array($rs1)) {
            $cadVisitador.="rutero2:$reg[1]<br>";
        }
        $consulta="
            SELECT f.codigo_funcionario ,concat(f.paterno, ' ', f.materno, ' ', f.nombres) nom
            FROM grupo_especial_detalle_visitadores gedv, funcionarios f
            WHERE gedv.codigo_grupo_especial=$codGrupo
            AND gedv.codigo_funcionario=f.codigo_funcionario
            ORDER BY f.paterno, f.materno, f.nombres ";
        $rs1 = mysql_query($consulta);
        //$nroRegs=mysql_num_rows($rs1);
        //$cadVisitador.="$consulta<br>";
        $cadVisitador.="grpesp:";
        while($reg = mysql_fetch_array($rs1)) {
            $cadVisitador.="$reg[1]<br>";
        }
    } else if($nroRegs==1) {
        while($reg = mysql_fetch_array($rs1)) {
            $cadVisitador.="rutero1:$reg[1]<br>";
        }
    } else {
        $cadVisitador.="rutero0:";
    }
    if($cadVisitador=="") $cadVisitador="&nbsp;";
    //
    echo "<tr>";
    echo "<td>$nombreGrupo</td><td>$nombreMedico</td><td>$especialidad</td><td>$categoria</td><td>$cadLineaVisita</td><td>$cadVisitador</td>";
    echo "</tr>";
}
echo "</table>-</center><br>";

?>
