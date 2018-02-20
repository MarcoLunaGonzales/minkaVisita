<?php
require('conexion.inc');
$codGestion = 1010;
$codCiclo = 11;
$codLinea = 1021;

$sql="select f.codigo_funcionario, f.cod_ciudad from funcionarios f, funcionarios_lineas fli
	where f.codigo_funcionario=fli.codigo_funcionario and 
	fli.codigo_linea=$codLinea and f.estado=1 and f.cod_cargo=1011";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){

	$codFuncionario=$dat[0];
	$codCiudad=$dat[1];
	
	echo "VISITADOR ***************  $codFuncionario $codCiudad <br>";
	
	$sqlMedicos="select distinct(rd.cod_med), rd.cod_especialidad, rd.categoria_med from rutero_maestro_cab rc, rutero_maestro r, rutero_maestro_detalle rd
		where rc.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto and 
		rc.codigo_ciclo=$codCiclo and rc.codigo_gestion=$codGestion and rc.codigo_linea=$codLinea and rc.cod_visitador=$codFuncionario";
	
	$respMedicos=mysql_query($sqlMedicos);
	while($datMedicos=mysql_fetch_array($respMedicos)){
		$codMed=$datMedicos[0];
		$codEspe=$datMedicos[1];
		$catMed=$datMedicos[2];
		
		echo "$codMed $codEspe $catMed <br>";
		$sqlLineas="select fli.codigo_linea from funcionarios f, funcionarios_lineas fli
			where f.codigo_funcionario=fli.codigo_funcionario and 
			fli.codigo_linea>1031 and f.estado=1 and f.cod_cargo=1011 and f.codigo_funcionario=$codFuncionario";
		$respLineas=mysql_query($sqlLineas);
		while($datLineas=mysql_fetch_array($respLineas)){
			$codigoLineaNew=$datLineas[0];
			
			echo "NUEVA LINEA ++++++++ $codigoLineaNew  +++++++ <br>";
			$sqlInsert="insert into categorias_lineas values('$codigoLineaNew','$codMed','$codEspe','$catMed',0,0)";
			$respInsert=mysql_query($sqlInsert);
			
			$sqlInsert="insert into medico_asignado_visitador values('$codMed','$codFuncionario','$codigoLineaNew')";
			$respInsert=mysql_query($sqlInsert);			
		}		
	}
}
?>