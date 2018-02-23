<?php
echo "<script language='Javascript'>
function validar(f)
{	if(f.fecha.value=='')
	{	alert('El campo fecha esta vacio.');
		return(false);
	}
	if(f.numero_guia.value=='')
	{	alert('El campo Número de Guía esta vacio.');
		f.numero_guia.focus();
		return(false);
	}
	if(f.nro_cajas.value=='')
	{	alert('El campo Número de Cajas esta vacio.');
		f.nro_cajas.focus();
		return(false);
	}
	
	f.submit();
}
function escribe_obs(f)
{
	f.observaciones.value='Los documentos se encuentran en la caja nro. '+f.nro_cajas.value;
	return(true);
}
</script>";
require("conexion.inc");
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}
echo "<form method='get' action='guarda_datosdespacho.php'>";
echo "<center><table border='0' class='textotit'><tr><th>Registrar Datos de Despacho<th></tr></table></center><br>";
echo "<table border='1' class='texto' cellspacing='0' align='center' width='90%'>";
echo "<tr><th>Número(s) de Salida</th><th>Fecha Despacho</th><th>Número de Guía</th><th>Tipo de Transporte</th></tr>";
$vector=explode(",",$datos);
$n=sizeof($vector);
$cadena_nrocorrelativo="";
for($i=0;$i<$n;$i++)
{
	$sql_correlativo="select nro_correlativo from salida_almacenes where cod_salida_almacenes='$vector[$i]'";
	$resp_correlativo=mysql_query($sql_correlativo);
	$dat_correlativo=mysql_fetch_array($resp_correlativo);
	$nro_correlativo=$dat_correlativo[0];
	$cadena_nrocorrelativo.=" $nro_correlativo -";
}
echo "<input type='hidden' name='datos' value='$datos'>";
echo "<tr><td align='center'>$cadena_nrocorrelativo</td>";
$fecha=date("d/m/Y");
echo "<td align='center'>";
	echo"<INPUT type='text' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
	echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
	echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
	echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
	echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
	echo" input_element_id='fecha'";
	echo" click_element_id='imagenFecha'></DLCALENDAR></td>";
echo "<td align='center'><input type='text' name='numero_guia' class='texto'></td>";
$sql1="select cod_tipotransporte, nombre_tipotransporte from tipos_transporte order by nombre_tipotransporte";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='tipo_transporte' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tipotransporte=$dat1[0];
	$nombre_tipotransporte=$dat1[1];
	echo "<option value='$cod_tipotransporte'>$nombre_tipotransporte</option>";
}
echo "</select></td></tr>";
echo "<tr><th>Número de Cajas</th><th>Monto [Bs]</th><th>Peso [Kg]</th><th>&nbsp;</th></tr>";
echo "<tr><td align='center'><input type='text' name='nro_cajas' class='texto' OnKeyUp='escribe_obs(this.form)'></td>";
echo "<td align='center'><input type='text' name='monto' class='texto'></td>";
echo "<td align='center'><input type='text' name='peso' class='texto'></td><td>&nbsp;</td></tr>";
echo "<tr><th colspan='4'>Observaciones</th></tr>";
echo "<tr><td align='center' colspan='4'><input type='text' name='observaciones' class='texto' size='100'></td>";
echo "</tr></table>";  
echo "<input type='hidden' name='codigo_salida' value='$codigo_registro'>";
echo "<input type='hidden' name='tipo_material' value='$grupo_salida'>";
if($tipo_material==1)
{	echo"\n<br><table align='center'><tr><td><a href='navegador_salidamuestras.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
}
else
{	echo"\n<br><table align='center'><tr><td><a href='navegador_salidamateriales.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
}
echo "<table border='0' align='center' class='texto'>";
echo "<tr><td><input type='button' value='Guardar' name='adicionar' OnClick='validar(this.form)' class='boton'></td></tr></table>";
echo "</div></body>";
echo "</form>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>