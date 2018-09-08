<?php
require("conexion.inc");
require("estilos_regional_pri.inc");
require("funciones.php");



error_reporting(-1);
set_time_limit(0);

$fechahora=date("dmy.Hi");
$archivoName=$fechahora.$_FILES['archivo']['name'];

echo "<h1>Cargado de Archivo de CloseUp</h1>";


echo "<h3>Hora inicio: " . date("H:i:s")."</h3>";


if ($_FILES['archivo']["error"] > 0){
	echo "Error: " . $_FILES['archivo']['error'] . "<br>";
}
else{
	if($_FILES['archivo']['type']=="application/x-zip-compressed"){
		//echo "Nombre: " . $_FILES['archivo']['name'] . "<br>";
		//echo "Tipo: " . $_FILES['archivo']['type'] . "<br>";
		//echo "Tamaño: " . (($_FILES["archivo"]["size"])/1024)/1024 . " MB<br>";
		//echo "Carpeta temporal: " . $_FILES['archivo']['tmp_name'];
		move_uploaded_file($_FILES['archivo']['tmp_name'], "archivosCUP/".$archivoName);	
		
		echo "<h3>1. El archivo de CloseUp fue cargado correctamente!</h3>";
		
		//ACA DESCOMPRIMIMOS EL ARCHIVO
		$zip = new ZipArchive;
		if ($zip->open('archivosCUP/'.$archivoName) === TRUE) {
			$zip->extractTo('archivosCUP/procesar/');
			$zip->close();
			echo "<h3>2. El archivo tiene el formato correcto!</h3>";
		} else {
			echo "<h3>2. El archivo NO tiene el formato correcto, contacte con el administrador!</h3>";
		}
		
		/*REALIZAMOS EL BORRADO DE LAS TABLAS*/
		
		$sqlDelete="TRUNCATE TABLE cup_medicos";
		$respDelete=mysql_query($sqlDelete);
		
		$sqlDelete="TRUNCATE TABLE cup_laboratorio";
		$respDelete=mysql_query($sqlDelete);
		
		$sqlDelete="TRUNCATE TABLE cup_mercado";
		$respDelete=mysql_query($sqlDelete);
		
		$sqlDelete="TRUNCATE TABLE cup_mercados_productos";
		$respDelete=mysql_query($sqlDelete);
		
		$sqlDelete="TRUNCATE TABLE cup_presentacion";
		$respDelete=mysql_query($sqlDelete);
		
		$sqlDelete="TRUNCATE TABLE cup_datos";
		$respDelete=mysql_query($sqlDelete);
		
		
		//ACA EMPEZAMOS LA LECTURA DE LOS ARCHIVOS
		
		/*MEDICOS*/		
		
		$file = fopen("archivosCUP/procesar/TARGET/Medicos.txt", "r") or exit("No se puede abrir el archivo Medicos!");
		//Output a line of the file until the end is reached
		$indice=1;
		$insert_str="";
		while(!feof($file)){
			$varMedicos=fgets($file);
			if($varMedicos!=""){
				list($idRegion, $alfanum, $nombreMedico, $idMedico, $direccion, $regionTxt, $region2, $alfanum2, $especialidad)=explode(",", $varMedicos);
				$idRegion=trim($idRegion);
				$idRegion=str_replace('"', '', $idRegion);
				
				$nombreMedico=trim($nombreMedico);
				$nombreMedico=sanear_string(utf8_encode($nombreMedico));
				
				$idMedico=trim($idMedico);
				$idMedico=str_replace('"', '', $idMedico);
				
				$direccion=trim($direccion);
				$direccion=sanear_string(utf8_encode($direccion));
				
				$regionTxt=trim($regionTxt);
				$regionTxt=str_replace('"', '', $regionTxt);
				
				$region2=trim($region2);
				$region2=str_replace('"', '', $region2);
				
				$alfanum2=trim($alfanum2);
				$alfanum2=str_replace('"', '', $alfanum2);
				
				$especialidad=trim($especialidad);
				$especialidad=str_replace('"', '', $especialidad);
				
				$insert_str .= "('$idRegion', '$alfanum', '$nombreMedico', '$idMedico', '$direccion', '$regionTxt', '$region2', '$alfanum2', '$especialidad'),";	
			}
			$indice++;
		}
		$insert_str = substr_replace($insert_str, '', -1, 1);
		$sqlInserta="insert into cup_medicos (region, alfanum, nombre_medico, cod_cup, direccion, regiontxt, region2, alfanum2, especialidad) 
			values ".$insert_str;
		$respInserta=mysql_query($sqlInserta) or die(mysql_error());
		fclose($file);
		
		
		
		/*LABORATORIOS*/		
		$file = fopen("archivosCUP/procesar/TARGET/Laboratorios.txt", "r") or exit("No se puede abrir el archivo Laboratorios!");
		//Output a line of the file until the end is reached
		$indice=1;
		$insert_str="";
		while(!feof($file)){
			$varLab=fgets($file);
			if($varLab!=""){
				list($codLabo, $nombreLabo)=explode(",", $varLab);
				$codLabo=trim($codLabo);
				$nombreLabo=trim($nombreLabo);
				$codLabo=str_replace('"', '', $codLabo);
				$nombreLabo=str_replace('"', '', $nombreLabo);
				
				//echo $codLabo." -- ".$nombreLabo;	
				$insert_str .= "($indice,'$nombreLabo','$codLabo'),";	
			}
			$indice++;
		}
		$insert_str = substr_replace($insert_str, '', -1, 1);
		$sqlInserta="insert into cup_laboratorio (id, nombre, abreviatura) 
			values ".$insert_str;
		$respInserta=mysql_query($sqlInserta) or die(mysql_error());
		fclose($file);
		
		/*MERCADOS*/		
		$file = fopen("archivosCUP/procesar/TARGET/Mercados.txt", "r") or exit("No se puede abrir el archivo Mercados!");
		//Output a line of the file until the end is reached
		$indice=1;
		$insert_str="";
		while(!feof($file)){
			$varMercado=fgets($file);
			if($varMercado!=""){
				list($idUsuario, $idMercado, $nombreMercado, $fuenteMercado)=explode(",", $varMercado);
				$idUsuario=trim($idUsuario);
				$idUsuario=str_replace('"', '', $idUsuario);
				$idMercado=trim($idMercado);
				$nombreMercado=trim($nombreMercado);
				$nombreMercado=str_replace('"', '', $nombreMercado);
				$fuenteMercado=trim($fuenteMercado);
				$fuenteMercado=str_replace('"', '', $fuenteMercado);
				
				//echo $nombreMercado."<br>";
				$insert_str .= "('$idUsuario','$idMercado','$nombreMercado','$fuenteMercado','0'),";	
			}
			$indice++;
		}
		$insert_str = substr_replace($insert_str, '', -1, 1);
		$sqlInserta="insert into cup_mercado (id_usuario, id_mercado, mercado, fuente, id_lineamkt) 
			values ".$insert_str;
		$respInserta=mysql_query($sqlInserta) or die(mysql_error());
		fclose($file);
		
		
		/*MERCADOS PRODUCTOS*/		
		$file = fopen("archivosCUP/procesar/TARGET/Mercados_Productos.txt", "r") or exit("No se puede abrir el archivo Mercados_Productos!");
		//Output a line of the file until the end is reached
		$indice=1;
		$insert_str="";
		while(!feof($file)){
			$varMercadoProducto=fgets($file);
			if($varMercadoProducto!=""){
				list($idUsuario, $idMercado, $idRaiz, $idConcentracion, $idPresentacion, $idFormaF)=explode(",", $varMercadoProducto);
				$idUsuario=trim($idUsuario);
				$idUsuario=str_replace('"', '', $idUsuario);
				$idMercado=trim($idMercado);
				
				$idRaiz=trim($idRaiz);
				$idRaiz=str_replace('"', '', $idRaiz);
				
				$idConcentracion=trim($idConcentracion);
				$idConcentracion=str_replace('"', '', $idConcentracion);
				
				$idPresentacion=trim($idPresentacion);
				$idPresentacion=str_replace('"', '', $idPresentacion);
				
				$idFormaF=trim($idFormaF);
				$idFormaF=str_replace('"', '', $idFormaF);
				
				//echo $idRaiz."<br>";
				$insert_str .= "('$idUsuario','$idMercado','$idRaiz','$idConcentracion','$idPresentacion','$idFormaF'),";	
			}
			$indice++;
		}
		$insert_str = substr_replace($insert_str, '', -1, 1);
		$sqlInserta="insert into cup_mercados_productos (id_usuario, id_mercado, id_raiz, id_concentracion, id_presentacion, id_formaf) 
			values ".$insert_str;
		$respInserta=mysql_query($sqlInserta) or die(mysql_error());
		fclose($file);
		
		
		/*PRESENTACIONES*/		
		$file = fopen("archivosCUP/procesar/TARGET/Presentaciones.txt", "r") or exit("No se puede abrir el archivo Presentaciones!");
		//Output a line of the file until the end is reached
		$indice=1;
		$insert_str="";
		while(!feof($file)){
			$varPresentaciones=fgets($file);
			if($varPresentaciones!=""){
				list($idRaiz, $idConcentracion, $idPresentacion, $idFormaF, $nombrePresentacion, $fechaLanzamiento, $codEtico, $idLaboratorio, $idClase)=explode(",", $varPresentaciones);				

				$idRaiz=trim($idRaiz);
				$idRaiz=str_replace('"', '', $idRaiz);
				
				$idConcentracion=trim($idConcentracion);
				$idConcentracion=str_replace('"', '', $idConcentracion);
				
				$idPresentacion=trim($idPresentacion);
				$idPresentacion=str_replace('"', '', $idPresentacion);
				
				$idFormaF=trim($idFormaF);
				$idFormaF=str_replace('"', '', $idFormaF);
				
				$nombrePresentacion=trim($nombrePresentacion);
				$nombrePresentacion=sanear_string(utf8_encode($nombrePresentacion));
				
				$fechaLanzamiento=trim($fechaLanzamiento);
				$fechaLanzamiento=str_replace('"', '', $fechaLanzamiento);
				
				$codEtico=trim($codEtico);
				$codEtico=str_replace('"', '', $codEtico);
				
				$idLaboratorio=trim($idLaboratorio);
				$idLaboratorio=str_replace('"', '', $idLaboratorio);
				
				$idClase=trim($idClase);
				$idClase=str_replace('"', '', $idClase);


				//echo $idRaiz."<br>";
				$insert_str .= "('$idRaiz','$idConcentracion','$idPresentacion','$idFormaF','$nombrePresentacion','$fechaLanzamiento','$codEtico','$idLaboratorio','$idClase'),";	
			}
			$indice++;
		}
		$insert_str = substr_replace($insert_str, '', -1, 1);
		$sqlInserta="insert into cup_presentacion (id_raiz, id_concentracion, id_presentaciones, id_formaf, presentacion, fecha_lanzamiento, etico, id_laboratorio, id_clase) 
			values ".$insert_str;
		//echo $sqlInserta;
		$respInserta=mysql_query($sqlInserta) or die(mysql_error());
		fclose($file);
		
		
		/*DATOS*/		
		$file = fopen("archivosCUP/procesar/TARGET/Datos.txt", "r") or exit("No se puede abrir el archivo Datos!");
		//Output a line of the file until the end is reached
		$indice=1;
		$insert_str="";
		while(!feof($file)){
			$varDatos=fgets($file);
			if($varDatos!=""){
				list($idRaiz, $idConcentracion, $idPresentacion, $idFormaF, $idMedico, $idEspecialidad, $idRegion, $idLaboratorio, $idClase, $cantPx1, $cantPx2, $idMes)=explode(",", $varDatos);				

				$idRaiz=trim($idRaiz);
				$idRaiz=str_replace('"', '', $idRaiz);
				
				$idConcentracion=trim($idConcentracion);
				$idConcentracion=str_replace('"', '', $idConcentracion);
				
				$idPresentacion=trim($idPresentacion);
				$idPresentacion=str_replace('"', '', $idPresentacion);
				
				$idFormaF=trim($idFormaF);
				$idFormaF=str_replace('"', '', $idFormaF);
				
				$idMedico=trim($idMedico);
				$idMedico=str_replace('"', '', $idMedico);
				
				$idEspecialidad=trim($idEspecialidad);
				$idEspecialidad=str_replace('"', '', $idEspecialidad);
				
				$idRegion=trim($idRegion);
				$idRegion=str_replace('"', '', $idRegion);
				
				$idLaboratorio=trim($idLaboratorio);
				$idLaboratorio=str_replace('"', '', $idLaboratorio);
				
				$idClase=trim($idClase);
				$idClase=str_replace('"', '', $idClase);
				
				$cantPx1=trim($cantPx1);
				$cantPx2=trim($cantPx2);

				$idMes=trim($idMes);
				$idMes=str_replace('"', '', $idMes);
	
				list($anioCup, $mesCup)=explode("/",$idMes);
				$fechaCup=$anioCup."-".$mesCup."-01";
				//echo $idRaiz."<br>";
				$insert_str .= "('$idRaiz','$idConcentracion','$idPresentacion','$idFormaF','$idMedico','$idEspecialidad','$idRegion','$idLaboratorio','$idClase','$cantPx1','$cantPx2','$idMes','$fechaCup'),";	
			}
			
			/*insertamos de 1000 en 1000*/
			if($indice%20000==0){
				$insert_str = substr_replace($insert_str, '', -1, 1);
				$sqlInserta="insert into cup_datos (id_raiz, id_concentracion, id_presentacion, id_formaf, id_medico, id_especialidad, id_region, id_laboratorio, id_clase, cant_pxs1, cant_pxs2, mes, fecha) 
					values ".$insert_str.";";
				
				//echo $sqlInserta."<br>";
				$respInserta=mysql_query($sqlInserta) or die(mysql_error());
				$insert_str="";
			}
			$indice++;
		}
		$insert_str = substr_replace($insert_str, '', -1, 1);
		$sqlInserta="insert into cup_datos (id_raiz, id_concentracion, id_presentacion, id_formaf, id_medico, id_especialidad, id_region, id_laboratorio, id_clase, cant_pxs1, cant_pxs2, mes, fecha) 
			values ".$insert_str.";";
		//echo $sqlInserta;
		$respInserta=mysql_query($sqlInserta) or die(mysql_error());
		fclose($file);
		
	}else{
		echo "<h3>Error con el tipo de archivo. No es un archivo .zip</h3>";
	}
}

echo "<h3>Hora Fin: " . date("H:i:s")."</h3>";

?>