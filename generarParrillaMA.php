<?php
require("conexion.inc");

$codigoCiclo=9;

	$sqlX="update parrilla_detalle set parrilla_detalle.codigo_material='0', 
		parrilla_detalle.cantidad_material=0
		where parrilla_detalle.codigo_parrilla in (
		select parrilla.codigo_parrilla from parrilla where parrilla.codigo_gestion=1011 
		and parrilla.cod_ciclo=$codigoCiclo)";
	
	echo $sqlX;
	//$respX=mysql_query($sqlX);

	

$sql="select a.codigo_mm, ad.dist_a, ad.dist_b, ad.dist_c, ad.contactoA, ad.espe_linea, ad.linea_mkt, ad.codigo_ma, ad.contactoB, ad.contactoC 
	from asignacion_ma_excel a, asignacion_ma_excel_detalle ad 
	where a.id=ad.id_asignacion_ma order by a.codigo_mm, ad.espe_linea, ad.linea_mkt";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codMM=$dat[0];
	$cantA=$dat[1];
	$cantB=$dat[2];
	$cantC=$dat[3];
	$numContactoA=$dat[4];
	$codEspe=$dat[5];
	$codLinea=$dat[6];
	$codMaterial=$dat[7];	
	$numContactoB=$dat[8];
	$numContactoC=$dat[9];
	
	if($cantA>0){
		$sqlUpd="update parrilla_detalle set parrilla_detalle.codigo_material='$codMaterial', 
		parrilla_detalle.cantidad_material=$cantA
		where parrilla_detalle.codigo_muestra='$codMM' and parrilla_detalle.codigo_parrilla in (
		select parrilla.codigo_parrilla from parrilla where parrilla.codigo_linea='$codLinea' 
		and parrilla.codigo_gestion=1011 
		and parrilla.cod_ciclo=$codigoCiclo and parrilla.cod_especialidad='$codEspe' and parrilla.categoria_med='A' 
		and parrilla.numero_visita=$numContactoA)";
		echo $sqlUpd."<br>";
		//$respUpd=mysql_query($sqlUpd);
		
	}
	if($cantB>0){
		$sqlUpd="update parrilla_detalle set parrilla_detalle.codigo_material='$codMaterial', 
		parrilla_detalle.cantidad_material=$cantB
		where parrilla_detalle.codigo_muestra='$codMM' and parrilla_detalle.codigo_parrilla in (
		select parrilla.codigo_parrilla from parrilla where parrilla.codigo_linea='$codLinea' 
		and parrilla.codigo_gestion=1011 
		and parrilla.cod_ciclo=$codigoCiclo and parrilla.cod_especialidad='$codEspe' and parrilla.categoria_med='B' 
		and parrilla.numero_visita=$numContactoB)";
		echo $sqlUpd."<br>";
		//$respUpd=mysql_query($sqlUpd);
	}
	if($cantC>0){
		$sqlUpd="update parrilla_detalle set parrilla_detalle.codigo_material='$codMaterial', 
		parrilla_detalle.cantidad_material=$cantC
		where parrilla_detalle.codigo_muestra='$codMM' and parrilla_detalle.codigo_parrilla in (
		select parrilla.codigo_parrilla from parrilla where parrilla.codigo_linea='$codLinea' 
		and parrilla.codigo_gestion=1011 
		and parrilla.cod_ciclo=$codigoCiclo and parrilla.cod_especialidad='$codEspe' and parrilla.categoria_med='C' 
		and parrilla.numero_visita=$numContactoC)";
		echo $sqlUpd."<br>";
		//$respUpd=mysql_query($sqlUpd);
	}	
}

?>