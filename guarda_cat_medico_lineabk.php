<?php

//set_time_limit(0);
//ini_set('post_max_size', '128M'); 
require("conexion.inc");
require("estilos_gerencia.inc");

/*while ($post = each($_POST))
{
echo $post[0] . " = " . $post[1];
}*/


$cat="cat";
$med="med";
$vis="func";
$perfil="perfil";
$lin="linea";

$cantidad=$_GET['cantidad'];
//echo $cantidad;

for($i=0;$i<=$cantidad-1;$i++)
{
	//echo "entro1";
	$medico="$med$i";
	$especialidad_med="especialidad_med$i";
	$categoria="$cat$i";
	$visitador="$vis$i";
	$linea="$lin$i";
	
	$v_medico=$$medico;
	$v_especialidad=$$especialidad_med;
	$v_categoria=$$categoria;
	$v_visitador=$$visitador;
	$v_linea=$$linea;
	

	$frecuenciaMedico=0;
	
	echo "$v_medico $v_especialidad $v_categoria $v_visitador $v_linea <br>";
	//
	if($v_visitador!=0){
		
		$sqlDel="delete from categorias_lineas where codigo_linea='$v_linea' and cod_med='$v_medico'";
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
		
		$sqlDel="delete from medico_asignado_visitador where cod_med='$v_medico' and codigo_linea='$v_linea'";
		$respDel=mysql_query($sqlDel);
		
		$sql_inserta="insert into medico_asignado_visitador values('$v_medico','$v_visitador','$v_linea')";
		//echo $sql_inserta."<br>";
		$resp_inserta=mysql_query($sql_inserta);
	}
	if($v_visitador==0){
		$sql_inserta="delete from categorias_lineas where cod_med='$v_medico' and codigo_linea='$v_linea'";
		//echo "delete ".$sql_inserta;
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
		//echo "borra registro: ".$sql_inserta;
		$resp_inserta=mysql_query($sql_inserta);
	}
}
if($cantidad>0){
	echo "<script language='Javascript'>
			alert('Los datos se registraron satisfactoriamente');
			location.href='medicosCiudadesGeneral.php';
		</script>";
}else{
	echo "<center>Ocurrio un problema en la transacción por favor comunicarse con el administrador de sistema.</center>";
}


?>