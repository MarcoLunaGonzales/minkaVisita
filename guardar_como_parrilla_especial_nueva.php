<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	require('conexion.inc');
	require('estilos.inc');
	$fecha_creacion=date("Y-m-d");
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
	$categoria=explode(",",$vec_categoria);
	$num_categorias=sizeof($categoria);
	//aqui insertamos en la tabla de parrillas
	$sql_aux=mysql_query("select codigo_parrilla_especial from parrilla_especial order by codigo_parrilla_especial desc");
	$num_filas=mysql_num_rows($sql_aux);
	if($num_filas==0)
	{	$codigo_parrilla=1000;
	}
	else
	{
		$dat_aux=mysql_fetch_array($sql_aux);
		$codigo_parrilla=$dat_aux[0];
		$codigo_parrilla++;
	}
		$sql_inserta="insert into parrilla_especial values ($codigo_parrilla,'$p_ciclo','$p_especialidad',$global_linea,'$fecha_creacion','$fecha_creacion','$j_num_vis','$j_agencia','$j_grupoespe','$j_muestras_extras','$codigo_gestion')";
		$resp_inserta=mysql_query($sql_inserta);
		echo $sql_inserta;
		for($i=0;$i<=$num_elementos;$i++)
		{
			$sql="insert into parrilla_detalle_especial values($codigo_parrilla,'$muestras[$i]',$cantidades[$i],'$apoyo[$i]',$cantidadess[$i],$prioridad[$i],'$observaciones[$i]',$extras[$i])";
			$resp=mysql_query($sql);
		}

	if($resp_inserta==1)
	{
	 		echo "<script language='Javascript'>
			alert('Los datos se registraron satisfactoriamente');
			location.href='navegador_parrilla_especial_ciclos.php?ciclo_trabajo=$p_ciclo';
			</script>";
	}
	else
	{
	  	echo "<script language='Javascript'>
			alert('No puede insertar una parrilla duplicada');
			history.back(-1);
			</script>";
	}
?>