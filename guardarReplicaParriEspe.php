<?php
require("conexion.inc");

$lineaOrigen=$_POST['lineaOrigen'];
$lineaDestino=$_POST['lineaDestino'];
$codGrupoOrigen=$_POST['GEOrigen'];
$codMMDestino=$_POST['GEDestino'];
$codCicloGestionOri=$_POST['cicloOrigen'];
$codCicloGestionDest=$_POST['cicloDestino'];
list($cicloOrigen, $gestionOrigen)=explode("|", $codCicloGestionOri);
list($cicloDestino, $gestionDestino)=explode("|", $codCicloGestionDest);

/*echo $lineaOrigen." ".$lineaDestino."<br>";
echo $codMMOrigen." ".$codMMDestino."<br>";
echo $cicloOrigen." ".$gestionOrigen."<br>";
echo $cicloDestino." ".$gestionDestino."<br>";*/
$fechaActual=date("Y-m-d");

$sqlParrilla="select p.codigo_parrilla_especial, p.cod_ciclo, p.codigo_linea, p.fecha_creacion, p.fecha_modificacion, 
	p.numero_visita, p.agencia, p.codigo_grupo_especial, p.muestras_extra, p.codigo_gestion  
	from parrilla_especial p
	where p.codigo_grupo_especial='$codGrupoOrigen' and p.codigo_gestion='$gestionOrigen' and p.cod_ciclo='$cicloOrigen'";

//echo $sqlParrilla."<br>";

$respParrilla=mysql_query($sqlParrilla);
while($datParrilla=mysql_fetch_array($respParrilla)){
	$codParrilla=$datParrilla[0];
	$nroVisita=$datParrilla[5];
	
	$sqlGruposNew="select g.codigo_grupo_especial, g.agencia from grupo_especial g where g.cod_muestra='$codMMDestino' and g.codigo_linea='$lineaDestino'";
	//echo $sqlGruposNew."<br>";
	$respGruposNew=mysql_query($sqlGruposNew);
	while($datGNew=mysql_fetch_array($respGruposNew)){
		$codigoGENew=$datGNew[0];
		$agenciaGENew=$datGNew[1];
		
		//aqui borrar sus parrillas
		$sqlDel="delete from parrilla_especial where parrilla_especial.codigo_grupo_especial='$codigoGENew' and 
			parrilla_especial.codigo_gestion='$gestionDestino' and parrilla_especial.cod_ciclo='$cicloDestino' and numero_visita='$nroVisita'";
		$respDe=mysql_query($sqlDel);
		//echo $sqlDel."<br>";
		$sqlDel="delete from parrilla_detalle_especial where parrilla_detalle_especial.codigo_parrilla_especial in (
			select parrilla_especial.codigo_parrilla_especial from parrilla_especial where parrilla_especial.codigo_grupo_especial='$codigoGENew' and 
			parrilla_especial.codigo_gestion='$gestionDestino' and parrilla_especial.cod_ciclo='$cicloDestino' and numero_visita='$nroVisita')";
		$respDe=mysql_query($sqlDel);
		//echo $sqlDel."<br>";

		
		//insert
		$sqlCod="select max(p.codigo_parrilla_especial)+1 from parrilla_especial p";
		$respCod=mysql_query($sqlCod);
		$codigoNuevo=mysql_result($respCod,0,0);
		
		$sqlInsert="insert into parrilla_especial values('$codigoNuevo','$cicloDestino', '','$lineaDestino','$fechaActual',
			'$fechaActual','$nroVisita','$agenciaGENew', '$codigoGENew', 0, '$gestionDestino')";
		$respInsert=mysql_query($sqlInsert);
		//echo $sqlInsert."<br>";
		
		$sqlDet="select p.codigo_muestra, p.cantidad_muestra, p.codigo_material, p.cantidad_material, p.prioridad, 
		p.observaciones, p.extra from parrilla_detalle_especial p where p.codigo_parrilla_especial='$codParrilla'";
		$respDet=mysql_query($sqlDet);
		while($datDet=mysql_fetch_array($respDet)){
			$codMM=$datDet[0];
			$cantMM=$datDet[1];
			$codMat=$datDet[2];
			$cantMat=$datDet[3];
			$prioridad=$datDet[4];
			$obs=$datDet[5];
			$extra=$datDet[6];
			
			$sqlInsertDet="insert into parrilla_detalle_especial values('$codigoNuevo','$codMM','$cantMM','$codMat','$cantMat','$prioridad','$obs','$extra')";
			$respInsertDet=mysql_query($sqlInsertDet);
			//echo $sqlInsertDet."<br>";
		}
		
	}
	
	
}

echo "<h1 align='center'>Se replicaron las parrillas correctamente!.</h1>"


?>