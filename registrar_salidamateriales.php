<?php
require("conexion.inc");

echo "<script language='Javascript'>
	function enviar_form(f)
	{	f.submit();
	}
	function ver_detalle(f,indice)
	{	var material,cadena,ind,codigo_material;
		var i;
		cadena='materiales'+indice;
		for(i=0;i<=f.length-1;i++)
		{	if(f.elements[i].name==cadena)
			{	codigo_material=f.elements[i].value;
				ind=i+1;
			}
		}
		window.open('registrar_salidadetallemuestras.php?codigo_muestra='+codigo_material+'&indice='+ind+'','_blank',toolbar=0);
	}
	function validar(f)
	{
		var i,j,cantidad_material;
		variables=new Array(f.length-1);
		vector_material=new Array(30);
		vector_nrolote=new Array(30);
		vector_cantidades=new Array(30);
		vector_fechavenci=new Array(30);
		var indice,fecha, tipo_salida, almacen, funcionario, observaciones;
		fecha=f.fecha.value;
		tipo_salida=f.tipo_salida.value;
		observaciones=f.observaciones.value;
		cantidad_material=f.cantidad_material.value;
		almacen=f.almacen.value;
		funcionario=f.funcionario.value;
		territorio=f.territorio.value;
		if(f.fecha.value=='')
		{	alert('El campo Fecha esta vacio.');
			f.fecha.focus();
			return(false);
		}
		if(f.territorio.value=='')
		{	alert('El campo Territorio esta vacio.');
			f.territorio.focus();
			return(false);
		}
		if(f.almacen.value=='' && f.funcionario.value=='')
		{	alert('Al menos uno de los siguientes campos debe ser llenado (Almacen Destino, Funcionario).');
			f.focus();
			return(false);
		}
		for(i=6;i<=f.length-2;i++)
		{
			variables[i]=f.elements[i].value;
			if(f.elements[i].value=='')
			{
				alert('Algun elemento no tiene valor');
				return(false);
			}
		}
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
		var buscado,cant_buscado;
		for(k=0;k<=indice;k++)
		{	buscado=vector_material[k];
			cant_buscado=0;
			for(m=0;m<=indice;m++)
			{	if(buscado==vector_material[m])
				{	cant_buscado=cant_buscado+1;
				}
			}
			if(cant_buscado>1)
			{	alert('Los Materiales no pueden repetirse.');
				return(false);
			}
		}
		for(k=0;k<=f.length-1;k++)
		{	if(f.elements[k].name.indexOf('cantidad_unitaria')!=-1)
			{	cant_unitaria=f.elements[k].value*1;
				stock_unitario=f.elements[k-1].value*1
				if(cant_unitaria > stock_unitario)
				{	alert('No puede sacar cantidades superiores a lo que se tiene en stock.');
					f.elements[k].focus();
					return(false);
				}
			}
		}
		/*if(f.almacen.value!='' && f.funcionario.value!='')
		{	alert('Los campos Almacen Destino y Funcionario son excluyentes. Seleccione solamente un campo por favor.');
			return(false);
		}*/
		location.href='guarda_salidamateriales.php?vector_material='+vector_material+'&vector_fechavenci='+vector_fechavenci+'&vector_cantidades='+vector_cantidades+'&fecha='+fecha+'&tipo_salida='+tipo_salida+'&observaciones='+observaciones+'&cantidad_material='+cantidad_material+'&almacen='+almacen+'&funcionario='+funcionario+'&territorio='+territorio+'';
	}
	</script>";

if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}

if($fecha=="")
{	$fecha=date("d/m/Y");
}
$sql="select nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' and grupo_salida='2' order by cod_salida_almacenes desc";

//echo $sql;

$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo=1;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
echo "<form action='' method='get'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Registrar Salida de Almacen X</th></tr></table><br>";
echo "<table border='1' class='texto' cellspacing='0' align='center' width='90%'>";
echo "<tr><th>N&uacute;mero de Salida</th><th>Fecha</th><th>Tipo de Salida</th><th>Territorio Destino</th><th>Almacen Destino</th></tr>";
echo "<tr>";
echo "<td align='center'>$codigo</td>";
echo "<td align='center'>";
	echo"<INPUT type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
	echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
	/*echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
	echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
	echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
	echo" input_element_id='fecha'";
	echo" click_element_id='imagenFecha'></DLCALENDAR></td>";*/
echo "</td>";
$sql1="select cod_tiposalida, nombre_tiposalida from tipos_salida where tipo_almacen='$global_tipoalmacen' order by nombre_tiposalida";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='tipo_salida' class='texto' OnChange='enviar_form(this.form)'>";
echo "<option value=''></option>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tiposalida=$dat1[0];
	$nombre_tiposalida=$dat1[1];
	if($cod_tiposalida==$tipo_salida)
	{	echo "<option value='$cod_tiposalida' selected>$nombre_tiposalida</option>";
	}
	else
	{	echo "<option value='$cod_tiposalida'>$nombre_tiposalida</option>";
	}
}
echo "</select></td>";
$sql1="select * from ciudades order by descripcion";
$resp1=mysql_query($sql1);
echo "<td align='center'><select name='territorio' class='texto' OnChange='enviar_form(this.form)'>";
echo "<option value=''></option>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_ciudad=$dat1[0];
	$nombre_ciudad=$dat1[1];
	if($territorio==$cod_ciudad)
	{	echo "<option value='$cod_ciudad' selected>$nombre_ciudad</option>";
	}
	else
	{	echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
	}
}
echo "</select></td>";
$sql3="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$territorio' order by nombre_almacen";
//echo($sql3);
$resp3=mysql_query($sql3);
echo "<td align='center'><select name='almacen' class='texto'>";
while($dat3=mysql_fetch_array($resp3))
{	$cod_almacen=$dat3[0];
	$nombre_almacen="$dat3[1] $dat3[2] $dat3[3]";
	if($almacen==$cod_almacen)
	{	echo "<option value='$cod_almacen' selected>$nombre_almacen</option>";
	}
	else
	{	echo "<option value='$cod_almacen'>$nombre_almacen</option>";
	}
}
echo "</select></td></tr>";
echo "<tr><th colspan=2>Funcionario</th><th colspan=3>Observaciones</th></tr>";
if($tipo_salida==1000)
{	$sql2="select f.codigo_funcionario, f.paterno, f.materno, f.nombres, c.cargo from funcionarios f, cargos c
	where f.cod_ciudad='$territorio' and f.cod_cargo=c.cod_cargo and f.cod_cargo='1011'
	and f.estado=1 order by c.cargo,f.paterno, f.materno";
	$resp2=mysql_query($sql2);
}
echo "<tr><td align='center' colspan=2><select name='funcionario' class='texto'>";
echo "<option value=''></option>";
while($dat2=mysql_fetch_array($resp2))
{	$cod_funcionario=$dat2[0];
	$nombre_funcionario="$dat2[1] $dat2[2] $dat2[3]";
	$cargo=$dat2[4];
	if($cod_funcionario==$funcionario)
	{		echo "<option value='$cod_funcionario' selected>$nombre_funcionario ($cargo)</option>";
	}
	else
	{		echo "<option value='$cod_funcionario'>$nombre_funcionario ($cargo)</option>";
	}
}
echo "</select></td>";
echo "<td align='center' colspan=3><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td></tr>";
echo "</table><br>";
echo "<table border=1 class='texto' width='70%' align='center'>";
echo "<tr><th colspan='3'>Cantidad de Materiales a sacar:  <select name='cantidad_material' OnChange='enviar_form(this.form)' class='texto'>";
for($i=0;$i<=40;$i++)
{	if($i==$cantidad_material)
	{	echo "<option value='$i' selected>$i</option>";
	}
	else
	{	echo "<option value='$i'>$i</option>";
	}
}
echo "</select><th></tr>";
echo "<tr><th>&nbsp;</th><th>Material</th><th>Cantidad Unitaria</th><th>Stock</th></tr>";
for($indice_detalle=1;$indice_detalle<=$cantidad_material;$indice_detalle++)
{	echo "<tr><td align='center'>$indice_detalle</td>";
	
	if($global_almacen==1000){
		$sql_materiales="select codigo_material, descripcion_material from material_apoyo 
		where estado='Activo' and codigo_material not in (0) order by descripcion_material";
	}else{
		$sql_materiales="select codigo_material, descripcion_material from material_apoyo 
		where estado='Activo' order by descripcion_material";
	}
	
	$resp_materiales=mysql_query($sql_materiales);
	//obtenemos los valores de las variables creadas en tiempo de ejecucion
	$var_material="materiales$indice_detalle";
	$valor_material=$$var_material;
	//echo "<td align='center'><select name='materiales$indice_detalle' class='textomini' OnChange='enviar_form(this.form)'>";
	echo "<td align='center'><select name='materiales$indice_detalle' class='textomini'>";
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
	$var_cant_unit="cantidad_unitaria$indice_detalle";
	$valor_cant_unit=$$var_cant_unit;
	$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
	where id.cod_material='$valor_material' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$global_almacen'";
	
	//echo $sql_stock;
	
	$resp_stock=mysql_query($sql_stock);
	$dat_stock=mysql_fetch_array($resp_stock);
	$stock_real=$dat_stock[0];
	if($stock_real=="")
	{	$stock_real=0;
	}
	echo "<input type='hidden' name='stock$indice_detalle' value='$stock_real'>";
	echo "<td align='center'><input type='text' name='cantidad_unitaria$indice_detalle' value='$valor_cant_unit' class='texto''></td><td align='center'>$stock_real</td>";
	echo "</tr>";
}
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_salidamuestras.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
//echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "<center><input type='button' class='boton' value='Actualizar' onClick='enviar_form(this.form)'><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "</div></body>";
?>