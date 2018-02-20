<?php

set_time_limit(0);
require("../../conexion.inc");

$cadena = $_REQUEST['cadena'];
$cadena = substr($cadena, 0, -1);
$cadena_explode = explode("@", $cadena);
$cadena_chunk = array_chunk($cadena_explode, 8);

$sql_max = mysql_query("SELECT max(id) from muestras_agregadas");
$max_id  = mysql_result($sql_max, 0, 0);

if($max_id == '' or $max_id == 'null'){
	$id = 1;
}else{
	$id = $max_id + 1;
}


foreach ($cadena_chunk as $key => $value) {

	if($value[5]== 1){
		$posicion        = "todo";
		$ultima_posicion = 1;
	}else{
		$posicion = $value[4];
		$ultima_posicion = 0;
	}
	if ($value[7]== 1) {
		$cantidad = "todo";
	}else{
		$cantidad = $value[6];
	}
	$codigoMedico=$value[0];
	//validamos si no esta el medico y de ser asi borramos los registros
	$sqlDelCab="DELETE from muestras_agregadas where cod_med in ($codigoMedico)";
	$sqlDelDet="DELETE from muestras_agregadas_frecuencia where id 
			in (select muestras_agregadas.id from muestras_agregadas where muestras_agregadas.cod_med in ($codigoMedico))";
	mysql_query($sqlDelDet);
	mysql_query($sqlDelCab);
	//terminamos el borrado si el medico ya esta.

	mysql_query("INSERT into muestras_agregadas (id,cod_med,codigo_muestra,frecuencia, posicion, cantidad,linea,ultima_posicion) values ($id,$value[0],'$value[2]','$value[3]','$posicion','$cantidad',$value[1],$ultima_posicion)");

	$frecuencia = substr($value[3], 0, -1);
	$frecuencia = explode("#", $frecuencia);

	foreach ($frecuencia as $value) {
		mysql_query("INSERT into muestras_agregadas_frecuencia (id,frecuencia) values ($id,$value)");
	}

	$id++;

}

$arr = array("mensaje" => "Guardado Satisfactoriamente");
echo json_encode($arr);
?>
