<?php
	require('conexion.inc');
    require('estilos.inc');
	$fecha_modificacion=date("Y-m-d");
	$muestras=explode(",",$vec_muestras);
  	$cantidades=explode(",",$vec_cant);
  	$apoyo=explode(",",$vec_apoyo);
	$cantidadess=explode(",",$vec_cant_a);
	$observaciones=explode(",",$vector_obs);
	$prioridad=explode(",",$vector_priori);
	$extras=explode(",",$vector_extra);
	$num_elementos=sizeof($muestras);
    $p_ciclo=$j_ciclo;
	$p_especialidad=$j_especialidad;
	$p_categoria=$j_categoria;
	$agencia=$j_agencia;
	//aqui modificamos la tabla de parrillas
	//sacamos los codigos de las parrillas asociadas
	$grupoEspecial=$j_grupoespe;
	
	//$gestion=$global_gestion;
	$gestion=1014;
	
	//echo $grupoEspecial." ".$gestion." ".$p_ciclo;
	$sqlCodParrillas="select p.codigo_parrilla_especial FROM parrilla_especial p where p.codigo_grupo_especial in (
		select ge.codigo_grupo_especial from grupo_especial ge where ge.cod_muestra in (
		select g.cod_muestra from grupo_especial g where g.codigo_grupo_especial='$grupoEspecial')) 
		and p.cod_ciclo='$p_ciclo' and p.codigo_gestion='$gestion' and p.numero_visita='$j_num_vis'";
	
	//echo $sqlCodParrillas."<br>";
	
	$respCodParrillas=mysql_query($sqlCodParrillas);
	$txtCodigoP="0";
	while($datCodP=mysql_fetch_array($respCodParrillas)){
		$txtCodigoP=$txtCodigoP.",".$datCodP[0];
	}
	$sql_modi=mysql_query("update parrilla_especial
					set fecha_modificacion='$fecha_modificacion', numero_visita='$j_num_vis', muestras_extra='$j_muestras_extras'
							where codigo_parrilla_especial in ($txtCodigoP)");
	$sql_del="delete from parrilla_detalle_especial where codigo_parrilla_especial in ($txtCodigoP)";
	$resp_del=mysql_query($sql_del);
	
	for($j=0;$j<=$num_elementos-1;$j++){	
		$arrayTxtCodigo=explode(",", $txtCodigoP);
		$tamArray=sizeOf($arrayTxtCodigo);
		
		for($ii=1; $ii<=$tamArray-1; $ii++){
			$sql_insert="insert into parrilla_detalle_especial values
				($arrayTxtCodigo[$ii],'$muestras[$j]',$cantidades[$j],'$apoyo[$j]',$cantidadess[$j],$prioridad[$j],'$observaciones[$j]',$extras[$j])";
			$resp_insert=mysql_query($sql_insert);
		}	
	}
	
	//DESDE ACA CREAMOS LAS NUEVAS PARRILLAS
	$sqlNew="select ge.codigo_grupo_especial from grupo_especial ge where ge.cod_muestra  
		in (select g.cod_muestra from grupo_especial g where g.codigo_grupo_especial='$grupoEspecial')";
	$respNew=mysql_query($sqlNew);
	while($datNew=mysql_fetch_array($respNew)){
		$codGrupoNew=$datNew[0];
		//veri
		$sqlVeriNew="select p.codigo_parrilla_especial FROM parrilla_especial p where p.codigo_grupo_especial=$codGrupoNew 
			and p.cod_ciclo='$p_ciclo' and p.codigo_gestion='$gestion' and p.numero_visita='$j_num_vis'";
		$respVeriNew=mysql_query($sqlVeriNew);
		$numFilasVeriNew=mysql_num_rows($respVeriNew);
		if($numFilasVeriNew==0){
			//echo $codGrupoNew." GRUPO SIN PARRILLA<BR>";
			
			$sql_aux=mysql_query("select codigo_parrilla_especial from parrilla_especial order by codigo_parrilla_especial desc");
			$num_filas=mysql_num_rows($sql_aux);
			$dat_aux=mysql_fetch_array($sql_aux);
			$codigo_parrilla=$dat_aux[0];
			$codigo_parrilla++;
			
			$sql_inserta="insert into parrilla_especial values 
			($codigo_parrilla,'$p_ciclo','',$global_linea,'$fecha_modificacion','$fecha_modificacion','$j_num_vis','$j_agencia','$codGrupoNew','$j_muestras_extras','$gestion')";
			$resp_inserta=mysql_query($sql_inserta);
			
			//echo $sql_inserta."<br>";
			
			for($j=0;$j<=$num_elementos-1;$j++){									
				$sql_insert="insert into parrilla_detalle_especial values
					($codigo_parrilla,'$muestras[$j]',$cantidades[$j],'$apoyo[$j]',$cantidadess[$j],$prioridad[$j],'$observaciones[$j]',$extras[$j])";
				
				//echo $sql_insert."det<br>";
				
				$resp_insert=mysql_query($sql_insert);				
			}
			
		}
		
	}

	
	echo "<script language='Javascript'>
			alert('Se modificaron todas las parrillas asociadas a los Grupos Especiales');
			location.href='navegador_parrillas_especial_ciclos_grupos.php?ciclo_trabajo=$p_ciclo';
		</script>";
?>