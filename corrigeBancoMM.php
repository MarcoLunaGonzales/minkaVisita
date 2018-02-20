<?php

require("conexion.inc");
$ciclo="5";
$gestion="1013";
echo "<table border=1>";
$sql="select b.cod_med, 
	(select concat(m.ap_pat_med,' ', m.nom_med) from medicos m where m.cod_med=b.cod_med)medico, bd.cod_muestra, 
	(select c.descripcion from medicos m, ciudades c where m.cod_med=b.cod_med and m.cod_ciudad=c.cod_ciudad)regional, 
	(select concat(m.descripcion, ' ', m.presentacion) from muestras_medicas m where m.codigo=bd.cod_muestra)muestra, bd.cantidad, b.id
	from banco_muestras b, banco_muestras_detalle bd
	where b.id=bd.id and b.gestion=$gestion and b.ciclo_inicio<=$ciclo and b.ciclo_final>=$ciclo and b.estado=1
	order by regional, medico, muestra";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codMed=$dat[0];
	$medico=$dat[1];
	$codMM=$dat[2];
	$regional=$dat[3];
	$muestra=$dat[4];
	$cantidadCab=$dat[5];
	$id=$dat[6];
	
	$sqlDet="select b.cod_visitador, 
	(select concat(f.paterno, ' ', f.materno, ' ', f.nombres) from funcionarios f where f.codigo_funcionario=b.cod_visitador)visitador, 
	(select f.estado from funcionarios f where f.codigo_funcionario=b.cod_visitador)estado, 
	b.cantidad, b.id
	from banco_muestra_cantidad_visitador b where b.id_for=$id and b.codigo_muestra='$codMM' and b.cantidad>0;";
	$respDet=mysql_query($sqlDet);
	$txtDet="<table>";
	$cantidadDet=0;
	while($datDet=mysql_fetch_array($respDet)){
		$codVisitador=$datDet[0];
		$nombreVisitador=$datDet[1];
		$estadoVis=$datDet[2];
		$cantidadVis=$datDet[3];
		$cantidadDet=$cantidadDet+$cantidadVis;
		$idVis=$datDet[4];
		//verifica en rutero
		$sqlRut="select * from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
			where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_gestion=$gestion and rc.codigo_ciclo=$ciclo and 
			rc.estado_aprobado=1 and rc.cod_visitador=$codVisitador and rd.cod_med=$codMed";
		$respRut=mysql_query($sqlRut);
		$numFilasRut=mysql_num_rows($respRut);
		
		//verifica todos los ruteros
		$sqlRutTodo="select * from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
			where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_gestion=$gestion and rc.codigo_ciclo=$ciclo and 
			rc.estado_aprobado=1 and rd.cod_med=$codMed";
		$respRutTodo=mysql_query($sqlRutTodo);
		$numFilasRutTodo=mysql_num_rows($respRutTodo);
		
		
		$obs="";
		if($numFilasRut==0){
			$obs="#00ffff";
		}
		$obsTodo="";
		if($numFilasRutTodo==0){
			$obsTodo="EN NINGUN RUTERO";
		}
		if($estadoVis==1){
			$obsEstado="";
		}else{
			$obsEstado="Inactivoooo";
		}
		
		$txtDet=$txtDet."<tr bgcolor='$obs'><td>$idVis $codVisitador $nombreVisitador $estadoVis $cantidadVis $obsEstado</td></tr>";
		
	}
	$txtDet=$txtDet."</table>";
	
	if($cantidadCab!=$cantidadDet){
		$color="#ff0000";
	}else{
		$color="#ffffff";
	}
	if($cantidadCab<$cantidadDet){
		/*$varTxt="delete from banco_muestra_cantidad_visitador where id='$idVis'";
		mysql_query($varTxt);*/
	}
	echo "<tr bgcolor='$color'><td>$regional</td><td>$codMed $medico  ($obsTodo)   $id</td><td>$codMM $muestra</td><td>$cantidadCab</td><td>$txtDet $varTxt</td></tr>";

}

echo "</tr>";

?>