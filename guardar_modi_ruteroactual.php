<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2006
*/
require("conexion.inc");
require("estilos_visitador.inc");
$vector=explode(",",$variables);
$num_elementos=sizeof($vector);
$dia_contacto=$vector[0];
$fecha=$vector[1];
$turno=$vector[2];
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$cod_contacto=$vector[$num_elementos-2];
$numero_contactos=$vector[3];
	//esto es para el codigo de los contactos
	for($i=4;$i<=$num_elementos-5;$i=$i+5)
	{	$orden_visita=$vector[$i];
		$cod_med=$vector[$i+1];
		$cod_zona=$vector[$i+2];
		$cod_espe=$vector[$i+3];
		$cod_categoria=$vector[$i+4];
		//echo "numero $num_elementos vis $orden_visita med $cod_med zona $cod_zona espe $cod_espe categoria $cod_categoria";
		$sql="update rutero_detalle set orden_visita=$orden_visita, cod_med='$cod_med' ,cod_especialidad='$cod_espe', categoria_med='$cod_categoria', cod_zona='$cod_zona' where cod_contacto=$cod_contacto and orden_visita=$orden_visita";
		$resp=mysql_query($sql);
	}
	//sacamos el numero de contactos que se tiene
	//echo $cod_contacto;
	$sql_contactos="select * from rutero_detalle where cod_contacto=$cod_contacto";
	$resp_contactos=mysql_query($sql_contactos);
	$numero_contactos_bd=mysql_num_rows($resp_contactos);
		if($numero_contactos<$numero_contactos_bd)
		{
			$entero=intval($i/5);
			$entero++;
			for($i=$entero;$i<=$numero_contactos_bd;$i++)
			{	$sql_del="delete from rutero_detalle where cod_contacto=$cod_contacto and orden_visita=$i";
				$resp_del=mysql_query($sql_del);
			}
		}
		if($numero_contactos>$numero_contactos_bd)
		{	$entero=intval($i/5);
			for($i=$numero_contactos_bd+1;$i<=$numero_contactos;$i++)
			{	
				$indice=($i*5)-1;
				$orden_visita=$vector[$indice];
				$cod_med=$vector[$indice+1];
				$cod_zona=$vector[$indice+2];
				$cod_espe=$vector[$indice+3];
				$cod_categoria=$vector[$indice+4];
				$sql="insert into rutero_detalle values($cod_contacto ,'$i' , '$global_visitador' , '$cod_med' , '$cod_espe' , '$cod_categoria' , '$cod_zona' ,0)";
				$resp=mysql_query($sql);
			}
		}
	echo "<script language='Javascript'>
				alert('Los datos del rutero actual se modificaron satisfactoriamente.');
				location.href='registrar_visita_medica.php?dia_registro=$dia_contacto&cod_ciclo=$ciclo_global';
			</script>";
?>