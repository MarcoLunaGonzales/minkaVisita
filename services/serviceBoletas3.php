<?php
	require("../conexionInicial.inc");
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
		$X=file_get_contents("php://input");
		$json = json_decode(utf8_encode($X));		
		$fechaActual=date("Y-m-d hh:mm:ss");
		
		//mysql_query("insert into pruebajson (dato, fecha) values ('$X','$fechaActual')");
		
		foreach($json as $obj){
			$fecha=$obj->fechavisita;
			//$fecha=convierteFecha($fecha);			
			
			$idBoleta=$obj->id_boleta;
			/*empezamos a sacar los datos del registro de las boletas*/
			$sqlContacto="select b.fecha, b.cod_contacto, b.id_medico from boletas_visita_cabXXX b where b.id_boleta='$idBoleta'";
			$respContacto=mysql_query($sqlContacto);
			$fechaVisitaX=mysql_result($respContacto,0,0);
			$codContactoX=mysql_result($respContacto,0,1);
			$codMedX=mysql_result($respContacto,0,2);
			
			$sqlVerifica="select * from reg_visita_cab where cod_contacto='$codContactoX' and cod_med='$codMedX'";
			$respVerifica=mysql_query($sqlVerifica);
			$numFilas=mysql_num_rows($respVerifica);
			
			if($numFilas==0){
				$sqlDatosCab="select rc.codigo_gestion, rc.codigo_ciclo, rm.cod_visitador, rd.cod_med, rd.cod_especialidad, rd.categoria_med, o.id from
				rutero_maestro_detalle_aprobado rd, rutero_maestro_aprobado rm, orden_dias o, rutero_maestro_cab_aprobado rc
				where rc.cod_rutero=rm.cod_rutero and rd.cod_contacto=rm.cod_contacto and rd.cod_visitador=rm.cod_visitador
				and rc.cod_visitador=rm.cod_visitador and o.dia_contacto=rm.dia_contacto and
				 rd.cod_contacto='$codContactoX' and rd.cod_med='$codMedX'";
				$respDatosCab=mysql_query($sqlDatosCab);
				$codGestionX=mysql_result($respDatosCab,0,0);
				$codCicloX=mysql_result($respDatosCab,0,1);
				$codVisitadorX=mysql_result($respDatosCab,0,2);
				//$codMedX
				$codEspecialidadX=mysql_result($respDatosCab,0,4);
				$categoriaX=mysql_result($respDatosCab,0,5);
				$idDiaX=mysql_result($respDatosCab,0,6);
				
				//sacamos el codigo
				//GENERAMOS EL CODIGO CORRELATIVO
				$fecha_registro=date("Y-m-d");
				$hora_registro=date("H:i:s");
				$sql  = "SELECT max(cod_reg_visita) from reg_visita_cab";
				$resp = mysql_query($sql);
				$dat  = mysql_fetch_array($resp);
				$num_filas = mysql_num_rows($resp);
				if ($num_filas == 0) {
					$codigo = 1;
				} else {
					$codigo = $dat[0];
					$codigo++;
				}
				$sqlInsertCab  = "INSERT into reg_visita_cab values 
				($codigo, $codContactoX, $codVisitadorX, $codMedX, '$codEspecialidadX', '$categoriaX', $codGestionX, $codCicloX, $idDiaX, '$fecha_registro', '$hora_registro', '$fechaVisitaX', '0')";
				$respInsertCab = mysql_query($sqlInsertCab);			
				/*fin datos de las boletas*/
				
				$detalle=$obj->detalle;
				foreach ($detalle as $objDet){
					$muestra=$objDet->muestra;
					$codigo_muestra=$objDet->codigo_muestra;
					$cantidad_muestra=$objDet->cantidad_muestra;
					$cantidad_material=$objDet->cantidad_material;
					$tipo=$objDet->tipo;
					
					//INSERTAMOS EL DETALLE
					$sql = "INSERT into reg_visita_detalle values ($codigo, '$codigo_muestra', $cantidad_muestra, 0, 0, $cantidad_material, 0,'','$tipo')";
					$resp = mysql_query($sql);				
					//FIN INSERTAR DETALLE
				}
				
			}
			
			$query="update boletas_visita_cabXXX set observacion='$obj->observacion', firma='$obj->firma', fecha_visita='$fecha', latitud='$obj->latitud', longitud='$obj->longitud', estado=1 where id_boleta='$obj->id_boleta'";
			$resp=mysql_query($query);
			
			$sql_upd = "UPDATE rutero_maestro_detalle_aprobado set estado = 1 where cod_contacto = $codContactoX and cod_med = $codMedX";
			$resp_upd = mysql_query($sql_upd);
		
		}
	}

	if(isset($_GET['codigo'])){ 	
		$codigo=$_GET['codigo'];
		//echo "hola get $codigo";
		$sql = "select id_boleta, id_visitador_hermes, id_visitador_zeus, territorio, visitador, medico, linea, gestion, id_ciclo, id_gestion, 
		fecha, id_medico, nro_boleta, direccion1, telefono, celular, especialidad, supervisor, estado
		from boletas_visita_cabXXX where id_visitador_zeus='$codigo'";
		
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
			
			$sqlDet="select id_boleta, orden, muestra, cantidad_muestra, material, cantidad_material, tipo, codigo_muestra 
			from boletas_visita_detalleXXX where id_boleta='$id_boleta'";
			
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
