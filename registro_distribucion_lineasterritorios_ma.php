<?php
echo "<script language='JavaScript'>
	function envia_campos(f)
	{	var i, indice;
		vector_campos=new Array();
		var var_incompleta;
		indice=1;
		var valor, valor_planificado;
		var global_linea;
		global_linea=f.global_linea.value;
		for(i=1;i<=f.length-1;i++)
		{	if(f.elements[i].type=='text' && f.elements[i].value!='')
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
			}
		}
		location.href='guarda_registro_distribucionlineasterritorios.php?vector_campos='+vector_campos+'&global_linea='+global_linea+'';
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
	echo "<input type='hidden' name='global_linea' value='$global_linea'>";
	echo "<center><table border='0' class='textotit'><tr><th>Distribución de Material de Apoyo x Línea Gestión: $global_gestion_distribucion Ciclo: $global_ciclo_distribucion</th></tr></table></center><br>";
	$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
	$resp_territorios=mysql_query($sql_territorios);
	echo "<table border='1' class='texto' cellspacing='0' align='center'>";
	while($dat_territorios=mysql_fetch_array($resp_territorios))
	{	$cod_territorio=$dat_territorios[0];
		echo "<th colspan='3'><a href='registro_distribucion_visitadores_ma.php?territorio=$cod_territorio&global_linea=$global_linea'>$dat_territorios[1]</a></th>";	
	}
	echo "</table><br>";
	$sql_productos="select codigo_material, descripcion_material from material_apoyo order by descripcion_material";
	$resp_productos=mysql_query($sql_productos);
	$cad_3columns="<table border=1 class='textomini' width='100%'><tr><td width='30px'>CP</td><td width='30px'>CS</td><td width='30px'>CD</td></tr></table>";
	echo "<table border=1 class='texto' width='1600 px'><tr><th>Producto</th>";
	$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
	$resp_territorios=mysql_query($sql_territorios);
	while($dat_territorios=mysql_fetch_array($resp_territorios))
	{	echo "<th colspan='3'>$dat_territorios[1] $cad_3columns</th>";	
	}
	echo "<th>Total Planificado</th><th>Existencias Almacen Central</th><th>Estado</th></tr>";
	while($dat_productos=mysql_fetch_array($resp_productos))
	{	$codigo_producto=$dat_productos[0];
		$nombre_producto=$dat_productos[1];
		$cadena_producto="";
		$cadena_producto="$cadena_producto<tr><td width='220px'>$nombre_producto</td>";
		$bandera=0;
		$suma_cant_producto=0;
		$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
		$resp_territorios=mysql_query($sql_territorios);
		while($dat_territorios=mysql_fetch_array($resp_territorios))
		{	//echo "hola";
			$cod_territorio=$dat_territorios[0];
			$nombre_territorio=$dat_territorios[1];
			$sql_lineaterritorio_producto="select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_productos_visitadores
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$cod_territorio' and 
			codigo_linea='$global_linea' and codigo_producto='$codigo_producto'";
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
				$cadena_txt="<input type='hidden' name='' value='$valor_maximo_asacar'><input type='text' class='texto' value='$cantidad_a_sacar' name='$cod_territorio|$codigo_producto|' size='3'>";
			}
			if($cantidad_planificada<=0)
			{	$cadena_txt="";
			}
			if($cantidad_a_sacar==0)
			{	$cadena_txt="<input type='hidden' name='' value='$valor_maximo_asacar'><input type='text' disabled='true' class='texto' value='' name='$cod_territorio|$codigo_producto|' size='3'>";	
			}
			$cadena_producto="$cadena_producto<td bgcolor='#ffffcc' align='right' width='40px'>$cantidad_planificada&nbsp;</td><td align='right' bgcolor='#66ffcc' width='40px'>$cantidad_distribuida&nbsp;</td><td bgcolor='#ffcccc' width='40px'>&nbsp;$cadena_txt</td>";
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
			if($suma_cant_distribuida==$suma_cant_producto)
			{	$img_estado="<img src='imagenes/si.png'>";
			}
			if($suma_cant_distribuida<$suma_cant_producto)
			{	$img_estado="<img src='imagenes/pendiente.png'>";
			}
			if($cantidad_distribuida==0)
			{	$img_estado="<img src='imagenes/no.png'>";
			}
			$cadena_producto="$cadena_producto<td align='right' width='40px'>$suma_cant_producto</td><td align='right'>$stock_real</td><td align='center'>$img_estado</td></tr>";		
			echo $cadena_producto;
		}
	}
	echo "</table>";
	echo"\n<br><table align='center'><tr><td><a href='navegador_lineas_distribucion.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><input type='button' onClick='envia_campos(this.form)' value='Guardar' class='boton'></center>";
	echo "</form>";
?>