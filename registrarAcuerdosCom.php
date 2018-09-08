<script language='JavaScript'>
function validar(f)
{
	var almacen, ciudad, responsable;
	if(f.nombre_almacen.value=='')
	{	alert('El campo Nombre de Almacen esta vacio.');
		f.nombre_almacen.focus();
		return(false);
	}
	almacen=f.nombre_almacen.value;
	ciudad=f.territorio.value;
	responsable=f.responsable.value;
	location.href='guarda_almacenes.php?nombre_almacen='+almacen+'&territorio='+ciudad+'&responsable='+responsable+'';
}
function envia_form(f)
{	f.submit();
}
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function ajaxMontoPromedio(codigo, nroMeses){
	var codCliente=codigo.value;
	var nroMesesPromedio=nroMeses;
	var contenedor;
	contenedor = document.getElementById('divMontoPromedio');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxMontoPromedio.php?codCliente='+codCliente+'&nroMeses='+nroMesesPromedio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function ajaxMontoObjetivo(cliente, codigo, nroMeses){
	var codCliente=cliente.value;
	var crecimiento=codigo.value;
	var nroMesesPromedio=nroMeses;
	var contenedor;
	contenedor = document.getElementById('divMontoObjetivo');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxMontoObjetivo.php?codCliente='+codCliente+'&crecimiento='+crecimiento+'&nroMeses='+nroMesesPromedio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function ajaxMontoTotal(codigo){
	var nroMeses=codigo.value;
	//alert('numeromese:'+nroMeses);
	var montoObjetivo=document.getElementById('monto_objetivo').value;
	var montoObjetivoTotal=nroMeses*montoObjetivo;
	montoObjetivoTotal=Math.round(montoObjetivoTotal);
	document.getElementById('monto_objetivototal').value=montoObjetivoTotal;
	var montoFormato=new Intl.NumberFormat().format(montoObjetivoTotal);
	document.getElementById('divMontoObjetivoTotal').innerHTML=montoFormato;	
}
function ajaxMontoRebate(codigo){
	var porcentajeRebate=codigo.value;
	var montoObjetivoTotal=document.getElementById('monto_objetivototal').value;
	var montoRebate=montoObjetivoTotal*(porcentajeRebate/100);
	montoRebate=Math.round(montoRebate);
	document.getElementById('monto_rebate').value=montoRebate;
	var montoFormato=new Intl.NumberFormat().format(montoRebate);
	document.getElementById('divMontoRebate').innerHTML=montoFormato;	
}
function ajaxFechaFin(codigo){
	var fechaIni=codigo.value;
	var nroMeses=document.getElementById('nro_meses').value;
	var contenedor;
	contenedor = document.getElementById('divFechaFin');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxFechaFin.php?fechaIni='+fechaIni+'&nroMeses='+nroMeses,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
</script>
<?php
	
require("conexion.inc");
require("estilos_administracion.inc");

echo "<form action='guardarAcuerdosCom.php' method='post'>";

echo "<h1>Registrar Acuerdo Comercial</h1>";

echo "<center><table class='texto'>";

$globalAgencia=$_COOKIE['global_agencia'];

$sqlNroMeses="select valor from configuraciones where clave='mesespromedio'";
$respNroMeses=mysql_query($sqlNroMeses);
$nroMesesPromedio=mysql_result($respNroMeses,0,0);

$sqlConf2="select valor from configuraciones where clave='mesespromedio'";
$respConf2=mysql_query($sqlConf2);
$crecimientoMinimo=mysql_result($respConf2,0,0);

$sqlConf3="select valor from configuraciones where clave='mesespromedio'";
$respConf3=mysql_query($sqlConf3);
$rebateMaximo=mysql_result($respConf3,0,0);

echo "<tr><th>Cliente</th><th>Venta Promedio($nroMesesPromedio meses)</th></tr>";

$sqlCliente="select cod_cliente, nombre_cliente from clientes where cod_ciudad='$globalAgencia' order by 2";
$respCliente=mysql_query($sqlCliente);
echo "<tr><td><select name='cod_cliente' id='cod_cliente' class='textograndenegro' onChange='ajaxMontoPromedio(this, $nroMesesPromedio)' style='width:500px;' required>
<option value=''>- -</option>";
while($datCliente=mysql_fetch_array($respCliente)){
	$codClienteX=$datCliente[0];
	$nombreClienteX=$datCliente[1];
	echo "<option value='$codClienteX'>$nombreClienteX</option>";
}
echo "</select></td>";
echo "<td><div id='divMontoPromedio' class='textograndenegro'>-</div></td></tr>";

echo "<tr><th>% Crecimiento</th><th>Monto Objetivo/Mes</th></tr>";
echo "<tr><td><input type='number' name='porcentaje_crecimiento' id='porcentaje_crecimiento' min='$crecimientoMinimo' onChange='ajaxMontoObjetivo(this.form.cod_cliente,this,$nroMesesPromedio)' class='textograndenegro' required></td>";
echo "<td><div id='divMontoObjetivo' class='textograndenegro'>-</div></td></tr>";

echo "<tr><th>Nro. Meses</th><th>Monto Objetivo total</th></tr>";
echo "<tr><td><input type='number' name='nro_meses' id='nro_meses' min='1' onChange='ajaxMontoTotal(this)' class='textograndenegro' required></td>";
echo "<td><div id='divMontoObjetivoTotal' class='textogranderojo'>-</div>
<input type='hidden' name='monto_objetivototal' id='monto_objetivototal' value='0' class='textogranderojo'></td></tr>";

echo "<tr><th>% Rebate</th><th>Monto Rebate</th></tr>";
echo "<tr><td><input type='number' name='porcentaje_rebate' id='porcentaje_rebate' min='1' max='$rebateMaximo' onChange='ajaxMontoRebate(this)' class='textograndenegro' required></td>";
echo "<td><div id='divMontoRebate' class='textogranderojo'>-</div>
<input type='hidden' name='monto_rebate' id='monto_rebate' value='0' class='textogranderojo'></td></tr>";

$fechaActual=date('Y-m-d');
echo "<tr><th>Fecha Inicio</th><th>Fecha Final</th></tr>";
echo "<tr><td><input type='date' name='fecha_inicio' id='fecha_inicio' min='$fechaActual' onChange='ajaxFechaFin(this)' class='textograndenegro' required></td>";
echo "<td><div id='divFechaFin' class='textogranderojo'>-</div>
</td></tr>";

echo "<tr><th colspan='2'>Detalle de Rebate</th></tr>";
echo "<tr><td colspan='2'><input type='text' name='detalle_rebate' id='detalle_rebate' size='100' class='textograndenegro' required>
</td></tr>";


echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar'>";

echo "</form>";
?>