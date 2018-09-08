<?php
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
	
	
	require("../conexionInicial.inc");
	
	
	$sql = "select f.codigo_funcionario as codPersonal, 
		(select ca.cod_cargo from cargos ca where ca.cod_cargo=f.cod_cargo) as codCargo,
		(select u.nombre_usuario from usuarios_sistema u where u.codigo_funcionario=f.codigo_funcionario) as nombreUsuario, 
		(select u.contrasena from usuarios_sistema u where u.codigo_funcionario=f.codigo_funcionario) as contraseniaUsuario,
		f.nombres as nombresPersonal, f.paterno as apPaternoPersonal, f.materno as apMaternoPersonal,
		f.cod_ciudad as codAreaEmpresa, 
		c.descripcion as nombreAreaEmpresa 
		from funcionarios f, ciudades c where 
		f.cod_ciudad=c.cod_ciudad and f.estado=1";
    $result = mysql_query($sql) or die("Error in Selecting. ");

    $emparray[] = array();
    while($row =mysql_fetch_assoc($result))
    {
        $emparray[] = $row;
    }
	
	array_splice($emparray, 0,1);
	
    echo json_encode(utf8json($emparray));

    //close the db connection
    mysql_close($conexion);
?>
