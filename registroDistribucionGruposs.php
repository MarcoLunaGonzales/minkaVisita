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
		var valor, valor_planificado;
		var global_linea;
		global_linea=f.global_linea.value;
		var comp='valor_maximo';
		var suma_producto=0;
		for(i=1;i<=f.length-1;i++)
		{	if(f.elements[i].name.indexOf(comp)!=-1)
			{	//alert(suma_producto);
			    //alert(f.elements[i].value);
				if(suma_producto>f.elements[i].value)
				{	alert('No puede sacar cantidades superiores a las existentes en almacen. Item: '+f.elements[i].id);
					return(false);
				}
				suma_producto=0;
			}
			if(f.elements[i].type=='text' && f.elements[i].value!='')
			{	if(valor>valor_planificado)
				{	alert('No se pueden sacar cantidades mayores a las planificadas');
					alert(valor+','+valor_planificado);
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
	    ajax.open('POST', 'guardaRegistroDistribucionGrupos.php');
   		ajax.onreadystatechange=function()
		{
			if(ajax.readyState==1){
				div_contenido.innerHTML='CARGANDO...';
			}
			if (ajax.readyState==4) {
				//div_contenido.innerHTML=ajax.responseText;
				
				alert('Los datos se insertaron satisfactoriamente');
				
				location.href='registroDistribucionGrupos.php';
			}
		}
		ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
    	ajax.send('vector_campos='+vector_campos+'&global_linea='+global_linea);
		
	}
	function cerear(f, cod_prod)
	{	for(i=1;i<=f.length-1;i++)
		{	if(f.elements[i].name.indexOf(cod_prod)!=-1)
			{	f.elements[i].value=0;
			}
		}
	}
	</script>";
require("conexion.inc"); 
if($global_usuario==1052)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
	echo "<form name='form1' onSubmit='return false'>";
	echo "<input type='hidden' name='url_guardar' value=''>";
	$sql_nombrelinea="select nombre_linea from lineas where codigo_linea='$global_linea'";
	$resp_nombrelinea=mysql_query($sql_nombrelinea);
	$dat_nombrelinea=mysql_fetch_array($resp_nombrelinea);
	$nombre_linea=$dat_nombrelinea[0];
	echo "<input type='hidden' name='global_linea' value='$global_linea'>";
	echo "<center><table border='0' class='textotit'><tr><th>Distribución de Muestras x Grupos Especiales Gestión: $global_gestion_distribucion Ciclo: $global_ciclo_distribucion</th></tr></table></center><br>";
	$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
	$resp_territorios=mysql_query($sql_territorios);
	echo "<table border='1' class='texto' cellspacing='0' align='center'>";
	while($dat_territorios=mysql_fetch_array($resp_territorios))
	{	$cod_territorio=$dat_territorios[0];
		echo "<th colspan='3'><a href='registro_distribucion_visitadores.php?territorio=$cod_territorio&global_linea=$global_linea'>$dat_territorios[1]</a></th>";
	}
	echo "</table><br>";
	
	
	$sql_productos="
		select distinct(d.`codigo_producto`),
		(select concat(m.descripcion, ' ', m.presentacion) from `muestras_medicas` m where m.`codigo` = d.codigo_producto)
		from `distribucion_grupos_especiales` d where d.cod_ciclo = $global_ciclo_distribucion and d.`codigo_gestion` = '$global_gestion_distribucion' 
		and d.`grupo_salida`=1 order by 2";

		$resp_productos=mysql_query($sql_productos);
	$indice_productos=0;
	while($dat_productos=mysql_fetch_array($resp_productos))
	{	$indice_productos++;
		$productos[$indice_productos][1]=$dat_productos[0];
		$productos[$indice_productos][2]=$dat_productos[1];
	}
	
	$sql_productos="select distinct(d.`codigo_producto`),
		(select m.descripcion_material from `material_apoyo` m where m.`codigo_material` = d.codigo_producto)
		from `distribucion_grupos_especiales` d where d.cod_ciclo = $global_ciclo_distribucion and d.`codigo_gestion` = '$global_gestion_distribucion' 
		and d.`grupo_salida`=2 and d.codigo_producto<>0 order by 2";
	$resp_productos=mysql_query($sql_productos);
	while($dat_productos=mysql_fetch_array($resp_productos))
	{	$indice_productos++;
		$productos[$indice_productos][1]=$dat_productos[0];
		$productos[$indice_productos][2]="$dat_productos[1]";
	}
	
	$cad_3columns="<table border=1 class='textomini' width='100%'><tr><td width='30px'>CP</td><td width='30px'>CD</td><td width='30px'>CR</td></tr></table>";
	echo "<table border=1 class='texto' width='1600 px'><tr><th>Producto</th>";
	$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
	$resp_territorios=mysql_query($sql_territorios);
	while($dat_territorios=mysql_fetch_array($resp_territorios))
	{	echo "<th colspan='3'>$dat_territorios[1] $cad_3columns</th>";
	}
	echo "<th>Total Planificado</th><th>Total Distribuido</th><th>Existencias Almacen Central</th><th>Estado</th></tr>";
	for($j=1;$j<=$indice_productos;$j++)
	{	$codigo_producto=$productos[$j][1];
		$nombre_producto=$productos[$j][2];
		$cadena_producto="";
		$cadena_producto="$cadena_producto<tr><td width='220px'>$nombre_producto <a href=javascript:cerear(form1,'$codigo_producto')>Cerear</a></td>";
		$bandera=0;
		$suma_cant_distribuida;
		$suma_cant_producto=0;
		$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
		$resp_territorios=mysql_query($sql_territorios);
		while($dat_territorios=mysql_fetch_array($resp_territorios))
		{	//echo "hola";
			$cod_territorio=$dat_territorios[0];
			$nombre_territorio=$dat_territorios[1];
			$sql_lineaterritorio_producto="select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_grupos_especiales
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$cod_territorio' and
			codigo_producto='$codigo_producto'";
			//echo $sql_lineaterritorio_producto."<br>";
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
				<input type='text' class='texto' value='$cantidad_a_sacar' name='$cod_territorio|$codigo_producto|' size='3'>";
			}
			if($cantidad_planificada<=0)
			{	$cadena_txt="";
				$cantidad_planificada="";
				$cantidad_distribuida="";
			}
			if($cantidad_a_sacar==0)
			{	//$cadena_txt="<input type='hidden' name='' value='$valor_maximo_asacar'><input type='text' disabled='true' class='texto' value='' name='$cod_territorio|$codigo_producto|' size='3'>";
				$cadena_txt="<input type='hidden' name='' value='$valor_maximo_asacar'><input type='hidden' disabled='true' class='texto' value='' name='$cod_territorio|$codigo_producto|' size='3'>";
			}
			$cadena_producto="$cadena_producto
			<td bgcolor='#ffffcc' align='right' width='40px' title='$nombre_territorio $nombre_producto'>$cantidad_planificada&nbsp;</td><td align='right' bgcolor='#66ffcc' width='40px' title='$nombre_territorio $nombre_producto'>$cantidad_distribuida&nbsp;</td><td bgcolor='#ffcccc' width='40px' title='$nombre_territorio $nombre_producto'>&nbsp;$cadena_txt</td>";
			$suma_cant_producto=$suma_cant_producto+$cantidad_planificada;
			$suma_cant_distribuida=$suma_cant_distribuida+$cantidad_distribuida;
		}
		if($bandera==1)
		{	$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
			where id.cod_material='$codigo_producto' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='1000'";
			$resp_stock=mysql_query($sql_stock);
			$dat_stock=mysql_fetch_array($resp_stock);
			$stock_real=$dat_stock[0];
			if($stock_real=="")
			{	$stock_real=0;
			}
			//vemos cuanto se saco del item para el ciclo
			/*$sql_cant_sacada="select sum(cantidad_distribuida), sum(cantidad_sacadaalmacen) from distribucion_grupos_especiales where
			codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and codigo_linea='$global_linea' and
			codigo_producto='$codigo_producto'";*/
			$sql_cant_sacada="select sum(cantidad_distribuida), sum(cantidad_sacadaalmacen) from distribucion_grupos_especiales where
			codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and
			codigo_producto='$codigo_producto'";
			
			$resp_cant_sacada=mysql_query($sql_cant_sacada);
			$dat_cant_sacada=mysql_fetch_array($resp_cant_sacada);
			$cant_sacada_pais=$dat_cant_sacada[0];
			$cant_sacada_almacen=$dat_cant_sacada[1];
			
			//CANTIDAD SACADA DE LA DISTRIBUCION NORMAL
			$sql_cant_sacadaDistNormal="select sum(cantidad_distribuida), sum(cantidad_sacadaalmacen) from distribucion_productos_visitadores where
			codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and
			codigo_producto='$codigo_producto'";
			$resp_cant_sacadaDistNormal=mysql_query($sql_cant_sacadaDistNormal);
			$dat_cant_sacadaDistNormal=mysql_fetch_array($resp_cant_sacadaDistNormal);
			$cant_sacada_paisNormal=$dat_cant_sacadaDistNormal[0];
			$cant_sacada_almacenNormal=$dat_cant_sacadaDistNormal[1];
			//FIN CANTIDAD SACADA DISTRIBUCION NORMAL
			
			$sql_cant_sacadaLinea="select sum(cantidad_distribuida), sum(cantidad_sacadaalmacen) from distribucion_grupos_especiales where
			codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and
			codigo_producto='$codigo_producto'";
			
			$resp_cant_sacadaLinea=mysql_query($sql_cant_sacadaLinea);
			$dat_cant_sacada=mysql_fetch_array($resp_cant_sacadaLinea);
			$cant_sacada_linea=$dat_cant_sacada[0];
			
			
			//esta variable es para validar cantidades por item
			$maximo_a_sacarporitem=$stock_real-$cant_sacada_pais+$cant_sacada_almacen;
			$maximo_a_sacarporitem=$maximo_a_sacarporitem-$cant_sacada_paisNormal+$cant_sacada_almacenNormal;
			
			
			//echo $maximo_a_sacarporitem."<br>";
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
			$stock_disponible=$stock_real-$cant_sacada_pais+$cant_sacada_almacen;
			$cadena_producto="$cadena_producto<td align='right' width='40px'>$suma_cant_producto</td>
			<td>$cant_sacada_linea</td><td align='right'>$stock_disponible</td>
			<td align='center'>$img_estado</td></tr>";
			echo $cadena_producto;
			echo "<input type='hidden' id='$nombre_producto' name='valor_maximo$i' value='$maximo_a_sacarporitem'>";
		}
	}
	echo "<tr><th>Totales Muestras:</th>";
	$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
	$resp_territorios=mysql_query($sql_territorios);
	while($dat_territorios=mysql_fetch_array($resp_territorios))
	{	$cod_territorio=$dat_territorios[0];
		$nombre_territorio=$dat_territorios[1];
		$sql_totales="select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_grupos_especiales
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and 
			territorio='$cod_territorio' and grupo_salida=1";
		$resp_totales=mysql_query($sql_totales);
		$dat_totales=mysql_fetch_array($resp_totales);
		$total_territorio_planificado=$dat_totales[0];
		$total_territorio_distribuido=$dat_totales[1];
		echo "<th title='Cant. Planificada: $nombre_territorio'>&nbsp;$total_territorio_planificado</th>
		<th title='Cant. Distribuida: $nombre_territorio'>&nbsp;$total_territorio_distribuido</th><th>&nbsp;</th>";
	}
	echo "</tr>";
	echo "<tr><th>Totales MA:</th>";
	$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
	$resp_territorios=mysql_query($sql_territorios);
	while($dat_territorios=mysql_fetch_array($resp_territorios))
	{	$cod_territorio=$dat_territorios[0];
		$nombre_territorio=$dat_territorios[1];
		$sql_totales="select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_grupos_especiales
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and 
			territorio='$cod_territorio' and grupo_salida=2";
		$resp_totales=mysql_query($sql_totales);
		$dat_totales=mysql_fetch_array($resp_totales);
		$total_territorio_planificado=$dat_totales[0];
		$total_territorio_distribuido=$dat_totales[1];
		echo "<th title='Cant. Planificada: $nombre_territorio'>&nbsp;$total_territorio_planificado</th>
		<th title='Cant. Distribuida: $nombre_territorio'>&nbsp;$total_territorio_distribuido</th><th>&nbsp;</th>";
	}
	echo "</tr>";
	
	echo "</table>";
	
	echo"\n<br><table align='center'><tr><td><a href='navegador_lineas_distribucion.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><input type='button' onClick='envia_campos(this.form)' value='Guardar' class='boton'></center>";
	echo "<div id='div_contenido'></div>";
	echo "</form>";

?>