<?php
echo "<script language='Javascript'>
	function enviar_form(f)
	{	f.submit();
	}
	function validar(f)
	{
		var i,j,cantidad_material,nombre_tipo, nota_entrega;
		variables=new Array(f.length-1);
		vector_material=new Array();
		vector_nrolote=new Array();
		vector_fechavenci=new Array();
		vector_cantidades=new Array();
		vector_tipomaterial=new Array();
		var indice,fecha, tipo_ingreso, observaciones;
		fecha=f.fecha.value;
		tipo_ingreso=f.tipo_ingreso.value;
		observaciones=f.observaciones.value;
		cantidad_material=f.cantidad_material.value;
		nota_entrega=f.nota_entrega.value;
		var grupoIngreso=f.grupoIngreso.value;
		
		
		if(f.fecha.value=='')
		{	alert('El campo Fecha esta vacio.');
			f.fecha.focus();
			return(false);
		}
		if(f.nota_entrega.value=='')
		{	alert('El campo Nota de Entrega esta vacio.');
			f.nota_entrega.focus();
			return(false);
		}
		//validamos los elementos formatos y demas situaciones
		var nroFilasDet=f.cantidad_material.value;
		for(xx=1;xx<=nroFilasDet;xx++){
			if(document.getElementById('materiales'+xx).value==''){
				alert('El item no puede estar vacio. Fila: '+xx);
				return(false);
			}
			if(grupoIngreso==1){
				if(document.getElementById('nrolote'+xx).value==''){
					alert('El Nro. de lote no puede estar vacio. Fila: '+xx);
					return(false);
				}
				if(document.getElementById('fecha_vencimiento'+xx).value==''){
					alert('La fecha de vencimiento esta vacia o no tiene el formato correcto. Fila: '+xx);
					return(false);
				}	
			}
			if(document.getElementById('cantidad_unitaria'+xx).value==''){
				alert('La cantidad no puede estar vacia. Fila: '+xx);
				return(false);
			}
		}		
		//fin validar
		
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('materiales')!=-1)	
			{	vector_material[indice]=f.elements[j].value;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('nrolote')!=-1)	
			{	vector_nrolote[indice]=f.elements[j].value;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('fecha_vencimiento')!=-1)	
			{	vector_fechavenci[indice]=f.elements[j].value;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('cantidad_unitaria')!=-1)	
			{	vector_cantidades[indice]=f.elements[j].value;
				indice++;	
			}
		}
		if(grupoIngreso==1){
			var buscado,cant_buscado;
			for(k=0;k<=indice;k++)
			{	buscado=vector_nrolote[k];
				cant_buscado=0;
				for(m=0;m<=indice;m++)
				{	if(buscado==vector_nrolote[m])
					{	cant_buscado=cant_buscado+1;
					}
				}
				if(cant_buscado>1)
				{	alert('Los Numeros de Lote no pueden repetirse.');
					return(false);
				}
			}
		}
		location.href='guarda_ingresomuestras.php?vector_material='+vector_material+'&vector_nrolote='+vector_nrolote+'&vector_fechavenci='+vector_fechavenci+'&vector_cantidades='+vector_cantidades+'&fecha='+fecha+'&tipo_ingreso='+tipo_ingreso+'&observaciones='+observaciones+'&cantidad_material='+cantidad_material+'&vector_tipomaterial='+vector_tipomaterial+'&nota_entrega='+nota_entrega+'&grupoIngreso='+grupoIngreso+'';
	}
	</script>";
	
	
require("conexion.inc");
require("estilos_almacenes.inc");


if($fecha=="")
{	$fecha=date("d/m/Y");
}

echo "<form action='' method='post'>";
$grupoIngreso=$_GET["grupoIngreso"];
echo "<input type='hidden' name='grupoIngreso' value='$grupoIngreso' id='grupoIngreso'>";
if($grupoIngreso==1){
	echo "<h1>Registrar Ingreso de Muestras</h1>";
}else{
	echo "<h1>Registrar Ingreso de Material</h1>";
}

echo "<center><table class='texto'>";
echo "<tr><th>Nro. de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Nota Entrega</th></tr>";
$sql="select max(nro_correlativo) from ingreso_almacenes where cod_almacen='$global_almacen' 
	and grupo_ingreso='$grupoIngreso'";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$nro_correlativo=1;
}
else
{	$nro_correlativo=$dat[0];
	$nro_correlativo++;
}
echo "<tr>";
echo "<td align='center'>$nro_correlativo</td>";
echo "<td align='center'>";
	echo"<INPUT type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
	echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
	/*echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
	echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
	echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
	echo" input_element_id='fecha'";
	echo" click_element_id='imagenFecha'></DLCALENDAR></td>";*/
$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso where tipo_almacen='$global_tipoalmacen' order by nombre_tipoingreso";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='tipo_ingreso' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tipoingreso=$dat1[0];
	$nombre_tipoingreso=$dat1[1];
	if($cod_tipoingreso==$tipo_ingreso)
	{	echo "<option value='$cod_tipoingreso' selected>$nombre_tipoingreso</option>";
	}
	else
	{	echo "<option value='$cod_tipoingreso'>$nombre_tipoingreso</option>";
	}
}
echo "</select></td>";
echo "<td align='center'><input type='text' class='texto' name='nota_entrega' value='$nota_entrega'></td></tr>";
echo "<tr><th colspan='4'>Observaciones</th></tr>";
echo "<tr><td align='center' colspan='4'><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td></tr>";
echo "</table><br>";

echo "<table class='texto'>";
echo "<tr><th colspan='5'>Cantidad de Materiales a ingresar:  <select name='cantidad_material' OnChange='enviar_form(this.form)' class='texto'>";
for($i=0;$i<=50;$i++)
{	if($i==$cantidad_material)
	{	echo "<option value='$i' selected>$i</option>";
	}
	else
	{	echo "<option value='$i'>$i</option>";
	}
}
echo "</select><th></tr>";
echo "<tr><th width='5%'>&nbsp;</th><th width='35%'>Material</th><th width='20%'>Nro. Lote</th><th width='20%'>Fecha Vencimiento</th><th width='20%'>Cantidad Unitaria</th></tr>";
for($indice_detalle=1;$indice_detalle<=$cantidad_material;$indice_detalle++)
{	echo "<tr><td align='center'>$indice_detalle</td>";
	
	if($grupoIngreso==1){
		$sql_materiales="select codigo,concat(descripcion,' ',presentacion) from muestras_medicas order by 2";
	}else{
		$sql_materiales="select codigo_material, descripcion_material from material_apoyo order by 2";
	}
	
	$resp_materiales=mysql_query($sql_materiales);
	//obtenemos los valores de las variables creadas en tiempo de ejecucion
	$var_material="materiales$indice_detalle";
	$valor_material=$$var_material;
	echo "<td align='center'><select name='materiales$indice_detalle' id='materiales$indice_detalle' style='width:400px'>";
	echo "<option></option>";
	while($dat_materiales=mysql_fetch_array($resp_materiales))
	{	$cod_material=$dat_materiales[0];
		$nombre_material=$dat_materiales[1];
		$presentacion_material=$dat_materiales[2];
		if($cod_material==$valor_material)
		{	echo "<option value='$cod_material' selected>$nombre_material</option>";
		}
		else
		{	echo "<option value='$cod_material'>$nombre_material</option>";
		}
	}
	echo "</select></td>";
	
	$var_nrolote="nrolote$indice_detalle";
	$valor_nrolote=$$var_nrolote;
	
	if($grupoIngreso==1){
		echo "<td align='center'>
		<input type='text' name='nrolote$indice_detalle' id='nrolote$indice_detalle' value='$valor_nrolote' class='texto' onKeyUp='javascript:this.value=this.value.toUpperCase();'>
		</td>";
	}else{
		echo "<td align='center'>
		<input type='text' name='nrolote$indice_detalle' id='nrolote$indice_detalle' value='$valor_nrolote' class='texto' onKeyUp='javascript:this.value=this.value.toUpperCase();' disabled>
		</td>";
	}
	
	
	$var_fecha_vencimiento="fecha_vencimiento$indice_detalle";
	$valor_fecha_vencimiento=$$var_fecha_vencimiento;

	if($valor_fecha_vencimiento==""){
		$valor_fecha_vencimiento=date("Y-m-d");
	}
	
	if($grupoIngreso==1){
		echo "<td align='center'>";
		echo" <INPUT type='date' class='texto' value='$valor_fecha_vencimiento' id='fecha_vencimiento$indice_detalle' size='10' name='fecha_vencimiento$indice_detalle'>
		</td>";	
	}else{
		echo "<td align='center'>";
		echo" <INPUT type='date' class='texto' value='$valor_fecha_vencimiento' id='fecha_vencimiento$indice_detalle' size='10' name='fecha_vencimiento$indice_detalle' disabled>
		</td>";	
	}

	$var_cant_unit="cantidad_unitaria$indice_detalle";
	$valor_cant_unit=$$var_cant_unit;
	if($valor_cant_unit==""){
		$valor_cant_unit=1;
	}
	echo "<td align='center'><input type='number' name='cantidad_unitaria$indice_detalle' id='cantidad_unitaria$indice_detalle' value='$valor_cant_unit' class='texto' min='1' max='9000000'></td>";
	echo "</tr>";
}
echo "</table></center>";

echo "<div class='divBotones'>
<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_ingresomuestras.php?grupoIngreso=$grupoIngreso\"'>
</div>";
echo "</form>";
echo "</div></body>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>