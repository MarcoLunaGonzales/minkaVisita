<?php
echo "<script language='JavaScript'>
	function envia_campos(f)
	{	var i, indice;
		vector_campos=new Array();
		var var_incompleta;
		indice=1;
		for(i=0;i<=f.length-1;i++)
		{	if(f.elements[i].type=='text')
			{	var_incompleta=f.elements[i].name;
				var_completa=f.elements[i].name+f.elements[i].value;
				vector_campos[indice]=var_completa;
				indice++;
			}
		}
		location.href='guarda_registro_districucionterritorios.php?vector_campos='+vector_campos+'';
	}
	</script>";
require("conexion.inc");
if($global_usuario==1032)
{	require("estilos_gerencia.inc");	
}
else
{	require("estilos_inicio_adm.inc");
}
	$rpt_territorio=$cod_territorio;
	$sql_cabecera_gestion=mysql_query("select codigo_gestion, nombre_gestion from gestiones where estado='Activo'");
	$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
	$codigo_gestion=$datos_cab_gestion[0];
	$nombre_cab_gestion=$datos_cab_gestion[1];
	
	$sql_ciclo="select cod_ciclo from ciclos where estado='Activo'";
	$resp_ciclo=mysql_query($sql_ciclo);
	$dat_ciclo=mysql_fetch_array($resp_ciclo);
	$ciclo_rpt=$dat_ciclo[0];
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
	echo "<form action='guarda_registro_distribucionterritorios.php' method='post'>";
	echo "<center><table border='0' class='textotit'><tr><th>Territorio: $nombre_territorio<br>Distribución de Muestras x Territorio Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	$sql_lineas="select codigo_linea, nombre_linea from lineas order by nombre_linea";
	$resp_lineas=mysql_query($sql_lineas);
	echo "<table class='texto' border='1' align='center'><tr><th colspan='7'>Seleccione una línea para realizar su distribucion detallada.</th></tr><tr>";
	while($dat_lineas=mysql_fetch_array($resp_lineas))
	{	$codigo_linea_dist=$dat_lineas[0];
		$nombre_linea_dist=$dat_lineas[1];
		echo "<td><a href='registro_distribucion_visitador.php?linea=$codigo_linea_dist&territorio=$rpt_territorio'>$nombre_linea_dist</a></td>";
	}
	echo "</tr></table><br>";
	//$cadena_subtabla="<table width='100%' class='textosupermini' border=1 cellspacing='0'><tr><th width='33%'>CP</th><th width='33%'>CS</th><th width='33%'>CD</th></tr></table>";
	$cadena_subtabla="<table class='textosupermini' border=1 cellspacing='0' width='100%'><tr><th width='33%'>CP</th><th width='33%'>CS</th><th width='33%'>CD</th></tr></table>";
	$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
	$resp_territorios=mysql_query($sql_territorios);
	echo "<table border='1' class='texto' cellspacing='0' width='1660'>";
	echo "<tr><th width='220px'>Producto</th>";
	while($dat_territorios=mysql_fetch_array($resp_territorios))
	{	$cod_territorio=$dat_territorios[0];
		$nombre_territorio=$dat_territorios[1];
		echo "<th colspan='3' width='120px'>$nombre_territorio $cadena_subtabla</th>";
	}
	echo "</tr>";
	$sql_productos="select codigo, descripcion, presentacion from muestras_medicas order by descripcion";
	$resp_productos=mysql_query($sql_productos);
	while($dat_productos=mysql_fetch_array($resp_productos))
	{	$codigo_producto=$dat_productos[0];
		$nombre_producto=$dat_productos[1];
		$presentacion_producto=$dat_productos[2];
		$cadena_producto="";
		$cadena_producto="$cadena_producto<tr><td width='220px'>$nombre_producto</td>";
		$bandera=0;
		$suma_cant_producto=0;
		$sql_territorios="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
		$resp_territorios=mysql_query($sql_territorios);
		while($dat_territorios=mysql_fetch_array($resp_territorios))
		{	$cod_territorio=$dat_territorios[0];
			$nombre_territorio=$dat_territorios[1];
			$sql_lineaterritorio_producto="select sum(cantidad_planificada), sum(cantidad_distribuida) from distribucion_productos_visitadores
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$cod_territorio' and 
			codigo_linea='$global_linea' and codigo_producto='$codigo_producto'";
			$resp_lineaterritorio_producto=mysql_query($sql_lineaterritorio_producto);
			$dat_lineaterritorio_producto=mysql_fetch_array($resp_lineaterritorio_producto);
			$cantidad_planificada=$dat_lineaterritorio_producto[0];	
			$cantidad_distribuida=$dat_lineaterritorio_producto[1];
			//echo $cantidad_planificada;
			$cantidad_a_sacar=$cantidad_planificada-$cantidad_distribuida;
			if($cantidad_planificada>0)
			{	$bandera=1;
				$cadena_txt="<input type='text' class='texto' value='$cantidad_a_sacar' name='$rpt_territorio|$codigo_linea|$codigo_producto|' size='3'>";
			}
			else
			{	$cadena_txt="";
			}
			$cadena_producto="$cadena_producto<td bgcolor='#ffffcc' align='right' width='40px'>$cantidad_planificada&nbsp;</td><td align='right' bgcolor='#66ffcc' width='40px'>$cantidad_distribuida&nbsp;</td><td bgcolor='#ffcccc' width='40px'>&nbsp;$cadena_txt</td>";
			$suma_cant_producto=$suma_cant_producto+$cantidad_planificada;
		}
		if($bandera==1)
		{	$sql_stock="select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
			where id.cod_material='$codigo_producto' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 
			and i.cod_almacen='1000'";
			$resp_stock=mysql_query($sql_stock);
			$dat_stock=mysql_fetch_array($resp_stock);
			$stock_real=$dat_stock[0];
			if($stock_real=="")
			{	$stock_real=0;
			}
			$cadena_producto="$cadena_producto<td align='right' width='40px'>$suma_cant_producto</td><td align='right'>$stock_real</td></tr>";		
			echo $cadena_producto;
		}
	}
	echo "</table>";
	echo"\n<br><table align='center'><tr><td><a href='navegador_distribucion_mmma.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><input type='button' onClick='envia_campos(this.form)' value='Guardar' class='boton'></center>";
	echo "</form>";
?>