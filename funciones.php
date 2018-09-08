<?php



function redondear2($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}

function formatonumero($valor) { 
   $float_redondeado=number_format($valor, 0); 
   return $float_redondeado; 
}

function formatonumeroDec($valor) { 
   $float_redondeado=number_format($valor, 2); 
   return $float_redondeado; 
}

function codigoParrilla(){
	$sql = "SELECT max(p.codigo_parrilla)+1 from parrilla p";
	$resp = mysql_query($sql);
	$num_filas = mysql_result($resp,0,0);
	return $num_filas;	
}

function formateaFechaVista($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[6].$cadena_fecha[7].$cadena_fecha[8].$cadena_fecha[9]."-".$cadena_fecha[3].$cadena_fecha[4]."-".$cadena_fecha[0].$cadena_fecha[1];
	return($cadena_formatonuevo);
}

function formatearFecha2($cadena_fecha)
{	$cadena_formatonuevo=$cadena_fecha[8].$cadena_fecha[9]."/".$cadena_fecha[5].$cadena_fecha[6]."/".$cadena_fecha[0].$cadena_fecha[1].$cadena_fecha[2].$cadena_fecha[3];
	return($cadena_formatonuevo);
}

function obtenerCodigo($sql)
{	require("conexion.inc");
	$resp=mysql_query($sql);
	$nro_filas_sql = mysql_num_rows($resp);
	if($nro_filas_sql==0){
		$codigo=1;
	}else{
		while($dat=mysql_fetch_array($resp))
		{	$codigo =$dat[0];
		}
			$codigo = $codigo+1;
	}
	return($codigo);
}

function stockMaterialesEdit($almacen, $item, $cantidad){
	//
	require("conexion.inc");
	$cadRespuesta="";
	$consulta="
	    SELECT SUM(id.cantidad_restante) as total
	    FROM ingreso_detalle_almacenes id, ingreso_almacenes i
	    WHERE id.cod_material='$item' AND i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.ingreso_anulado=0 AND i.cod_almacen='$almacen'";
	$rs=mysql_query($consulta);
	$registro=mysql_fetch_array($rs);
	$cadRespuesta=$registro[0];
	if($cadRespuesta=="")
	{   $cadRespuesta=0;
	}
	$cadRespuesta=$cadRespuesta+$cantidad;
	$cadRespuesta=redondear2($cadRespuesta);
	return($cadRespuesta);
}
function restauraCantidades($codigo_registro){
	$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria
				from salida_detalle_ingreso
				where cod_salida_almacen='$codigo_registro'";
	$resp_detalle=mysql_query($sql_detalle);
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$codigo_ingreso=$dat_detalle[0];
		$material=$dat_detalle[1];
		$cantidad=$dat_detalle[2];
		$nro_lote=$dat_detalle[3];
		$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
								where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		$resp_ingreso_cantidad=mysql_query($sql_ingreso_cantidad);
		$dat_ingreso_cantidad=mysql_fetch_array($resp_ingreso_cantidad);
		$cantidad_restante=$dat_ingreso_cantidad[0];
		$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
		$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_restante_actualizada
						where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
		
		$resp_actualiza=mysql_query($sql_actualiza);			
	}
	return(1);
}

function sanear_string($string)
{
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\\", "¨", "º", "-", "~",
             "#", "@", "|", "!", "\"",
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        ' ',
        $string
    );
 
    return $string;
}
function fechasNombresCUP(){
	$sqlMax="select max(d.fecha) from cup_datos d";
	$respMax=mysql_query($sqlMax);
	$fechaMaxima=mysql_result($respMax,0,0);
	
	$trim4Fin=date("M.y", strtotime($fechaMaxima));
	$trim4Ini=date("M.y", strtotime($fechaMaxima."- 2 month"));
	$trim4=$trim4Ini." ".$trim4Fin;
	
	$trim3Fin=date("M.y", strtotime($fechaMaxima."- 3 month"));
	$trim3Ini=date("M.y", strtotime($fechaMaxima."- 5 month"));
	$trim3=$trim3Ini." ".$trim3Fin;
	
	$trim2Fin=date("M.y", strtotime($fechaMaxima."- 6 month"));
	$trim2Ini=date("M.y", strtotime($fechaMaxima."- 8 month"));
	$trim2=$trim2Ini." ".$trim2Fin;
	
	$trim1Fin=date("M.y", strtotime($fechaMaxima."- 9 month"));
	$trim1Ini=date("M.y", strtotime($fechaMaxima."- 11 month"));
	$trim1=$trim1Ini." ".$trim1Fin;
	
	$vectorNombresFechas[0]=$trim1;
	$vectorNombresFechas[1]=$trim2;
	$vectorNombresFechas[2]=$trim3;
	$vectorNombresFechas[3]=$trim4;
	
	return($vectorNombresFechas);
}
function fechasCUPAnioMovil(){
	$sqlMax="select max(d.fecha) from cup_datos d";
	$respMax=mysql_query($sqlMax);
	$fechaMaxima=mysql_result($respMax,0,0);
	
	$fechaIni=date("Y-m-d", strtotime($fechaMaxima."- 11 month"));
	$fechaFin=date("Y-m-d", strtotime($fechaMaxima));

	$vectorCUPAnoMovil[0]=$fechaIni;
	$vectorCUPAnoMovil[1]=$fechaFin;
	
	return($vectorCUPAnoMovil);	
}
function fechasCUPTrimestreIni(){
	$sqlMax="select max(d.fecha) from cup_datos d";
	$respMax=mysql_query($sqlMax);
	$fechaMaxima=mysql_result($respMax,0,0);
	
	$trim4Fin=date("Y-m-d", strtotime($fechaMaxima));
	$trim4Ini=date("Y-m-d", strtotime($fechaMaxima."- 2 month"));
	
	$trim3Fin=date("Y-m-d", strtotime($fechaMaxima."- 3 month"));
	$trim3Ini=date("Y-m-d", strtotime($fechaMaxima."- 5 month"));
	
	$trim2Fin=date("Y-m-d", strtotime($fechaMaxima."- 6 month"));
	$trim2Ini=date("Y-m-d", strtotime($fechaMaxima."- 8 month"));
	
	$trim1Fin=date("Y-m-d", strtotime($fechaMaxima."- 9 month"));
	$trim1Ini=date("Y-m-d", strtotime($fechaMaxima."- 11 month"));
	
	$vectorTrimIni[0]=$trim1Ini;
	$vectorTrimIni[1]=$trim2Ini;
	$vectorTrimIni[2]=$trim3Ini;
	$vectorTrimIni[3]=$trim4Ini;

	return($vectorTrimIni);
}
function fechasCUPTrimestreFin(){
	$sqlMax="select max(d.fecha) from cup_datos d";
	$respMax=mysql_query($sqlMax);
	$fechaMaxima=mysql_result($respMax,0,0);
	
	$trim4Fin=date("Y-m-d", strtotime($fechaMaxima));
	$trim4Ini=date("Y-m-d", strtotime($fechaMaxima."- 2 month"));
	
	$trim3Fin=date("Y-m-d", strtotime($fechaMaxima."- 3 month"));
	$trim3Ini=date("Y-m-d", strtotime($fechaMaxima."- 5 month"));
	
	$trim2Fin=date("Y-m-d", strtotime($fechaMaxima."- 6 month"));
	$trim2Ini=date("Y-m-d", strtotime($fechaMaxima."- 8 month"));
	
	$trim1Fin=date("Y-m-d", strtotime($fechaMaxima."- 9 month"));
	$trim1Ini=date("Y-m-d", strtotime($fechaMaxima."- 11 month"));
	
	$vectorTrimFin[0]=$trim1Fin;
	$vectorTrimFin[1]=$trim2Fin;
	$vectorTrimFin[2]=$trim3Fin;
	$vectorTrimFin[3]=$trim4Fin;

	return($vectorTrimFin);
}

function funcionUltimoDiaMes() { 
	  $month = date('m');
	  $year = date('Y');
	  $day = date("d", mktime(0,0,0, $month+1, 0, $year));
	  return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
};
 
function funcionPrimerDiaMes() {
	  $month = date('m');
	  $year = date('Y');
	  return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
}

?>