<?php
require("conexion.inc");

$sql="select cod_med, categoria_med, codigo_linea, cod_especialidad from categorias_lineas c 
where c.cod_med>=1626403 and c.cod_med in (select cod_med from medicos where cod_ciudad=122)";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codMed=$dat[0];
	$catMed=$dat[1];
	$codLinea=$dat[2];
	$codEspe=$dat[3];
	
	$sqlLineas="select f.codigo_funcionario, fl.codigo_linea from 
		funcionarios f, funcionarios_lineas fl where f.codigo_funcionario=fl.codigo_funcionario and 
		f.estado=1 and f.cod_ciudad=122";
	$respLineas=mysql_query($sqlLineas);
	while($datLineas=mysql_fetch_array($respLineas)){
		$codVis=$datLineas[0];
		$codLineaOf=$datLineas[1];
		
		$sqlInsert="insert into categorias_lineas(codigo_linea, cod_med, cod_especialidad, categoria_med) values
		($codLineaOf, $codMed, '$codEspe', '$catMed')";
		echo $sqlInsert."<br>";
		if(mysql_query($sqlInsert)){
			echo "inserto cat lineas $codMed $codLineaOf<br>";
		}else{
			echo "duplicado cat lineas $codMed $codLineaOf<br>";
		}
		
		$sqlInsert2="insert into medico_asignado_visitador(cod_med, codigo_visitador, codigo_linea) values
		($codMed, $codVis, $codLineaOf)";
		echo $sqlInsert2."<br>";

		if(mysql_query($sqlInsert2)){
			echo "inserto $codMed $codVis<br>";
		}else{
			echo "duplicado $codMed $codVis<br>";
		}
		
		
	}

	
	echo "$codMed $catMed $codLinea<br>";
}

?>