<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	require('estilos.inc');
	require('conexion.inc');
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
	$p_categoria=$j_categoria;
	
	$codigo_gestion=1011;
	
	if($p_categoria!="")
	{
		//aqui insertamos en la tabla de parrillas
		$sql_aux=mysql_query("select codigo_parrilla from parrilla order by codigo_parrilla desc");
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
		$sql_inserta=mysql_query("insert into parrilla values ($codigo_parrilla,'$p_ciclo','$p_especialidad','$p_categoria',$global_linea,'$fecha_creacion','$fecha_creacion','$j_num_visita','$j_agencia','$j_linea_visita','$j_muestras_extras','$codigo_gestion', '0')");
		for($i=0;$i<=$num_elementos;$i++)
		{
			$sql="insert into parrilla_detalle values($codigo_parrilla,'$muestras[$i]',$cantidades[$i],'$apoyo[$i]',$cantidadess[$i],$prioridad[$i],'$observaciones[$i]','$extras[$i]')";
			$resp=mysql_query($sql);
		}
	}
	if($sql_inserta==1)
	{
	 	echo "<script language='Javascript'>
			alert('Los datos se registraron satisfactoriamente');
			location.href='navegador_parrillas_ciclos_detalle.php?codTerritorio=$j_agencia&ciclo_trabajo=$p_ciclo&gestion_trabajo=$codigo_gestion';
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