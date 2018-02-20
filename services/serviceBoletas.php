<?php
	require("../conexion.inc");
	include("funcionScaner.php");

	
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
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		//echo "hola post";
		$X=file_get_contents("php://input");
		$json = json_decode(utf8_encode($X));		
		$fechaActual=date("Y-m-d hh:mm:ss");
		//mysql_query("insert into pruebajson (dato, fecha) values ('$X','$fechaActual')");
		foreach($json as $obj){
			$fecha=$obj->fechavisita;
			//$fecha=convierteFecha($fecha);			
			if($fecha>="2016-12-14"){
				$query="update boletas_visita_cab set observacion='$obj->observacion', firma='$obj->firma', fecha_visita='$fecha', latitud='$obj->latitud', longitud='$obj->longitud', estado=1 where id_boleta='$obj->id_boleta'";
				$resp=mysql_query($query);
			}			
			//$sqlinsert=mysql_query("insert into pruebajson (dato) values ('$obs-')");			
		}
	}

	if(isset($_GET['codigo'])){ 	
		$codigo=$_GET['codigo'];
		//echo "hola get $codigo";
		$sql = "select id_boleta, id_visitador_hermes, id_visitador_zeus, territorio, visitador, medico, linea, gestion, id_ciclo, id_gestion, 
		fecha, id_medico, nro_boleta, direccion1, telefono, celular, especialidad, supervisor, estado
		from boletas_visita_cab where id_visitador_zeus='$codigo'";
		
		$result = mysql_query($sql) or die("Error in Selecting. ");

		$emparray[] = array();
		$array2[]=array();
		
		//$array2[]=array("xxx"=>"3020", "yyy"=>"230", "zz"=>"234");
		while($row =mysql_fetch_array($result))
		{
			$id_boleta=$row[0];
			//echo "boleta $id_boleta";
			$id_visitador_hermes=$row[1];
			$id_visitador_zeus=$row[2];
			$territorio=$row[3];
			$visitador=$row[4];
			$medico=$row[5];
			$linea=$row[6];
			$gestion=$row[7];
			$id_ciclo=$row[8];
			$id_gestion=$row[9];
			$fecha=$row[10];
			$id_medico=$row[11];
			$nro_boleta=$row[12];
			$direccion1=$row[13];
			$telefono=$row[14];
			$celular=$row[15];
			$especialidad=$row[16];
			$supervisor=$row[17];
			$estado=$row[18];
			
			$sqlDet="select id_boleta, orden, muestra, cantidad_muestra, material, cantidad_material, tipo from 
			boletas_visita_detalle where id_boleta='$id_boleta'";
			
			//echo $sqlDet;
			
			$respDet=mysql_query($sqlDet);
			$arrayDetalle[] = array();
			while($rowDet = mysql_fetch_assoc($respDet))
			{
				$arrayDetalle[] = $rowDet;
			}
			
			array_splice($arrayDetalle, 0,1);
			
			$emparray[]=array("id_boleta"=>$id_boleta, "id_visitador_hermes"=>$id_visitador_hermes, 
			"id_visitador_zeus"=>$id_visitador_zeus,
			"territorio"=>$territorio,
			"visitador"=>$visitador,
			"medico"=>$medico,
			"linea"=>$linea,
			"gestion"=>$gestion,
			"id_ciclo"=>$id_ciclo,
			"id_gestion"=>$id_gestion,
			"fecha"=>$fecha,
			"id_medico"=>$id_medico,
			"nro_boleta"=>$nro_boleta,
			"direccion1"=>$direccion1,
			"telefono"=>$telefono,
			"celular"=>$celular,
			"especialidad"=>$especialidad,
			"supervisor"=>$supervisor,
			"estado"=>$estado,
			"detalle"=>$arrayDetalle);
			
			
			unset($arrayDetalle);
		}
		
		array_splice($emparray, 0,1);
		
		//echo "$emparray[0]";
		echo json_encode(utf8json($emparray));

		//close the db connection
		mysql_close($conexion);		
	}
	
?>
