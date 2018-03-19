<?php
require("conexion.inc");
require("estilos_almacenes.inc");

$grupoSalida=$_GET["grupoSalida"];

$sql_confirmacion=mysql_query("SELECT * from salida_almacenes where cod_salida_almacenes='$codigo_registro' and salida_anulada=0");
$numero_filas_afectadas=mysql_num_rows($sql_confirmacion);

if($numero_filas_afectadas==1) {		
	$sql="UPDATE salida_almacenes set salida_anulada=1, estado_salida=4 where cod_salida_almacenes='$codigo_registro' 
	and salida_anulada=0";
	$resp=mysql_query($sql);

	if($grupoSalida==1) {	
		$sql_detalle="SELECT cod_ingreso_almacen, material, cantidad_unitaria, nro_lote from salida_detalle_ingreso 
		where cod_salida_almacen='$codigo_registro' and grupo_salida_ingreso=$grupoSalida";
		$resp_detalle=mysql_query($sql_detalle);
		while($dat_detalle=mysql_fetch_array($resp_detalle)) {	
			$codigo_ingreso = $dat_detalle[0];
			$material       = $dat_detalle[1];
			$cantidad       = $dat_detalle[2];
			$nro_lote       = $dat_detalle[3];

			$sql_actualiza="UPDATE ingreso_detalle_almacenes set cantidad_restante=cantidad_restante+$cantidad
			where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material' and nro_lote='$nro_lote'";
			$resp_actualiza=mysql_query($sql_actualiza);
		}
	}
	if($grupoSalida==2) {	
		$sql_detalle="SELECT cod_ingreso_almacen, material, cantidad_unitaria 
		from salida_detalle_ingreso where cod_salida_almacen='$codigo_registro' and grupo_salida_ingreso=$grupoSalida";
		$resp_detalle=mysql_query($sql_detalle);
		while($dat_detalle=mysql_fetch_array($resp_detalle)) {	
			$codigo_ingreso=$dat_detalle[0];
			$material=$dat_detalle[1];
			$cantidad=$dat_detalle[2];
			
			$sql_actualiza="UPDATE ingreso_detalle_almacenes set cantidad_restante=cantidad_restante+$cantidad 
			where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'";
			$resp_actualiza=mysql_query($sql_actualiza);
		}
	}
	
	echo "<script language='Javascript'> 
		alert('El registro fue anulado.'); 
		location.href='navegador_salidamuestras.php?grupoSalida=$grupoSalida'; 
	</script>";

} else {	
	echo "<script language='Javascript'>
		alert('Esta salida ya esta anulada.'); 
		history.back(); 
	</script>";
}
?>