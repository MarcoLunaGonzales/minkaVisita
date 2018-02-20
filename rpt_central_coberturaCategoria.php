<script type='text/javascript' language='javascript'>
function totales(){
    var main=document.getElementById('main');
    var numFilas=main.rows.length;
    var numCols=main.rows[1].cells.length;

    for(var j=2; j<=numCols-1; j++){
        var subtotal=0;
        for(var i=1; i<=numFilas-2; i++){
            var dato=parseInt(main.rows[i].cells[j].innerHTML);
            subtotal=subtotal+dato;
        }
        var fila=document.createElement('TH');
        main.rows[numFilas-1].appendChild(fila);
        main.rows[numFilas-1].cells[j].innerHTML=subtotal;
    }

    var coberturaA=parseFloat((main.rows[numFilas-1].cells[6].innerHTML / main.rows[numFilas-1].cells[2].innerHTML)*100);
    coberturaA=Math.round(coberturaA);
    main.rows[numFilas-1].cells[10].innerHTML=coberturaA+' %';

    var coberturaB=parseFloat((main.rows[numFilas-1].cells[7].innerHTML / main.rows[numFilas-1].cells[3].innerHTML)*100);
    coberturaB=Math.round(coberturaB);
    main.rows[numFilas-1].cells[11].innerHTML=coberturaB+' %';

    var coberturaC=parseFloat((main.rows[numFilas-1].cells[8].innerHTML / main.rows[numFilas-1].cells[4].innerHTML)*100);
    coberturaC=Math.round(coberturaC);
    main.rows[numFilas-1].cells[12].innerHTML=coberturaC+' %';

    var coberturaTotal=parseFloat((main.rows[numFilas-1].cells[9].innerHTML / main.rows[numFilas-1].cells[5].innerHTML)*100);
    coberturaTotal=Math.round(coberturaTotal);
    main.rows[numFilas-1].cells[13].innerHTML=coberturaTotal+' %';

    var coberturaActA=parseFloat((main.rows[numFilas-1].cells[6].innerHTML / (main.rows[numFilas-1].cells[2].innerHTML - main.rows[numFilas-1].cells[14].innerHTML))*100);
    coberturaActA=Math.round(coberturaActA);
    main.rows[numFilas-1].cells[18].innerHTML=coberturaActA+' %';

    var coberturaActB=parseFloat((main.rows[numFilas-1].cells[7].innerHTML / (main.rows[numFilas-1].cells[3].innerHTML - main.rows[numFilas-1].cells[15].innerHTML))*100);
    coberturaActB=Math.round(coberturaActB);
    main.rows[numFilas-1].cells[19].innerHTML=coberturaActB+' %';

    var coberturaActC=parseFloat((main.rows[numFilas-1].cells[8].innerHTML / (main.rows[numFilas-1].cells[4].innerHTML - main.rows[numFilas-1].cells[16].innerHTML))*100);
    coberturaActC=Math.round(coberturaActC);
    main.rows[numFilas-1].cells[20].innerHTML=coberturaActC+' %';

    var coberturaTotalAct=parseFloat((main.rows[numFilas-1].cells[9].innerHTML / (main.rows[numFilas-1].cells[5].innerHTML - main.rows[numFilas-1].cells[17].innerHTML))*100);
    coberturaTotalAct=Math.round(coberturaTotalAct);
    main.rows[numFilas-1].cells[21].innerHTML=coberturaTotalAct+' %';

}
</script>
<?php
function compara_fechas($fecha1,$fecha2) {   
    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
        list($dia1,$mes1,$anio1)=split("/",$fecha1);
    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
        list($dia1,$mes1,$anio1)=split("-",$fecha1);
    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
        list($dia2,$mes2,$anio2)=split("/",$fecha2);
    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
        list($dia2,$mes2,$anio2)=split("-",$fecha2);
    $dif = mktime(0,0,0,$mes1,$dia1,$anio1) - mktime(0,0,0, $mes2,$dia2,$anio2);
    return ($dif);
}

require("conexion.inc");
error_reporting(0);
require("estilos_reportes.inc");
require("funcion_nombres.php");
echo "<html><body onload='totales();'>";
$rpt_ciclo=$rpt_ciclo;
$rpt_gestion=$rpt_gestion;
$rpt_territorio=$rpt_territorio;
$rpt_visitador=$rpt_visitador;
$rpt_linea=$rpt_linea;
$nombreGestion=nombreGestion($rpt_gestion);

//esta parte saca el dia de contacto actual
$sql_dias_ini_fin="SELECT fecha_ini, fecha_fin from ciclos where cod_ciclo='$rpt_ciclo' and codigo_gestion='$rpt_gestion'and codigo_linea='$rpt_linea'";

// echo $sql_dias_ini_fin;

$resp_dias_ini_fin=mysql_query($sql_dias_ini_fin);
$dat_dias=mysql_fetch_array($resp_dias_ini_fin);
$fecha_ini_actual=$dat_dias[0];
$fecha_fin_actual=$dat_dias[1];
$fecha_actual=$fecha_ini_actual;
$inicio=$fecha_ini_actual;
$k=0;
list($anio,$mes,$dia)=explode("-",$fecha_actual);
$dia1=$dia;
while($inicio<$fecha_fin_actual) {
    $ban=0;
    while($ban==0) {   
        $nueva1 = mktime(0,0,0, $mes,$dia1,$anio);
        $dia_semana=date("l",$nueva1);
        if($dia_semana=='Sunday' or $dia_semana=='Saturday') {   
            $dia1=$dia1+1;
        } else {
            $ban=1;
        }
    }
    $num_dia=intval($k/5)+1;
    if($dia_semana=='Monday'){$dias[$k]="Lunes $num_dia";}
    if($dia_semana=='Tuesday'){$dias[$k]="Martes $num_dia";}
    if($dia_semana=='Wednesday'){$dias[$k]="Miercoles $num_dia";}
    if($dia_semana=='Thursday'){$dias[$k]="Jueves $num_dia";}
    if($dia_semana=='Friday'){$dias[$k]="Viernes $num_dia";}

    $fecha_actual=date("Y-m-d",$nueva1);
    $inicio=$fecha_actual;
    list($anio,$mes,$dia)=explode("-",$fecha_actual);
    $dia1=$dia+1;
    $fecha_actual_formato="$dia/$mes/$anio";
    $fechas[$k]=$fecha_actual_formato;
    $k++;
}
//fin vectores dias y fechas
$contador=1;
//desde aqui sacamos las fechas nuevas
$fecha_sistema=date("d/m/Y");
list($d_actual,$m_actual,$a_actual)=explode("/",$fecha_sistema);
$sec_actual=mktime(0,0,0,$m_actual,$d_actual,$a_actual);
for($i=0;$i<=$k-1;$i++) {   
    list($d_comp,$m_comp,$a_comp)=explode("/",$fechas[$i]);
    $sec_comp=mktime(0,0,0,$m_comp,$d_comp,$a_comp);
    if($sec_comp<=$sec_actual) {   
        $posicion=$i;
    }
}
$dia_contacto_sistema=$dias[$posicion];
$sql_id="SELECT id from orden_dias where dia_contacto='$dia_contacto_sistema'";
$resp_id=mysql_query($sql_id);
$dat_id=mysql_fetch_array($resp_id);
$id_sistema=$dat_id[0];
//fin sacar dia contacto actual
$sql_cabecera_gestion=mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion='$rpt_gestion' and codigo_linea='$rpt_linea'");
$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion=$datos_cab_gestion[0];
$sql_cab="SELECT cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
$dato=mysql_fetch_array($resp1);
$nombre_territorio=$dato[1];	

$sql_visitador="SELECT f.codigo_funcionario, f.paterno, f.materno, f.nombres from funcionarios f  
where f.codigo_funcionario in ($rpt_visitador) order by f.paterno, f.materno";

$resp_visitador=mysql_query($sql_visitador);
echo "<center><table border='0' class='textotit'><tr><th>Cobertura x Visitador<br>
Territorio: $nombre_territorio<br>Gestion: $nombreGestion Ciclo: $rpt_ciclo</th></tr></table></center><br>";
$indice_tabla=1;
echo "<center><table border='1' class='texto' width='100%' cellspacing='0' id='main'>";
echo "<tr><th>&nbsp;</th><th>Visitador</th>
<th>Cont. A</th>
<th>Cont. B</th>
<th>Cont. C</th>
<th>Total Cont.</th>
<th>Ejecutado A</th>
<th>Ejecutado B</th>
<th>Ejecutado C</th>
<th>Total Ejecutado</th>
<th>Cobertura A</th>
<th>Cobertura B</th>
<th>Cobertura C</th>
<th>Total Cobertura</th>
<th>Baja Cont. A</th>
<th>Baja Cont. B</th>
<th>Baja Cont. C</th>
<th>Total Bajas</th>
<th>Cobertura Act. A</th>
<th>Cobertura Act. B</th>
<th>Cobertura Act. C</th>
<th>Total Cobertura Act.</th>
</tr>";

while($dat_visitador=mysql_fetch_array($resp_visitador)) {
    $nro_contactos_baja_diasA=0;
    $nro_contactos_baja_diasB=0;
    $nro_contactos_baja_diasC=0;

    $bajaMedA=0;
    $bajaMedB=0;
    $bajaMedC=0;

    $codigo_visitador=$dat_visitador[0];
    $nombre_visitador="$dat_visitador[1] $dat_visitador[3]";

    $sql_contactos_maestro="SELECT count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, 
	rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and rd.cod_contacto=r.cod_contacto and 
	rc.cod_visitador='$codigo_visitador' and rc.codigo_ciclo='$rpt_ciclo' and rc.codigo_gestion='$rpt_gestion' and 
	rd.categoria_med='A' and rc.estado_aprobado=1";
    //echo $sql_contactos_maestro;
	$resp_contactos_maestro=mysql_query($sql_contactos_maestro);
    $dat_maestro=mysql_fetch_array($resp_contactos_maestro);
    $numero_contactos_maestroA=$dat_maestro[0];

    $sql_contactos_maestro="SELECT count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, 
	rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and rd.cod_contacto=r.cod_contacto and 
	rc.cod_visitador='$codigo_visitador' and rc.codigo_ciclo='$rpt_ciclo' and rc.codigo_gestion='$rpt_gestion' 
	and rd.categoria_med='B' and rc.estado_aprobado=1";
    $resp_contactos_maestro=mysql_query($sql_contactos_maestro);
    $dat_maestro=mysql_fetch_array($resp_contactos_maestro);
    $numero_contactos_maestroB=$dat_maestro[0];

    $sql_contactos_maestro="SELECT count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, 
	rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and rd.cod_contacto=r.cod_contacto and 
	rc.cod_visitador='$codigo_visitador' and rc.codigo_ciclo='$rpt_ciclo' and rc.codigo_gestion='$rpt_gestion' 
	and rd.categoria_med='C' and rc.estado_aprobado=1";
    $resp_contactos_maestro=mysql_query($sql_contactos_maestro);
    $dat_maestro=mysql_fetch_array($resp_contactos_maestro);
    $numero_contactos_maestroC=$dat_maestro[0];

    $totalContactosMaestro=$numero_contactos_maestroA+$numero_contactos_maestroB+$numero_contactos_maestroC;
    $totalContactosMaestro=$numero_contactos_maestroA+$numero_contactos_maestroB+$numero_contactos_maestroC;

    $sql_contactos_ejecutado="SELECT count(rd.cod_contacto) from rutero_detalle rd, rutero r where 
	r.cod_ciclo='$rpt_ciclo' and r.codigo_gestion='$rpt_gestion' and r.cod_visitador='$codigo_visitador' and 
	r.cod_contacto=rd.cod_contacto and rd.estado='1' and rd.categoria_med='A'";
    $resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
    $dat_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
    $numero_contactos_ejecutadoA=$dat_ejecutado[0];
    if($numero_contactos_maestroA!=0){
        $cobertura_visitadorA=($numero_contactos_ejecutadoA/$numero_contactos_maestroA)*100;
    }else{
        $cobertura_visitadorA=0;
    }
    $cobertura_visitadorA=round($cobertura_visitadorA);

    $sql_contactos_ejecutado="SELECT count(rd.cod_contacto) from rutero_detalle rd, rutero r where r.cod_ciclo='$rpt_ciclo' 
	and r.codigo_gestion='$rpt_gestion' and r.cod_visitador='$codigo_visitador' 
	and r.cod_contacto=rd.cod_contacto and rd.estado='1' and rd.categoria_med='B'";
    $resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
    $dat_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
    $numero_contactos_ejecutadoB=$dat_ejecutado[0];
    if($numero_contactos_maestroB!=0){
        $cobertura_visitadorB=($numero_contactos_ejecutadoB/$numero_contactos_maestroB)*100;
    }else{
        $cobertura_visitadorB=0;
    }
    $cobertura_visitadorB=round($cobertura_visitadorB);

    $sql_contactos_ejecutado="SELECT count(rd.cod_contacto) from rutero_detalle rd, rutero r where 
	r.cod_ciclo='$rpt_ciclo' and r.codigo_gestion='$rpt_gestion' and r.cod_visitador='$codigo_visitador' and 
	r.cod_contacto=rd.cod_contacto and rd.estado='1' and rd.categoria_med='C'";
    $resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
    $dat_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
    $numero_contactos_ejecutadoC=$dat_ejecutado[0];
    if($numero_contactos_maestroC!=0){
        $cobertura_visitadorC=($numero_contactos_ejecutadoC/$numero_contactos_maestroC)*100;
    }else{
        $cobertura_visitadorC=0;
    }
    $cobertura_visitadorC=round($cobertura_visitadorC);

    $totalEjecutado=$numero_contactos_ejecutadoA+$numero_contactos_ejecutadoB+$numero_contactos_ejecutadoC;
    $totalCobertura=round(($totalEjecutado/$totalContactosMaestro)*100);

    $numero_medicos_baja=0;

    $sql_medicos_baja="SELECT distinct(bm.cod_med), bm.inicio, bm.fin, rd.categoria_med from 
	baja_medicos bm, medicos m, rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, 
	rutero_maestro_detalle_aprobado rd where m.cod_ciudad = '$rpt_territorio' and bm.codigo_linea in ($rpt_linea) and m.cod_med = bm.cod_med and rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and   rc.codigo_gestion=$rpt_gestion and rc.codigo_linea in ($rpt_linea) and rc.codigo_ciclo=$rpt_ciclo and rc.cod_visitador=$codigo_visitador and rd.cod_med=m.cod_med";
    
	//echo $sql_medicos_baja."<br />";

    $resp_medicos_baja=mysql_query($sql_medicos_baja);

    while($dat_medicos=mysql_fetch_array($resp_medicos_baja)) {   
        $codigo_medico=$dat_medicos[0];
        $inicio_baja=$dat_medicos[1];
        $fin_baja=$dat_medicos[2];
        $catMed=$dat_medicos[3];

        $inicio_baja_real=$inicio_baja[8].$inicio_baja[9]."/".$inicio_baja[5].$inicio_baja[6]."/".$inicio_baja[0].$inicio_baja[1].$inicio_baja[2].$inicio_baja[3];
        $fin_baja_real=$fin_baja[8].$fin_baja[9]."/".$fin_baja[5].$fin_baja[6]."/".$fin_baja[0].$fin_baja[1].$fin_baja[2].$fin_baja[3];
        $sql_verifica_medico="SELECT r.dia_contacto from rutero r, rutero_detalle rd where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$rpt_ciclo' and r.codigo_gestion='$rpt_gestion' and r.cod_visitador='$codigo_visitador'and r.codigo_linea='$rpt_linea' and rd.cod_med='$codigo_medico'";
        // echo $sql_verifica_medico."<br />";
        $ccoodd_mmed = '';
        $resp_verifica_medico=mysql_query($sql_verifica_medico);
        while($dat_verifica=mysql_fetch_array($resp_verifica_medico)) {   
            $dia_contacto_medico=$dat_verifica[0];
            $ccoodd_mmed .= $codigo_medico.",";
            for($j=1;$j<=30;$j++) {   
                // echo $dias[$j]." as<br />";
                if($dias[$j]==$dia_contacto_medico) {   
                    $fecha_baja_contacto=$fechas[$j];
                    if((compara_fechas($fecha_baja_contacto,$inicio_baja_real)>=0) and (compara_fechas($fecha_baja_contacto,$fin_baja_real)<=0)) {   
                        if($catMed=="A"){
                            $bajaMedA++;
                        }
                        if($catMed=="B"){
                            $bajaMedB++;
                        }
                        if($catMed=="C"){
                            $bajaMedC++;
                        }
                    }
                }
            }
        }
    }

    $sql_me_a = mysql_query("SELECT COUNT(rd.cod_med) from rutero r, rutero_detalle rd where r.cod_contacto = rd.cod_contacto and r.cod_ciclo = $rpt_ciclo and r.codigo_gestion = $rpt_gestion and r.cod_visitador = $codigo_visitador and rd.estado = 2 and rd.categoria_med = 'A'");
    $me_A = mysql_result($sql_me_a, 0, 0);    
    $sql_me_b = mysql_query("SELECT COUNT(rd.cod_med) from rutero r, rutero_detalle rd where r.cod_contacto = rd.cod_contacto and r.cod_ciclo = $rpt_ciclo and r.codigo_gestion = $rpt_gestion and r.cod_visitador = $codigo_visitador and rd.estado = 2 and rd.categoria_med = 'B'");
    $me_B = mysql_result($sql_me_b, 0, 0);
    $sql_me_c = mysql_query("SELECT COUNT(rd.cod_med) from rutero r, rutero_detalle rd where r.cod_contacto = rd.cod_contacto and r.cod_ciclo = $rpt_ciclo and r.codigo_gestion = $rpt_gestion and r.cod_visitador = $codigo_visitador and rd.estado = 2 and rd.categoria_med = 'C'");
    $me_C = mysql_result($sql_me_c, 0, 0);
    // echo "dsadsa".$bajaMedA."<br />";
    
	
	$sql_baja_dias="SELECT distinct(b.dia_contacto), b.turno from baja_dias b, baja_dias_detalle bd, baja_dias_detalle_visitador bdv 
	where b.codigo_baja=bd.codigo_baja and bd.codigo_baja=bdv.codigo_baja and b.ciclo='$rpt_ciclo' 
	and b.gestion='$rpt_gestion' and bdv.codigo_visitador='$codigo_visitador'";
    
	//echo $sql_baja_dias;
	
	$resp_baja_dias=mysql_query($sql_baja_dias);

    while($dat_baja_dias=mysql_fetch_array($resp_baja_dias)) {   
        $dia_contacto_baja=$dat_baja_dias[0];
        $turno_contacto_baja=$dat_baja_dias[1];
        $sql_nro_contactos_baja="SELECT count(r.cod_contacto), rd.categoria_med from rutero r, rutero_detalle rd 
			where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$rpt_ciclo' and r.codigo_gestion='$rpt_gestion' 
			and r.cod_visitador='$codigo_visitador' and r.dia_contacto='$dia_contacto_baja' and turno='$turno_contacto_baja' 
			group by rd.categoria_med";
        $resp_nro_contactos_baja=mysql_query($sql_nro_contactos_baja);

        while($dat_contactos_baja=mysql_fetch_array($resp_nro_contactos_baja)){
            $numContactos=$dat_contactos_baja[0];
            $categoriaMed=$dat_contactos_baja[1];
            if($categoriaMed=="A"){
                $nro_contactos_baja_diasA=$nro_contactos_baja_diasA+$numContactos;
            }
            if($categoriaMed=="B"){
                $nro_contactos_baja_diasB=$nro_contactos_baja_diasB+$numContactos;
            }
            if($categoriaMed=="C"){
                $nro_contactos_baja_diasC=$nro_contactos_baja_diasC+$numContactos;
            }
        }
    }
    $bajaTotalA=$nro_contactos_baja_diasA+$bajaMedA+$me_A;
    $bajaTotalB=$nro_contactos_baja_diasB+$bajaMedB+$me_B;
    $bajaTotalC=$nro_contactos_baja_diasC+$bajaMedC+$me_C;

    $totalBajaMed=$bajaTotalA+$bajaTotalB+$bajaTotalC;

    $contactos_realesA=$numero_contactos_maestroA-$bajaTotalA;
    $contactos_realesB=$numero_contactos_maestroB-$bajaTotalB;
    $contactos_realesC=$numero_contactos_maestroC-$bajaTotalC;

    $totalContactosReales=$contactos_realesA+$contactos_realesB+$contactos_realesC;

    if($contactos_realesA!=0){
        $cobertura_realA=round(($numero_contactos_ejecutadoA/$contactos_realesA)*100);
    }else{
        $cobertura_realA=0;
    }
    if($contactos_realesB!=0){
        $cobertura_realB=round(($numero_contactos_ejecutadoB/$contactos_realesB)*100);
    }else{
        $cobertura_realB=0;
    }
    if($contactos_realesC!=0){
        $cobertura_realC=round(($numero_contactos_ejecutadoC/$contactos_realesC)*100);
    }else{
        $cobertura_realC=0;
    }
    if($totalContactosReales!=0){
        $totalCoberturaReal=round(($totalEjecutado/$totalContactosReales)*100);
    }else{
        $totalCoberturaReal=0;
    }

    echo "<tr>
    <td align='center'>$indice_tabla</td>
    <td>$nombre_visitador</td>
    <td align='center'>$numero_contactos_maestroA</td>
    <td align='center'>$numero_contactos_maestroB</td>
    <td align='center'>$numero_contactos_maestroC</td>
    <td align='center'>$totalContactosMaestro</td>
    <td align='center'>$numero_contactos_ejecutadoA</td>
    <td align='center'>$numero_contactos_ejecutadoB</td>
    <td align='center'>$numero_contactos_ejecutadoC</td>
    <td align='center'>$totalEjecutado</td>
    <td align='center'>$cobertura_visitadorA %</td>
    <td align='center'>$cobertura_visitadorB %</td>
    <td align='center'>$cobertura_visitadorC %</td>
    <td align='center'>$totalCobertura %</td>
    <td align='center'>$bajaTotalA</td>
    <td align='center'>$bajaTotalB</td>
    <td align='center'>$bajaTotalC</td>
    <td align='center'>$totalBajaMed</td>
    <td align='center'>$cobertura_realA %</td>
    <td align='center'>$cobertura_realB %</td>
    <td align='center'>$cobertura_realC %</td>
    <td align='center'>$totalCoberturaReal %</td>
    </tr>";
    $indice_tabla++;
}
echo "<tr><th>&nbsp;</th><TH>TOTALES</TH></tr>";

echo "</table></center><br>";
echo "</body></html>";
