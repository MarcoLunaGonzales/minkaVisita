<script language='JavaScript'>
function totales(){
   var main=document.getElementById('main');   
   var numFilas=main.rows.length;
   var numCols=main.rows[2].cells.length;
	 
	 for(var j=1; j<=numCols-1; j++){
	 		var subtotal=0;
	 		for(var i=2; i<=numFilas-2; i++){
	 				var dato=parseInt(main.rows[i].cells[j].innerHTML);
	 				subtotal=subtotal+dato;
	 		}
	 		var fila=document.createElement('TD');
			main.rows[numFilas-1].appendChild(fila);
			main.rows[numFilas-1].cells[j].innerHTML=subtotal;
	 }	 
}
</script>
<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funcion_nombres.php");

$global_agencia=$rpt_territorio;
$rpt_gestion=$rpt_gestion;
$rpt_ciclo=$rpt_ciclo;
$nombreGestion=nombreGestion($rpt_gestion);

$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion=$datos_cab_gestion[0];
if($rpt_clasi==0){$nombre_clasi="En Ruteros Maestro";}
if($rpt_clasi==1){$nombre_clasi="En Ruteros Maestro Aprobados";}
if($rpt_clasi==2){$nombre_clasi="En listado Madre";}
echo "<html><body onload='totales();'>";
echo "<center><table border='0' class='textotit'><tr><th>Universo de Medicos<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo<br>
Clasificación: $nombre_clasi</th></tr></table></center><br>";
$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
$resp=mysql_query($sql);
echo "<center><table border='1' class='texto' cellspacing='0' width='100%' id='main'>";
echo "<tr><th>Especialidad</th>";
$sql_ciudades="select cod_ciudad, descripcion from ciudades where cod_ciudad in ($rpt_territorio) order by descripcion";
$resp_ciudades=mysql_query($sql_ciudades);
$num_ciudades=mysql_num_rows($resp_ciudades);
while($dat_ciudades=mysql_fetch_array($resp_ciudades))
{	$nombre_territorio=$dat_ciudades[1];
	echo "<th class='textomini' colspan='8'>$nombre_territorio</th>";
}
echo "<th class='textomini' colspan='8'>Totales</th>";
echo "<tr><th></th>";
for($i=1; $i<=$num_ciudades; $i++) {
    echo "<th>A</th><th>B</th><th>C</th><th>Sub-Total</th><th>Cont. A</th><th>Cont. B</th><th>Cont. C</th><th>Sub-Total Cont.</th>";
}
echo "<th>A</th>
			<th>B</th>
			<th>C</th>
			<th>TOTAL</th>
			<th>Cont.A</th>
			<th>Cont.B</th>
			<th>Cont.C</th>
			<th>TOTAL</th>
			</tr>";
while($dat=mysql_fetch_array($resp))
{
	$cod_espe=$dat[0];
	$nom_espe=$dat[1];
	echo "<tr><td align='left'>$nom_espe</td>";
	$sql_ciudades="select cod_ciudad, descripcion from ciudades where cod_ciudad in ($rpt_territorio) order by descripcion";
	$resp_ciudades=mysql_query($sql_ciudades);
	
	$totalA=0;
	$totalB=0;
	$totalC=0;
	$totalContA=0;
	$totalContB=0;
	$totalContC=0;
	$contA=0;
	$contB=0;
	$contC=0;
	
	$total_med_espe=0;
	while($dat_ciudades=mysql_fetch_array($resp_ciudades))
	{	$codTerritorio=$dat_ciudades[0];
		//CONSULTA PARA MEDICOS LISTADO MADRE
		if($rpt_clasi==0 || $rpt_clasi==1){
			$sql_cantidad_medicos="select COUNT(distinct(cod_med)) from rutero_maestro_cab rc, rutero_maestro rm, 
			rutero_maestro_detalle rd, funcionarios f
			where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto AND 
			rc.cod_visitador=f.codigo_funcionario and rc.codigo_linea='$rpt_linea' and f.cod_ciudad='$codTerritorio' 
			and f.estado=1 and rd.cod_especialidad='$cod_espe' and rd.categoria_med='A'";
			if($rpt_clasi==1){	$sql_cantidad_medicos.=" and rc.estado_aprobado=1"; }
			$resp_cantidad_medicos=mysql_query($sql_cantidad_medicos);
			$dat_cantidad_medicos=mysql_fetch_array($resp_cantidad_medicos);
			$cant_medicosA=$dat_cantidad_medicos[0];
			$totalA=$totalA+$cant_medicosA;
			$sqlFrec="select gd.`frecuencia` from `grilla` g, `grilla_detalle` gd where g.`agencia`=$codTerritorio and g.`codigo_linea`='$rpt_linea' 
			and gd.`cod_especialidad`='$cod_espe' and gd.`cod_categoria`='A' and g.`codigo_grilla`=gd.`codigo_grilla` and g.`estado`=1";
			$respFrec=mysql_query($sqlFrec);
			$frec=mysql_result($respFrec,0);
			$contA=$cant_medicosA*$frec;
			$totalContA=$totalContA+$contA;
			
			$sql_cantidad_medicos="select COUNT(distinct(cod_med)) from rutero_maestro_cab rc, rutero_maestro rm, 
			rutero_maestro_detalle rd, funcionarios f
			where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto AND 
			rc.cod_visitador=f.codigo_funcionario and rc.codigo_linea='$rpt_linea' and f.cod_ciudad='$codTerritorio' 
			and f.estado=1 and rd.cod_especialidad='$cod_espe' and rd.categoria_med='B'";
			if($rpt_clasi==1){	$sql_cantidad_medicos.=" and rc.estado_aprobado=1"; }
			$resp_cantidad_medicos=mysql_query($sql_cantidad_medicos);
			$dat_cantidad_medicos=mysql_fetch_array($resp_cantidad_medicos);
			$cant_medicosB=$dat_cantidad_medicos[0];
			$totalB=$totalB+$cant_medicosB;
			$sqlFrec="select gd.`frecuencia` from `grilla` g, `grilla_detalle` gd where g.`agencia`=$codTerritorio and g.`codigo_linea`='$rpt_linea' 
			and gd.`cod_especialidad`='$cod_espe' and gd.`cod_categoria`='B' and g.`codigo_grilla`=gd.`codigo_grilla` and g.`estado`=1";
			$respFrec=mysql_query($sqlFrec);
			$frec=mysql_result($respFrec,0);
			$contB=$cant_medicosB*$frec;
			$totalContB=$totalContB+$contB;

			$sql_cantidad_medicos="select COUNT(distinct(cod_med)) from rutero_maestro_cab rc, rutero_maestro rm, 
			rutero_maestro_detalle rd, funcionarios f
			where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto AND 
			rc.cod_visitador=f.codigo_funcionario and rc.codigo_linea='$rpt_linea' and f.cod_ciudad='$codTerritorio' 
			and f.estado=1 and rd.cod_especialidad='$cod_espe' and rd.categoria_med='C'";
			if($rpt_clasi==1){	$sql_cantidad_medicos.=" and rc.estado_aprobado=1"; }
			$resp_cantidad_medicos=mysql_query($sql_cantidad_medicos);
			$dat_cantidad_medicos=mysql_fetch_array($resp_cantidad_medicos);
			$cant_medicosC=$dat_cantidad_medicos[0];
			$totalC=$totalC+$cant_medicosC;
			$sqlFrec="select gd.`frecuencia` from `grilla` g, `grilla_detalle` gd where g.`agencia`=$codTerritorio and g.`codigo_linea`='$rpt_linea' 
			and gd.`cod_especialidad`='$cod_espe' and gd.`cod_categoria`='C' and g.`codigo_grilla`=gd.`codigo_grilla` and g.`estado`=1";
			$respFrec=mysql_query($sqlFrec);
			$frec=mysql_result($respFrec,0);
			$contC=$cant_medicosC*$frec;
			$totalContC=$totalContC+$contC;
		}
		if($rpt_clasi==2){
			$sql_cantidad_medicos="select count(m.cod_med) from medicos m, categorias_lineas c
			where m.cod_med=c.cod_med and m.cod_ciudad='$codTerritorio' and c.codigo_linea='$rpt_linea'
			and c.cod_especialidad='$cod_espe' and c.categoria_med='A'";
			$resp_cantidad_medicos=mysql_query($sql_cantidad_medicos);
			$dat_cantidad_medicos=mysql_fetch_array($resp_cantidad_medicos);
			$cant_medicosA=$dat_cantidad_medicos[0];
			$totalA=$totalA+$cant_medicosA;
			$sqlFrec="select gd.`frecuencia` from `grilla` g, `grilla_detalle` gd where g.`agencia`=$codTerritorio and g.`codigo_linea`='$rpt_linea' 
			and gd.`cod_especialidad`='$cod_espe' and gd.`cod_categoria`='A' and g.`codigo_grilla`=gd.`codigo_grilla` and g.`estado`=1";
			$respFrec=mysql_query($sqlFrec);
			$frec=mysql_result($respFrec,0);
			$contA=$cant_medicosA*$frec;
			$totalContA=$totalContA+$contA;

			$sql_cantidad_medicos="select count(m.cod_med) from medicos m, categorias_lineas c
			where m.cod_med=c.cod_med and m.cod_ciudad='$codTerritorio' and c.codigo_linea='$rpt_linea'
			and c.cod_especialidad='$cod_espe' and c.categoria_med='B'";
			$resp_cantidad_medicos=mysql_query($sql_cantidad_medicos);
			$dat_cantidad_medicos=mysql_fetch_array($resp_cantidad_medicos);
			$cant_medicosB=$dat_cantidad_medicos[0];
			$totalB=$totalB+$cant_medicosB;
			$sqlFrec="select gd.`frecuencia` from `grilla` g, `grilla_detalle` gd where g.`agencia`=$codTerritorio and g.`codigo_linea`='$rpt_linea' 
			and gd.`cod_especialidad`='$cod_espe' and gd.`cod_categoria`='B' and g.`codigo_grilla`=gd.`codigo_grilla` and g.`estado`=1";
			$respFrec=mysql_query($sqlFrec);
			$frec=mysql_result($respFrec,0);
			$contB=$cant_medicosB*$frec;
			$totalContB=$totalContB+$contB;

			$sql_cantidad_medicos="select count(m.cod_med) from medicos m, categorias_lineas c
			where m.cod_med=c.cod_med and m.cod_ciudad='$codTerritorio' and c.codigo_linea='$rpt_linea'
			and c.cod_especialidad='$cod_espe' and c.categoria_med='C'";
			$resp_cantidad_medicos=mysql_query($sql_cantidad_medicos);
			$dat_cantidad_medicos=mysql_fetch_array($resp_cantidad_medicos);
			$cant_medicosC=$dat_cantidad_medicos[0];
			$totalC=$totalC+$cant_medicosC;
			$sqlFrec="select gd.`frecuencia` from `grilla` g, `grilla_detalle` gd where g.`agencia`=$codTerritorio and g.`codigo_linea`='$rpt_linea' 
			and gd.`cod_especialidad`='$cod_espe' and gd.`cod_categoria`='C' and g.`codigo_grilla`=gd.`codigo_grilla` and g.`estado`=1";
			$respFrec=mysql_query($sqlFrec);
			$frec=mysql_result($respFrec,0);
			$contC=$cant_medicosC*$frec;
			$totalContC=$totalContC+$contC;

		}
		
		$subtotal=$cant_medicosA+$cant_medicosB+$cant_medicosC;
		$subtotalCont=$contA+$contB+$contC;
		
		echo "<td align='right'>$cant_medicosA</td>
					<td align='right'>$cant_medicosB</td>
					<td align='right'>$cant_medicosC</td>
					<td align='right'>$subtotal</td>
					<td align='right'>$contA</td>
					<td align='right'>$contB</td>
					<td align='right'>$contC</td>
					<td align='right'>$subtotalCont</td>";
	}
	$total_med_espe=$totalA+$totalB+$totalC;
	$totalContactos=$totalContA+$totalContB+$totalContC;
	
	echo "<td align='right'>$totalA</td>
				<td align='right'>$totalB</td>
				<td align='right'>$totalC</td>
				<td align='right'>$total_med_espe</td>
				<td align='right'>$totalContA</td>
				<td align='right'>$totalContB</td>
				<td align='right'>$totalContC</td>
				<td align='right'>$totalContactos</td></tr>";
}
echo "<tr><TH>TOTALES</TH></tr>";
echo "</table></center>";

require("imprimirInc.php");

echo "</body></html>";
?>