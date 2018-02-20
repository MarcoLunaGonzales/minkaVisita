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
set_time_limit(0);

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
echo "<html><body onload=''>";

$rpt_ciclo=$rpt_ciclo;
$rpt_gestion=$rpt_gestion;
$rpt_territorio=$rpt_territorio;
$rpt_visitador=$rpt_visitador;
$rpt_linea=$rpt_linea;
$nombreGestion=nombreGestion($rpt_gestion);
$rpt_categoria=$rpt_categoria;
$rpt_categoria=str_replace("|","'",$rpt_categoria);
$rpt_nombreterritorio=$rpt_nombreterritorio;

$sql_cabecera_gestion=mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion='$rpt_gestion' and codigo_linea='$rpt_linea'");
$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion=$datos_cab_gestion[0];



echo "<center><table border='0' class='textotit'><tr><th>Cobertura Semanal<br>
Territorio: $rpt_nombreterritorio   Cat. Medico: $rpt_categoria<br>Gestion: $nombreGestion Ciclo: $rpt_ciclo</th></tr></table></center><br>";
$indice_tabla=1;
echo "<center><table border='1' class='texto' width='60%' cellspacing='0' id='main'>";
echo "<tr>
<th>&nbsp;</th>
<th>Semana</th>
<th>Planificado</th>
<th>Realizado</th>
<th>No Realizado</th>
<th>Desasignacion</th>
<th>Promedio Visita</th>
<th>Cumplimiento</th>
<th>Cobertura</th>
</tr>";

$totalContactosMaestro=0;
$totalContactosEjecutado=0;
$totalBajaContactos=0;

for ($ii=1; $ii<=5; $ii++){

	$semanaX="Semana $ii";

    $sql_contactos_maestro="SELECT count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, 
	rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and rd.cod_contacto=r.cod_contacto and
	rc.cod_visitador=r.cod_visitador and r.cod_visitador=rd.cod_visitador and rc.estado_aprobado=1 and
	rc.cod_visitador in (select codigo_funcionario from funcionarios where cod_ciudad in ($rpt_territorio)) and rc.codigo_ciclo='$rpt_ciclo' and 
	rc.codigo_gestion='$rpt_gestion' and rd.categoria_med in ($rpt_categoria) and r.dia_contacto like '%$ii%'";	
	$resp_contactos_maestro=mysql_query($sql_contactos_maestro);
    $dat_maestro=mysql_fetch_array($resp_contactos_maestro);
    $numero_contactos_maestro=$dat_maestro[0];
	$totalContactosMaestro=$totalContactosMaestro+$numero_contactos_maestro;
	
    $sql_contactos_ejecutado="SELECT count(*) from rutero_detalle rd, rutero r where 
	r.cod_ciclo='$rpt_ciclo' and r.codigo_gestion='$rpt_gestion' and r.cod_visitador in (select codigo_funcionario from funcionarios where cod_ciudad in ($rpt_territorio)) and 
	r.cod_contacto=rd.cod_contacto and r.cod_visitador=rd.cod_visitador and rd.estado='1' 
	and rd.categoria_med in ($rpt_categoria) and r.dia_contacto like '%$ii%'";
    $resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
    $dat_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
    $numero_contactos_ejecutado=$dat_ejecutado[0];
	$totalContactosEjecutado=$totalContactosEjecutado+$numero_contactos_ejecutado;
	
	
	$sqlNumVisitadores="SELECT count(*) from rutero_maestro_cab_aprobado rc 
	where rc.cod_visitador in (select codigo_funcionario from funcionarios where cod_ciudad in ($rpt_territorio)) 
	and rc.estado_aprobado=1 and rc.codigo_ciclo='$rpt_ciclo' and rc.codigo_gestion='$rpt_gestion'";
	$respNumVisitadores=mysql_query($sqlNumVisitadores);
	$numeroVisitadores=mysql_result($respNumVisitadores,0,0);
	
	$promedioVisita=round($numero_contactos_ejecutado/5/$numeroVisitadores);
	
    //DESDE ACA SON LAS BAJAS
	/*$sql_medicos_baja="SELECT distinct(bm.cod_med), bm.inicio, bm.fin, rd.categoria_med from 
	baja_medicos bm, medicos m, rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, 
	rutero_maestro_detalle_aprobado rd where m.cod_ciudad = '$rpt_territorio' and bm.codigo_linea in 
	($rpt_linea) and m.cod_med = bm.cod_med and rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and   rc.codigo_gestion=$rpt_gestion and rc.codigo_linea in ($rpt_linea) and rc.codigo_ciclo=$rpt_ciclo and rc.cod_visitador=$codigo_visitador and rd.cod_med=m.cod_med";
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
	*/
	
	
	$sqlBajasContactos="SELECT COUNT(rd.cod_med) from rutero r, rutero_detalle rd where r.cod_contacto = rd.cod_contacto and 
		r.cod_ciclo = $rpt_ciclo and r.codigo_gestion = $rpt_gestion and r.cod_visitador in (select codigo_funcionario from funcionarios where cod_ciudad in ($rpt_territorio)) and 
		rd.estado = 2 and rd.categoria_med in ($rpt_categoria) and r.dia_contacto like '%$ii%'";
    $sql_me_a = mysql_query($sqlBajasContactos);
    $numeroBajas = mysql_result($sql_me_a, 0, 0);    
    $totalBajaContactos=$totalBajaContactos+$numeroBajas;
	
	if($numero_contactos_maestro!=0){
		$cobertura_visitador=($numero_contactos_ejecutado/($numero_contactos_maestro-$numeroBajas))*100;
        $cumplimiento_visitador=($numero_contactos_ejecutado/$numero_contactos_maestro)*100;
    }else{
        $cobertura_visitador=0;
		$cumplimiento_visitador=0;
    }

    $cumplimiento_visitador=round($cumplimiento_visitador);
	$cobertura_visitador=round($cobertura_visitador);

	$noRealizado=$numero_contactos_maestro-$numero_contactos_ejecutado;
	

    echo "<tr>
    <td align='center'>$indice_tabla</td>
    <td>$semanaX</td>
    <td align='center'>$numero_contactos_maestro</td>
    <td align='center'>$numero_contactos_ejecutado</td>
	<td align='center'>$noRealizado</td>
	<td align='center'>$numeroBajas</td>
	<td align='center'>$promedioVisita</td>
    <td align='center'>$cumplimiento_visitador %</td>  
	<td align='center'>$cobertura_visitador %</td>
    </tr>";
    $indice_tabla++;
}
$totalNoRealizado=$totalContactosMaestro-$totalContactosEjecutado;
$totalCobertura=round(($totalContactosEjecutado/($totalContactosMaestro-$totalBajaContactos))*100);
$totalCumplimiento=round(($totalContactosEjecutado/$totalContactosMaestro)*100);


echo "<tr><th>&nbsp;</th><TH>TOTALES</TH><th>$totalContactosMaestro</th><th>$totalContactosEjecutado</th>
<th>$totalNoRealizado</th><th>0</th><th>-</th><th>$totalCumplimiento %</th><th>$totalCobertura %</th></tr>";

echo "</table></center><br>";
echo "</body></html>";
