<?php
header('Content-Type: application/json');

	require("conexionInicial.inc");
	require("funcion_nombres.php");
	require("funciones.php");
	
	$codVis=$_GET['codVis'];
	$fechaVis=$_GET['fechaVis'];
	
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
	
	$codMed="0";

	$emparray[] = array();
	
	$sqlLatLong="select b.medico, b.longitud, b.latitud, b.fecha_visita, b.especialidad from boletas_visita_cabXXX b 
	where b.id_visitador_hermes='$codVis' and b.estado=1 and longitud<>0 and b.fecha_visita between '$fechaVis 00:00:00' and 
	'$fechaVis 23:59:59'
		ORDER BY b.fecha_visita;";
	//echo $sqlLatLong;
	$resp=mysql_query($sqlLatLong);
	while($dat=mysql_fetch_array($resp)){
		$nombreMedico=$dat[0];
		$latitud=$dat[2];
		$longitud=$dat[1];
		$fecha=$dat[3];
		$especialidad=$dat[4];
		list($fechaVisita, $horaVisita)=explode(" ",$fecha);
		$horaVisita=substr($horaVisita,0,-3);
		
		$emparray[]=array("nombreMedico"=>$nombreMedico, "latitud"=>$latitud, 
			"longitud"=>$longitud, "fechaVisita"=>$fecha, "hora"=>$horaVisita, "especialidad"=>$especialidad);
	}
	array_splice($emparray, 0,1);
	//echo json_encode(utf8json($emparray));
	echo json_encode($emparray);
?>