<?php

require("conexion.inc");


$sqlLinea="select codigo_linea, nombre_linea from lineas l where l.codigo_linea in (1042) ORDER BY 1";
$respLinea=mysql_query($sqlLinea);
while($datLinea=mysql_fetch_array($respLinea)){
	$codLineaNew=$datLinea[0];
	$nombreLineaNew=$datLinea[1];
	
	$sql="select g.codigo_grupo_especial, g.nombre_grupo_especial, g.cod_muestra,g.agencia, g.codigo_linea, g.tipo_grupo 
	from grupo_especial g where g.codigo_linea=1033";
	$resp=mysql_query($sql);
	
	
	
	while($dat=mysql_fetch_array($resp)){
		$codGrupo=$dat[0];
		$nombreGrupo=$dat[1];
		$nombreGrupo=$nombreGrupo." ".$nombreLineaNew;
		$codMuestra=$dat[2];
		$codAgencia=$dat[3];
		$codLinea=$dat[4];
		$tipoGrupo=$dat[5];


		$sqlCodigoNew="select max(codigo_grupo_especial)+1 from grupo_especial";
		$respCodigoNew=mysql_query($sqlCodigoNew);
		$codigoNewGrupo=mysql_result($respCodigoNew,0,0);

		//insert cabecera
		$sqlInsert="insert into grupo_especial values('$codigoNewGrupo','$nombreGrupo','$codMuestra','$codAgencia','$codLineaNew','$tipoGrupo')";
		$respInsert=mysql_query($sqlInsert);
		echo $sqlInsert."<br>";

		
		$sqlDetalle="select g.codigo_grupo_especial, g.cod_med from grupo_especial_detalle g where g.codigo_grupo_especial=$codGrupo";
		$respDetalle=mysql_query($sqlDetalle);
		while($datDetalle=mysql_fetch_array($respDetalle)){
			$codGrupoDet=$datDetalle[0];
			$codMed=$datDetalle[1];
			
			//echo $codGrupoDet." ".$codMed." <br>";
			$sqlInsertDet="insert into grupo_especial_detalle values('$codigoNewGrupo','$codMed')";
			$respInsertDet=mysql_query($sqlInsertDet);
			echo $sqlInsertDet."<br>";
		}	
	}
}


?>