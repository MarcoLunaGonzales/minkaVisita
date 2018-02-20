<?php
	header("Content-Type: text/html;charset=utf-8");
	
	function utf8json($inArray) { 

    static $depth = 0; 

    /* our return object */ 
    $newArray = array(); 

    /* safety recursion limit */ 
    $depth ++; 
    if($depth >= '1000000') { 
        return false; 
    } 

    /* step through inArray */ 
    foreach($inArray as $key=>$val) { 
        if(is_array($val)) { 
            /* recurse on array elements */ 
            $newArray[$key] = utf8json($val); 
        } else { 
            /* encode string values */ 
            $newArray[$key] = utf8_encode($val); 
        } 
    } 

    /* return utf8 encoded array */ 
    return $newArray; 
	}
	
	
	require("../conexion.inc");
	
	mysql_query("SET NAMES 'utf8'");
	
	$codRegional=$_GET['codRegional'];
	
	$sql = "select m.cod_med as codMedico, m.ap_pat_med as apPaternoMedico, m.ap_mat_med as apMaternoMedico, m.nom_med as nombreMedico, 
	m.cod_ciudad as codCiudad, m.cod_closeup as codCloseup, 
	(select e.cod_especialidad from especialidades_medicos e where e.cod_med=m.cod_med limit 0,1)as codEspecialidad,
	(select c.categoria_med from categorias_lineas c, lineas l where c.cod_med=m.cod_med and l.codigo_linea=c.codigo_linea and l.estado=1 limit 0,1)as categoriaMed,
	(select d.direccion from direcciones_medicos d where d.cod_med=m.cod_med limit 0,1)as direccion
	 from medicos m where m.cod_med<>0 and m.cod_ciudad in (select c.cod_ciudad from ciudades c where c.cod_area_empresa in ($codRegional))";
    $result = mysql_query($sql) or die("Error in Selecting. ");

    $emparray[] = array();
    while($row =mysql_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
    echo json_encode(utf8json($emparray));

    //close the db connection
    mysql_close($conexion);
?>
