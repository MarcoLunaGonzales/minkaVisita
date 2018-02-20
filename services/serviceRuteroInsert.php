<?php
	ini_set("memory_limit","512M");
	
	require("../conexion.inc");
	require("conexionCRM.inc");
	
	$sqlCiclo="select c.cod_ciclo, c.codigo_gestion from ciclos c where c.estado='Activo'";
	$respCiclo=mysql_query($sqlCiclo,$conexion);
	$datCiclo=mysql_fetch_assoc($respCiclo);
	$cicloRev=$datCiclo["cod_ciclo"];
	$gestionRev=$datCiclo["codigo_gestion"];
	
	$cicloRev=3;

	/*$sql = "select r.codigo_linea as linea_vis, r.codigo_ciclo as ciclo, r.codigo_gestion as gestion, rd.cod_especialidad as especialidad,
		rd.categoria_med as categoria, 
		(select c.cod_ciudad from funcionarios f, ciudades c where f.cod_ciudad=c.cod_ciudad and f.codigo_funcionario=r.cod_visitador) as ciudad_id,
		rd.cod_med as medico_id, r.cod_visitador as usuario_id
		 from rutero_maestro_cab_aprobado r, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
		where r.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and r.codigo_gestion='$gestionRev' and r.codigo_ciclo='$cicloRev' 
		and r.cod_visitador in (select codigo_funcionario from funcionarios where cod_ciudad in (102,114,113,104,119,120,116,109,118,122,124))
		and r.cod_visitador not in (1381, 1383, 1132, 1409, 1266, 1423, )
		GROUP BY r.cod_visitador, rd.cod_med";
	*/	
	
	$sql = "select r.codigo_linea as linea_vis, r.codigo_ciclo as ciclo, r.codigo_gestion as gestion, rd.cod_especialidad as especialidad,
		rd.categoria_med as categoria, 
		(select c.cod_ciudad from funcionarios f, ciudades c where f.cod_ciudad=c.cod_ciudad and f.codigo_funcionario=r.cod_visitador) as ciudad_id,
		rd.cod_med as medico_id, r.cod_visitador as usuario_id
		 from rutero_maestro_cab_aprobado r, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
		where r.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and r.codigo_gestion='$gestionRev' and r.codigo_ciclo='$cicloRev' 
		and r.cod_visitador not in (1381, 1383, 1132, 1409, 1266, 1349, 1413, 1372, 1389, 1415, 1420,1423,1433,1435,1431,1432)
		GROUP BY r.cod_visitador, rd.cod_med";
		
    $result = mysql_query($sql,$conexion) or die("Error in Selecting. ");
	$indice=300000;
    while($row =mysql_fetch_array($result))
    {	
		$linea=$row[0];
		$ciclo=$row[1];
		$gestion=$row[2];
		$codEspe=$row[3];
		$codCat=$row[4];
		$ciudad=$row[5];
		$codMed=$row[6];
		$codVis=$row[7];
		
		//echo "$linea $ciclo $gestion $codEspe $codCat $ciudad $codMed $codVis";
		
		$sqlInsert="insert into rutero (id, linea_mkt, linea_vis, ciclo, gestion, especialidad, categoria, ciudad_id, medico_id, usuario_id) 
		 values($indice, 1, $linea, $ciclo, '2017', '$codEspe','$codCat','$ciudad','$codMed',(select id from usuario where codhermes='$codVis' limit 0,1))";
		echo $sqlInsert."<br>";
		$respInsert=mysql_query($sqlInsert, $conexionCRM) or die ("ERROR EN INSERT;");
		
		$indice++;
    }

    //close the db connection
    mysql_close($conexion);
	mysql_close($conexionCRM);
	
	echo "**************FIN**********";
	
?>
