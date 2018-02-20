<?php
require("conexion.inc");
require("estilos_visitador.inc");
$rutero_a_completar=$rutero;
$sql_rutero="select cod_contacto, cod_visitador, dia_contacto, turno, zona_viaje from rutero_maestro where cod_rutero='$rutero_a_completar'";
$resp_rutero=mysql_query($sql_rutero);
while($dat_rutero=mysql_fetch_array($resp_rutero))
{	
	$cod_contacto=$dat_rutero[0];
	$cod_visitador=$dat_rutero[1];
	$dia_contacto=$dat_rutero[2];
	$turno=$dat_rutero[3];
	$zona_viaje=$dat_rutero[4];
	$sql_detalle="select orden_visita, cod_visitador, cod_med, cod_especialidad, categoria_med, cod_zona, estado from rutero_maestro_detalle where cod_contacto='$cod_contacto'";
	$resp_detalle=mysql_query($sql_detalle);
		$sql_cod="select cod_contacto from rutero_maestro order by cod_contacto desc";
		$resp_cod=mysql_query($sql_cod);
		$num_filas=mysql_num_rows($resp_cod);
		if($num_filas==0)
		{	$cod_contacto_nuevo=1000;
		}
		else
		{	$dat_cod=mysql_fetch_array($resp_cod);
			$cod_contacto_nuevo=$dat_cod[0];
			$cod_contacto_nuevo=$cod_contacto_nuevo+1;
		}
		if($dia_contacto=="Lunes 1"){$dia_contacto="Lunes 3";}
		if($dia_contacto=="Martes 1"){$dia_contacto="Martes 3";}
		if($dia_contacto=="Miercoles 1"){$dia_contacto="Miercoles 3";}
		if($dia_contacto=="Jueves 1"){$dia_contacto="Jueves 3";}
		if($dia_contacto=="Viernes 1"){$dia_contacto="Viernes 3";}
		if($dia_contacto=="Lunes 2"){$dia_contacto="Lunes 4";}
		if($dia_contacto=="Martes 2"){$dia_contacto="Martes 4";}
		if($dia_contacto=="Miercoles 2"){$dia_contacto="Miercoles 4";}
		if($dia_contacto=="Jueves 2"){$dia_contacto="Jueves 4";}
		if($dia_contacto=="Viernes 2"){$dia_contacto="Viernes 4";}
		$sql_inserta="insert into rutero_maestro values('$cod_contacto_nuevo','$rutero_a_completar','$cod_visitador','$dia_contacto','$turno','$zona_viaje')";
		//echo $sql_inserta;
		$resp_inserta=mysql_query($sql_inserta);
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$orden_visita=$dat_detalle[0];
		$cod_med=$dat_detalle[2];
		$cod_espe=$dat_detalle[3];
		$cod_cat=$dat_detalle[4];
		$cod_zona=$dat_detalle[5];
		$estado=$dat_detalle[6];
		$sql_inserta_detalle="insert into rutero_maestro_detalle values('$cod_contacto_nuevo','$orden_visita','$cod_visitador','$cod_med','$cod_espe','$cod_cat','$cod_zona','$estado', 2)";	
		$resp_inserta_detalle=mysql_query($sql_inserta_detalle);
	}
}
echo "<script language='Javascript'>
			alert('El rutero se completo satisfactoriamente.');
			location.href='rutero_maestro_todo.php?rutero=$rutero_a_completar';
			</script>";
?>