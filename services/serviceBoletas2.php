<?php
	require("../conexion.inc");
	include("funcionScaner.php");
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$X=file_get_contents("php://input");
		$json = json_decode($X);
		
		foreach($json as $obj){
			$fecha=$obj->fecha_visita;
			$fecha=convierteFecha($fecha);
			
			$query="update boletas_visita_cab set observacion='$obj->observacion', firma='$obj->firma', fecha_visita='$fecha', latitud='$obj->latitud', longitud='$obj->longitud', estado=1 where id_boleta='$obj->id_boleta'";
			$resp=mysql_query($query);
			
			//$sqlinsert=mysql_query("insert into pruebajson (dato) values ('$obs-')");		
		
		}
	}

	if(isset($_GET['codigo'])){ 	
	
		$codigo=$_GET['codigo'];
	
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
		
		echo json_encode($emparray);

		//close the db connection
		mysql_close($conexion);		
	}
	
?>
