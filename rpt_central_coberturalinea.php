<?php
$global_agencia = $rpt_territorio;
$global_linea = $rpt_linea;
require("conexion.inc");
require("estilos_reportes_central.inc");
$sql_cabecera_gestion = mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
$datos_cab_gestion = mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion = $datos_cab_gestion[0];
$sql_cab = "select cod_ciudad, descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp1 = mysql_query($sql_cab);
$dato = mysql_fetch_array($resp1);
$nombre_territorio = $dato[1];
if ($rpt_territorio == 0) {
    $nombre_territorio = "Todo";
} 
$rptRutero=$rpt_rutero;
if($rptRutero==0){
	$nombreVistaRutero="Aprobado";
	$codVistaRutero="1";
}else{
	$nombreVistaRutero="Todo";
	$codVistaRutero="0,1";
}

$rptTerritorioNuevo="";
if($rpt_territorio==0){
	$sqlTerritorio="select cod_ciudad from ciudades";
	$respTerritorio=mysql_query($sqlTerritorio);
	while($datTerritorio=mysql_fetch_array($respTerritorio)){
		$rptTerritorioNuevo.="$datTerritorio[0],";
	}	
	$rptTerritorioNuevo.="0";
}
else{
	$rptTerritorioNuevo=$rpt_territorio;
}
$sql_cab = "select nombre_linea from lineas where codigo_linea=$global_linea";
$resp1 = mysql_query($sql_cab);
$dato = mysql_fetch_array($resp1);
$nombre_linea = $dato[0];

echo "<center><table border='0' class='textotit'><tr><th>Cobertura de Medicos Listado Madre Vs. Rutero Maestro<br>
Territorio: $nombre_territorio<br>
Linea: $nombre_linea Ruteros Maestro: $nombreVistaRutero</th></tr></table></center><br>";
    $sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
    $resp = mysql_query($sql);
    echo "<center><table border='1' class='texto' cellspacing='0' width='50%'>";
    echo "<tr><th>Especialidad</th><th>Medicos Listado Madre</th>
    <th colspan=4>Medicos Rutero Maestro</th><th>Cobertura</th></tr>";
    echo "<tr><th>&nbsp;</th><th>&nbsp;</th>
    <th>A</th><th>B</th><th>C</th><th>Total</th><th>&nbsp;</th></tr>";
    $total_medicos = 0;
    $total_medicoslinea = 0;
    $totalA=0;
    $totalB=0;
    $totalC=0;
    while ($dat = mysql_fetch_array($resp)) {
        $cod_espe = $dat[0];
        $nom_espe = $dat[1];

        $sql_cantidad_medicos = "select count(*) as total_medicos from medicos m, especialidades_medicos e 
        where e.cod_med=m.cod_med and m.cod_ciudad in ($rptTerritorioNuevo) and e.cod_especialidad='$cod_espe'";
        $resp_cantidad_medicos = mysql_query($sql_cantidad_medicos);
        $dat_cantidad_medicos = mysql_fetch_array($resp_cantidad_medicos);
        $cant_medicos = $dat_cantidad_medicos[0];

        $sqlCantRutero = "select count(DISTINCT(rd.cod_med))
				from rutero_maestro_cab rm, rutero_maestro r, rutero_maestro_detalle rd, funcionarios f
				where rm.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto and rm.estado_aprobado in ($codVistaRutero)
				and rm.cod_visitador=f.codigo_funcionario and f.cod_ciudad in ($rptTerritorioNuevo)
				and rm.codigo_linea='$global_linea' and rd.cod_especialidad='$cod_espe' and rd.categoria_med='A'";
        $respCantRutero = mysql_query($sqlCantRutero);
        $datCantRutero = mysql_fetch_array($respCantRutero);
        $cantMedRuteroA = $datCantRutero[0];
				$totalA=$totalA+$cantMedRuteroA;

        $sqlCantRutero = "select count(DISTINCT(rd.cod_med))
				from rutero_maestro_cab rm, rutero_maestro r, rutero_maestro_detalle rd, funcionarios f
				where rm.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto  and rm.estado_aprobado in ($codVistaRutero)
				and rm.cod_visitador=f.codigo_funcionario and f.cod_ciudad in ($rptTerritorioNuevo)
				and rm.codigo_linea='$global_linea' and rd.cod_especialidad='$cod_espe' and rd.categoria_med='B'";
        $respCantRutero = mysql_query($sqlCantRutero);
        $datCantRutero = mysql_fetch_array($respCantRutero);
        $cantMedRuteroB = $datCantRutero[0];
				$totalB=$totalB+$cantMedRuteroB;

        $sqlCantRutero = "select count(DISTINCT(rd.cod_med))
				from rutero_maestro_cab rm, rutero_maestro r, rutero_maestro_detalle rd, funcionarios f
				where rm.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto  and rm.estado_aprobado in ($codVistaRutero)
				and rm.cod_visitador=f.codigo_funcionario and f.cod_ciudad in ($rptTerritorioNuevo)
				and rm.codigo_linea='$global_linea' and rd.cod_especialidad='$cod_espe' and rd.categoria_med='C'";
        $respCantRutero = mysql_query($sqlCantRutero);
        $datCantRutero = mysql_fetch_array($respCantRutero);
        $cantMedRuteroC = $datCantRutero[0];
				$totalC=$totalC+$cantMedRuteroC;
				
				$cantMedTotal=$cantMedRuteroA+$cantMedRuteroB+$cantMedRuteroC;

        if ($cantMedTotal != 0) {
            $total_medicos = $total_medicos + $cant_medicos;
            $total_medicoslinea = $total_medicoslinea + $cantMedTotal;
            $cobertura = ($cantMedTotal / $cant_medicos) * 100;
            $cobertura = round($cobertura);
            echo "<tr><td>&nbsp;&nbsp;$nom_espe</td><td align='right'>$cant_medicos</td>
            <td align='right'>$cantMedRuteroA</td>
            <td align='right'>$cantMedRuteroB</td>
            <td align='right'>$cantMedRuteroC</td>
            <td align='right'>$cantMedTotal</td>
            <td align='right'>$cobertura %</td></tr>";
            $cobertura_total = ($total_medicoslinea / $total_medicos) * 100;
            $cobertura_total = round($cobertura_total);
        } 
    } 
    echo "<tr><th align='left'>&nbsp;&nbsp;TOTAL</td><td align='right'>$total_medicos</td>
		<td align='right'>$totalA</td>
		<td align='right'>$totalB</td>
		<td align='right'>$totalC</td>
		<td align='right'>$total_medicoslinea</td><td align='right'>$cobertura_total %</td></tr>";
    echo "</table></center>";
	
require("imprimirInc.php");

?>