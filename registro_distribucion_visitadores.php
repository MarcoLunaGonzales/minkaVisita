<?php
echo "<script language='JavaScript'>
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

	function envia_campos(f)
	{	var i, indice;
		vector_campos=new Array();
		var var_incompleta;
		indice=1;
		var comp='valor_maximo';
		var valor, valor_planificado;
		var global_linea, global_territorio;
		global_linea=f.global_linea.value;
		global_territorio=f.global_territorio.value;
		var suma_producto=0;
		for(i=1;i<=f.length-1;i++)
		{	if(f.elements[i].name.indexOf(comp)!=-1)
			{	if(suma_producto>f.elements[i].value)
				{	//alert(suma_producto);
				    //alert(f.elements[i].value);
					alert('No puede sacar cantidades superiores a las existentes en almacen. Item: '+f.elements[i].id);
					return(false);
				}
				suma_producto=0;
			}
			if(f.elements[i].type=='text' && f.elements[i].value!='')
			{	if(valor>valor_planificado)
				{	alert('No se pueden sacar cantidades mayores a las planificadas');
					f.elements[i].focus();
					return(false);
				}
				var_incompleta=f.elements[i].name;
				var_completa=f.elements[i].name+f.elements[i].value;
				vector_campos[indice]=var_completa;
				valor=f.elements[i].value*1;
				valor_planificado=f.elements[i-1].value*1;
				indice++;
				suma_producto=suma_producto+valor;
			}
		}
		ajax=nuevoAjax();
	    ajax.open('POST', 'guarda_registro_distribucionvisitadores.php');
   		ajax.onreadystatechange=function()
		{
			if (ajax.readyState==4) {
				alert('Los datos se insertaron satisfactoriamente');
				location.href='registro_distribucion_lineasterritorios1.php?global_linea=$global_linea';
			}
		}
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    	ajax.send('vector_campos='+vector_campos+'&global_linea='+global_linea+'&global_territorio='+global_territorio);
		
		//location.href='guarda_registro_distribucionvisitadores.php?vector_campos='+vector_campos+'&global_linea='+global_linea+'&global_territorio='+global_territorio+'';
	}
	</script>";
require("conexion.inc");
if($global_usuario==1032)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
	echo "<form action='guarda_registro_distribucionterritorios.php' method='post'>";
	$sql_nombrelinea="select nombre_linea from lineas where codigo_linea='$global_linea'";
	$resp_nombrelinea=mysql_query($sql_nombrelinea);
	$dat_nombrelinea=mysql_fetch_array($resp_nombrelinea);
	$nombre_linea=$dat_nombrelinea[0];
	echo "<input type='hidden' name='global_linea' value='$global_linea'>";
	echo "<input type='hidden' name='global_territorio' value='$territorio'>";
	echo "<center><table border='0' class='textotit'><tr><th>Distribución de Muestras x Línea <br>Territorio: Gestión: $global_gestion_distribucion Ciclo: $global_ciclo_distribucion Línea: $nombre_linea</th></tr></table></center><br>";
	$sql_productos="select codigo, descripcion, presentacion from muestras_medicas order by descripcion";
	$resp_productos=mysql_query($sql_productos);
	$indice_productos=0;
	while($dat_productos=mysql_fetch_array($resp_productos))
	{	$indice_productos++;
		$productos[$indice_productos][1]=$dat_productos[0];
		$productos[$indice_productos][2]="$dat_productos[1] $dat_productos[2]";
	}
	$sql_productos="select codigo_material, descripcion_material from material_apoyo order by descripcion_material";
	$resp_productos=mysql_query($sql_productos);
	while($dat_productos=mysql_fetch_array($resp_productos))
	{	$indice_productos++;
		$productos[$indice_productos][1]=$dat_productos[0];
		$productos[$indice_productos][2]="$dat_productos[1]";
	}
	$cad_3columns="<table border=1 class='textomini' width='100%'><tr><td width='30px'>CP</td><td width='30px'>CS</td><td width='30px'>CD</td></tr></table>";

	$sql_visitador="select f.codigo_funcionario, f.paterno, f.nombres from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' 
	and f.cod_ciudad='$territorio' and f.estado=1 and f.cod_cargo='1011' order by f.paterno, f.materno";
	$resp_visitador=mysql_query($sql_visitador);
	$num_visitadores=mysql_num_rows($resp_visitador);
	$ancho_tabla=($num_visitadores*120)+220+240;
	echo "<table border=1 class='texto' width='$ancho_tabla px' align='center'><tr><th>Producto</th>";
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	echo "<th colspan='3'>$dat_visitador[1] $cad_3columns</th>";
	}
	echo "<th>Total Planificado</th><th>Total Distribuido</th><th>Existencias Almacen Regional</th><th>Existencias Almacen Central</th><th>Estado</th></tr>";
	for($j=1;$j<=$indice_productos;$j++)
	{	$codigo_producto=$productos[$j][1];
		$nombre_producto=$productos[$j][2];
		$cadena_producto="";
		$cadena_producto="$cadena_producto<tr><td width='220px'>$nombre_producto</td>";
		$bandera=0;
		$suma_cant_producto=0;
		$suma_cant_distribuida=0;
		$sql_visitador="select f.codigo_funcionario, f.paterno, f.nombres from funcionarios f, funcionarios_lineas fl
		where f.codigo_funcionario=fl.codigo_funcionario and f.estado=1 
		and fl.codigo_linea='$global_linea' and f.cod_ciudad='$territorio' and f.cod_cargo='1011' order by f.paterno, f.materno";
		$resp_visitador=mysql_query($sql_visitador);
		$i=0;
		while($dat_visitador=mysql_fetch_array($resp_visitador))
		{		//echo "hola";
			$cod_visitador=$dat_visitador[0];
			$nombre_visitador="$dat_visitador[1] $dat_visitador[2]";
			$sql_lineaterritorio_producto="select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_productos_visitadores
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$territorio' and
			codigo_linea='$global_linea' and codigo_producto='$codigo_producto' and cod_visitador='$cod_visitador'";
			//echo $sql_lineaterritorio_producto;
			$resp_lineaterritorio_producto=mysql_query($sql_lineaterritorio_producto);
			$dat_lineaterritorio_producto=mysql_fetch_array($resp_lineaterritorio_producto);
			$cantidad_planificada=$dat_lineaterritorio_producto[0];
			$cantidad_distribuida=$dat_lineaterritorio_producto[1];
			$valor_maximo_asacar=$cantidad_planificada-$cantidad_distribuida;
			//echo $cantidad_planificada;
			$cantidad_a_sacar=$cantidad_planificada-$cantidad_distribuida;
			if($cantidad_planificada>0)
			{	$bandera=1;
				$cadena_txt="<input type='hidden' name='' value='$valor_maximo_asacar'>
				<input type='text' class='texto' value='$cantidad_a_sacar' name='$cod_visitador|$codigo_producto|' size='3'>";
			}
			if($cantidad_planificada<=0)
			{	$cadena_txt="";
				$cantidad_planificada="";
				$cantidad_distribuida="";
			}
			if($cantidad_a_sacar==0)
			{	$cadena_txt="<input type='hidden' name='' value='$valor_maximo_asacar'><input type='hidden' disabled='true' class='texto' value='' name='$cod_territorio|$codigo_producto|' size='3'>";
			}
			$cadena_producto="$cadena_producto<td bgcolor='#ffffcc' align='right' width='40px' title='$nombre_visitador $nombre_producto'>
			$cantidad_planificada&nbsp;</td><td align='right' bgcolor='#66ffcc' width='40px' title='$nombre_visitador $nombre_producto'>$cantidad_distribuida&nbsp;
			</td><td bgcolor='#ffcccc' width='40px' title='$nombre_visitador $nombre_producto'>&nbsp;$cadena_txt</td>";
			$suma_cant_producto=$suma_cant_producto+$cantidad_planificada;
			$suma_cant_distribuida=$suma_cant_distribuida+$cantidad_distribuida;

		}
		if($bandera==1)
		{	$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
			where id.cod_material='$codigo_producto' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and 
			i.ingreso_anulado=0 and i.cod_almacen='1000'";
			$resp_stock=mysql_query($sql_stock);
			$dat_stock=mysql_fetch_array($resp_stock);
			$stock_real=$dat_stock[0];
			if($stock_real=="")
			{	$stock_real=0;
			}
			$sql_almacen="select cod_almacen from almacenes where cod_ciudad='$territorio'";
			$resp_almacen=mysql_query($sql_almacen);
			$dat_almacen=mysql_fetch_array($resp_almacen);
			$cod_almacen=$dat_almacen[0];
			$sql_stock_regional="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
			where id.cod_material='$codigo_producto' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='$cod_almacen'";
			$resp_stock_regional=mysql_query($sql_stock_regional);
			$dat_stock_regional=mysql_fetch_array($resp_stock_regional);
			$stock_real_regional=$dat_stock_regional[0];
			if($stock_real_regional=="")
			{	$stock_real_regional=0;
			}
			//vemos cuanto se saco del item para el ciclo
			$sql_cant_sacada="select sum(cantidad_distribuida), sum(cantidad_sacadaalmacen) from distribucion_productos_visitadores where
			codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' 
			and territorio='$territorio' and codigo_producto='$codigo_producto'";
			$resp_cant_sacada=mysql_query($sql_cant_sacada);
			$dat_cant_sacada=mysql_fetch_array($resp_cant_sacada);
			$cant_sacada_pais=$dat_cant_sacada[0];
			$cant_sacada_paisalmacen=$dat_cant_sacada[1];
			//esta variable es para validar cantidades por item
			$maximo_a_sacarporitem=$stock_real-$cant_sacada_pais+$cant_sacada_paisalmacen;
			//echo $maximo_a_sacarporitem;
			if($cant_sacada_pais==$suma_cant_producto)
			{	$img_estado="<img src='imagenes/si.png'>";
			}
			if($cant_sacada_pais<$suma_cant_producto)
			{	$img_estado="<img src='imagenes/pendiente.png'>";
			}
			if($cant_sacada_pais==0)
			{	$img_estado="<img src='imagenes/no.png'>";
			}
			//stock disponible muestra la cantidad real menos la distribuida hasta el momento
			$stock_disponible=$stock_real-$cant_sacada_pais+$cant_sacada_paisalmacen;
			$cadena_producto="$cadena_producto<td align='right' width='40px'>$suma_cant_producto</td>
			<td align='center'>$suma_cant_distribuida</td><td align='right'>$stock_real_regional</td>
			<td align='right'>$stock_disponible</td><td align='center'>$img_estado</td></tr>";
			echo $cadena_producto;
			echo "<input type='hidden' id='$nombre_producto' name='valor_maximo$i' value='$maximo_a_sacarporitem'>";
		}
	}
	echo "<tr><th>Totales MM:</th>";
	$sql_visitador="select f.codigo_funcionario, f.paterno, f.nombres from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' 
	and f.cod_ciudad='$territorio' and f.estado=1 and f.cod_cargo='1011' order by f.paterno, f.materno";
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_funcionario=$dat_visitador[0];
		$nombre_funcionario=$dat_visitador[1];
		$sql_totales="select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_productos_visitadores
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and 
			territorio='$territorio' and cod_visitador='$codigo_funcionario' and codigo_linea='$global_linea'
			 and grupo_salida=1";
		$resp_totales=mysql_query($sql_totales);
		$dat_totales=mysql_fetch_array($resp_totales);
		$total_territorio_planificado=$dat_totales[0];
		$total_territorio_distribuido=$dat_totales[1];
		echo "<th title='Cant. Planificada: $nombre_funcionario'>&nbsp;$total_territorio_planificado</th>
		<th title='Cant. Distribuida: $nombre_funcionario'>&nbsp;$total_territorio_distribuido</th><th>&nbsp;</th>";
	}
	echo "</tr>";
	echo "<tr><th>Totales MA:</th>";
	$sql_visitador="select f.codigo_funcionario, f.paterno, f.nombres from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' 
	and f.cod_ciudad='$territorio' and f.estado=1 and f.cod_cargo='1011' order by f.paterno, f.materno";
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_funcionario=$dat_visitador[0];
		$nombre_funcionario=$dat_visitador[1];
		$sql_totales="select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_productos_visitadores
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and 
			territorio='$territorio' and cod_visitador='$codigo_funcionario' and codigo_linea='$global_linea'
			 and grupo_salida=2";
		$resp_totales=mysql_query($sql_totales);
		$dat_totales=mysql_fetch_array($resp_totales);
		$total_territorio_planificado=$dat_totales[0];
		$total_territorio_distribuido=$dat_totales[1];
		echo "<th title='Cant. Planificada: $nombre_funcionario'>&nbsp;$total_territorio_planificado</th>
		<th title='Cant. Distribuida: $nombre_funcionario'>&nbsp;$total_territorio_distribuido</th><th>&nbsp;</th>";
	}
	echo "</tr>";
	echo "</table>";
	echo"\n<br><table align='center'><tr><td><a href='registro_distribucion_lineasterritorios1.php?global_linea=$global_linea'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
	echo "<center><input type='button' onClick='envia_campos(this.form)' value='Guardar' class='boton'></center>";
	echo "</form>";
?>