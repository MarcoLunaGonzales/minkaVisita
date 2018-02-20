<?php

require("conexion.inc");	
	$sql="select g.codigo_grupo_especial, g.nombre_grupo_especial, g.cod_muestra,g.agencia, g.codigo_linea, g.tipo_grupo 
	from grupo_especial g";
	$resp=mysql_query($sql);
	
	while($dat=mysql_fetch_array($resp)){
		$codGrupo=$dat[0];
		$nombreGrupo=$dat[1];
		$nombreGrupo=$nombreGrupo." ".$nombreLineaNew;
		$codMuestra=$dat[2];
		$codAgencia=$dat[3];
		$codLinea=$dat[4];
		$tipoGrupo=$dat[5];

		echo $nombreGrupo;
		
		$sqlUpd="update parrilla_especial set agencia=$codAgencia where codigo_grupo_especial=$codGrupo";
		$respUpd=mysql_query($sqlUpd);
	}


?>