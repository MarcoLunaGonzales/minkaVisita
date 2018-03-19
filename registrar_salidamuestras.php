 <!--meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 <script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
 <script type='text/javascript' language='javascript'-->

<?php
error_reporting(0);
require("conexion.inc");
require("estilos_almacenes.inc");

echo "<script language='Javascript'>
	function nuevoAjax(){	
	var xmlhttp=false;
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
	function ajaxStock(ind, grupoSalida){
		var codmat=document.getElementById('materiales'+ind).value;
		var codalm=document.getElementById('codalmacen').value;
		
		var contenedor;
		contenedor = document.getElementById('idstock'+ind);
		ajax=nuevoAjax();
		ajax.open('GET', 'programas/salidas/ajaxStockSalidaMateriales.php?codmat='+codmat+'&codalm='+codalm+'&indice='+ind+'&grupoSalida='+grupoSalida,true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				contenedor.innerHTML = ajax.responseText
			}
		}
		ajax.send(null)
	}	
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
	function validar(f, grupoSalida)
	{
		var i,j,cantidad_material, cant_unitaria, stock_unitario;
		variables=new Array(f.length-1);
		vector_material=new Array(100);
		vector_nrolote=new Array(100);
		vector_cantidades=new Array(100);
		vector_fechavenci=new Array(100);
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

		//validamos los elementos formatos y demas situaciones
		var nroFilasDet=f.cantidad_material.value;
		for(xx=1;xx<=nroFilasDet;xx++){
			if(document.getElementById('materiales'+xx).value==''){
				alert('El item no puede estar vacio. Fila: '+xx);
				return(false);
			}
			if(document.getElementById('cantidad_unitaria'+xx).value==''){
				alert('La cantidad no puede estar vacia. Fila: '+xx);
				return(false);
			}
			if(document.getElementById('stock'+xx).value==''){
				alert('No se tiene el stock del item. Favor volver a escoger el item. Fila: '+xx);
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
		
		for(xx=1;xx<=nroFilasDet;xx++){
			var cantidadX, stockX;
			cantidadX=document.getElementById('cantidad_unitaria'+xx).value*1;
			stockX=document.getElementById('stock'+xx).value*1;
			if(cantidadX > stockX){	
				//alert('No puede sacar cantidades superiores al stock. Fila: '+xx+ cantidadX+ ' ' +stockX);
				alert('No puede sacar cantidades superiores al stock. Fila: '+xx);
				return(false);
			}
		}
		location.href='guarda_salidamuestras.php?vector_material='+vector_material+'&vector_fechavenci='+vector_fechavenci+'&vector_cantidades='+vector_cantidades+'&fecha='+fecha+'&tipo_salida='+tipo_salida+'&observaciones='+observaciones+'&cantidad_material='+cantidad_material+'&almacen='+almacen+'&funcionario='+funcionario+'&territorio='+territorio+'&grupoSalida='+grupoSalida+'';
	}
	</script>";


if ($fecha == "") {
    $fecha = date("d/m/Y");
}
echo "<form action='' method='get'>";

$grupoSalida=$_GET["grupoSalida"];

echo "<input type='hidden' name='grupoSalida' value='$grupoSalida' id='grupoSalida'>";
echo "<input type='hidden' name='codalmacen' value='$global_almacen' id='codalmacen'>";


if($grupoSalida==1){
	echo "<h1>Registrar Salida de Muestras</h1>";	
}else{
	echo "<h1>Registrar Salida de Material</h1>";
}

echo "<center><table class='texto'>";
echo "<tr><th>Nro. Salida</th><th>Fecha</th><th>Tipo de Salida</th><th>Territorio Destino</th><th>Almacen Destino</th></tr>";

$sql = "select max(nro_correlativo) as nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' and grupo_salida='$grupoSalida' ";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$num_filas = mysql_num_rows($resp);
if ($num_filas == 0) {
    $codigo = 1;
} else {
    $codigo = $dat[0];
    $codigo++;
}
echo "<tr>";
echo "<td align='center'>$codigo</td>";
echo "<td align='center'>";
echo"<INPUT type='text' disabled='true' class='texto' value='$fecha' id='fecha' size='10' name='fecha'>";
echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
/* echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
  echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
  echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
  echo" input_element_id='fecha'";
  echo" click_element_id='imagenFecha'></DLCALENDAR></td>"; */
echo "</td>";
$sql1 = "select cod_tiposalida, nombre_tiposalida from tipos_salida where tipo_almacen='$global_tipoalmacen'
order by nombre_tiposalida";
$resp1 = mysql_query($sql1);
echo "<td align='center'><select name='tipo_salida' class='texto' OnChange='enviar_form(this.form)'>";
echo "<option value=''></option>";
while ($dat1 = mysql_fetch_array($resp1)) {
    $cod_tiposalida = $dat1[0];
    $nombre_tiposalida = $dat1[1];
    if ($cod_tiposalida == $tipo_salida) {
        echo "<option value='$cod_tiposalida' selected>$nombre_tiposalida</option>";
    } else {
        echo "<option value='$cod_tiposalida'>$nombre_tiposalida</option>";
    }
}
echo "</select></td>";
$sql1 = "select * from ciudades order by descripcion";
$resp1 = mysql_query($sql1);
echo "<td align='center'><select name='territorio' class='texto' OnChange='enviar_form(this.form)'>";
echo "<option value=''></option>";
while ($dat1 = mysql_fetch_array($resp1)) {
    $cod_ciudad = $dat1[0];
    $nombre_ciudad = $dat1[1];
    if ($territorio == $cod_ciudad) {
        echo "<option value='$cod_ciudad' selected>$nombre_ciudad</option>";
    } else {
        echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
    }
}
echo "</select></td>";
$sql3 = "select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$territorio' order by nombre_almacen";
$resp3 = mysql_query($sql3);
echo "<td align='center'><select name='almacen' class='texto'>";
while ($dat3 = mysql_fetch_array($resp3)) {
    $cod_almacen = $dat3[0];
    $nombre_almacen = "$dat3[1] $dat3[2] $dat3[3]";
    if ($almacen == $cod_almacen) {
        echo "<option value='$cod_almacen' selected>$nombre_almacen</option>";
    } else {
        echo "<option value='$cod_almacen'>$nombre_almacen</option>";
    }
}
echo "</select></td></tr>";
echo "<tr><th colspan=2>Funcionario</th><th colspan=3>Observaciones</th></tr>";
if ($tipo_salida == 1000) {
    $sql2 = "select f.codigo_funcionario, f.paterno, f.materno, f.nombres, c.cargo from funcionarios f, cargos c
	where f.cod_ciudad='$territorio' and f.cod_cargo=c.cod_cargo and f.cod_cargo='1011'
	and f.estado=1 order by c.cargo,f.paterno, f.materno";
    $resp2 = mysql_query($sql2);
}
echo "<tr><td align='center' colspan=2><select name='funcionario' class='texto'>";
echo "<option value=''></option>";
while ($dat2 = mysql_fetch_array($resp2)) {
    $cod_funcionario = $dat2[0];
    $nombre_funcionario = "$dat2[1] $dat2[2] $dat2[3]";
    $cargo = $dat2[4];
    if ($cod_funcionario == $funcionario) {
        echo "<option value='$cod_funcionario' selected>$nombre_funcionario ($cargo)</option>";
    } else {
        echo "<option value='$cod_funcionario'>$nombre_funcionario ($cargo)</option>";
    }
}
echo "</select></td>";
echo "<td align='center' colspan=3><input type='text' class='texto' name='observaciones' value='$observaciones' size='100'></td></tr>";
echo "</table><br>";


echo "<table class='texto'>";
echo "<tr><th colspan='3'>Cantidad de Materiales a sacar:  <select name='cantidad_material' OnChange='enviar_form(this.form)' class='texto'>";
for ($i = 0; $i <= 50; $i++) {
    if ($i == $cantidad_material) {
        echo "<option value='$i' selected>$i</option>";
    } else {
        echo "<option value='$i'>$i</option>";
    }
}
echo "</select><th></tr>";
echo "<tr><th>&nbsp;</th><th>Material</th><th>Cantidad Unitaria</th><th>Stock</th></tr>";
for ($indice_detalle = 1; $indice_detalle <= $cantidad_material; $indice_detalle++) {
    echo "<tr><td align='center'>$indice_detalle</td>";
	
	if($grupoSalida==1){
		$sql_materiales = "select codigo, concat(descripcion,' ',presentacion) from muestras_medicas 
		where codigo not in ('0') order by 2";			
	}else{
		$sql_materiales = "select codigo_material, descripcion_material from material_apoyo 
		where codigo_material not in ('0') order by 2";		
	}

	
	//echo $sql_materiales;
	$resp_materiales = mysql_query($sql_materiales);
    //obtenemos los valores de las variables creadas en tiempo de ejecucion
    $var_material = "materiales$indice_detalle";
    $valor_material = $$var_material;
    echo "<td align='center'><select id='materiales$indice_detalle' name='materiales$indice_detalle' style='width:450px' onChange='ajaxStock($indice_detalle,$grupoSalida)'>";
    echo "<option></option>";
    while ($dat_materiales = mysql_fetch_array($resp_materiales)) {
        $cod_material = $dat_materiales[0];
        $nombre_material = $dat_materiales[1];
        if ($cod_material == $valor_material) {
            echo "<option value='$cod_material' selected>$nombre_material</option>";
        } else {
            echo "<option value='$cod_material'>$nombre_material</option>";
        }
    }
    echo "</select></td>";
    $var_cant_unit = "cantidad_unitaria$indice_detalle";
    $valor_cant_unit = $$var_cant_unit;
	
    //echo "<input type='hidden' name='stock$indice_detalle' id='stock$indice_detalle'  value='$stock_real'>";
	echo "<td align='center'><input type='number' min='1' name='cantidad_unitaria$indice_detalle' id='cantidad_unitaria$indice_detalle' value='$valor_cant_unit'></td>";
	 
	echo "<td><div id='idstock$indice_detalle'>";
    echo "<input type='text' id='stock$indice_detalle' name='stock$indice_detalle' value='$stock_real' size='9' readonly>";
    echo "</div></td>";
	
    echo "</tr>";
}
echo "</table><br></center>";

//echo"\n<table align='center'><tr><td><a href='navegador_salidamuestras.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";

echo "<div class='divBotones'>
<input type='button' class='boton' value='Guardar' onClick='validar(this.form, $grupoSalida)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_salidamuestras.php?grupoSalida=$grupoSalida\"'>
</div>";

echo "</form>";
echo "</div></body>";
?>