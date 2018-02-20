<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') && isset($_POST['accion'])){   	
    
	//$link = mysqli_connect('localhost', 'root', '', 'visita20140527');
	require("../conexioni.inc");
	require("../conexion.inc");
	require("../funcion_nombres.php");
	
	
	$devolver = null;
	$consulta = '';
	$accion = $_POST['accion'];
	switch($accion){
		case 'insertar':{ // Inserción de un nuevo elemento
			$nombre = $_POST['nombre'];
			$orden = $_POST['orden'];
			$clave = $_POST['clave'];
			
			list($idParrilla, $espe, $contacto, $ciudad, $lineaMkt, $lineaVis) = explode("|", $clave);
						
			$consulta = "INSERT INTO asignacion_productos_excel_detalle 
			(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
			VALUES ('$idParrilla','$espe','$lineaVis','$orden','$ciudad','$nombre','$lineaMkt','$contacto')";
			$nombreProducto=nombreProducto($nombre);
			if (mysqli_query($link, $consulta)){
				//$devolver = array ('valor' => mysqli_insert_id($link));
				$devolver = array ('valor' => $idParrilla.'|'.$espe.'|'.$ciudad.'|'.$lineaMkt.'|'.$nombre.'|'.$contacto);
				$devolver = array ('valor1' => $nombreProducto);
				
			}
			break;
		}
		case 'eliminar':{ // Eliminación de un nuevo elemento
			$id=$_POST['id'];
			$orden=$_POST['orden'];
			list($idParrilla, $espe, $ciudad, $lineaMkt, $codProd, $contacto, $lineaVis) = explode("|", $id);
			$consulta = "delete from asignacion_productos_excel_detalle where 
				id='$idParrilla' and especialidad='$espe' and ciudad='$ciudad' and contacto='$contacto'
				and linea_mkt='$lineaMkt' and producto='$codProd' and linea='$lineaVis'";
			echo $consulta;
			if (mysqli_query($link, $consulta)){
                $consulta = "UPDATE asignacion_productos_excel_detalle 
				SET posicion = posicion -1 WHERE posicion > '$orden' and 
				id='$idParrilla' and especialidad='$espe' and ciudad='$ciudad' and contacto='$contacto'
				and linea_mkt='$lineaMkt' and linea='$lineaVis'" ;
                mysqli_query($link,$consulta);
				$devolver = array ('realizado' => true);
			}		
			break;
		}
		case 'editar':{ // Edición de un elemento
			$id = mysqli_real_escape_string($link, $_POST['id']);
			$nombre = mysqli_real_escape_string($link, $_POST['nombre']);
			$consulta = "UPDATE elementos SET nombre = '".$nombre."' WHERE id = ".$id;
			if (mysqli_query($link, $consulta)){
				$devolver = array ('realizado' => true);
			}
			break;
		}
		case 'ordenar':{ // Ordenar los elementos
			$puntos = explode(',',$_POST['puntos']);
            for($ii=0; $ii<sizeof($puntos); $ii++){
				//echo $ii." --- ".$puntos[$ii]."<br>";
				list($idParrilla, $espe, $ciudad, $lineaMkt, $codProd, $contacto, $lineaVis) = explode("|", $puntos[$ii]);
				$consulta = "UPDATE asignacion_productos_excel_detalle 
				SET posicion = $ii+1 WHERE id='$idParrilla' and especialidad='$espe' 
				and ciudad='$ciudad' and contacto='$contacto'
				and linea_mkt='$lineaMkt' and producto='$codProd' and linea='$lineaVis'" ;
				echo $consulta."     ";
                if (mysqli_query($link, $consulta)){
					$devolver = array ('realizado' => true);
				}
			}			
			break;
		}
	}
	if ($devolver)
		echo json_encode($devolver);
}
else {
	die('No se está accediendo correctamente');
}
?>