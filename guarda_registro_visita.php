<?php
	require("conexion.inc");
	require("estilos_visitador_sincab.inc");	
	require("function_formatofecha.php");
	$vector_constancia=explode(",",$constancia);
	$vector_muestras=explode(",",$muestras);
	$vector_material=explode(",",$material);
	$vector_cantidad_muestras=explode(",",$cantidad_muestras);
	$vector_cantidad_apoyo=explode(",",$cantidad_apoyo);
	$vector_prod_extra=explode(",",$prod_extra);
	$vector_mat_extra=explode(",",$mat_extra);
	$vector_cant_ent_extra=explode(",",$cant_entregada_extra);
	$vector_cant_ent_apoyo_extra=explode(",",$cant_entregada_apoyo_extra);
	$vector_cantidad_extraentregada=explode(",",$cantidad_extraentregada);
	$vector_cantidad_extraapoyo=explode(",",$cantidad_extraapoyo);
	$vector_obs=explode(",",$obs);
	$vector=explode("-",$valores);
	$contacto=$vector[0];
	$orden_visita=$vector[1];
	$parrilla=$vector[2];
	$num_elementos=$vector[3]-1;
	$fecha_registro=date("Y-m-d");
	$hora_registro=date("H:i:s");

	$fechaVisitaReal=$_GET['fechaVisitaReal'];
	$fechaVisitaReal=cambia_formatofecha($fechaVisitaReal);

	for($j=0;$j<=$num_elementos;$j++)
	{
		$prioridad=$j+1;
		$valor_muestra=$vector_muestras[$j];
		$valor_cant_muestra=$vector_cantidad_muestras[$j];
		$valor_apoyo=$vector_material[$j];
		$valor_cant_material=$vector_cantidad_apoyo[$j];
		$valor_cantidad_extraentregada=$vector_cantidad_extraentregada[$j];
		$valor_cantidad_extraapoyo=$vector_cantidad_extraapoyo[$j];
		$valor_obs=$vector_obs[$j];
		
		if($valor_cantidad_extraapoyo=="")
		{	$valor_cantidad_extraapoyo=0;	}
		if($valor_cantidad_extraentregada=="")
		{	$valor_cantidad_extraentregada=0;	}
		if($vector_constancia[$j]==1)
		{	$sql="insert into registro_visita values($ciclo_global,$codigo_gestion,$contacto,$orden_visita,$parrilla,$prioridad,'$valor_muestra',$valor_cant_muestra,$valor_cantidad_extraentregada,$valor_apoyo,$valor_cant_material,$valor_cantidad_extraapoyo,0,'$fecha_registro','$hora_registro','$valor_obs','$fechaVisitaReal')";
			//echo $sql;
			$resp=mysql_query($sql);
		}
		$sql_upd="update rutero_detalle set estado=1 where cod_contacto=$contacto and orden_visita=$orden_visita";
		$resp_upd=mysql_query($sql_upd);
	}
	for($j=0;$j<=$num_extras;$j++)
	{
		$prioridad=$j+$num_elementos+1;
		$valor_muestra=$vector_prod_extra[$j];
		$valor_cant_muestra=$vector_cant_ent_extra[$j];
		$valor_apoyo=$vector_mat_extra[$j];
		$valor_cant_material=$vector_cant_ent_apoyo_extra[$j];
		if($vector_constancia[$j]==1)
		{	$sql="insert into registro_visita values($ciclo_global,$codigo_gestion,$contacto,$orden_visita,$parrilla,$prioridad,'$valor_muestra',$valor_cant_muestra,0,$valor_apoyo,$valor_cant_material,0,1,'$fecha_registro','$hora_registro','$valor_obs', '$fechaVisitaReal')";
			//echo $sql;
			$resp=mysql_query($sql);
		}
	}
	echo "<script language='Javascript'>
			alert('Los datos se registraron satisfactoriamente');
			opener.location.reload();
			window.close();
		</script>";
	
?>