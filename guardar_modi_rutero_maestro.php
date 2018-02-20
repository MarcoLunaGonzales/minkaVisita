<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005
*/
require("conexion.inc");
require("estilos_visitador.inc");
$vector=explode(",",$variables);
$num_elementos=sizeof($vector);
$dia_contacto=$vector[0];
$fecha=$vector[1];
$turno=$vector[2];
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$cod_contacto=$vector[$num_elementos-3];
$numero_contactos=$vector[3];
	$sql_aux="select cod_ciclo from ciclos where estado='activo'";
	$resp_aux=mysql_query($sql_aux);
	$dat=mysql_fetch_array($resp_aux);
	$cod_ciclo=$dat[0];
	//esto es para el codigo de los contactos
		$sql_upd="update rutero_maestro set dia_contacto='$dia_contacto', turno='$turno' where cod_contacto=$cod_contacto";
		$resp_upd=mysql_query($sql_upd);
	for($i=4;$i<=$num_elementos-5;$i=$i+5)
	{	$orden_visita=$vector[$i];
		$cod_med=$vector[$i+1];
		$cod_zona=$vector[$i+2];
		$cod_espe=$vector[$i+3];
		$cod_categoria=$vector[$i+4];
		//echo "numero $num_elementos vis $orden_visita med $cod_med zona $cod_zona espe $cod_espe categoria $cod_categoria";
		$sql="update rutero_maestro_detalle set orden_visita=$orden_visita, cod_med='$cod_med' ,cod_especialidad='$cod_espe', categoria_med='$cod_categoria', cod_zona='$cod_zona' where cod_contacto=$cod_contacto and orden_visita=$orden_visita";
		$resp=mysql_query($sql);
	}
	//sacamos el numero de contactos que se tiene
	echo $cod_contacto;
	$sql_contactos="select * from rutero_maestro_detalle where cod_contacto=$cod_contacto";
	$resp_contactos=mysql_query($sql_contactos);
	$numero_contactos_bd=mysql_num_rows($resp_contactos);
		if($numero_contactos<$numero_contactos_bd)
		{
			$entero=intval($i/5);
			$entero++;
			for($i=$entero;$i<=$numero_contactos_bd;$i++)
			{	$sql_del="delete from rutero_maestro_detalle where cod_contacto=$cod_contacto and orden_visita=$i";
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
				$sql="insert into rutero_maestro_detalle values($cod_contacto ,'$i' , '$global_visitador' , '$cod_med' , '$cod_espe' , '$cod_categoria' , '$cod_zona' ,0, 2)";
				$resp=mysql_query($sql);
			}
		}
	echo "<script language='Javascript'>
				alert('Los datos se modificaron satisfactoriamente');
				location.href='rutero_maestro_todo.php?rutero=$rutero';
			</script>";
?>