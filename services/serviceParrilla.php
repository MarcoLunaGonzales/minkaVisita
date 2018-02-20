<?php
	ini_set("memory_limit","512M");
	require("../conexion.inc");
	
	$sqlCiclo="select c.cod_ciclo, c.codigo_gestion from ciclos c where c.estado='Activo'";
	$respCiclo=mysql_query($sqlCiclo);
	$datCiclo=mysql_fetch_assoc($respCiclo);
	$cicloRev=$datCiclo["cod_ciclo"];
	$gestionRev=$datCiclo["codigo_gestion"];
	
	$cicloRev=6;
	
	/*$sql = "select p.cod_linea as linea_vis_id, p.cod_med as medico_id, p.numero_visita, p.orden_visita as orden_mm, p.cantidad_mm,
		p.cantidad_ma, p.cod_ciclo as ciclo, p.cod_gestion as gestion, p.cod_mm as muestra_medica, p.cod_ma as material_apoyo 
		from parrilla_personalizadareg p
		where p.cod_gestion='$gestionRev' and p.cod_ciclo='$cicloRev' and p.cod_med in (select m.cod_med from medicos m where 
		m.cod_ciudad in (113,116,122,124,102,120))";*/
		
		
	$sql = "select p.cod_linea as linea_vis_id, p.cod_med as medico_id, p.numero_visita, p.orden_visita as orden_mm, p.cantidad_mm,
		p.cantidad_ma, p.cod_ciclo as ciclo, p.cod_gestion as gestion, p.cod_mm as muestra_medica, p.cod_ma as material_apoyo 
		from parrilla_personalizadareg p
		where p.cod_gestion='$gestionRev' and p.cod_ciclo='$cicloRev' and p.cod_med in (select m.cod_med from medicos m where 
		m.cod_ciudad in (102))";
		
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
