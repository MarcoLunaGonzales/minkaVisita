<?php
header('Content-Type: application/json');

	require("conexion.inc");
	require("funcion_nombres.php");
	require("funciones.php");
	
	function utf8json($inArray) { 
	static $depth = 0; 
		/* our return object */ 
		$newArray = array(); 
		/* safety recursion limit */ 
		$depth ++; 
		if($depth >= '1000000') { 
			return false; 
		} 
		/* step through inArray */ 
		foreach($inArray as $key=>$val) { 
			if(is_array($val)) { 
				/* recurse on array elements */ 
				$newArray[$key] = utf8json($val); 
			} else { 
				/* encode string values */ 
				$newArray[$key] = utf8_encode($val); 
			} 
		} 
		/* return utf8 encoded array */ 
		return $newArray; 
	}
	
	$codMed="648";
	$codMedCUP=$_GET["codCUP"];
	$idMercado=$_GET["idMercado"];
	
	$nombreMedico=nombreMedico($codMed);
	$nombreMedicoCUP=nombreMedicoCUP($codMedCUP);

	$vectorFechas=fechasNombresCUP();
	$vectorCUPAnioMovil=fechasCUPAnioMovil();
	$vectorTrimIni=fechasCUPTrimestreIni();
	$vectorTrimFin=fechasCUPTrimestreFin();

	$emparray[] = array();
	
	$sqlLabs="select m.id_mercado, m.mercado, p.presentacion, p.id_raiz, p.id_concentracion, p.id_presentaciones, p.id_formaf,
		IFNULL(sum(d.cant_pxs1),0) from cup_datos d, cup_mercado m, 
		cup_mercados_productos mp, cup_presentacion p
		where d.id_raiz=mp.id_raiz and d.id_presentacion=mp.id_presentacion and d.id_concentracion=mp.id_concentracion and 
		d.id_formaf=mp.id_formaf and m.id_mercado=mp.id_mercado and
		d.id_raiz=p.id_raiz and d.id_presentacion=p.id_presentaciones and d.id_concentracion=p.id_concentracion and 
		d.id_formaf=p.id_formaf and d.id_laboratorio=p.id_laboratorio and d.id_clase=p.id_clase
		and d.id_medico='$codMedCUP' and m.id_mercado='$idMercado' and d.fecha BETWEEN 
		'$vectorCUPAnioMovil[0]' and '$vectorCUPAnioMovil[1]' group by m.id_mercado, m.mercado, p.presentacion, 
		p.id_raiz, p.id_concentracion, p.id_presentaciones, p.id_formaf
		having sum(d.cant_pxs1)>0 order by 8 desc limit 0,10";
	//echo $sqlLabs;
	$respLabs=mysql_query($sqlLabs);
	while($datProducto=mysql_fetch_array($respLabs)){
		$idMercado=$datProducto[0];
		$nombreMercado=$datProducto[1];
		$nombreProducto=$datProducto[2];
		$idRaiz=$datProducto[3];
		$idConcentracion=$datProducto[4];
		$idPresentacion=$datProducto[5];
		$idFormaF=$datProducto[6];
		$cantPxAnio=$datProducto[7];
		
		
		$datTrim1=0;
		$datTrim2=0;
		$datTrim3=0;
		$datTrim4=0;
		
		for($ii=0;$ii<=3;$ii++){
			$fechaIni=$vectorTrimIni[$ii];
			$fechaFin=$vectorTrimFin[$ii];
			
			$sqlTrim="select IFNULL(sum(d.cant_pxs1),0) from cup_datos d, cup_mercado m, 
				cup_mercados_productos mp, cup_presentacion p
				where d.id_raiz=mp.id_raiz and d.id_presentacion=mp.id_presentacion and d.id_concentracion=mp.id_concentracion and 
				d.id_formaf=mp.id_formaf and m.id_mercado=mp.id_mercado and
				d.id_raiz=p.id_raiz and d.id_presentacion=p.id_presentaciones and d.id_concentracion=p.id_concentracion and 
				d.id_formaf=p.id_formaf and d.id_laboratorio=p.id_laboratorio and d.id_clase=p.id_clase
				and d.id_medico='$codMedCUP' and m.id_mercado='$idMercado' and d.fecha BETWEEN 
				'$fechaIni' and '$fechaFin' and p.id_raiz='$idRaiz' and p.id_concentracion='$idConcentracion' and 
				p.id_presentaciones='$idPresentacion' and p.id_formaf='$idFormaF'";
			$respTrim=mysql_query($sqlTrim);
			$numFilas=mysql_num_rows($respTrim);
			$pxTrim=mysql_result($respTrim,0,0);
			if($ii==0){
				$datTrim1=$pxTrim;
			}			
			if($ii==1){
				$datTrim2=$pxTrim;
			}
			if($ii==2){
				$datTrim3=$pxTrim;
			}
			if($ii==3){
				$datTrim4=$pxTrim;
			}
		}
		$emparray[]=array("idPresentacion"=>$idPresentacion, "nombreProducto"=>$nombreProducto, 
			"trim1"=>$datTrim1,
			"trim2"=>$datTrim2,
			"trim3"=>$datTrim3,
			"trim4"=>$datTrim4);
	}
	array_splice($emparray, 0,1);
	//echo json_encode(utf8json($emparray));
	echo json_encode($emparray);
?>