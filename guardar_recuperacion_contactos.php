<?php
require("conexion.inc");
require("estilos_visitador.inc");
$sql="SELECT cod_contacto, dia_contacto, turno, zona_viaje from rutero_maestro where cod_rutero='$rutero_rec' and cod_visitador=$global_visitador";
echo $sql;
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)) {	
	$cod_contacto=$dat[0];
	$dia_contacto=$dat[1];
	$turno=$dat[2];
	$zona_viaje=$dat[3];
		//sacamos el codigo que corresponde al contacto
	$sql_aux=mysql_query("SELECT cod_contacto from rutero_maestro order by cod_contacto desc");
	$dat_aux=mysql_fetch_array($sql_aux);
	$codigo_contacto_actual=$dat_aux[0]+1;		
	$sql_inserta="INSERT into rutero_maestro values($codigo_contacto_actual,'$rutero_trabajo','$global_visitador','$dia_contacto','$turno','$zona_viaje')";
	$resp_inserta=mysql_query($sql_inserta);
	$sql_det="SELECT * from rutero_maestro_detalle where cod_contacto=$cod_contacto";
	$resp_det=mysql_query($sql_det);
	while($dat_det=mysql_fetch_array($resp_det)) {	
		$orden_visita=$dat_det[1];
		$cod_med=$dat_det[3];
			//sacamos la especialidad y categoria del medico
		$sql_especat="SELECT cod_especialidad, categoria_med from categorias_lineas where codigo_linea='$global_linea' and cod_med='$cod_med'";
		$resp_especat=mysql_query($sql_especat);
		$dat_especat=mysql_fetch_array($resp_especat);
		// $cod_espe=$dat_especat[0];
		// $cat_med=$dat_especat[1];
			$cod_espe=$dat_det[4];
			$cat_med=$dat_det[5];
		$cod_zona=$dat_det[6];
		$codiggos_finalesmed .= $cod_med.",";
		$sql_inserta1="INSERT into rutero_maestro_detalle values($codigo_contacto_actual,$orden_visita,'$global_visitador',$cod_med,'$cod_espe','$cat_med','$cod_zona',0, 2)";
			// echo $codiggos_finalesmed;
			// echo $sql_inserta1."<br />";
		$resp_inserta1=mysql_query($sql_inserta1);
	}
}
	echo "<script language='Javascript'>
			alert('Se replico el rutero maestro satisfactoriamente.');
			location.href='rutero_maestro_todo.php?rutero=$rutero_trabajo';
		</script>";
?>