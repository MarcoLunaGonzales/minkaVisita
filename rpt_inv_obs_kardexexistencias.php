<?php

error_reporting(0);
set_time_limit(0);
require('estilos_reportes_almacencentral.php');

require('conexion.inc');

require('function_formatofecha.php');

require('function_comparafechas.php');

echo "$rpt_territorio $rpt_almacen $tipo_item";


$sql_nombre_territorio="SELECT descripcion from ciudades where cod_ciudad='$rpt_territorio'";

$resp_territorio=mysql_query($sql_nombre_territorio);

$dat_territorio=mysql_fetch_array($resp_territorio);

$nombre_territorio=$dat_territorio[0];

$sql_nombre_almacen="SELECT nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";

$resp_nombre_almacen=mysql_query($sql_nombre_almacen);

$dat_almacen=mysql_fetch_array($resp_nombre_almacen);

$nombre_almacen=$dat_almacen[0];

echo "<table align='center' class='textotit'><tr><th align='center'>Reporte Observaciones entre Kardex de Existencia Fisica y Existencias<br>Territorio: <strong>$nombre_territorio</strong> Almacen: <strong>$nombre_almacen</strong>Tipo de Item: <strong>$nombre_tipoitem</strong></th></tr></table>";

echo "<center><br><table border='1' class='texto' cellspacing='0' width='100%'>";

echo "<tr class='textomini'><th>Codigo</th><th>Item</th><th>Saldo segun Kardex</th><th>Saldo segun Existencias</th></tr>";	

if($tipo_item==1) {	

	$nombre_tipoitem="Muestra M�dica";
	$sql_item="SELECT codigo, descripcion, presentacion from muestras_medicas order by descripcion";

} else {

	$nombre_tipoitem="Material de Apoyo";
	$sql_item="SELECT codigo_material, descripcion_material from material_apoyo where estado='Activo' order by descripcion_material";

}

$resp_item=mysql_query($sql_item);

while($dat_item=mysql_fetch_array($resp_item)) {	

	$codigo_item=$dat_item[0];

	$nombre_item="$dat_item[1] $dat_item[2]";		

	$sql_fechas_ingresos="SELECT SUM(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$rpt_almacen' and i.grupo_ingreso=$tipo_item and i.ingreso_anulado=0 and id.cod_material='$codigo_item'";

	$resp_fechas_ingresos=mysql_query($sql_fechas_ingresos);

	$dat_kardex_ingresos=mysql_fetch_array($resp_fechas_ingresos);

	$cantidad_ingreso_kardex=$dat_kardex_ingresos[0];

	$sql_fechas_salidas="SELECT SUM(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$rpt_almacen' and s.grupo_salida=$tipo_item and s.salida_anulada=0 and sd.cod_material='$codigo_item'";

	$resp_fechas_salidas=mysql_query($sql_fechas_salidas);

	$dat_kardex_salidas=mysql_fetch_array($resp_fechas_salidas);

	$cantidad_salida_kardex=$dat_kardex_salidas[0];

	$cantidad_kardex=$cantidad_ingreso_kardex-$cantidad_salida_kardex;

		//por existencias

	$sql_stock="SELECT SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i where id.cod_material='$codigo_item' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$rpt_almacen'";

	$resp_stock=mysql_query($sql_stock);

	$dat_stock=mysql_fetch_array($resp_stock);

	$stock_real=$dat_stock[0];

	if($cantidad_kardex!=$stock_real) {

		$cadena_final .= $codigo_item.",";
		echo "<tr><td>$codigo_item</td><td align='left'>$nombre_item</td><td align='right'>$cantidad_kardex</td><td align='right'>$stock_real</td></tr>";

	}		

}

echo "</table></center><br>";	
echo $cadena_final;
echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table></center>";

?>