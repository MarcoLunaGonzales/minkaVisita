<?php
echo "<script language='Javascript'>
	function enviar_form(f)
	{	f.submit();
	}
	function validar(f)
	{
		var i,j,cantidad_material,nombre_tipo, nota_entrega, codigo_registro;
		variables=new Array(f.length-1);
		vector_material=new Array(50);
		vector_nrolote=new Array(50);
		vector_fechavenci=new Array(50);
		vector_cantidades=new Array(50);
		vector_tipomaterial=new Array(50);
		var indice,fecha, tipo_ingreso, observaciones;
		fecha=f.fecha.value;
		tipo_ingreso=f.tipo_ingreso.value;
		observaciones=f.observaciones.value;
		cantidad_material=f.cantidad_material.value;
		nota_entrega=f.nota_entrega.value;
		codigo_registro=f.codigo_registro.value;
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
				{	alert('Los Número de Lote no pueden repetirse.');
					return(false);
				}
			}
		}
		location.href='guarda_editaringresomuestras.php?vector_material='+vector_material+'&vector_nrolote='+vector_nrolote+'&vector_fechavenci='+vector_fechavenci+'&vector_cantidades='+vector_cantidades+'&fecha='+fecha+'&tipo_ingreso='+tipo_ingreso+'&observaciones='+observaciones+'&cantidad_material='+cantidad_material+'&vector_tipomaterial='+vector_tipomaterial+'&nota_entrega='+nota_entrega+'&codigo_registro='+codigo_registro+'&grupoIngreso='+grupoIngreso+'';
	}
	</script>";
require("conexion.inc");
require("funciones.php");

require("estilos_almacenes.inc");


if($fecha=="")
{	$fecha=date("d/m/Y");
}

echo "<form action='editar_ingresomuestras.php' method='post'>";

echo "<h1>Editar Ingreso de Muestras</h1>";

echo "<center><table class='texto'>";
//sacamos los valores iniciales para la edicion

echo "<input type='hidden' name='grupoIngreso' id='grupoIngreso' value='$grupoIngreso'>";

if($valor_inicial==1)
{	$sql_val_inicial="select cod_ingreso_almacen, cod_almacen, cod_tipoingreso, fecha, hora_ingreso, observaciones, 
				grupo_ingreso, cod_salida_almacen, nota_entrega, nro_correlativo from ingreso_almacenes
				where cod_ingreso_almacen='$codigo_registro'";
	$resp_val_inicial=mysql_query($sql_val_inicial);
	$dat_val_inicial=mysql_fetch_array($resp_val_inicial);
	$tipo_ingreso=$dat_val_inicial[2];
	$observaciones=$dat_val_inicial[5];
	$nota_entrega=$dat_val_inicial[8];
	$nro_correlativo=$dat_val_inicial[9];

	$sql_val_inicial_detalle="select cod_material, nro_lote, fecha_vencimiento, cantidad_unitaria, cantidad_restante
	from ingreso_detalle_almacenes where cod_ingreso_almacen='$codigo_registro'";
	$resp_val_inicial_detalle=mysql_query($sql_val_inicial_detalle);
	$indice_inicial=1;
	$cantidad_material=mysql_num_rows($resp_val_inicial_detalle);
	while($dat_inicial=mysql_fetch_array($resp_val_inicial_detalle))
	{	$var_material_ini="materiales$indice_inicial";
		$var_nrolote_ini="nrolote$indice_inicial";
		$var_fechavenci_ini="fecha_vencimiento$indice_inicial";
		$var_cant_unit_ini="cantidad_unitaria$indice_inicial";
		$$var_material_ini=$dat_inicial[0];
		$$var_nrolote_ini=$dat_inicial[1];
		$fecha_ingsinformato=$dat_inicial[2];
		$$var_fechavenci_ini="$fecha_ingsinformato[8]$fecha_ingsinformato[9]/$fecha_ingsinformato[5]$fecha_ingsinformato[6]/$fecha_ingsinformato[0]$fecha_ingsinformato[1]$fecha_ingsinformato[2]$fecha_ingsinformato[3]";
		$$var_cant_unit_ini=$dat_inicial[3];
		$indice_inicial++;
	}
}
//fin sacar valores iniciales

echo "<tr><th>Nro. Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Nota Entrega</th></tr>";
echo "<input type='hidden' name='nro_correlativo' value='$nro_correlativo'>";
echo "<input type='hidden' name='codigo_registro' value='$codigo_registro'>";
echo "<tr>";
echo "<td align='center'>$nro_correlativo</td>";
echo "<td align='center'>";
	echo"<INPUT type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
	echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";

	
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

echo "<table class='texto'";
echo "<tr><th colspan='5'>Cantidad de Materiales:  <select name='cantidad_material' OnChange='enviar_form(this.form)' class='texto'>";
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
{	
	echo "<tr><td align='center'>$indice_detalle</td>";
	if($grupoIngreso==1){
		$sql_materiales="select codigo, concat(descripcion, ' ', presentacion) from muestras_medicas order by 2";	
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
			<input type='text' name='nrolote$indice_detalle' id='nrolote$indice_detalle' value='$valor_nrolote' onKeyUp='javascript:this.value=this.value.toUpperCase();'>
			</td>";	
	}else{
		echo "<td align='center'>
			<input type='text' name='nrolote$indice_detalle' id='nrolote$indice_detalle' value='$valor_nrolote' onKeyUp='javascript:this.value=this.value.toUpperCase();' disabled>
			</td>";	
	}
	
	
	$var_fecha_vencimiento="fecha_vencimiento$indice_detalle";
	$valor_fecha_vencimiento=$$var_fecha_vencimiento;
	
	$valor_fecha_vencimiento=formateaFechaVista($valor_fecha_vencimiento);
	
	if($grupoIngreso==1){
		echo "<td align='center'>
		<INPUT type='date' value='$valor_fecha_vencimiento' id='fecha_vencimiento$indice_detalle' size='10' name='fecha_vencimiento$indice_detalle'>
		</td>";	
	}else{
		echo "<td align='center'>
		<INPUT type='date' value='$valor_fecha_vencimiento' id='fecha_vencimiento$indice_detalle' size='10' name='fecha_vencimiento$indice_detalle' disabled>
		</td>";
	}


	$var_cant_unit="cantidad_unitaria$indice_detalle";
	$valor_cant_unit=$$var_cant_unit;
	
	echo "<td align='center'><input type='number' min='1' max='5000000' name='cantidad_unitaria$indice_detalle'  id='cantidad_unitaria$indice_detalle' value='$valor_cant_unit'></td>";
	
	echo "</tr>";
}
echo "</table></center>";
echo "<div class='divBotones'>
<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_ingresomuestras.php?grupoIngreso=$grupoIngreso\"'>
</div>";
echo "</form>";
echo "</div></body>";
?>