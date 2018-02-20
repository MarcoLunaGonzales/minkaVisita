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
	

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$X=file_get_contents("php://input");
		$json = json_decode(utf8_encode($X));		
		
		$fechaActual=date("Y-m-d hh:mm:ss");
		//mysql_query("insert into pruebajson (dato, fecha) values ('$X','$fechaActual')");
		
		foreach($json as $obj){
			$idBoleta=$obj->id_boleta;
			
			$sqlContacto="select b.id_gestion, b.id_ciclo, b.cod_contacto, b.id_medico from boletas_visita_cabXXX b where b.id_boleta='$idBoleta'";
			$respContacto=mysql_query($sqlContacto);
			$cod_gestion=mysql_result($respContacto,0,0);
			$cod_ciclo=mysql_result($respContacto,0,1);
			$cod_contacto=mysql_result($respContacto,0,2);
			$id_medico=mysql_result($respContacto,0,3);
			
			//buscamos el orden visita del medico
			$sqlOrdenVisita="select orden_visita from rutero_detalle where cod_contacto = '$cod_contacto' and cod_med = '$id_medico'";
			$respOrdenVisita=mysql_query($sqlOrdenVisita);
			$orden_visita=mysql_result($respOrdenVisita,0,0);
			
			$fechaRegistro=$obj->fechaRegistro;
			list($fecha,$hora) = explode(" ", $fechaRegistro);
			$cod_motivo=$obj->cod_motivo;
			
			$sqlVeri="select count(*) from registro_no_visita where codigo_gestion='$cod_gestion' and cod_contacto='$cod_contacto' and
				codigo_ciclo='$cod_ciclo' and orden_visita='$orden_visita'";
			$respVeri=mysql_query($sqlVeri);
			$numFilasVeri=mysql_result($respVeri,0,0);
			
			if($numFilasVeri==0){
				$sql="INSERT into registro_no_visita values('$cod_ciclo','$cod_gestion','$cod_contacto','$orden_visita','$cod_motivo','$fecha','$hora',3)";
				$resp=mysql_query($sql);
				
				$sql_upd="UPDATE rutero_detalle set estado = 3 where cod_contacto = $cod_contacto and cod_med = $id_medico";
				$resp_upd=mysql_query($sql_upd);
				
				$query="update boletas_visita_cabXXX set observacion='$obj->obs', fecha_visita='$fecha', estado=2 where id_boleta='$obj->id_boleta'";
				$resp=mysql_query($query);
			}

		}
	}
	

	if(isset($_GET['codigo'])){ 
		$sql = "SELECT codigo_motivo as id, descripcion_motivo as motivo from motivos_baja where tipo_motivo = 3 order by 2";
		$result = mysql_query($sql) or die("Error in Selecting. ");

		$emparray[] = array();
		while($row =mysql_fetch_assoc($result))
		{
			$emparray[] = $row;
		}
		
		array_splice($emparray, 0,1);
		
		echo json_encode(utf8json($emparray));
	}

    //close the db connection
    mysql_close($conexion);
?>
