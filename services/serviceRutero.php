<?php
	ini_set("memory_limit","512M");
	
	require("../conexion.inc");
	
	$sqlCiclo="select c.cod_ciclo, c.codigo_gestion from ciclos c where c.estado='Activo'";
	$respCiclo=mysql_query($sqlCiclo);
	$datCiclo=mysql_fetch_assoc($respCiclo);
	$cicloRev=$datCiclo["cod_ciclo"];
	$gestionRev=$datCiclo["codigo_gestion"];
	
	$cicloRev=3;

	$sql = "select r.codigo_linea as linea_vis, r.codigo_ciclo as ciclo, r.codigo_gestion as gestion, rd.cod_especialidad as especialidad,
		rd.categoria_med as categoria, 
		(select c.cod_ciudad from funcionarios f, ciudades c where f.cod_ciudad=c.cod_ciudad and f.codigo_funcionario=r.cod_visitador) as ciudad_id,
		rd.cod_med as medico_id, r.cod_visitador as usuario_id
		 from rutero_maestro_cab_aprobado r, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
		where r.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and r.codigo_gestion='$gestionRev' and r.codigo_ciclo='$cicloRev' 
		and r.cod_visitador in (select codigo_funcionario from funcionarios where cod_ciudad in (102))
		GROUP BY r.cod_visitador, rd.cod_med";
		
    $result = mysql_query($sql) or die("Error in Selecting. ");

    $emparray[] = array();
    while($row =mysql_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
    echo json_encode($emparray);

    //close the db connection
    mysql_close($conexion);
	
?>
