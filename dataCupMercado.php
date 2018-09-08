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
	$nombreMedico=nombreMedico($codMed);
	$nombreMedicoCUP=nombreMedicoCUP($codMedCUP);

	$vectorFechas=fechasNombresCUP();
	$vectorCUPAnioMovil=fechasCUPAnioMovil();
	$vectorTrimIni=fechasCUPTrimestreIni();
	$vectorTrimFin=fechasCUPTrimestreFin();

	$emparray[] = array();
	
	/*IMPORTANTE ACA ESTA EL PREFIJO PARA LOS MERCADOS
	*/
	$prefijoMercado="P-";
	
	$sqlLabs="select m.id_mercado, m.mercado, sum(d.cant_pxs1) from cup_datos d, cup_mercado m, cup_mercados_productos mp
		where d.id_raiz=mp.id_raiz and d.id_presentacion=mp.id_presentacion and d.id_concentracion=mp.id_concentracion and 
		d.id_formaf=mp.id_formaf and m.id_mercado=mp.id_mercado and d.id_medico='$codMedCUP' 
		and d.fecha BETWEEN 
		'$vectorCUPAnioMovil[0]' and '$vectorCUPAnioMovil[1]' and m.mercado like '$prefijoMercado%'
		group by m.id_mercado, m.mercado order by 3 desc limit 0,10";
	$respLabs=mysql_query($sqlLabs);
	while($datLabs=mysql_fetch_array($respLabs)){
		$idMercado=$datLabs[0];
		$nombreMercado=$datLabs[1];
		$cantPxAnio=$datLabs[2];
		
		
		$datTrim1=0;
		$datTrim2=0;
		$datTrim3=0;
		$datTrim4=0;
		
		for($ii=0;$ii<=3;$ii++){
			$fechaIni=$vectorTrimIni[$ii];
			$fechaFin=$vectorTrimFin[$ii];
			
			$sqlTrimLabo="select sum(d.cant_pxs1) from cup_datos d, cup_mercado m, cup_mercados_productos mp
				where d.id_raiz=mp.id_raiz and d.id_presentacion=mp.id_presentacion and d.id_concentracion=mp.id_concentracion and 
				d.id_formaf=mp.id_formaf and m.id_mercado=mp.id_mercado and d.id_medico='$codMedCUP' and m.id_mercado='$idMercado'
				and d.fecha BETWEEN 
				'$fechaIni' and '$fechaFin'";
			$respTrimLabo=mysql_query($sqlTrimLabo);
			$numFilas=mysql_num_rows($respTrimLabo);
			$pxTrim=mysql_result($respTrimLabo,0,0);
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
		$emparray[]=array("idMercado"=>$idLab, "nombreMercado"=>$nombreMercado, 
			"trim1"=>$datTrim1,
			"trim2"=>$datTrim2,
			"trim3"=>$datTrim3,
			"trim4"=>$datTrim4);
	}
	array_splice($emparray, 0,1);
	//echo json_encode(utf8json($emparray));
	echo json_encode($emparray);
?>