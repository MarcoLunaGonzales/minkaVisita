<?php

set_time_limit(0);
require_once ('../../conexion.inc');
$valor = $_REQUEST['valores'];
$codigo_ciclo = $_REQUEST['ciclo'];
$codigo_gestion = $_REQUEST['gestion'];
$codigo_territorio = $_REQUEST['territorios'];
//$lineas=$_REQUEST['lineas'];

$valorCadena = substr($valor, 0, -3);

$fecha_actual = date("Y-m-d");
$id_sql = mysql_query("SELECT MAX(id) from grupos_especiales");
$num_id_sql = mysql_result($id_sql, 0, 0);

//$sql_veri = mysql_query("SELECT id from grupos_especiales where territorio in ($codigo_territorio) and ciclo = $codigo_ciclo and codigo_gestion='$codigo_gestion'");

$txtSql="select ge.id from grupos_especiales ge where ge.codigo_grupo_especial in (
	SELECT g.codigo_grupo_especial from grupo_especial g where g.agencia in ($codigo_territorio)
	and ge.ciclo in ($codigo_ciclo) and ge.gestion in ($codigo_gestion))";

	//echo $txtSql."<br>";
$sql_veri = mysql_query($txtSql);

$num_veri = mysql_num_rows($sql_veri);
if ($num_veri >= 1) {
    while ($row_id = mysql_fetch_array($sql_veri)) {
        $codigos_id .= $row_id[0] . ",";
    }
    $codigos_id_finales = substr($codigos_id, 0, -1);
    
	$sqlTxtDel="DELETE from grupos_especiales where id in ($codigos_id_finales)";
	//echo $sqlTxtDel;
	mysql_query($sqlTxtDel);
    mysql_query("DELETE from grupos_especiales_detalle where id in ($codigos_id_finales)");
}

if ($num_id_sql == 0) {
    $id = 1;
} else {
    $id = $num_id_sql + 1;
}

//echo $valorCadena;
$valorCadenaMedico=explode("|,", $valorCadena);
for($i=0; $i<sizeOf($valorCadenaMedico); $i++){
	list($codigoGruposArray, $codMedico, $codVisitador)=explode(",", $valorCadenaMedico[$i]);
	//echo "grupos $codigoGruposArray medico $codMedico vis $codVisitador <br>";
	$codigoGruposArray=explode("-", $codigoGruposArray);
	
	for($j=0; $j<sizeOf($codigoGruposArray); $j++){
		//echo $id."  ".$codigoGruposArray[$j]." ".$codMedico." ". $codVisitador."<br>";
		
		$txtInsertCab="INSERT into grupos_especiales values($id,$codigo_ciclo,$codigo_gestion,$codigoGruposArray[$j],$codigo_territorio,'$fecha_actual')";	
		mysql_query($txtInsertCab);
		
		$query2 = "INSERT into grupos_especiales_detalle  VALUES ($id, $codMedico, $codVisitador)";
		mysql_query($query2);
		
		$id++;
	}
}

echo json_encode("Los datos se guardaron satisfactoriamente.");

/*
foreach ($valor_explode as $primeros_valores) {
    $cadena = '';
    $primeros_valores_sub = substr($primeros_valores, 0, -1);
	
	echo $primeros_valores_sub."<br>";
	
    $primeros_valores_explode = explode(",", $primeros_valores_sub);
    $primer_valor_array = $primeros_valores_explode[0];
    unset($primeros_valores_explode[0]);
    $primer_valor_array_explode = explode("-", $primer_valor_array);
	
	$txtInsertCab="INSERT into grupos_especiales values($id,$codigo_ciclo,$codigo_gestion,$primer_valor_array_explode[0],$primer_valor_array_explode[1],'$fecha_actual')";
	
	echo $txtInsertCab;
	
	mysql_query($txtInsertCab);
    
	foreach ($primeros_valores_explode as $valores_finales => $a) {
        $cadena .= $id . "," . $a . ",";
    }
    $cadena_sub = substr($cadena, 0, -1);
    $cadena_explode = explode(",", $cadena_sub);
    $cadena_chunk = array_chunk($cadena_explode, 4);
    foreach ($cadena_chunk as $aa => $bb) {
        unset($bb[2]);
        $query2 = "INSERT into grupos_especiales_detalle  VALUES (";
        foreach ($bb as $cc => $dd) {
            $query2 .= $dd . ",";
        }
        $query2 = substr($query2, 0, -1);
        $query2 .= ");";
		
		//echo "<br>query: ".$query2;
        
		mysql_query($query2);
    }
    $id++;
}

echo json_encode("Los datos se guardaron satisfactoriamente.");
*/
?>