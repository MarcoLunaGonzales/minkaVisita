<?php

require("conexion.inc");

$sql="select m.cod_med, e.cod_especialidad, c.categoria_med, m.cod_visitador from medicos m, especialidades_medicos e, 
	categorias_lineas c where m.cod_med=e.cod_med and c.cod_med=m.cod_med and 
	c.cod_especialidad=e.cod_especialidad and m.fecha_registro>'2014-02-01' 
	and e.cod_med=c.cod_med  and c.codigo_linea=1021
	order by m.cod_med;";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codMed=$dat[0];
	$codEspe=$dat[1];
	$catMed=$dat[2];
	$codVisitador=$dat[3];
	
	echo "$codMed $codEspe $catMed $codVisitador <br>";
	
	$sqlLineas="select f.codigo_linea from funcionarios_lineas f where f.codigo_funcionario=$codVisitador";
	$respLineas=mysql_query($sqlLineas);
	while($datLineas=mysql_fetch_array($respLineas)){
		$codigoLinea=$datLineas[0];
		echo "$datLineas[0] <br>";
		
		$sqlInsert="insert into categorias_lineas values ('$codigoLinea', '$codMed', '$codEspe', '$catMed', 1,1)";
		$respInsert=mysql_query($sqlInsert);
		
		$sqlInsert="insert into medico_asignado_visitador values ('$codMed', '$codVisitador', '$codigoLinea')";
		$respInsert=mysql_query($sqlInsert);
		
	}
	
	
}

?>