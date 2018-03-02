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
	
	$numeroVisitadores=count($vis);
	$cadenaVisitadores=implode(",",$vis);
	
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
	
	
	//BORRAMOS LOS RUTEROS DONDE SE ENCUENTRE DE LOS VISITADORES QUE ESTAMOS SACANDO
	if($numeroVisitadores>0){
		$sqlVeriBorrar="select codigo_visitador from medico_asignado_visitador where cod_med='$v_medico' and codigo_linea='$v_linea' and 
		codigo_visitador not in ($cadenaVisitadores)";
		$respVeriBorrar=mysql_query($sqlVeriBorrar);
		while($datVeriBorrar=mysql_fetch_array($respVeriBorrar)){
			$codVisBorrarRutero=$datVeriBorrar[0];
			
			$seleccion_medicos_rutero="select rmd.cod_contacto, rmd.orden_visita from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd
								where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and 
								rm.cod_visitador=rmd.cod_visitador and rmc.cod_visitador=rm.cod_visitador and
								rmc.codigo_linea='$v_linea' and rmd.cod_med='$v_medico' and rm.cod_visitador='$codVisBorrarRutero'";
			//echo $seleccion_medicos_rutero;
			$resp_medicos_rutero=mysql_query($seleccion_medicos_rutero);
			while($datos_medicos=mysql_fetch_array($resp_medicos_rutero))
			{	$cod_contacto=$datos_medicos[0];
				$orden_visita=$datos_medicos[1];
				$sql_actualiza_rutero="delete from rutero_maestro_detalle where cod_contacto='$cod_contacto' and cod_med='$v_medico'";
				//echo $sql_actualiza_rutero." -- ";
				$resp_actualiza_rutero=mysql_query($sql_actualiza_rutero);
				
				//ACA REORDENAMOS EL CONTACTO
				$sqlOrdena="select orden_visita, cod_med from rutero_maestro_detalle where cod_contacto='$cod_contacto'
				 order by orden_visita";		
				//echo $sqlOrdena;
				$respOrdena=mysql_query($sqlOrdena);
				$contadorOrdena=1;
				while($datOrdena=mysql_fetch_array($respOrdena)){
					$ordenVisitaX=$datOrdena[0];
					$codMedX=$datOrdena[1];
					//aqui ejecutamos el update de reordenamiento
					$sqlUpdOrdena="update rutero_maestro_detalle set orden_visita='$contadorOrdena' where 
						cod_med='$codMedX' and cod_contacto='$cod_contacto'";
					$respUpdOrdena=mysql_query($sqlUpdOrdena);
					$contadorOrdena++;
				}
				//FIN REORDENAR CONTACTO
			}
		}
	}
	//FIN BORRAR
	
	if($numeroVisitadores>0){
		
		$sqlDel="delete from categorias_lineas where codigo_linea='$v_linea' and cod_med='$v_medico'";
		//echo $sqlDel."<br>";
		$respDel=mysql_query($sqlDel);
		
		$sqlDel="delete from medico_asignado_visitador where cod_med='$v_medico' and codigo_linea='$v_linea'";
		//echo $sqlDel."<br>";
		$respDel=mysql_query($sqlDel);

		
		$sql_inserta="insert into categorias_lineas values($v_linea,$v_medico,'$v_especialidad','$v_categoria',$frecuenciaMedico, $frecuenciaMedico)";
		$resp_inserta=mysql_query($sql_inserta);
		
		$seleccion_medicos_rutero="select rmd.cod_contacto, rmd.orden_visita from rutero_maestro_cab rmc, rutero_maestro rm, 
							rutero_maestro_detalle rmd
							where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and
							rm.cod_visitador=rmd.cod_visitador and rmc.cod_visitador=rm.cod_visitador and
							rmc.codigo_linea='$global_linea' and rmd.cod_med='$v_medico'";
		$resp_medicos_rutero=mysql_query($seleccion_medicos_rutero);
		while($datos_medicos=mysql_fetch_array($resp_medicos_rutero))
		{	$cod_contacto=$datos_medicos[0];
			$orden_visita=$datos_medicos[1];
			$sql_actualiza_rutero="update rutero_maestro_detalle set cod_especialidad='$v_especialidad',categoria_med='$v_categoria'
								   where cod_contacto='$cod_contacto' and cod_med='$v_medico'";
			$resp_actualiza_rutero=mysql_query($sql_actualiza_rutero);
		}
		
		for($jj=0; $jj<$numeroVisitadores;$jj++){
			$codVisitadorXX=$vis[$jj];
			$sql_inserta="insert into medico_asignado_visitador values('$v_medico','$codVisitadorXX','$v_linea')";
			$resp_inserta=mysql_query($sql_inserta);	
		}

	}
	if($numeroVisitadores==0){
		$sql_inserta="delete from categorias_lineas where cod_med='$v_medico' and codigo_linea='$v_linea'";
		$resp_inserta=mysql_query($sql_inserta);
		
		$seleccion_medicos_rutero="select rmd.cod_contacto, rmd.orden_visita from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd
							where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and 
							rm.cod_visitador=rmd.cod_visitador and rmc.cod_visitador=rm.cod_visitador and
							rmc.codigo_linea='$global_linea' and rmd.cod_med='$v_medico'";
		$resp_medicos_rutero=mysql_query($seleccion_medicos_rutero);
		while($datos_medicos=mysql_fetch_array($resp_medicos_rutero))
		{	$cod_contacto=$datos_medicos[0];
			$orden_visita=$datos_medicos[1];
			$sql_actualiza_rutero="delete from rutero_maestro_detalle where cod_contacto='$cod_contacto' and cod_med='$v_medico'";
			$resp_actualiza_rutero=mysql_query($sql_actualiza_rutero);
			
			//ACA REORDENAMOS EL CONTACTO
			$sqlOrdena="select orden_visita, cod_med from rutero_maestro_detalle where cod_contacto='$cod_contacto'
			 order by orden_visita";		
			//echo $sqlOrdena;
			$respOrdena=mysql_query($sqlOrdena);
			$contadorOrdena=1;
			while($datOrdena=mysql_fetch_array($respOrdena)){
				$ordenVisitaX=$datOrdena[0];
				$codMedX=$datOrdena[1];
				//aqui ejecutamos el update de reordenamiento
				$sqlUpdOrdena="update rutero_maestro_detalle set orden_visita='$contadorOrdena' where 
					cod_med='$codMedX' and cod_contacto='$codigo_contacto_actual'";
				$respUpdOrdena=mysql_query($sqlUpdOrdena);
				$contadorOrdena++;
			}
			//FIN REORDENAR CONTACTO
		}
		
		$sql_inserta="delete from medico_asignado_visitador where cod_med='$v_medico' and codigo_linea='$v_linea'";
		$resp_inserta=mysql_query($sql_inserta);
	}
}
//echo "LOS DATOS SE GUARDARON CORRECTAMENTE!";
?>