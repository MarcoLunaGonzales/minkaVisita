<?php

require("conexion.inc");
require("estilos_almacenes.inc");

echo "<script language='Javascript'>
function validar(f, grupoSalida)
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


$grupoSalida=$_GET["grupoSalida"];
echo "<form method='get' action='guarda_datosdespacho.php'>";

echo "<input type='hidden' name='grupoSalida' id='grupoSalida' value='$grupoSalida'>";

echo "<h1>Registrar Datos de Despacho</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Numero(s) Salida</th><th>Fecha Despacho</th><th>Nro. Guia</th><th>Tipo de Transporte</th></tr>";
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
echo"<INPUT type='date' class='texto' value='$fecha' id='fecha' size='10' name='fecha'></td>";

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
echo "<tr><th>Nro. Cajas</th><th>Monto [Bs]</th><th>Peso [Kg]</th><th>&nbsp;</th></tr>";
echo "<tr><td align='center'><input type='number' name='nro_cajas' class='texto' OnKeyUp='escribe_obs(this.form)'></td>";
echo "<td align='center'><input type='number' name='monto' class='texto'></td>";
echo "<td align='center'><input type='number' name='peso' class='texto'></td><td>&nbsp;</td></tr>";
echo "<tr><th colspan='4'>Observaciones</th></tr>";
echo "<tr><td align='center' colspan='4'><input type='text' name='observaciones' class='texto' size='100'></td>";
echo "</tr></table></center>";  

echo "<input type='hidden' name='codigo_salida' value='$codigo_registro'>";
echo "<input type='hidden' name='tipo_material' value='$grupo_salida'>";

echo "<div class='divBotones'>
	<input type='button' value='Guardar' name='adicionar' OnClick='validar(this.form, $grupoSalida)' class='boton'>
	<input type='button' value='Cancelar' name='cancelar' OnClick='location.href=\"navegador_salidamuestras.php?grupoSalida=$grupoSalida\"' class='boton2'>
	</div>";

echo "</body>";
echo "</form>";

?>