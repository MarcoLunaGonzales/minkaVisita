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
	$sql_modi=mysql_query("update parrilla_especial
					set cod_ciclo='$p_ciclo',cod_especialidad='$p_especialidad',fecha_modificacion='$fecha_modificacion',numero_visita='$j_num_vis',codigo_grupo_especial='$j_grupoespe',muestras_extra='$j_muestras_extras'
							where codigo_parrilla_especial=$codigo_parrilla");

	//aqui sacamos el numero de elementos de la parrilla
	$sql_aux="select * from parrilla_detalle_especial where codigo_parrilla_especial=$codigo_parrilla";
	$resp_aux=mysql_query($sql_aux);
	$num_filas=mysql_num_rows($resp_aux);
	//echo "num_el $num_elementos filas $num_filas";

		for($i=0;$i<=$num_elementos-1;$i++)
		{	$sql="update parrilla_detalle_especial set
				codigo_muestra='$muestras[$i]',cantidad_muestra=$cantidades[$i],codigo_material='$apoyo[$i]',cantidad_material=$cantidadess[$i],observaciones='$observaciones[$i]',extra='$extras[$i]'
					where codigo_parrilla_especial=$codigo_parrilla and prioridad=$prioridad[$i]";
			$resp=mysql_query($sql);
		}

		if($num_filas>$num_elementos)
		{	for($j=$i+1;$j<=$num_filas;$j++)
			{	$sql_del="delete from parrilla_detalle_especial where codigo_parrilla_especial=$codigo_parrilla and prioridad='$j'";
				echo $sql_del.$j;
				$resp_del=mysql_query($sql_del);
			}
		}
		if($num_filas<$num_elementos)
		{	for($j=$num_filas;$j<=$num_elementos;$j++)
			{	$sql_insert="insert into parrilla_detalle_especial values($codigo_parrilla,'$muestras[$j]',$cantidades[$j],'$apoyo[$j]',$cantidadess[$j],$prioridad[$j],'$observaciones[$j]',$extras[$j])";
				echo $sql_insert;
				$resp_insert=mysql_query($sql_insert);
			}
		}
	echo "<script language='Javascript'>
			alert('Los datos se modificaron satisfactoriamente');
			location.href='navegador_parrilla_especial_ciclos.php?ciclo_trabajo=$p_ciclo';
		</script>";
?>