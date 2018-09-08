<?php

require("conexion.inc");


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
		
		$insert_str .= "('$idUsuario','$idMercado','$idRaiz','$idConcentracion','$idPresentacion','$idFormaF'),";	
		/*$sqlInserta="insert into cup_mercados_productos (id_usuario, id_mercado, id_raiz, id_concentracion, id_presentacion, id_formaf) 
		values ('$idUsuario','$idMercado','$idRaiz','$idConcentracion','$idPresentacion','$idFormaF')";
		$respInserta=mysql_query($sqlInserta);*/
	}
	$indice++;
}
$insert_str = substr_replace($insert_str, '', -1, 1);
$sqlInserta="insert into cup_mercados_productos (id_usuario, id_mercado, id_raiz, id_concentracion, id_presentacion, id_formaf) 
	values ".$insert_str;
$respInserta=mysql_query($sqlInserta);
fclose($file);


?>