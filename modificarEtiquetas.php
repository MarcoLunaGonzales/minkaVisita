<?php

require("conexion.inc");
require('estilos_almacenes_central_sincab.php');

echo "<form method='get' action='guardaModificarEtiquetas.php'>";
$sql = "select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.grupo_ingreso, i.nro_correlativo , i.cod_orden_compra
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.cod_ingreso_almacen='$codigo_ingreso' and i.grupo_ingreso=2";
$resp = mysql_query($sql);
echo "<center><table border='0' class='textotit'><tr><th>Modificar Etiquetas de Ingreso de Material de Apoyo</th></tr></table></center><br>";
echo "<table border='1' class='texto' cellspacing='0' width='90%' align='center'>";
echo "<tr><th>N&uacute;mero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>N&deg; Orden Compra</th><th>Observaciones</th></tr>";
$dat = mysql_fetch_array($resp);
$codigo = $dat[0];
echo "<input type='hidden' name='cod_ingreso' value='$codigo'>";
$fecha_ingreso = $dat[1];
$fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
$nombre_tipoingreso = $dat[2];
$obs_ingreso = $dat[3];
$grupo_ingreso = $dat[4];
$nro_correlativo = $dat[5];
$orden_compra = $dat[6];
echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>$orden_compra</td><td>&nbsp;$obs_ingreso</td></tr>";
echo "</table>";

$sql_detalle = "select m.codigo_material, m.descripcion_material, ie.etiqueta, ie.cantidad from ingreso_detalle_almacenes id, 
material_apoyo m, ingreso_etiquetas ie
where id.cod_ingreso_almacen=ie.cod_ingreso and m.codigo_material=ie.cod_material and id.cod_ingreso_almacen=$codigo  and m.codigo_material=id.cod_material
order by m.descripcion_material, ie.etiqueta";
$resp_detalle = mysql_query($sql_detalle);
echo "<br><table border=1 cellspacing='0' class='textomini' width='60%' align='center'>";
echo "<tr><th>Material</th><th>Etiqueta</th><th>Cantidad</th></tr>";
$indice = 1;
while ($dat_detalle = mysql_fetch_array($resp_detalle)) {
    $cod_material = $dat_detalle[0];
    $nombre_material = $dat_detalle[1];
    $nroEtiqueta=$dat_detalle[2];
    $cantidad_unitaria = $dat_detalle[3];
	echo "<input type='hidden' name='indice$indice' value='$nroEtiqueta'>";	
	echo "<input type='hidden' name='material$indice' value='$cod_material'>";
    echo "<tr><td>$nombre_material</td><td align='center'>$indice</td><td align='center'>
	<input type='text' value='$cantidad_unitaria' name='canti$indice' id='canti$indice' size='4'></td></tr>";
    $indice++;
}
$indice--;
echo "<input type='hidden' name='nro_empaques' value='$indice'>";
echo "</table>";
echo "<br><center><input type='submit' value='Modificar'></center>";
echo "</form>";
//	echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
?>