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
require("funcion_nombres.php");
error_reporting(0);
require("estilos_reportes.inc");
echo "<html><body onload=''>";

$rpt_ciclo=$rpt_ciclo;
$rpt_gestion=$rpt_gestion;
$rpt_territorio=$global_agencia;
$rpt_visitador=$global_visitador;
$rpt_linea=$global_linea;
$nombreGestion=nombreGestion($rpt_gestion);
$rpt_categoria=$rpt_categoria;
$rpt_categoria=str_replace("|","'",$rpt_categoria);

$nombreVisitador=nombreVisitador($rpt_visitador);

$sql_cabecera_gestion=mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion='$rpt_gestion' and codigo_linea='$rpt_linea'");
$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion=$datos_cab_gestion[0];



echo "<center><table border='0' class='textotit'><tr><th>Cobertura por Dia<br>
Cat. Medico: $rpt_categoria<br>Gestion: $nombreGestion Ciclo: $rpt_ciclo<br>$nombreVisitador
</th></tr></table></center><br>";
$indice_tabla=1;
echo "<center><table border='0' class='texto' width='60%' cellspacing='0' id='main'>";
echo "<tr>
<th>&nbsp;</th>
<th>Dia</th>
<th>Planificado</th>
<th>Realizado</th>
<th>No Realizado</th>";
$sqlTiposBaja="select m.codigo_motivo, m.descripcion_motivo from motivos_baja m where m.tipo_motivo=3 order by 2";
$respTiposBaja=mysql_query($sqlTiposBaja);
$colorBaja="#F78181";
while($datTiposBaja=mysql_fetch_array($respTiposBaja)){
	$codTipoBaja=$datTiposBaja[0];
	$nombreTipoBaja=$datTiposBaja[1];
	echo "<th bgcolor='$colorBaja'>$nombreTipoBaja</th>";
}
echo "<th>Desasignacion</th>
<th>Promedio Visita</th>
<th>Cumplimiento</th>
<th>Cobertura</th>
</tr>";

$totalContactosMaestro=0;
$totalContactosEjecutado=0;
$totalBajaContactos=0;

for($jj=1; $jj<=5; $jj++){
	
	$totalContactosMaestroSemana=0;
	$totalContactosEjecutadoSemana=0;
	$totalBajaContactosSemana=0;

	for ($ii=1; $ii<=5; $ii++){

		if($ii==1){	$diaX="Lunes"; }
		if($ii==2){	$diaX="Martes"; }
		if($ii==3){	$diaX="Miercoles"; }
		if($ii==4){	$diaX="Jueves"; }
		if($ii==5){	$diaX="Viernes"; }
		$diaX=$diaX." ".$jj;

		//$semanaX="Semana $ii";

		$sql_contactos_maestro="SELECT count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, 
		rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and rd.cod_contacto=r.cod_contacto and
		rc.cod_visitador=r.cod_visitador and r.cod_visitador=rd.cod_visitador and rc.estado_aprobado=1 and
		rc.cod_visitador in ($rpt_visitador) and rc.codigo_ciclo='$rpt_ciclo' and 
		rc.codigo_gestion='$rpt_gestion' and rd.categoria_med in ($rpt_categoria) and r.dia_contacto like '$diaX'";	
		$resp_contactos_maestro=mysql_query($sql_contactos_maestro);
		$dat_maestro=mysql_fetch_array($resp_contactos_maestro);
		$numero_contactos_maestro=$dat_maestro[0];
		$totalContactosMaestro=$totalContactosMaestro+$numero_contactos_maestro;
		$totalContactosMaestroSemana=$totalContactosMaestroSemana+$numero_contactos_maestro;
		
		
		$sql_contactos_ejecutado="SELECT count(*) from rutero_detalle rd, rutero r where 
		r.cod_ciclo='$rpt_ciclo' and r.codigo_gestion='$rpt_gestion' and r.cod_visitador in ($rpt_visitador) and 
		r.cod_contacto=rd.cod_contacto and r.cod_visitador=rd.cod_visitador and rd.estado='1' 
		and rd.categoria_med in ($rpt_categoria) and r.dia_contacto like '$diaX'";
		$resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
		$dat_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
		$numero_contactos_ejecutado=$dat_ejecutado[0];
		$totalContactosEjecutado=$totalContactosEjecutado+$numero_contactos_ejecutado;
		$totalContactosEjecutadoSemana=$totalContactosEjecutadoSemana+$numero_contactos_ejecutado;
		
		
		$sqlNumVisitadores="SELECT count(*) from rutero_maestro_cab_aprobado rc 
		where rc.cod_visitador in ($rpt_visitador) 
		and rc.estado_aprobado=1 and rc.codigo_ciclo='$rpt_ciclo' and rc.codigo_gestion='$rpt_gestion'";
		$respNumVisitadores=mysql_query($sqlNumVisitadores);
		$numeroVisitadores=mysql_result($respNumVisitadores,0,0);
		
		$promedioVisita=round($numero_contactos_ejecutado/$numeroVisitadores);
		
		$sqlTiposBaja="select m.codigo_motivo, m.descripcion_motivo from motivos_baja m where m.tipo_motivo=3 order by 2";
		$respTiposBaja=mysql_query($sqlTiposBaja);
		$cadTiposBaja="";
		while($datTiposBaja=mysql_fetch_array($respTiposBaja)){
			$codTipoBaja=$datTiposBaja[0];
			$nombreTipoBaja=$datTiposBaja[1];
			$sqlBajaTipoContacto="SELECT COUNT(rd.cod_med) from rutero r, rutero_detalle rd, registro_no_visita rn where r.cod_contacto = rd.cod_contacto and r.cod_visitador=rd.cod_visitador 
			and rn.cod_contacto=rd.cod_contacto and rn.orden_visita=rd.orden_visita 
			and r.cod_ciclo = $rpt_ciclo and r.codigo_gestion = $rpt_gestion and r.cod_visitador in ($rpt_visitador) and 
			rd.estado = 2 and rd.categoria_med in ($rpt_categoria) and r.dia_contacto like '$diaX' and rn.codigo_motivo='$codTipoBaja'";
			$respBajaTipoContacto=mysql_query($sqlBajaTipoContacto);
			$numeroBajasPorTipo=mysql_result($respBajaTipoContacto,0,0);
			$cadTiposBaja=$cadTiposBaja."<td bgcolor='$colorBaja' align='center'>$numeroBajasPorTipo</td>";
		}
		
		$sqlBajasContactos="SELECT COUNT(rd.cod_med) from rutero r, rutero_detalle rd where r.cod_contacto = rd.cod_contacto and r.cod_visitador=rd.cod_visitador and
			r.cod_ciclo = $rpt_ciclo and r.codigo_gestion = $rpt_gestion and r.cod_visitador in ($rpt_visitador) and 
			rd.estado = 2 and rd.categoria_med in ($rpt_categoria) and r.dia_contacto like '$diaX'";
		$sql_me_a = mysql_query($sqlBajasContactos);
		$numeroBajas = mysql_result($sql_me_a, 0, 0);    
		$totalBajaContactos=$totalBajaContactos+$numeroBajas;
		$totalBajaContactosSemana=$totalBajaContactosSemana+$numeroBajas;
		
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
		<td align='center'></td>
		<td>$diaX</td>
		<td align='center'>$numero_contactos_maestro</td>
		<td align='center'>$numero_contactos_ejecutado</td>
		<td align='center'>$noRealizado</td>";
		echo $cadTiposBaja;
		echo "<td align='center'>$numeroBajas</td>
		<td align='center'>$promedioVisita</td>
		<td align='center'>$cumplimiento_visitador %</td>  
		<td align='center'>$cobertura_visitador %</td>
		</tr>";
		$indice_tabla++;
	}

	$totalNoRealizadoSemana=$totalContactosMaestroSemana-$totalContactosEjecutadoSemana;
	$totalCoberturaSemana=round(($totalContactosEjecutadoSemana/($totalContactosMaestroSemana-$totalBajaContactosSemana))*100);
	$totalCumplimientoSemana=round(($totalContactosEjecutadoSemana/$totalContactosMaestroSemana)*100);

	echo "<tr bgcolor='#FE9A2E'><th>&nbsp;</th><TH>Semana $jj</TH><th>$totalContactosMaestroSemana</th>
	<th>$totalContactosEjecutadoSemana</th>
	<th>$totalNoRealizadoSemana</th>";
	$sqlTiposBaja="select m.codigo_motivo, m.descripcion_motivo from motivos_baja m where m.tipo_motivo=3 order by 2";
	$respTiposBaja=mysql_query($sqlTiposBaja);
	$cadTiposBaja="";
	while($datTiposBaja=mysql_fetch_array($respTiposBaja)){
		$codTipoBaja=$datTiposBaja[0];
		$nombreTipoBaja=$datTiposBaja[1];
		$sqlBajaTipoContacto="SELECT COUNT(rd.cod_med) from rutero r, rutero_detalle rd, registro_no_visita rn where r.cod_contacto = rd.cod_contacto and r.cod_visitador=rd.cod_visitador 
		and rn.cod_contacto=rd.cod_contacto and rn.orden_visita=rd.orden_visita 
		and r.cod_ciclo = $rpt_ciclo and r.codigo_gestion = $rpt_gestion and r.cod_visitador in ($rpt_visitador) and 
		rd.estado = 2 and rd.categoria_med in ($rpt_categoria) and r.dia_contacto like '%$jj%' and rn.codigo_motivo='$codTipoBaja'";
		$respBajaTipoContacto=mysql_query($sqlBajaTipoContacto);
		$numeroBajasPorTipo=mysql_result($respBajaTipoContacto,0,0);
		$cadTiposBaja=$cadTiposBaja."<th align='center'>$numeroBajasPorTipo</th>";
	}
	echo $cadTiposBaja;
	echo "<th>$totalBajaContactosSemana</th><th>-</th><th>$totalCumplimientoSemana %</th><th>$totalCoberturaSemana %</th></tr>";
}

$totalNoRealizado=$totalContactosMaestro-$totalContactosEjecutado;
$totalCobertura=round(($totalContactosEjecutado/($totalContactosMaestro-$totalBajaContactos))*100);
$totalCumplimiento=round(($totalContactosEjecutado/$totalContactosMaestro)*100);


echo "<tr><th>&nbsp;</th><TH>TOTALES</TH><th>$totalContactosMaestro</th><th>$totalContactosEjecutado</th>
<th>$totalNoRealizado</th>";
$sqlTiposBaja="select m.codigo_motivo, m.descripcion_motivo from motivos_baja m where m.tipo_motivo=3 order by 2";
$respTiposBaja=mysql_query($sqlTiposBaja);
$cadTiposBaja="";
while($datTiposBaja=mysql_fetch_array($respTiposBaja)){
	$codTipoBaja=$datTiposBaja[0];
	$nombreTipoBaja=$datTiposBaja[1];
	$sqlBajaTipoContacto="SELECT COUNT(rd.cod_med) from rutero r, rutero_detalle rd, registro_no_visita rn where r.cod_contacto = rd.cod_contacto and r.cod_visitador=rd.cod_visitador 
	and rn.cod_contacto=rd.cod_contacto and rn.orden_visita=rd.orden_visita 
	and r.cod_ciclo = $rpt_ciclo and r.codigo_gestion = $rpt_gestion and r.cod_visitador in ($rpt_visitador) and 
	rd.estado = 2 and rd.categoria_med in ($rpt_categoria) and rn.codigo_motivo='$codTipoBaja'";
	$respBajaTipoContacto=mysql_query($sqlBajaTipoContacto);
	$numeroBajasPorTipo=mysql_result($respBajaTipoContacto,0,0);
	$cadTiposBaja=$cadTiposBaja."<th align='center'>$numeroBajasPorTipo</th>";
}
echo $cadTiposBaja;
echo "<th>$totalBajaContactos</th><th>-</th><th>$totalCumplimiento %</th><th>$totalCobertura %</th></tr>";

echo "</table></center><br>";
echo "</body></html>";
