<?php
require("conexion.inc");
require("estilos_regional_pri.inc");

echo "Hora inicio: " . date("H:i:s");


error_reporting(-1);
$fechahora=date("dmy.Hi");
$archivoName=$fechahora.$_FILES['archivo']['name'];

echo "<h1>Cargado de Archivo de CloseUp</h1>";

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
		$sqlDelete="delete from cup_laboratorio";
		$respDelete=mysql_query($sqlDelete);
		
		$sqlDelete="delete from cup_mercado";
		$respDelete=mysql_query($sqlDelete);
		
		$sqlDelete="delete from cup_mercados_productos";
		$respDelete=mysql_query($sqlDelete);
		
		
		//ACA EMPEZAMOS LA LECTURA DE LOS ARCHIVOS
		/*LABORATORIOS*/		
		$file = fopen("archivosCUP/procesar/TARGET/Laboratorios.txt", "r") or exit("No se puede abrir el archivo Laboratorios!");
		//Output a line of the file until the end is reached
		$indice=1;
		while(!feof($file)){
			$varLab=fgets($file);
			if($varLab!=""){
				list($codLabo, $nombreLabo)=explode(",", $varLab);
				$codLabo=trim($codLabo);
				$nombreLabo=trim($nombreLabo);
				$codLabo=str_replace('"', '', $codLabo);
				$nombreLabo=str_replace('"', '', $nombreLabo);
				
				echo $codLabo." -- ".$nombreLabo;	
				
				$sqlInserta="insert into cup_laboratorio (id, nombre, abreviatura) 
				values ($indice,'$nombreLabo','$codLabo')";
				$respInserta=mysql_query($sqlInserta);
			}
			$indice++;
		}
		fclose($file);
		
		/*MERCADOS*/		
		$file = fopen("archivosCUP/procesar/TARGET/Mercados.txt", "r") or exit("No se puede abrir el archivo Mercados!");
		//Output a line of the file until the end is reached
		$indice=1;
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
				
				echo $nombreMercado."<br>";
				$sqlInserta="insert into cup_mercado (id_usuario, id_mercado, mercado, fuente, id_lineamkt) 
				values ('$idUsuario','$idMercado','$nombreMercado','$fuenteMercado','0')";
				$respInserta=mysql_query($sqlInserta);
			}
			$indice++;
		}
		fclose($file);
		
		
		/*MERCADOS PRODUCTOS*/		
		$file = fopen("archivosCUP/procesar/TARGET/Mercados_Productos.txt", "r") or exit("No se puede abrir el archivo Mercados_Productos!");
		//Output a line of the file until the end is reached
		$indice=1;
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
				
				echo $idRaiz."<br>";
				$sqlInserta="insert into cup_mercados_productos (id_usuario, id_mercado, id_raiz, id_concentracion, id_presentacion, id_formaf) 
				values ('$idUsuario','$idMercado','$idRaiz','$idConcentracion','$idPresentacion','$idFormaF')";
				$respInserta=mysql_query($sqlInserta);
			}
			$indice++;
		}
		fclose($file);
		
	}else{
		echo "<h3>Error con el tipo de archivo. No es un archivo .zip</h3>";
	}
}



echo "Hora Fin: " . date("H:i:s");

?>