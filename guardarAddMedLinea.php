<?php
require("conexion.inc");
$codMed=$_GET["codMed"];
$cantidadLineas=$_GET["cantidadLineas"];

for($i=0; $i<$cantidadLineas; $i++){
	$codLinea=$_GET["codLinea$i"];
	$codEspe=$_GET["espe$i"];
	$catLinea=$_GET["cat$i"];
	$codFunc=$_GET["func$i"];
	
	
	if($codFunc!=0){
		$sql="insert into categorias_lineas(codigo_linea, cod_med, cod_especialidad, categoria_med, frecuencia_linea, frecuencia_permitida) 
		 values('$codLinea', '$codMed', '$codEspe', '$catLinea', 2, 2)";
		mysql_query($sql);
		
		$sql1="insert into medico_asignado_visitador (cod_med, codigo_visitador, codigo_linea) 
		values ($codMed, $codFunc, $codLinea)";
		//echo $sql1;
		mysql_query($sql1);
	}

	
	
}

echo "<script language='Javascript'>
	alert('Los datos se guardaron satisfactoriamente');
	location.href='busquedaMedAnadir.php';
</script>";

?>