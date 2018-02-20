<?php
error_reporting(0);
set_time_limit(0);
require("conexion.inc");
require('estilos_reportes_administracion.inc');
require("funcion_nombres.php");
$rpt_ciclo = $rpt_ciclo;
$rpt_gestion = $rpt_gestion;
$global_gestion = $rpt_gestion;

$rptVer=$_GET['rpt_ver'];
$borrado_cab = mysql_query("DELETE from reporte_cab");
$borrado_detalle = mysql_query("DELETE from reporte_detalle");

$sql_dias_ini_fin = "SELECT fecha_ini,fecha_fin from ciclos where cod_ciclo='$rpt_ciclo' and codigo_gestion='$global_gestion' and codigo_linea='1032'";
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

$nombre_territorio = nombreTerritorio($rpt_territorio);
	
$sql_visitadores="select f.codigo_funcionario, f.paterno, f.materno, f.nombres, rc.codigo_linea, rc.codigo_linea 
	from rutero_maestro_cab_aprobado rc, funcionarios f
	where f.codigo_funcionario=rc.cod_visitador and f.codigo_funcionario in ($rpt_visitador) AND
	rc.codigo_ciclo='$rpt_ciclo' and rc.codigo_gestion='$rpt_gestion'";
//	rc.codigo_linea in (1041)";

$resp_visitadores  = mysql_query($sql_visitadores);
$indice_reporte    = 1;
while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {
    $codigo_funcionario = $dat_visitadores[0];
    $nombre_funcionario = "$dat_visitadores[1] $dat_visitadores[2] $dat_visitadores[3]";
    $linea_funcionario  = $dat_visitadores[4];
    $codigo_lineaclave  = $dat_visitadores[5];
	
    $nombre_linea       = nombreLinea($linea_funcionario);
    $nombre_supervisor = nombreSupervisor($rpt_territorio);
    
    $sql = "SELECT r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from rutero_maestro_aprobado r, 
	rutero_maestro_cab_aprobado rmc, orden_dias o where rmc.cod_rutero=r.cod_rutero and rmc.estado_aprobado=1 
	and r.cod_visitador='$codigo_funcionario'and rmc.cod_visitador=r.cod_visitador and r.dia_contacto=o.dia_contacto and 
	rmc.codigo_linea='$linea_funcionario' and rmc.codigo_ciclo = '$rpt_ciclo' and rmc.codigo_gestion='$rpt_gestion' 
	order by o.id, r.turno";
//	and rmc.codigo_linea in (1041)
	
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
		
        $sql1 = "SELECT c.orden_visita, m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, 
		c.categoria_med, c.estado, m.telf_med, m.telf_celular_med, m.cod_catcloseup from rutero_maestro_detalle_aprobado c, 
		medicos m, direcciones_medicos d where (c.cod_contacto=$cod_contacto) and (c.cod_visitador=$codigo_funcionario) and 
		(c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and (c.cod_zona=d.numero_direccion) order by c.orden_visita";
        $resp1 = mysql_query($sql1);
        while ($dat1 = mysql_fetch_array($resp1)) {
			
			$strNegados="";
					
			
            $orden_visita = $dat1[0];
            $codigo_medico = $dat1[1];
            $pat = $dat1[2];
            $mat = $dat1[3];
            $nombre = $dat1[4];
            $sql_dir = "SELECT direccion from direcciones_medicos where cod_med=$codigo_medico";
            $resp_dir = mysql_query($sql_dir);
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
                $indice_direccion++;
            }
            $direccion = $dat1[5];
            $nombre_medico = "$pat $mat $nombre";
            $espe = $dat1[6]." (".$dat1[7].")";
            $cat = $dat1[7];
            $telefono_medico = $dat1[9];
            $celular_medico = $dat1[10];
            $catCloseUp = $dat1[11];
                     
            $sql_numero_visita = "SELECT distinct(o.id), rmd.cod_contacto, rmd.orden_visita from orden_dias o, rutero_maestro_cab_aprobado rmc, 
			rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd where rm.cod_contacto=rmd.cod_contacto and 
			rmc.cod_rutero=rm.cod_rutero and rmc.estado_aprobado=1 and rmc.codigo_linea='$linea_funcionario' and 
			rm.cod_visitador='$codigo_funcionario' and rmd.cod_med='$codigo_medico' and o.dia_contacto=rm.dia_contacto and 
			rmc.codigo_ciclo='$rpt_ciclo' and rmc.codigo_gestion='$rpt_gestion' and rmc.cod_visitador = $codigo_funcionario  order by o.id";
            
			//echo $sql_numero_visita."<br />";
			$cadGrupo="";
			
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
			
			$numFilasPersonalizada=1;
			
			
                $sql_insert_reporte_cab = "INSERT into reporte_cab values($indice_reporte,'$nombre_territorio', '$nombre_funcionario',
				'$nombre_medico','$nombre_linea','$rpt_ciclo','$rpt_gestion', '$indice_boleta de $cantidad_total_boletas','$dir_rep1',
				'$dir_rep2','$dir_rep3','$telefono_medico', '$celular_medico','$espe','$fecha_planificada','$nombre_supervisor',
				'$cadGrupo','$codBPH')";
				
				//echo "$sql_insert_reporte_cab<br>";
                
				$resp_insert_reporte_cab = mysql_query($sql_insert_reporte_cab);
						
				$sql_parrilla_detalle="select p.cod_mm, 
					(select concat(m.descripcion,' ',m.presentacion) from muestras_medicas m where m.codigo=p.cod_mm) ,
					p.cantidad_mm, 
					(select ma.descripcion_material from material_apoyo ma where ma.codigo_material=p.cod_ma), p.cantidad_ma
					from parrilla_personalizada p
					where p.cod_gestion=$global_gestion and p.cod_ciclo=$rpt_ciclo and p.cod_med=$codigo_medico and 
					p.numero_visita=$numero_visita and p.cod_linea='$linea_funcionario' ORDER BY orden_visita";
				$resp_parrilla_detalle = mysql_query($sql_parrilla_detalle);
				
				$numFilasDetalle=mysql_num_rows($resp_parrilla_detalle);
				
				if($numero_visita==2 && $numFilasDetalle==0){
					$sql_parrilla_detalle="select p.cod_mm, 
					(select concat(m.descripcion,' ',m.presentacion) from muestras_medicas m where m.codigo=p.cod_mm) ,
					p.cantidad_mm, 
					(select ma.descripcion_material from material_apoyo ma where ma.codigo_material=p.cod_ma), p.cantidad_ma
					from parrilla_personalizada p
					where p.cod_gestion=$global_gestion and p.cod_ciclo=$rpt_ciclo and p.cod_med=$codigo_medico and 
					p.numero_visita=3 and p.cod_linea='$linea_funcionario' ORDER BY orden_visita";
				
				}
				
				$resp_parrilla_detalle = mysql_query($sql_parrilla_detalle);
				
				$filas_parrilla = mysql_num_rows($resp_parrilla_detalle);
				$orden_promocional = 1;
				while ($dat_parrilla_detalle = mysql_fetch_array($resp_parrilla_detalle)) {
					$ultima_poc = '';
					$codMuestra = $dat_parrilla_detalle[0];
					$muestra = $dat_parrilla_detalle[1];
					$cant_muestra = $dat_parrilla_detalle[2];
					$material = $dat_parrilla_detalle[3];
					$cant_material = $dat_parrilla_detalle[4];
					
					if ($material == " - ") {
						$material = " ";
					}
					if ($cant_material == 0) {
						$cant_material = " ";
					}
					$insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'$muestra','$cant_muestra','$material','$cant_material',1)";
					
					//echo "$insert_reporte_det<br>";
					
					$resp_reporte_det = mysql_query($insert_reporte_det);
					$orden_promocional++;			
				}
			
				//HACEMOS LOS GRUPOS ESPECIALES
				$sqlPGE="select pd.codigo_muestra, 
				(select concat(m.descripcion,' ',m.presentacion) from muestras_medicas m where m.codigo=pd.codigo_muestra),
				pd.cantidad_muestra, pd.codigo_material, 
				(select ma.descripcion_material from material_apoyo ma where ma.codigo_material=pd.codigo_material), 
				pd.cantidad_material
				from grupos_especiales g, grupos_especiales_detalle gd, parrilla_especial p, parrilla_detalle_especial pd
				where g.id=gd.id and g.ciclo='$rpt_ciclo' and g.gestion='$global_gestion' and gd.cod_med='$codigo_medico' 
				and gd.cod_visitador='$codigo_funcionario' and p.codigo_parrilla_especial=pd.codigo_parrilla_especial 
				and p.cod_ciclo=g.ciclo and p.codigo_gestion=g.gestion and p.codigo_grupo_especial=g.codigo_grupo_especial and 
				p.numero_visita='$numero_visita' order by p.codigo_grupo_especial, pd.prioridad;";
				
				$respPGE=mysql_query($sqlPGE);
				while($datPGE=mysql_fetch_array($respPGE)){
					$muestra=$datPGE[1];
					$cant_muestra=$datPGE[2];
					$material=$datPGE[4];
					$cant_material=$datPGE[5];
					if ($cant_material == 0) {
						$cant_material = " ";
					}
					$insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'$muestra','$cant_muestra','$material','$cant_material',2)";
					//echo "GRUPOSESPECIALES $insert_reporte_det<br>";
					$resp_reporte_det = mysql_query($insert_reporte_det);
					$orden_promocional++;			
				}
				//HACEMOS LOS BANCOS
				if($numero_visita==2){
					$sqlBancos="select bmv.codigo_muestra, 
					(select concat(m.descripcion,' ', m.presentacion) from muestras_medicas m where m.codigo=bmv.codigo_muestra),
					bmv.cantidad  from banco_muestras bm, banco_muestras_detalle bmd, banco_muestra_cantidad_visitador bmv
					where bm.id=bmd.id and bmd.id=bmv.id_for and bm.id=bmv.id_for and 
					bm.estado=1 and bm.cod_med='$codigo_medico' and bmv.cod_visitador='$codigo_funcionario' 
					and bmv.cantidad>0 and bm.cod_med=bmv.cod_medico and 
					bmd.cod_muestra=bmv.codigo_muestra group by bmv.cod_visitador, bmv.codigo_muestra order by 2";
					$respBancos=mysql_query($sqlBancos);
					while($datBancos=mysql_fetch_array($respBancos)){
						$muestra=$datBancos[1];
						$cant_muestra=$datBancos[2];
						$material="Sin Material Apoyo";
						$cant_material=0;
						$insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'$muestra','$cant_muestra','$material','',3)";
						//echo "BANCOS $insert_reporte_det<br>";
						$resp_reporte_det = mysql_query($insert_reporte_det);
						$orden_promocional++;						
					}
				}
					
					
                for ($ii = $orden_promocional - 1; $ii < 16; $ii++) {
                    $insert_reporte_det = "INSERT into reporte_detalle values($indice_reporte,$orden_promocional,'','','','',0)";
					//echo "$insert_reporte_det <br>";
                    $resp_reporte_det = mysql_query($insert_reporte_det);
                    $orden_promocional++;
                }
				
            $indice_boleta++;
            $indice_reporte++;
        }
    }
}
//verificar que maximo hayan 15 items.
	$sqlDelMax="delete from reporte_detalle where orden_promocional>15";
	$respDelMax=mysql_query($sqlDelMax);

echo "<script language='JavaScript'>
location.href='boletaspdfConjuntaXX.php';
</script>";
?>