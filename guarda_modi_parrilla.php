<?php
    require('estilos.inc');
	require('conexion.inc');
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
	//aqui insertamos en la tabla de parrillas
	$sql_modi=mysql_query("update parrilla 
					set cod_ciclo='$p_ciclo',cod_especialidad='$p_especialidad',categoria_med='$p_categoria',fecha_modificacion='$fecha_modificacion',numero_visita='$j_num_visita',agencia='$agencia',codigo_l_visita='$j_linea_visita',muestras_extra='$j_muestras_extras'
							where codigo_parrilla=$codigo_parrilla");
	
	$sql_delete_detalle=mysql_query("delete from parrilla_detalle where codigo_parrilla='$codigo_parrilla'");
	for($i=0;$i<=$num_elementos;$i++)
	{
		$sql="insert into parrilla_detalle values($codigo_parrilla,'$muestras[$i]',$cantidades[$i],'$apoyo[$i]',$cantidadess[$i],$prioridad[$i],'$observaciones[$i]','$extras[$i]')";
		$resp=mysql_query($sql);
	}
	/*//aqui sacamos el numero de elementos de la parrilla
	$sql_aux="select * from parrilla_detalle where codigo_parrilla=$codigo_parrilla";
	$resp_aux=mysql_query($sql_aux);
	$num_filas=mysql_num_rows($resp_aux);
	
	
		for($i=0;$i<=$num_elementos-1;$i++)
		{	echo $muestras[$i];
			$sql="update parrilla_detalle set
				codigo_muestra='$muestras[$i]',cantidad_muestra=$cantidades[$i],codigo_material='$apoyo[$i]',cantidad_material=$cantidadess[$i],observaciones='$observaciones[$i]'
					where codigo_parrilla=$codigo_parrilla and prioridad=$prioridad[$i] and extra=$extras[$i]";
			$resp=mysql_query($sql);
		}
		
		if($num_filas>$num_elementos)
		{	for($j=$i;$j<=$num_filas;$j++)
			{	$prioridad=$j+1;
				$sql_del="delete from parrilla_detalle where codigo_parrilla=$codigo_parrilla and prioridad=$prioridad";
				$resp_del=mysql_query($sql_del);
			}
		}
		if($num_filas<$num_elementos)
		{	for($j=$num_filas;$j<=$num_elementos;$j++)
			{	$prioridad=$j+1;
				$sql_insert="insert into parrilla_detalle values($codigo_parrilla,'$muestras[$j]',$cantidades[$j],'$apoyo[$j]',$cantidadess[$j],$prioridad,'$observaciones[$j]','$extras[$j]')";
				$resp_insert=mysql_query($sql_insert);
			}
		}
		*/
		echo "<script language='Javascript'>
			alert('Los datos se modificaron satisfactoriamente');
			location.href='navegador_parrillas_ciclos_detalle.php?cod_especialidad=$p_especialidad&ciclo_trabajo=$p_ciclo';
		</script>";
?>