<?php

error_reporting(0);
set_time_limit(0);

require("conexion.inc");

require('estilos_reportes_administracion.inc');

$global_gestion = 1010;


$rpt_ciclo = $rpt_ciclo;


$rpt_gestion = $rpt_gestion;



$borrado_cab     = mysql_query("DELETE from reporte_cab");

$borrado_detalle = mysql_query("DELETE from reporte_detalle");

$sql_dias_ini_fin = "SELECT fecha_ini,fecha_fin from ciclos where cod_ciclo='$rpt_ciclo' and codigo_gestion='$global_gestion' and codigo_linea='1021'";

$resp_dias_ini_fin = mysql_query($sql_dias_ini_fin);

$dat_dias = mysql_fetch_array($resp_dias_ini_fin);

$fecha_ini_actual = $dat_dias[0];

$fecha_fin_actual = $dat_dias[1];

$fecha_actual = $fecha_ini_actual;

$inicio = $fecha_ini_actual;

$k = 0;

list($anio, $mes, $dia) = explode("-", $fecha_actual);

$dia1 = $dia;

while ($inicio < $fecha_fin_actual) {

    $ban = 0;

    while ($ban == 0) {

        $nueva1 = mktime(0, 0, 0, $mes, $dia1, $anio);

        $dia_semana = date("l", $nueva1);

        if ($dia_semana == 'Sunday' or $dia_semana == 'Saturday') {

            $dia1 = $dia1 + 1;
        } else {

            $ban = 1;
        }
    }

    $num_dia = intval($k / 5) + 1;

    if ($dia_semana == 'Monday') {

        $dias[$k] = "Lunes $num_dia";
    }

    if ($dia_semana == 'Tuesday') {

        $dias[$k] = "Martes $num_dia";
    }

    if ($dia_semana == 'Wednesday') {

        $dias[$k] = "Miercoles $num_dia";
    }

    if ($dia_semana == 'Thursday') {

        $dias[$k] = "Jueves $num_dia";
    }

    if ($dia_semana == 'Friday') {

        $dias[$k] = "Viernes $num_dia";
    }

    $fecha_actual = date("Y-m-d", $nueva1);

    $inicio = $fecha_actual;

    list($anio, $mes, $dia) = explode("-", $fecha_actual);

    $dia1 = $dia + 1;

    $fecha_actual_formato = "$dia/$mes/$anio";

    $fechas[$k] = $fecha_actual_formato;

    $k++;
}

$sql_territorio    = "SELECT descripcion from ciudades where cod_ciudad = '$rpt_territorio'";

$resp_territorio   = mysql_query($sql_territorio);

$dat_territorio    = mysql_fetch_array($resp_territorio);

$nombre_territorio = $dat_territorio[0];

echo "<table align='center' class='textotit'><tr><th>Reporte Boletas de Visita</th></tr></table><br>";

$sql_visitadores   = "SELECT fl.codigo_funcionario, f.paterno, f.materno, f.nombres, fl.codigo_linea, f.codigo_lineaclave from funcionarios_lineas fl, funcionarios f where f.codigo_funcionario = fl.codigo_funcionario and f.cod_cargo = 1011 and f.estado = '1' and f.cod_ciudad = '$rpt_territorio' and f.codigo_funcionario in ($rpt_visitador)";

$resp_visitadores  = mysql_query($sql_visitadores);

$indice_reporte    = 1;

while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {

    $codigo_funcionario = $dat_visitadores[0];

    $nombre_funcionario = "$dat_visitadores[1] $dat_visitadores[2] $dat_visitadores[3]";

    $linea_funcionario  = $dat_visitadores[4];

    $codigo_lineaclave  = $dat_visitadores[5];

    $sql_linea          = "SELECT codigo_linea, nombre_linea from lineas where codigo_linea='$linea_funcionario'";

    $resp_linea         = mysql_query($sql_linea);

    $dat_linea          = mysql_fetch_array($resp_linea);

    $codigo_linea       = $dat_linea[0];

    $nombre_linea       = $dat_linea[1];

    $sql_ciudadtroncal  = "SELECT tipo from ciudades where cod_ciudad='$rpt_territorio'";

    $resp_ciudadtroncal = mysql_query($sql_ciudadtroncal);

    $dat_ciudadtroncal  = mysql_fetch_array($resp_ciudadtroncal);

    $tipo_ciudad        = $dat_ciudadtroncal[0];

    if ($tipo_ciudad == 0) {

        $sql_supervisor    = "SELECT paterno, materno, nombres from funcionarios where cod_cargo='1001'and cod_ciudad='115'";

        $resp_supervisor   = mysql_query($sql_supervisor);

        $dat_supervisor    = mysql_fetch_array($resp_supervisor);

        $nombre_supervisor = "$dat_supervisor[0] $dat_supervisor[2]";

    } else {

        $sql_supervisor = "SELECT f.paterno, f.materno, f.nombres from funcionarios f, funcionarios_lineas fl where f.cod_cargo='1001' and f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$codigo_linea' and f.cod_ciudad='$rpt_territorio' and f.estado=1";

        $resp_supervisor = mysql_query($sql_supervisor);

        $dat_supervisor = mysql_fetch_array($resp_supervisor);

        $nombre_supervisor = "$dat_supervisor[0] $dat_supervisor[2]";

    }

    $sql = "SELECT r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from rutero_maestro_aprobado r, rutero_maestro_cab_aprobado rmc, orden_dias o where rmc.cod_rutero=r.cod_rutero and rmc.estado_aprobado=1 and r.cod_visitador='$codigo_funcionario'and rmc.cod_visitador=r.cod_visitador and r.dia_contacto=o.dia_contacto and rmc.codigo_linea='$linea_funcionario' and rmc.codigo_ciclo = '$rpt_ciclo' and rmc.codigo_gestion='$rpt_gestion' order by o.id, r.turno";

    $resp = mysql_query($sql);

    $filas_rutero = mysql_num_rows($resp);

    $sql_nom_rutero = mysql_query("SELECT nombre_rutero from rutero_maestro_cab where cod_rutero='$rutero' and cod_visitador='$global_visitador'");

    $dat_nom_rutero = mysql_fetch_array($sql_nom_rutero);

    $nombre_rutero  = $dat_nom_rutero[0];

    $sql_cantidad_total_boletas = "SELECT rd.cod_contacto from rutero_maestro_aprobado r, rutero_maestro_cab_aprobado rmc, rutero_maestro_detalle_aprobado rd where rmc.cod_rutero=r.cod_rutero and rmc.estado_aprobado=1 and r.cod_visitador='$codigo_funcionario'and rmc.cod_visitador=r.cod_visitador and r.cod_visitador=rd.cod_visitador and rmc.codigo_linea='$linea_funcionario' and r.cod_contacto=rd.cod_contacto and rmc.codigo_ciclo='$rpt_ciclo' and rmc.codigo_gestion='$rpt_gestion'";

    $resp_cantidad_total_boletas = mysql_query($sql_cantidad_total_boletas);

    $cantidad_total_boletas = mysql_num_rows($resp_cantidad_total_boletas);

    $indice_boleta = 1;

    while ($dat = mysql_fetch_array($resp)) {

        $cod_contacto = $dat[0];

        $cod_ciclo = $dat[1];

        $dia_contacto = $dat[3];

        $turno = $dat[4];

        $zona_de_viaje = $dat[5];

        for ($ww = 0; $ww <= $k; $ww++) {

            if ($dias[$ww] == $dia_contacto) {

                $fecha_planificada = $fechas[$ww];
            }
        }

        if ($zona_de_viaje == 1) {

            $fondo_fila = "#FFD8BF";
        } else {

            $fondo_fila = "";
        }

        $sql1 = "SELECT c.orden_visita, m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, c.estado, m.telf_med, m.telf_celular_med, m.cod_catcloseup from rutero_maestro_detalle_aprobado c, medicos m, direcciones_medicos d where (c.cod_contacto=$cod_contacto) and (c.cod_visitador=$codigo_funcionario) and (c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and (c.cod_zona=d.numero_direccion) order by c.orden_visita";

        // echo $sql1."<br />";

        $resp1 = mysql_query($sql1);

        $contacto = "<table class='textomini' width='100%'>";

        $contacto = $contacto . "<tr><th width='5%'>Orden</th><th width='35%'>Medico</th><th width='10%'>Especialidad</th><th width='10%'>Categoria</th><th width='30%'>Direccion</th></tr>";

        while ($dat1 = mysql_fetch_array($resp1)) {

            $orden_visita = $dat1[0];

            $codigo_medico = $dat1[1];

            $pat = $dat1[2];

            $mat = $dat1[3];

            $nombre = $dat1[4];

            $sql_dir = "SELECT direccion from direcciones_medicos where cod_med=$codigo_medico";

            $resp_dir = mysql_query($sql_dir);

            $direccion_medico = "<table border=0 class='textomini'>";

            $indice_direccion = 1;

            $dir_rep1 = "";

            $dir_rep2 = "";

            $dir_rep3 = "";

            while ($dat_dir = mysql_fetch_array($resp_dir)) {

                $dir = $dat_dir[0];

                if ($indice_direccion == 1) {

                    $dir_rep1 = $dir;
                }

                if ($indice_direccion == 2) {

                    $dir_rep2 = $dir;
                }

                if ($indice_direccion == 3) {

                    $dir_rep3 = $dir;
                }

                $direccion_medico = "$direccion_medico<tr><td align='left'>$dir</td></tr>";

                $indice_direccion++;
            }

            $direccion_medico = "$direccion_medico</table>";

            $direccion = $dat1[5];

            $nombre_medico = "$pat $mat $nombre";

            $espe = $dat1[6];

            $cat = $dat1[7];

            $telefono_medico = $dat1[9];

            $celular_medico = $dat1[10];

            $catCloseUp = $dat1[11];

            $sqlGrilla = "SELECT gd.frecuencia from grilla g, grilla_detalle gd where g.codigo_grilla = gd.codigo_grilla and gd.cod_especialidad = '$espe' and gd.cod_categoria = '$cat' and g.agencia = $rpt_territorio";

            $respGrilla = mysql_query($sqlGrilla);

            $frecuenciaGrilla = mysql_result($respGrilla, 0, 0);

            $codBPH = "$catCloseUp$cat$frecuenciaGrilla";

            $verifica_lineas = "SELECT l.codigo_l_visita from lineas_visita l, lineas_visita_especialidad le, lineas_visita_visitadores_copy lv where l.codigo_l_visita=le.codigo_l_visita and l.codigo_l_visita=lv.codigo_l_visita and le.codigo_l_visita=lv.codigo_l_visita and lv.codigo_linea_visita='$linea_funcionario' and lv.codigo_funcionario='$codigo_funcionario' and le.cod_especialidad='$espe'   and lv.codigo_ciclo = $rpt_ciclo and lv.codigo_gestion = $global_gestion";
            echo $verifica_lineas."<br />";

            $resp_verifica_lineas = mysql_query($verifica_lineas);

            $filas_verifica = mysql_num_rows($resp_verifica_lineas);

            if ($filas_verifica != 0) {

                $dat_verifica = mysql_fetch_array($resp_verifica_lineas);

                $codigo_l_visita = $dat_verifica[0];
            } else {

                $codigo_l_visita = 0;
            }

            if ($filas_verifica > 0) {

                $sqlGrupo = "SELECT DISTINCT g.nombre_grupo_especial from grupo_especial g, grupos_especiales ge, grupos_especiales_detalle ged where ge.codigo_grupo_especial = g.codigo_grupo_especial and ge.id = ged.id and ged.cod_med = $codigo_medico and g.codigo_linea = $linea_funcionario and ged.cod_visitador = $codigo_funcionario and ge.gestion = $global_gestion and ge.ciclo = $rpt_ciclo";
            } else {

                $sqlGrupo = "SELECT DISTINCT g.nombre_grupo_especial, ge.codigo_grupo_especial from grupo_especial g, grupos_especiales ge, grupos_especiales_detalle ged where g.codigo_grupo_especial = ge.codigo_grupo_especial and ged.id = ge.id and ged.cod_med = $codigo_medico and g.codigo_linea = $linea_funcionario and ged.cod_visitador = $codigo_funcionario and ge.gestion = $global_gestion and ge.ciclo = $rpt_ciclo";
            }

            echo $sqlGrupo;

            $respGrupo = mysql_query($sqlGrupo);

            $numFilasGrupo = mysql_num_rows($respGrupo);

            if ($numFilasGrupo > 0) {

                // echo $numFilasGrupo . " ENTRO GRUPO ESPECIAL";
            }

            $cadGrupo = "";

            while ($datGrupo = mysql_fetch_array($respGrupo)) {

                $cadGrupo.=" $datGrupo[0]";
            }

            $sql_numero_visita = "SELECT distinct(o.id), rmd.cod_contacto, rmd.orden_visita from orden_dias o, rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd where rm.cod_contacto=rmd.cod_contacto and rmc.cod_rutero=rm.cod_rutero and rmc.estado_aprobado=1 and rmc.codigo_linea='$linea_funcionario' and rm.cod_visitador='$codigo_funcionario' and rmd.cod_med='$codigo_medico'and o.dia_contacto=rm.dia_contacto and rmc.codigo_ciclo='$rpt_ciclo' and rmc.codigo_gestion='$rpt_gestion' and rmc.cod_visitador = $codigo_funcionario  order by o.id";
            echo $sql_numero_visita."<br />";

            $resp_numero_visita = mysql_query($sql_numero_visita);

            $indice_visita = 1;

            while ($dat_numero_visita = mysql_fetch_array($resp_numero_visita)) {

                $contacto_sistema = $dat_numero_visita[1];

                $orden_visita_sistema = $dat_numero_visita[2];

                if ($contacto_sistema == $cod_contacto and $orden_visita_sistema == $orden_visita) {

                    $numero_visita = $indice_visita;
                }

                $indice_visita++;
            }

            $sql_parrilla = "SELECT * from parrilla p where p.codigo_linea = '$linea_funcionario' and p.cod_ciclo = '$rpt_ciclo' and p.codigo_gestion = '$rpt_gestion' and p.codigo_l_visita = '$codigo_l_visita' and p.agencia = '$rpt_territorio' and p.cod_especialidad = '$espe' and p.categoria_med = '$cat' and p.numero_visita = '$numero_visita' order by p.numero_visita";

            echo $sql_parrilla."<br />";

            $resp_parrilla = mysql_query($sql_parrilla);

            while ($dat_parrilla = mysql_fetch_array($resp_parrilla)) {

                $cod_parrilla     = $dat_parrilla[0];

                $cod_ciclo        = $dat_parrilla[1];

                $cod_espe         = $dat_parrilla[2];

                $cod_cat          = $dat_parrilla[3];

                $numero_de_visita = $dat_parrilla[7];

                $agencia          = $dat_parrilla[8];

                $sql_agregados_ult = mysql_query("SELECT m.* from muestras_agregadas m , muestras_agregadas_frecuencia mf where m.id = mf.id and  m.cod_med = $codigo_medico and m.linea = $linea_funcionario and mf.frecuencia = $numero_visita and m.ultima_posicion = 1");
                $num_agregados_ult = mysql_num_rows($sql_agregados_ult);

                if($num_agregados_ult > 0){

                    $sql_ultimo = mysql_query("SELECT count(m.codigo) from muestras_medicas m, parrilla_detalle p, material_apoyo mm where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad");
                    $ultimo = mysql_result($sql_ultimo, 0, 0);
                    while($row_ul = mysql_fetch_array($sql_agregados_ult)){
                        if($ultimo == 10){
                            mysql_query("UPDATE muestras_agregadas set posicion = ($ultimo) where id = $row_ul[0] and cod_med = $row_ul[1] and codigo_muestra = '$row_ul[2]'");
                        }else{
                            mysql_query("UPDATE muestras_agregadas set posicion = ($ultimo + 1) where id = $row_ul[0] and cod_med = $row_ul[1] and codigo_muestra = '$row_ul[2]'");
                        }
                    }
                }

                $sql_parrilla_detalle = "SELECT m.codigo, m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones from muestras_medicas m, parrilla_detalle p, material_apoyo mm where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
                // echo $sql_parrilla_detalle."<br />";

                $resp_parrilla_detalle = mysql_query($sql_parrilla_detalle);

                $filas_parrilla = mysql_num_rows($resp_parrilla_detalle);

                $parrilla_medica = "<table class='textomini' width='100%' border='1'>";

                $parrilla_medica = $parrilla_medica . "<tr><th colspan='2'>Productos</th><th colspan='2'>Material de Apoyo</th><th>Observaciones de PMA(*)</th></tr>";

                $sql_insert_reporte_cab = "INSERT into reporte_cab values($indice_reporte,'$nombre_territorio', '$nombre_funcionario','$nombre_medico','$nombre_linea','$rpt_ciclo','$rpt_gestion', '$indice_boleta de $cantidad_total_boletas','$dir_rep1','$dir_rep2','$dir_rep3','$telefono_medico', '$celular_medico','$espe','$fecha_planificada','$nombre_supervisor','$cadGrupo','$codBPH')";

                echo $sql_insert_reporte_cab."<br />";

                $resp_insert_reporte_cab = mysql_query($sql_insert_reporte_cab);

                $orden_promocional = 1;


                while ($dat_parrilla_detalle = mysql_fetch_array($resp_parrilla_detalle)) {
                    $ultima_poc = '';
                    // $orden_promocional = '';
                    $sql_agregados = mysql_query("SELECT m.* from muestras_agregadas m , muestras_agregadas_frecuencia mf where m.id = mf.id and  m.cod_med = $codigo_medico and m.linea = $linea_funcionario and mf.frecuencia = $numero_visita");
                    $num_agregados = mysql_num_rows($sql_agregados);

                    $codMuestra = $dat_parrilla_detalle[0];

                    $muestra = "$dat_parrilla_detalle[1] $dat_parrilla_detalle[2]";

                    $cant_muestra = $dat_parrilla_detalle[3];

                    $material = $dat_parrilla_detalle[4];

                    $cant_material = $dat_parrilla_detalle[5];

                    $sql_denegados = mysql_query("SELECT * from muestras_negadas where cod_med = $codigo_medico and codigo_muestra = '$codMuestra'");
                    $num_denegados = mysql_num_rows($sql_denegados);


                    if ($material == " - ") {
                        $material = " ";
                    }

                    if ($cant_material == 0) {
                        $cant_material = " ";
                    }

                    if($num_denegados == 0){

                        if($num_agregados > 0){

                            while ($row_a = mysql_fetch_array($sql_agregados)) {

                                $material_apoyo_ag = " - ";
                                $cant_material_agr = " ";
                                // echo $row_a[4]." - ".$orden_promocional."<br />";
                                // echo $row_a[4]."<br />";
                                if($row_a[4] == $orden_promocional){

                                    $sql_nom_mues = mysql_query("SELECT CONCAT(descripcion,' ',presentacion) from muestras_medicas where codigo = '$row_a[2]' ");
                                    $nom_mues_agre = mysql_result($sql_nom_mues, 0, 0)."(*A*)";

                                    $insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'$nom_mues_agre','$row_a[5]','$material_apoyo_ag','$cant_material_agr')";

                                    $resp_reporte_det = mysql_query($insert_reporte_det);

                                    $parrilla_medica = $parrilla_medica . "<tr><td width='30%'>$nom_mues_agre</td><td width='2%'>$row_a[5]</td><td width='30%'>$material_apoyo_ag</td><td width='2%'>$cant_material_agr</td><td align='center' width='36%'>&nbsp;</td></tr>";
                                    // echo $orden_promocional ." - ".$nom_mues_agre."<br />";

                                    $orden_promocional = $orden_promocional + 1;
                                    
                                } 
                                $ultima_poc = $row_a[4];
                            }

                            $insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'$muestra','$cant_muestra','$material','$cant_material')";

                            $resp_reporte_det = mysql_query($insert_reporte_det);

                            $parrilla_medica = $parrilla_medica . "<tr><td width='30%'>$muestra</td><td width='2%'>$cant_muestra</td><td width='30%'>$material</td><td width='2%'>$cant_material</td><td align='center' width='36%'>&nbsp;</td></tr>";

                            // echo $orden_promocional ." - ".$muestra."<br />";
                            $orden_promocional++;

                        }else{

                            $insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'$muestra','$cant_muestra','$material','$cant_material')";

                            $resp_reporte_det = mysql_query($insert_reporte_det);

                            $parrilla_medica = $parrilla_medica . "<tr><td width='30%'>$muestra</td><td width='2%'>$cant_muestra</td><td width='30%'>$material</td><td width='2%'>$cant_material</td><td align='center' width='36%'>&nbsp;</td></tr>";

                            $orden_promocional++;
                        }


                    }
                    
                }

                if($ultima_poc == ''){$ultima_poc=0;}

                // echo $ultima_poc. ">".($orden_promocional-1)."<br />";

                if($ultima_poc > ($orden_promocional-1)){

                    $sql_ag = mysql_query("SELECT m.* from muestras_agregadas m , muestras_agregadas_frecuencia mf where m.id = mf.id and  m.cod_med = $codigo_medico and m.linea = $linea_funcionario and m.ultima_posicion = 1 and mf.frecuencia = $numero_visita");
                    while ($row_b = mysql_fetch_array($sql_ag)) {
                        
                        $sql_nom_mues = mysql_query("SELECT CONCAT(descripcion,' ',presentacion) from muestras_medicas where codigo = '$row_b[2]' ");
                        $nom_mues_agre = mysql_result($sql_nom_mues, 0, 0)."(*A*)";

                        $insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'$nom_mues_agre','$row_b[5]','$material_apoyo_ag','$cant_material_agr')";

                        $resp_reporte_det = mysql_query($insert_reporte_det);

                        $parrilla_medica = $parrilla_medica . "<tr><td width='30%'>$nom_mues_agre</td><td width='2%'>$row_b[5]</td><td width='30%'>$material_apoyo_ag</td><td width='2%'>$cant_material_agr</td><td align='center' width='36%'>&nbsp;</td></tr>";
                    }

                }
                
                $sqlVeriNumLV  = "SELECT * from lineas_visita_especialidad l where l.cod_especialidad='$cod_espe'";

                $respVeriNumLV = mysql_query($sqlVeriNumLV);

                $numFilasVeriNumLV = mysql_num_rows($respVeriNumLV);

                if ($numFilasVeriNumLV > 0) {

                    $sqlParrillaEspe = "SELECT pd.codigo_muestra, (SELECT CONCAT(m.descripcion,' ',m.presentacion,'(*)') from muestras_medicas m where m.codigo=pd.codigo_muestra), pd.cantidad_muestra, pd.codigo_material, (SELECT ma.descripcion_material from material_apoyo ma where ma.codigo_material=pd.codigo_material), pd.cantidad_material from parrilla_especial p, parrilla_detalle_especial pd where p.codigo_parrilla_especial = pd.codigo_parrilla_especial and p.cod_ciclo = $rpt_ciclo and p.codigo_gestion = $rpt_gestion and p.numero_visita = $numero_de_visita and p.codigo_grupo_especial in (SELECT g.codigo_grupo_especial from grupos_especiales g, grupos_especiales_detalle gd where g.id = gd.id and gd.cod_med = '$codigo_medico' and gd.cod_visitador = $codigo_funcionario AND g.ciclo = $rpt_ciclo and g.gestion = $rpt_gestion and g.territorio = $rpt_territorio  )  and p.agencia = $rpt_territorio";
                } else {

                    $sqlParrillaEspe = "SELECT pd.codigo_muestra, (SELECT CONCAT(m.descripcion,' ',m.presentacion,'(*)') from muestras_medicas m where m.codigo=pd.codigo_muestra), pd.cantidad_muestra, pd.codigo_material, (SELECT ma.descripcion_material from material_apoyo ma where ma.codigo_material=pd.codigo_material), pd.cantidad_material from parrilla_especial p, parrilla_detalle_especial pd where p.codigo_parrilla_especial = pd.codigo_parrilla_especial and p.cod_ciclo = $rpt_ciclo and p.codigo_gestion = $rpt_gestion and p.numero_visita = $numero_de_visita and p.codigo_grupo_especial in (SELECT g.codigo_grupo_especial from grupos_especiales g, grupos_especiales_detalle gd where g.id = gd.id and gd.cod_med = '$codigo_medico' and gd.cod_visitador = $codigo_funcionario AND g.ciclo = $rpt_ciclo and g.gestion = $rpt_gestion and g.territorio = $rpt_territorio ) and p.agencia = $rpt_territorio";
                }

                $respParrillaEspe = mysql_query($sqlParrillaEspe);

                while ($datParrillaEspe = mysql_fetch_array($respParrillaEspe)) {

                    $codMuestraEsp = $datParrillaEspe[0];

                    $nombreMuestraEsp = $datParrillaEspe[1];

                    $cantMuestraEsp = $datParrillaEspe[2];

                    $codMaterialEsp = $datParrillaEspe[3];

                    $nombreMaterialEsp = $datParrillaEspe[4];

                    $cantMaterialEsp = $datParrillaEspe[5];

                    if ($nombreMaterialEsp == " - ") {

                        $nombreMaterialEsp = " ";
                    } else {

                        $nombreMaterialEsp = $nombreMaterialEsp . "(*)";
                    }

                    if ($cantMaterialEsp == 0) {

                        $cantMaterialEsp = " ";
                    }

                    $insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'$nombreMuestraEsp','$cantMuestraEsp','$nombreMaterialEsp','$cantMaterialEsp')";

                    $resp_reporte_det = mysql_query($insert_reporte_det);

                    $parrilla_medica = $parrilla_medica . "<tr><td width='30%'>$nombreMuestraEsp</td><td width='2%'>$cantMuestraEsp</td><td width='30%'>$nombreMaterialEsp</td><td width='2%'>$cantMaterialEsp</td><td align='center' width='36%'>&nbsp;</td></tr>";

                    $orden_promocional++;

                }

                $sqlBanco = "SELECT DISTINCT concat( m.descripcion, ' ', m.presentacion, '(++)' ) prod, bmv.cantidad FROM banco_muestras b, banco_muestras_detalle bm, muestras_medicas m, banco_muestra_cantidad_visitador bmv WHERE b.id = bm.id AND b.id = bmv.id_for and m.codigo = bmv.codigo_muestra AND b.cod_med = '$codigo_medico' AND b.nro_visita = $numero_de_visita AND bmv.cod_visitador = $codigo_funcionario and b.ciclo_inicio <= $rpt_ciclo and b.ciclo_final >= $rpt_ciclo and b.gestion = $rpt_gestion";

                $respBanco = mysql_query($sqlBanco);

                while ($datBanco = mysql_fetch_array($respBanco)) {
                    $prodBanco = $datBanco[0];
                    $cantBanco = $datBanco[1];
                    if($cantBanco == 0){

                    }else{
                        $insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'$prodBanco','$cantBanco','','')";
                        $resp_reporte_det = mysql_query($insert_reporte_det);
                        $orden_promocional++;
                    }
                }


                for ($ii = $orden_promocional - 1; $ii < 12; $ii++) {

                    $insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'','','','')";

                    $resp_reporte_det = mysql_query($insert_reporte_det);

                    $orden_promocional++;

                    $parrilla_medica = $parrilla_medica . "<tr><td width='30%'>&nbsp;</td><td width='2%'>&nbsp;</td><td width='30%'>&nbsp;</td><td width='2%'>&nbsp;</td><td align='center' width='36%'>&nbsp;</td></tr>";
                }

                $parrilla_medica = $parrilla_medica . "</table>";
            }

            $cabecera_izq = "<table border='0' class='textomini' width='100%'><tr><td width='50%'>Gesti�n: $rpt_gestion</td><td width='50%'>Ciclo: $rpt_ciclo</td></tr>";

            $cabecera_izq = "$cabecera_izq<tr><td width='50%'>Territorio: $nombre_territorio </td><td width='50%'>L�nea: $nombre_linea</td></tr>";

            $cabecera_izq = "$cabecera_izq<tr><td colspan=2>Visitador: $nombre_funcionario</td></tr>";

            $cabecera_izq = "$cabecera_izq<tr><td colspan=2>Supervisor: $nombre_supervisor</td></tr>";

            $cabecera_izq = "$cabecera_izq<tr><td colspan=2>N�mero de Boleta: $indice_boleta de $cantidad_total_boletas  Visita Nro: $numero_visita</td></tr></table>";

            $cabecera_der = "<table border='0' class='textomini' width='100%'><tr><td>M�dico:</td><td>$nombre_medico</td></tr>";

            $cabecera_der = "$cabecera_der<tr><td>Direcciones:</td><td>$direccion_medico</td></tr>";

            $cabecera_der = "$cabecera_der<tr><td>Tel�fono: </td><td>$telefono_medico </td></tr>";

            $cabecera_der = "$cabecera_der<tr><td>Especialidad: </td><td>$espe $cadGrupo</td></tr></table>";

            echo "<table class='textomini' width='100%' border='1' align='center'>";

            echo "<tr><td width='40%'>$cabecera_izq</td><td width='40%'>$cabecera_der</td><td width='20%' align='center'><img src='imagenes/logo_cofar.png'></td></tr>";

            echo "<tr><td colspan='3'>$parrilla_medica</td></tr>";

            echo "<tr><td>Comentarios:<br><br></td><td>Fecha de Visita:<br><br></td><td>Sello y firma M�dico<br><br></td></tr>";

            echo "<tr><td colspan='3'>(*)PMA: Producto y/o Material de Apoyo.</td></tr>";

            echo "</table><br><br>";

            $indice_boleta++;

            $indice_reporte++;
        }
    }

}

echo "<script language='JavaScript'>

location.href='boletaspdf.php';

</script>";

?>