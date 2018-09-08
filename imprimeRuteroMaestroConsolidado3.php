<script language='JavaScript'>
function totales(idtable){
    var main=document.getElementById(idtable);   
    var numFilas=main.rows.length;
    var numCols=main.rows[1].cells.length;

    for(var j=1; j<=numCols-1; j++){
        var subtotal=0;
        for(var i=2; i<=numFilas-2; i++){
            var dato=main.rows[i].cells[j].innerHTML;
            if(dato=="&nbsp;"){
                dato=0;
            }else{
                dato=parseInt(main.rows[i].cells[j].innerHTML);
            }
            subtotal=subtotal+dato;
        }
        var fila=document.createElement('TH');
        main.rows[numFilas-1].appendChild(fila);
        main.rows[numFilas-1].cells[j].innerHTML=subtotal;
    }    
}
</script>
<?php

function imprimeRuteroMaestro($codigoGestion, $codigoCiclo, $tipoRuteroRpt, $visitador, $idtable) {
    //require('conexion.inc');
    set_time_limit(0);
    $sqlNombreVis = "SELECT concat(f.paterno, ' ',f.nombres), c.descripcion from funcionarios f, ciudades c where f.codigo_funcionario='$visitador' and f.cod_ciudad=c.cod_ciudad";
    $respNombreVis = mysql_query($sqlNombreVis);
    $nombreVisitador = mysql_result($respNombreVis, 0, 0);
    $nombreTerritorio = mysql_result($respNombreVis, 0, 1);
    if ($tipoRuteroRpt == 0) {
        $tabla1 = "rutero_maestro_cab";
        $tabla2 = "rutero_maestro";
        $tabla3 = "rutero_maestro_detalle";
    }
    if ($tipoRuteroRpt == 1) {
        $tabla1 = "rutero_maestro_cab_aprobado";
        $tabla2 = "rutero_maestro_aprobado";
        $tabla3 = "rutero_maestro_detalle_aprobado";
    }
    
	$sqlRuteros = "SELECT r.cod_rutero, r.codigo_linea, l.nombre_linea from $tabla1 r, 
	lineas l where r.codigo_linea=l.codigo_linea  and 
	r.codigo_ciclo = $codigoCiclo and r.codigo_gestion = $codigoGestion and r.cod_visitador = $visitador";
    
	//echo $sqlRuteros;
	$respRuteros = mysql_query($sqlRuteros);
    while ($row_ci = mysql_fetch_array($respRuteros)){
        $linea_finales .= $row_ci[1].",";
    }
    $linea_finales = substr($linea_finales, 0, -1);
    $n = mysql_num_rows($respRuteros);
    $cadena_imprimir = '';
    ?>
    <table border="0" align='center' width='90%' id="<?php echo $idtable ?>" class='texto'>
        <?php  
        $cadena_imprimir .= "<tr>";
        $cadena_imprimir .= "<th>&nbsp;</th>";
        $respRuteros = mysql_query($sqlRuteros);
        $cadena_codigo_linea = '';
        while ($datRuteros = mysql_fetch_array($respRuteros)) {
            $rutero_maestro = $datRuteros[0];
            $codigo_linea   = $datRuteros[1];
            $nombre_linea   = $datRuteros[2];
            $cadena_codigo_linea .= $codigo_linea .",";
            $sql = "SELECT r.nombre_rutero, l.nombre_linea from $tabla1 r, lineas l where r.codigo_linea=l.codigo_linea and 
			r.cod_rutero=$rutero_maestro";
            
			//echo $sql;
			
			$resp = mysql_query($sql);
            $dat = mysql_fetch_array($resp);
            $nom_rutero = $dat[0];
            $nom_linea = $dat[1];
            $cadena_imprimir .= '<th colspan="9">'.$nom_linea.'  -  '.$nombreVisitador.'  -  '.$nombreTerritorio.'</th>';
        }
        $cadena_codigo_linea = substr($cadena_codigo_linea, 0, -1);
        $cadena_imprimir .= "<th>&nbsp;</th>";
        $cadena_imprimir .= "</tr>";
        $cadena_imprimir .= "<tr>";
        $cadena_imprimir .= "<th>Especialidad</th>";
        $respRuteros = mysql_query($sqlRuteros);
        while($datRuteros2 = mysql_fetch_array($respRuteros)){
            //$cadena_imprimir .= "<th>L&iacute;nea</th>";
            $cadena_imprimir .= "<th>Cat A</th>";
            $cadena_imprimir .= "<th>Cat B</th>";
            $cadena_imprimir .= "<th>Cat C</th>";
            $cadena_imprimir .= "<th>Total M&eacute;dicos</th>";
            $cadena_imprimir .= "<th>Cont. A</th>";
            $cadena_imprimir .= "<th>Cont. B</th>";
            $cadena_imprimir .= "<th>Cont. C</th>";
            $cadena_imprimir .= "<th>Sub-total Contactos</th>";
        }
        $cadena_imprimir .= "<th>TOTAL CONTACTOS</th>";
        $cadena_imprimir .= "</tr>";
        
		
		$sqlEspeTxt="select DISTINCT(rd.cod_especialidad) from $tabla1 rc, $tabla2 rm, 
			$tabla3 rd 
			where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rm.cod_visitador=rd.cod_visitador 
				and rc.codigo_gestion='$codigoGestion' and rc.codigo_ciclo='$codigoCiclo' and 
				rc.cod_visitador='$visitador'";
		if($tipoRuteroRpt==1){
			$sqlEspeTxt.=" and rc.estado_aprobado=1 ";
		}
		$sqlEspeTxt.=" order by 1";
		
		$sql_espe=mysql_query($sqlEspeTxt);
		//$sql_espe = mysql_query("SELECT DISTINCT c.cod_especialidad, e.desc_especialidad  from lineas_visita a , 
		//lineas_visita_visitadores_copy b, lineas_visita_especialidad c, especialidades e where a.codigo_l_visita = b.codigo_l_visita 
		//and b.codigo_l_visita = c.codigo_l_visita and e.cod_especialidad = c.cod_especialidad and b.codigo_funcionario = $visitador 
		//and b.codigo_linea_visita in ($linea_finales) and b.codigo_ciclo = $codigoCiclo and b.codigo_gestion = $codigoGestion order by 2");
        
		//$sqlXxx="SELECT DISTINCT c.cod_especialidad, e.desc_especialidad  from lineas_visita a , lineas_visita_visitadores_copy b, lineas_visita_especialidad c, especialidades e where a.codigo_l_visita = b.codigo_l_visita and b.codigo_l_visita = c.codigo_l_visita and e.cod_especialidad = c.cod_especialidad and b.codigo_funcionario = $visitador and b.codigo_linea_visita in ($linea_finales) and b.codigo_ciclo = $codigoCiclo and b.codigo_gestion = $codigoGestion order by 2";
		//echo $sqlXxx;
		// $sql_espe = mysql_query("SELECT DISTINCT c.cod_especialidad, e.desc_especialidad  from lineas_visita a , lineas_visita_visitadores_copy b, lineas_visita_especialidad c, especialidades e where a.codigo_l_visita = b.codigo_l_visita and b.codigo_l_visita = c.codigo_l_visita and e.cod_especialidad = c.cod_especialidad and b.codigo_funcionario = $visitador and b.codigo_linea_visita in ($linea_finales) and b.codigo_ciclo = $codigoCiclo and b.codigo_gestion = $codigoGestion order by 2");
        // echo("SELECT DISTINCT c.cod_especialidad, e.desc_especialidad  from lineas_visita a , lineas_visita_visitadores_copy b, lineas_visita_especialidad c, especialidades e where a.codigo_l_visita = b.codigo_l_visita and b.codigo_l_visita = c.codigo_l_visita and e.cod_especialidad = c.cod_especialidad and b.codigo_funcionario = $visitador and b.codigo_linea_visita in ($linea_finales) and b.codigo_ciclo = $codigoCiclo and b.codigo_gestion = $codigoGestion order by 2");
        while ($row_espe = mysql_fetch_array($sql_espe)) {
            $cadena_imprimir .= "<tr>";
            $cadena_imprimir .= "<td>$row_espe[0]</td>";
            $respRuteros = mysql_query($sqlRuteros);
            $total_gral_contactos = 0;
            while($row_l = mysql_fetch_array($respRuteros)){
                $cod_rutero_f = $row_l[0];
                $linea_casilla = $row_l[1];
                $sql_linea = mysql_query(" SELECT a.nom_orden from lineas_visita a , lineas_visita_visitadores b, lineas_visita_especialidad c where a.codigo_l_visita = b.codigo_l_visita and b.codigo_l_visita = c.codigo_l_visita and b.codigo_funcionario = $visitador and a.codigo_linea = $linea_casilla and b.codigo_ciclo = $codigoCiclo and b.codigo_gestion = $codigoGestion and c.cod_especialidad = '$row_espe[0]'");
                $num_lineas = mysql_num_rows($sql_linea);
                if($num_lineas >0){
                    $linea_casilla_final = mysql_result($sql_linea, 0,0);
                }else{
                    $linea_casilla_final = "&nbsp;";
                }
                //$cadena_imprimir .= "<td>$linea_casilla_final</td>";
                $sql_medicos = "SELECT DISTINCT ( rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med from $tabla1 rmc, $tabla2 rm, $tabla3 rmd, medicos m where rmc.cod_rutero=rm.cod_rutero and rmc.cod_rutero='$cod_rutero_f' and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.codigo_linea='$linea_casilla'and rmc.cod_visitador='$visitador' and rmd.cod_visitador='$visitador' and rmd.cod_especialidad='$row_espe[0]' order by m.ap_pat_med, m.ap_mat_med, m.nom_med";
                

				//echo $sql_medicos."<br />";

                $resp_medicos = mysql_query($sql_medicos);
                $num_filas = mysql_num_rows($resp_medicos);
                $numero_total_medicos = $numero_total_medicos + $num_filas;
                $numero_a = 0;
                $numero_b = 0;
                $numero_c = 0;
                $cant_contactos_a = 0;
                $cant_contactos_b = 0;
                $cant_contactos_c = 0;
                while ($dat_medicos = mysql_fetch_array($resp_medicos)) {
                    $cod_med = $dat_medicos[0];
                    $sql_cant_contactos = "SELECT rmd.cod_med from $tabla1 rmc, $tabla2 rm, $tabla3 rmd, medicos m 
					where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and rmc.cod_visitador=rm.cod_visitador and rm.cod_visitador=rmd.cod_visitador 
					and m.cod_med=rmd.cod_med and rmc.cod_rutero='$cod_rutero_f' and 
					rmc.codigo_linea='$linea_casilla'and rmd.cod_visitador='$visitador' and rmd.cod_especialidad='$row_espe[0]' and rmd.cod_med='$cod_med' 
					and rmd.categoria_med = '$dat_medicos[4]'";
                    // echo $sql_cant_contactos."<br />";
                    $resp_cant_contactos = mysql_query($sql_cant_contactos);
                    $num_contactos = mysql_num_rows($resp_cant_contactos);

                    $categoria_med = $dat_medicos[4];
                    if ($categoria_med == "A") {
                        $numero_a++;
                        $cant_contactos_a = $cant_contactos_a + $num_contactos;
                    }
                    if ($categoria_med == "B") {
                        $numero_b++;
                        $cant_contactos_b = $cant_contactos_b + $num_contactos;
                    }
                    if ($categoria_med == "C") {
                        $numero_c++;
                        $cant_contactos_c = $cant_contactos_c + $num_contactos;
                    }
                }
                if ($numero_a == "") {
                    $numero_a = 0;
                }
                if ($numero_b == "") {
                    $numero_b = 0;
                }
                if ($numero_c == "") {
                    $numero_c = 0;
                }
                $total_medicos_espe = $numero_a + $numero_b + $numero_c;
                $total_contactos = $cant_contactos_a + $cant_contactos_b + $cant_contactos_c;
                $numero_total_contactos = $numero_total_contactos + $total_contactos;
                if ($num_filas != 0) {
                    $total_gral_contactos = $total_gral_contactos + $total_contactos;
                    $cadena_imprimir .= "<td align='center'>$numero_a</td>";
                    $cadena_imprimir .= "<td align='center'>$numero_b</td>";
                    $cadena_imprimir .= "<td align='center'>$numero_c</td>";
                    $cadena_imprimir .= "<td align='center'>$total_medicos_espe</td>";
                    $cadena_imprimir .= "<td align='center'>$cant_contactos_a</td>";
                    $cadena_imprimir .= "<td align='center'>$cant_contactos_b</td>";
                    $cadena_imprimir .= "<td align='center'>$cant_contactos_c</td>";
                    $cadena_imprimir .= "<td align='center' class='subtotal'>$total_contactos</td>";
                }else{
                    $cadena_imprimir .= "<td>&nbsp;</td>";
                    $cadena_imprimir .= "<td>&nbsp;</td>";
                    $cadena_imprimir .= "<td>&nbsp;</td>";
                    $cadena_imprimir .= "<td>&nbsp;</td>";
                    $cadena_imprimir .= "<td>&nbsp;</td>";
                    $cadena_imprimir .= "<td>&nbsp;</td>";
                    $cadena_imprimir .= "<td>&nbsp;</td>";
                    $cadena_imprimir .= "<td class='subtotal'>&nbsp;</td>";
                }
            }
            $cadena_imprimir .= "<td align='center' class='total'>$total_gral_contactos</td>";
            $cadena_imprimir .= "</tr>";
        }
        $cadena_imprimir .= "<tr><th>Totales</th></tr>";
        ?>
        <?php echo $cadena_imprimir; ?>
    </table>
    <?php
    echo "<script language='JavaScript'>totales($idtable);</script>";  
}
