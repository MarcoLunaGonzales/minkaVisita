<?php
	ini_set("memory_limit","512M");
	require("../conexion.inc");
	require("conexionCRM.inc");
	
	$sqlCiclo="select c.cod_ciclo, c.codigo_gestion from ciclos c where c.estado='Activo'";
	$respCiclo=mysql_query($sqlCiclo, $conexion);
	$datCiclo=mysql_fetch_assoc($respCiclo);
	$cicloRev=$datCiclo["cod_ciclo"];
	$gestionRev=$datCiclo["codigo_gestion"];
	
	$cicloRev=3;
	
	/*$sql = "select p.cod_linea as linea_vis_id, p.cod_med as medico_id, p.numero_visita, p.orden_visita as orden_mm, p.cantidad_mm,
		p.cantidad_ma, p.cod_ciclo as ciclo, p.cod_gestion as gestion, p.cod_mm as muestra_medica, p.cod_ma as material_apoyo 
		from parrilla_personalizadareg p
		where p.cod_gestion='$gestionRev' and p.cod_ciclo='$cicloRev' and p.cod_med in (select m.cod_med from medicos m where 
		m.cod_ciudad in (113,116,122,124,102,120))";*/
		
		
	/*
	$sql = "select p.cod_linea as linea_vis_id, p.cod_med as medico_id, p.numero_visita, p.orden_visita as orden_mm, p.cantidad_mm,
		p.cantidad_ma, p.cod_ciclo as ciclo, p.cod_gestion as gestion, p.cod_mm as muestra_medica, p.cod_ma as material_apoyo 
		from parrilla_personalizadareg p
		where p.cod_gestion='$gestionRev' and p.cod_ciclo='$cicloRev' 
		and p.cod_med in (select m.cod_med from medicos m where 
		m.cod_ciudad in (102,114,113,104,119,120,116,109,118,122,124))";
		*/
		
	$sql = "select p.cod_linea as linea_vis_id, p.cod_med as medico_id, p.numero_visita, p.orden_visita as orden_mm, p.cantidad_mm,
		p.cantidad_ma, p.cod_ciclo as ciclo, p.cod_gestion as gestion, p.cod_mm as muestra_medica, p.cod_ma as material_apoyo 
		from parrilla_personalizadareg p
		where p.cod_gestion='$gestionRev' and p.cod_ciclo='$cicloRev' 
		and p.cod_med not in (select m.cod_med from medicos m where 
		m.cod_ciudad in (117))";	
		
    $result = mysql_query($sql, $conexion) or die("Error in Selecting. ");

	$indice=600000;
    while($row =mysql_fetch_array($result))
    {
        $linea=$row[0];
		$codMed=$row[1];
		$numVis=$row[2];
		$ordenVis=$row[3];
		$cantidadMM=$row[4];
		$cantidadMA=$row[5];
		$codCiclo=$row[6];
		$codGestion=$row[7];
		$codMM=$row[8];
		$codMA=$row[9];
		
		$sqlInsert="insert into parrilla (id, linea_vis_id, medico_id, numero_visita, orden_mm, cantidad_mm, cantidad_ma, 
		ciclo, gestion, muestra_medica, material_apoyo) 
		values ($indice, $linea, $codMed, $numVis, $ordenVis, $cantidadMM, $cantidadMA, $codCiclo, '2017', '$codMM', '$codMA')";
		echo $sqlInsert.";<br>";
		//$respInsert=mysql_query($sqlInsert, $conexionCRM) or die ("ERROR EN INSERCION");
		
		$indice++;
	}

    //close the db connection
    mysql_close($conexion);
	
?>
