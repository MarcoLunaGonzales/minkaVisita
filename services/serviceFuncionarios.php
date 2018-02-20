<?php
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

	require("../conexion.inc");
	
	
		$sql = "select f.cod_zeus, concat(f.paterno,' ',f.materno,' ',f.nombres)nombre, f.codigo_funcionario from funcionarios f where 
		f.cod_cargo=1011 and f.estado=1";

		$result = mysql_query($sql) or die("Error in Selecting. ");

		$emparray[] = array();
		$array2[]=array();

		while($row =mysql_fetch_array($result))
		{
			$codZeus=$row[0];
			$nombre=$row[1];
			$codHermes=$row[2];
			
			$emparray[]=array("codzeus"=>$codZeus, "nombre"=>$nombre, 
			"codhermes"=>$codHermes);
		}		
		array_splice($emparray, 0,1);
		
		//echo json_encode($emparray);
		echo json_encode(utf8json($emparray));

		//close the db connection
		mysql_close($conexion);		
?>
