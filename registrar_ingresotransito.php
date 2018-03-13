<?php
require("conexion.inc");
require("estilos_almacenes.inc");

echo "<script language='Javascript'>
	function validar(f, grupoIngreso)
	{
		var i,j,cantidad_material,nombre_tipo, codigo_salida, codigo_funcionario, nota_ingreso;
		variables=new Array(f.length-1);
		vector_material=new Array(30);
		vector_fechavenci=new Array(30);
		vector_cantidad_parcial=new Array(30);
		vector_obs=new Array(30);
		vector_cantidades=new Array(30);
		vector_tipomaterial=new Array(30);
		vector_tipoobs=new Array(30);
		vector_chk=new Array(30);
		var indice,fecha, tipo_ingreso, observaciones;
		fecha=f.fecha.value;
		codigo_funcionario=f.codigo_funcionario.value;
		observaciones=f.observaciones.value;
		cantidad_material=f.cantidad_material.value;
		codigo_salida=f.codigo_salida.value;
		nota_ingreso=f.nota_ingreso.value;
		if(f.fecha.value=='')
		{	alert('El campo Fecha esta vacio.');
			f.fecha.focus();	
			return(false);
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('chk')!=-1)	
			{	vector_chk[indice]=f.elements[j].checked;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('tipo_obs')!=-1)	
			{	vector_tipoobs[indice]=f.elements[j].value;
				indice++;	
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
			if(f.elements[j].name.indexOf('cantidad_unitaria')!=-1)	
			{	vector_cantidades[indice]=f.elements[j].value;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('cantidad_parcial')!=-1)	
			{	vector_cantidad_parcial[indice]=f.elements[j].value;
				indice++;	
			}
		}
		indice=0;
		for(j=0;j<=f.length-1;j++)
		{
			if(f.elements[j].name.indexOf('observacionesingreso')!=-1)	
			{	vector_obs[indice]=f.elements[j].value;
				indice++;	
			}
		}
		location.href='guarda_ingresotransito.php?vector_material='+vector_material+'&vector_cantidad_parcial='+vector_cantidad_parcial+'&vector_cantidades='+vector_cantidades+'&fecha='+fecha+'&tipo_ingreso='+observaciones+'&cantidad_material='+cantidad_material+'&vector_chk='+vector_chk+'&vector_tipoobs='+vector_tipoobs+'&vector_obs='+vector_obs+'&codigo_salida='+codigo_salida+'&nota_ingreso='+nota_ingreso+'&codigo_funcionario='+codigo_funcionario+'&grupoIngreso='+grupoIngreso;
	}
	function habilita_tipoobs(chk,f)
	{	var j;
		for(j=0;j<=f.length-1;j++)
		{	if(f.elements[j].name==chk.name)	
			{	if(chk.checked==true)
				{	f.elements[j+1].disabled=false;
					f.elements[j+2].disabled=false;
				}
				else
				{	f.elements[j+1].disabled=true;
					f.elements[j+2].disabled=true;
				}
			}
		}
		return(false);
	}
	</script>";

	
$grupoIngreso=$_GET["grupoIngreso"];
$sql_salida_visitador="select * from salida_detalle_visitador where cod_salida_almacen='$codigo_registro'";
$resp_salida_visitador=mysql_query($sql_salida_visitador);
$dat_salida_visitador=mysql_fetch_array($resp_salida_visitador);
$codigo_funcionario=$dat_salida_visitador[1];
if($fecha=="")
{	$fecha=date("Y-m-d");
}
$sql_datos_salidaorigen="select s.nro_correlativo, s.cod_tiposalida, a.nombre_almacen from salida_almacenes s, almacenes a
where a.cod_almacen=s.cod_almacen and s.cod_salida_almacenes='$codigo_registro'";
$resp_datos_salidaorigen=mysql_query($sql_datos_salidaorigen);
$datos_salidaorigen=mysql_fetch_array($resp_datos_salidaorigen);
$correlativo_salidaorigen=$datos_salidaorigen[0];
$tipo_salidaorigen=$datos_salidaorigen[1];
$nombre_almacen_origen=$datos_salidaorigen[2];

echo "<form action='' method='post'>";
echo "<input type='hidden' value='$codigo_funcionario' name='codigo_funcionario'>";
echo "<input type='hidden' value='$grupoIngreso' name='grupoIngreso'>";

if($grupoIngreso==1){
	echo "<h1>Aceptar Traspaso de Muestras</h1>";	
}else{
	echo "<h1>Aceptar Traspaso de Material</h1>";
}


echo "<center><table class='texto'>";
echo "<tr><th>Fecha</th><th>Nota de Ingreso</th><th>Observaciones</th></tr>";
echo "<tr><td>";
	
	echo"<INPUT type='date' class='texto' value='$fecha' id='fecha' size='10' name='fecha' readonly></td>";
	
echo "<td><input type='text' disabled='true' size='40' name='' value='Salida:$correlativo_salidaorigen $nombre_almacen_origen' class='texto'></td>";
echo "<input type='hidden' name='nota_ingreso' value='Salida:$correlativo_salidaorigen $nombre_almacen_origen'>";

echo "<td align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='60'></td></tr>";
echo "</table><br>";


echo "<table class='texto'>";

$sql_detalle_salida="select * from salida_detalle_almacenes where cod_salida_almacen='$codigo_registro' and cantidad_unitaria>0";
$resp_detalle_salida=mysql_query($sql_detalle_salida);
$cantidad_materiales=mysql_num_rows($resp_detalle_salida);

echo "<input type='hidden' name='codigo_salida' value='$codigo_registro'>";
echo "<input type='hidden' name='cantidad_material' value='$cantidad_materiales'>";
echo "<tr><th width='5%'>&nbsp;</th><th width='45%'>Material</th><th width='25%'>Cantidad de Origen</th><th>Cantidad Recibida</th><th>Obs.?</th><th>Tipo de Obs.</th><th>Observaciones</th></tr>";

$indice_detalle=1;

while($dat_detalle_salida=mysql_fetch_array($resp_detalle_salida))
{	$cod_material=$dat_detalle_salida[1];
	$cantidad_unitaria=$dat_detalle_salida[2];
	echo "<tr><td align='center'>$indice_detalle</td>";
	if($grupoIngreso==1)
	{	$sql_materiales="select codigo, concat(descripcion,' ',presentacion) from muestras_medicas where codigo='$cod_material' order by descripcion";
		$resp_materiales=mysql_query($sql_materiales);
		$dat_materiales=mysql_fetch_array($resp_materiales);
		$nombre_material=$dat_materiales[1];	
	}
	if($grupoIngreso==2)
	{	$sql_materiales="select codigo_material, descripcion_material from material_apoyo where codigo_material='$cod_material' and codigo_material<>0 order by descripcion_material";
		$resp_materiales=mysql_query($sql_materiales);
		$dat_materiales=mysql_fetch_array($resp_materiales);
		$nombre_material=$dat_materiales[1];
	}
	echo "<td>$nombre_material</td>";
	echo "<input type='hidden' value='$cod_material' name='materiales$indice_detalle'>";
	echo "<input type='hidden' value='$cantidad_unitaria' name='cantidad_unitaria$indice_detalle'>";
	echo "<td align='center'>$cantidad_unitaria</td>";
	echo "<td><input type='number' name='cantidad_parcial$indice_detalle' value='$cantidad_unitaria' min='1'></td>";
	echo "<td><input type='checkbox' name='chk_observaciones$indice_detalle' onClick='habilita_tipoobs(this,this.form)'></td>";
	echo "<td><select name='tipo_obs$indice_detalle' class='texto' disabled='true'>
		  <option value='1'>Faltante</option>
		  <option value='2'>Sobrante</option>
		  <option value='3'>Mal Estado</option>
		  </select></td>";
	echo "<td><input type='text' size='50' name='observacionesingreso$indice_detalle' disabled='true' class='texto'></td>";
	//echo "<td align='center'><input type='checkbox' OnClick='activar_entrega_parcial(this.form,$indice_detalle)' name='codigoingreso$indice_detalle' value='$cod_material'></td>";
	$indice_detalle++;
}
echo "</table></center>";

echo"<div class='divBotones'>
		<input type='button' class='boton' value='Guardar' onClick='validar(this.form, $grupoIngreso)'>
		<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_ingresomuestrastransito.php?grupoIngreso=$grupoIngreso\"'>
	</div>";

echo "</form>";
echo "</div></body>";
?>