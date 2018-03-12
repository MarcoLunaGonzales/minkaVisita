<?php

require("conexion.inc");
require('function_formatofecha.php');

echo "<script language='Javascript'>
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
	

function ShowBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
}

function HiddenBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
}

function ajaxBuscarIngresos(f){
	var fechaIniBusqueda, fechaFinBusqueda, notaIngreso, verBusqueda, global_almacen, provBusqueda;
	fechaIniBusqueda=document.getElementById('fechaIniBusqueda').value;
	fechaFinBusqueda=document.getElementById('fechaFinBusqueda').value;
	notaIngreso=document.getElementById('notaIngreso').value;
	global_almacen=document.getElementById('global_almacen').value;
	provBusqueda=document.getElementById('provBusqueda').value;
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open('GET', 'ajaxNavIngresos.php?fechaIniBusqueda='+fechaIniBusqueda+'&fechaFinBusqueda='+fechaFinBusqueda+'&notaIngreso='+notaIngreso+'&global_almacen='+global_almacen+'&provBusqueda='+provBusqueda,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}


function enviar_nav(grupoIngreso){	
	location.href='registrar_ingresomuestras.php?grupoIngreso='+grupoIngreso;
}
function editar_ingreso(f, grupoIngreso)
{
	var i;
	var j=0;
	var j_cod_registro;
	var fecha_registro;
	
	var hoy = new Date();
	var dd = hoy.getDate();
	var mm = hoy.getMonth()+1; //hoy es 0!
	var yyyy = hoy.getFullYear();
	if(dd<10) {
		dd='0'+dd
	} 
	if(mm<10) {
		mm='0'+mm
	} 
	hoy = dd+'-'+mm+'-'+yyyy;
	
	for(i=0;i<=f.length-1;i++)
	{
		if(f.elements[i].type=='checkbox')
			{	if(f.elements[i].checked==true)
				{	j_cod_registro=f.elements[i].value;
					fecha_registro=f.elements[i-1].value;
					j=j+1;
				}
			}
		}
		if(j>1)
			{	alert('Debe seleccionar solamente un registro para editarlo.');
	}
	else
	{
		if(j==0)
		{
			alert('Debe seleccionar un registro para editarlo.');
		}
		else
			{	
				//alert(hoy+' '+fecha_registro);
				
				if(hoy==fecha_registro)
				{	
				location.href='editar_ingresomuestras.php?codigo_registro='+j_cod_registro+'&grupoIngreso='+grupoIngreso+'&valor_inicial=1';	
				}
				else{	
					alert('Usted no esta autorizado(a) para modificar el ingreso.');
				}
	}
}
}
function anular_ingreso(f)
{
	var i;
	var j=0;
	var j_cod_registro;
	var fecha_registro;
	var hoy = new Date();
	var dd = hoy.getDate();
	var mm = hoy.getMonth()+1; //hoy es 0!
	var yyyy = hoy.getFullYear();
	if(dd<10) {
		dd='0'+dd
	} 
	if(mm<10) {
		mm='0'+mm
	} 
	hoy = dd+'-'+mm+'-'+yyyy;

	
	for(i=0;i<=f.length-1;i++)
	{
		if(f.elements[i].type=='checkbox')
			{	if(f.elements[i].checked==true)
				{	j_cod_registro=f.elements[i].value;
					fecha_registro=f.elements[i-1].value;
					j=j+1;
				}
			}
		}
		if(j>1)
			{	alert('Debe seleccionar solamente un registro para anularlo.');
	}
	else
	{
		if(j==0)
		{
			alert('Debe seleccionar un registro para anularlo.');
		}
		else
			{	if(hoy==fecha_registro)
				{	window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
			}
			else
				{	alert('Usted no esta autorizado(a) para anular el ingreso.');
		}
	}
}
}
</script>";

require("estilos_almacenes_central.inc");
$grupoIngreso=$_GET['grupoIngreso'];

echo "<input type='hidden' value='$global_almacen' name='global_almacen' id='global_almacen'>";
echo "<input type='hidden' value='$grupoIngreso' name='grupoIngreso' id='grupoIngreso'>";


echo "<form method='get' action=''>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";

$sql="SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, 
i.ingreso_anulado FROM ingreso_almacenes i, tipos_ingreso ti where i.cod_tipoingreso=ti.cod_tipoingreso and 
i.cod_almacen='$global_almacen' and i.grupo_ingreso='$grupoIngreso' order by nro_correlativo desc limit 0,50";

$resp=mysql_query($sql);
if($grupoIngreso==1){
	echo "<h1>Ingreso de Muestras</h1>";
}else{
	echo "<h1>Ingreso de Materiales</h1>";	
}

echo "<table border='1' cellspacing='0' class='textomini'><tr><th>Leyenda:</th><th>Ingresos Anulados</th><td bgcolor='#ff8080' width='10%'></td><th>Ingresos con movimiento</th><td bgcolor='#ffff99' width='10%'></td><th>Ingresos sin movimiento</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";

	echo "<div class='divBotones'><input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav($grupoIngreso)'>
	<input type='button' value='Editar' class='boton' onclick='editar_ingreso(this.form,$grupoIngreso)'>
	<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_ingreso(this.form,$grupoIngreso)'>
	<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'>
	</div>";

echo "<div id='divCuerpo'>";
	
echo "<br><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. Ingreso</th><th>Nota de Entrega</th><th>Fecha/Hora</th><th>Tipo de Ingreso</th><th>Observaciones</th><th>&nbsp;</th></tr>";
while($dat=mysql_fetch_array($resp)) {
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$hora_ingreso=$dat[2];
	$nombre_tipoingreso=$dat[3];
	$obs_ingreso=$dat[4];
	$nota_entrega=$dat[5];
	$nro_correlativo=$dat[6];
	$anulado=$dat[7];
	echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
	$bandera=0;
	$sql_verifica_movimiento="SELECT s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi 
	where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
	$resp_verifica_movimiento=mysql_query($sql_verifica_movimiento);
	$num_filas_movimiento=mysql_num_rows($resp_verifica_movimiento);
	if($num_filas_movimiento!=0) {	
		$color_fondo="#ffff99";
		$chkbox="";
	}
	if($anulado==1) {	
		$color_fondo="#ff8080";
		$chkbox="";
	}
	if($num_filas_movimiento==0 and $anulado==0) {	
		$color_fondo="";
		$chkbox="<input type='checkbox' name='codigo' value='$codigo'>";
	}
//		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
	echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox&nbsp;</td>
	<td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td>
	<td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td>
	<td align='center'><a target='_BLANK' href='navegador_detalleingresomuestras.php?codigo_ingreso=$codigo&grupoIngreso=$grupoIngreso'>
	<img src='imagenes/detalle.png' border='0' title='Ver Detalles del Ingreso' width='40'></a></td></tr>";
}
echo "</table></center><br>";
echo "</div>";

	echo "<div class='divBotones'><input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav($grupoIngreso)'>
	<input type='button' value='Editar' class='boton' onclick='editar_ingreso(this.form,$grupoIngreso)'>
	<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_ingreso(this.form,$grupoIngreso)'>
	<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'>
	</div>";

echo "</form>";
?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:78px; left:225px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:100px; left:250px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Ingresos</h2>
		<table align='center' class='texto'>
			<tr>
				<td>Fecha Ini(dd/mm/aaaa)</td>
				<td>
				<input type='text' name='fechaIniBusqueda' id="fechaIniBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Fecha Fin(dd/mm/aaaa)</td>
				<td>
				<input type='text' name='fechaFinBusqueda' id="fechaFinBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Nota de Ingreso</td>
				<td>
				<input type='text' name='notaIngreso' id="notaIngreso" class='texto'>
				</td>
			</tr>			
			<tr>
				<td>Muestra/Material</td>
				<td>
					<select name="ProvBusqueda" class="texto" id="provBusqueda" style="width:400px">
						<option value="0">--Todo--</option>
					<?php
						if($grupoIngreso==1){
							$sqlProv="select codigo, concat(descripcion,' ',presentacion) from muestras_medicas order by 2";	
						}else{
							$sqlProv="select codigo_material, descripcion_material from material_apoyo order by 2";
						}
						$respProv=mysql_query($sqlProv);
						while($datProv=mysql_fetch_array($respProv)){
							$codProvBus=$datProv[0];
							$nombreProvBus=$datProv[1];
					?>
							<option value="<?php echo $codProvBus;?>"><?php echo $nombreProvBus;?></option>
					<?php
						}
					?>
					</select>
				
				</td>
			</tr>			
		</table>	
		<center>
			<input class='boton' type='button' value='Buscar' onClick="ajaxBuscarIngresos(this.form)">
			<input class='boton' type='button' value='Cancelar' onClick="HiddenBuscar()">
			
		</center>
	</div>
</div>
