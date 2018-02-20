<?php
echo "<script language='JavaScript'>
	function enviar_cantidades(f)
	{	var i, suma, numero, indice;
		indice=f.indice.value;
		suma=0;
		for(i=0;i<=f.length-1;i++)
		{	if(f.elements[i].name.indexOf('cantidad')!=-1)
			{	numero=(f.elements[i].value)*1
				suma=suma+numero;
			}
		}
		f.submit();
		window.opener.document.forms[0].elements[indice].value=suma;
	}
</script>";
require("conexion.inc");
require('estilos_almacenes_sincab.inc');
echo $indice;
$sql_cab="select * from muestras_medicas where codigo='$codigo_muestra'";
$resp_cab=mysql_query($sql_cab);
$dat_cab=mysql_fetch_array($resp_cab);
$nombre_material="$dat_cab[1] $dat_cab[2]";
echo "<form action='guarda_salidadetallemuestras.php' method='get'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Registro de Salida de Almacen<br>Muestra: $nombre_material</th></tr></table><br>";
echo "<input type='hidden' name='indice' value='$indice'>";
$sql_ingresos="select i.cod_ingreso_almacen, i.fecha, id.nro_lote, id.fecha_vencimiento, id.cantidad_restante
from ingreso_almacenes i, ingreso_detalle_almacenes id
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and id.cod_material='$codigo_muestra' and i.cod_almacen='$global_almacen'
order by i.fecha desc";
$resp_ingresos=mysql_query($sql_ingresos);
echo "<table border='1' class='texto' align='center'>";
echo "<tr><th>Fecha Ingreso</th><th>Número de Lote</th><th>Fecha de Vencimiento</th><th>Cantidad Disponible</th><th>Cantidad a sacar</th></tr>";
$indice=1;
while($dat_ingresos=mysql_fetch_array($resp_ingresos))
{	$cod_ingreso=$dat_ingresos[0];
	$fecha_ingreso=$dat_ingresos[1];
	$numero_lote=$dat_ingresos[2];
	$fecha_vencimiento=$dat_ingresos[3];
	$cantidad_restante=$dat_ingresos[4];
	echo "<tr><td>$fecha_ingreso</td><td>$numero_lote</td><td>$fecha_vencimiento</td><td>$cantidad_restante</td>";
	echo "<td><input type='text' name='cantidad$indice'></td></tr>";
	echo "<input type='hidden' name='cod_ingreso$indice' value='$cod_ingreso'>";
	$indice++;
}
echo "<input type='hidden' name='numero_ingresos' value='$indice'>";
echo "<input type='hidden' name='material' value='$codigo_muestra'>";
echo "</table>";
echo "<br><center><input type='button' class='boton' value='Guardar' onClick='enviar_cantidades(this.form)'></center>";
echo "</form>";
?>