<?php

//set_time_limit(0);
//ini_set('post_max_size', '128M'); 
require("conexion.inc");
//require("estilos_gerencia.inc");

$json=file_get_contents('php://input');
$json=substr($json,7);

//echo $json;

$json = json_decode(utf8_encode($json));

$indice=1;
foreach($json as $obj){
	$esp=$obj->esp;
	$cat=$obj->cat;
	$vis=$obj->vis;
	$med=$obj->med;
	$linea=$obj->linea;
	
	//echo "$med $esp $cat $linea $indice <br>";
	$indice++;
	
	$v_medico=$med;
	$v_especialidad=$esp;
	$v_categoria=$cat;
	$v_visitador=$vis;
	$v_linea=$linea;
	

	$frecuenciaMedico=0;
	
	
	//echo "$v_medico $v_especialidad $v_categoria $v_visitador $v_linea <br>";
	
	
	//
	if($v_visitador!=0){
		
		$sqlDel="delete from categorias_lineas where codigo_linea='$v_linea' and cod_med='$v_medico'";
		//echo $sqlDel."<br>";
		$respDel=mysql_query($sqlDel);
		
		$sqlDel="delete from medico_asignado_visitador where cod_med='$v_medico' and codigo_linea='$v_linea'";
		//echo $sqlDel."<br>";
		$respDel=mysql_query($sqlDel);

		
		$sql_inserta="insert into categorias_lineas values($v_linea,$v_medico,'$v_especialidad','$v_categoria',$frecuenciaMedico, $frecuenciaMedico)";
		
		//echo $sql_inserta."<br>";
		
		$resp_inserta=mysql_query($sql_inserta);
		
		$seleccion_medicos_rutero="select rmd.cod_contacto, rmd.orden_visita from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd
							where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and
							rmc.codigo_linea='$global_linea' and rmd.cod_med='$v_medico'";
		$resp_medicos_rutero=mysql_query($seleccion_medicos_rutero);
		while($datos_medicos=mysql_fetch_array($resp_medicos_rutero))
		{	$cod_contacto=$datos_medicos[0];
			$orden_visita=$datos_medicos[1];
			$sql_actualiza_rutero="update rutero_maestro_detalle set cod_especialidad='$v_especialidad',categoria_med='$v_categoria'
								   where cod_contacto='$cod_contacto' and orden_visita='$orden_visita'";
			$resp_actualiza_rutero=mysql_query($sql_actualiza_rutero);
		}
				
		$sql_inserta="insert into medico_asignado_visitador values('$v_medico','$v_visitador','$v_linea')";
		
		//echo $sql_inserta."<br>";
		$resp_inserta=mysql_query($sql_inserta);
	}
	if($v_visitador==0){
		$sql_inserta="delete from categorias_lineas where cod_med='$v_medico' and codigo_linea='$v_linea'";
		
		//echo $sql_inserta."<br>";
		
		
		$resp_inserta=mysql_query($sql_inserta);
		
		$seleccion_medicos_rutero="select rmd.cod_contacto, rmd.orden_visita from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd
							where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and
							rmc.codigo_linea='$global_linea' and rmd.cod_med='$v_medico'";
		$resp_medicos_rutero=mysql_query($seleccion_medicos_rutero);
		while($datos_medicos=mysql_fetch_array($resp_medicos_rutero))
		{	$cod_contacto=$datos_medicos[0];
			$orden_visita=$datos_medicos[1];
			$sql_actualiza_rutero="delete rutero_maestro_detalle where cod_contacto='$cod_contacto' and orden_visita='$orden_visita'";
			$resp_actualiza_rutero=mysql_query($sql_actualiza_rutero);
		}
		
		$sql_inserta="delete from medico_asignado_visitador where cod_med='$v_medico' and codigo_linea='$v_linea'";
		
		//echo $sql_inserta."<br>";
		
		$resp_inserta=mysql_query($sql_inserta);
	}
}
//echo "LOS DATOS SE GUARDARON CORRECTAMENTE!";
?>