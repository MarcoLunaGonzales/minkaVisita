<?php
require("conexion.inc");
require("estilos_regional_pri.inc");
$sql_medicos_visitador="select cod_med from medico_asignado_visitador
						where codigo_linea='$linea_importada' and codigo_visitador='$visitador_importado'";
						
						
$resp_medicos_visitador=mysql_query($sql_medicos_visitador);
while($dat_med_vis=mysql_fetch_array($resp_medicos_visitador))
{	$codigo_medico=$dat_med_vis[0];
	$sql_medicos_linea="select cod_especialidad, categoria_med from categorias_lineas
						where codigo_linea='$linea_importada' and cod_med='$codigo_medico'";
	
	//echo $sql_medicos_linea;
	
	$resp_medicos_linea=mysql_query($sql_medicos_linea);
	$dat_medicos_linea=mysql_fetch_array($resp_medicos_linea);
	$especialidad_medico=$dat_medicos_linea[0];
	$categoria_medico=$dat_medicos_linea[1];
	//insertamos en las tablas correspondientes
	if($global_linea!=$linea_importada)
	{	$insertar_lineas="insert into categorias_lineas values('$global_linea','$codigo_medico','$especialidad_medico','$categoria_medico',0,0)";
		
		//echo $insertar_lineas;
		
		$resp_insertar_lineas=mysql_query($insertar_lineas);
	}
	$insertar_medicos_asignados="insert into medico_asignado_visitador values('$codigo_medico','$visitador','$global_linea')";
	$resp_insertar_medicos_asignados=mysql_query($insertar_medicos_asignados);
}
	//aqui creamos un nuevo rutero maestro
	$sql_obtener_codigo_rutero_maestro="select max(cod_rutero) from rutero_maestro_cab";
	$resp_obtener_codigo=mysql_query($sql_obtener_codigo_rutero_maestro);
	$dat_obtener=mysql_fetch_array($resp_obtener_codigo);
	$codigo_rutero_maestro=$dat_obtener[0]+1;
	$sql_crear_rutero_maestro="insert into rutero_maestro_cab values('$codigo_rutero_maestro','RUTERO IMPORTADO','$visitador',0,'$global_linea','','','0000-00-00',0)";
	$resp_crear_rutero_maestro=mysql_query($sql_crear_rutero_maestro);
	//fin crear rutero maestro nuevo
	//aqui recuperamos el rutero
	$sql="select cod_contacto, dia_contacto, turno, zona_viaje from rutero_maestro where cod_rutero='$rutero_importado' and cod_visitador=$visitador_importado";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$cod_contacto=$dat[0];
		$dia_contacto=$dat[1];
		$turno=$dat[2];
		$zona_viaje=$dat[3];
		//sacamos el codigo que corresponde al contacto
		$sql_aux=mysql_query("select cod_contacto from rutero_maestro order by cod_contacto desc");
		$dat_aux=mysql_fetch_array($sql_aux);
		$codigo_contacto_actual=$dat_aux[0]+1;		
		$sql_inserta="insert into rutero_maestro values($codigo_contacto_actual,'$codigo_rutero_maestro','$visitador','$dia_contacto','$turno','$zona_viaje')";
		$resp_inserta=mysql_query($sql_inserta);
		$sql_det="select * from rutero_maestro_detalle where cod_contacto=$cod_contacto";
		$resp_det=mysql_query($sql_det);
		while($dat_det=mysql_fetch_array($resp_det))
		{	$orden_visita=$dat_det[1];
			$cod_med=$dat_det[3];
			$cod_espe=$dat_det[4];
			$cat_med=$dat_det[5];
			$cod_zona=$dat_det[6];
			$sql_inserta1="insert into rutero_maestro_detalle values($codigo_contacto_actual,$orden_visita,'$visitador',$cod_med,'$cod_espe','$cat_med','$cod_zona',0, 2)";
			$resp_inserta1=mysql_query($sql_inserta1);
		}
	}
	//fin recuperar rutero
	echo "<script language='Javascript'>
			alert('El rutero fue importado correctamente.');
			location.href='navegador_funcionarios_regional.php';
			</script>";
?>