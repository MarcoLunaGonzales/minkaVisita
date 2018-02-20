<?php
echo "<script language='Javascript'>
	function enviar_form(f)
	{	f.submit();
	}
	function validar(f)
	{	var indice, numero_materiales,codigo_ciclo, grupo_salida,codigo_linea, cod_visitador,cod_territorio,codigo_salida_visitador;
		vector_material=new Array(50);
		vector_cantidades=new Array(50);
		vector_cantidadesplanificadas=new Array(50);
		var indice,fecha, tipo_salida, almacen, funcionario, observaciones,cant_unitaria,stock_unitario;
		fecha=f.fecha.value;
		observaciones=f.observaciones.value;
		grupo_salida=f.grupo_salida.value;
		nro_materiales=f.nro_mat.value;
		codigo_salida_visitador=f.codigo_salida_visitador.value;
		codigo_ciclo=f.codigo_ciclo.value;
		codigo_linea=f.codigo_linea.value;
		cod_visitador=f.cod_visitador.value;
		cod_territorio=f.cod_territorio.value;
		tipo_salida=f.tipo_salida.value;
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
			if(f.elements[j].name.indexOf('cantidad_planificada')!=-1)	
			{	vector_cantidadesplanificadas[indice]=f.elements[j].value;
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
		for(k=0;k<=f.length-1;k++)
		{	if(f.elements[k].name.indexOf('cantidad_unitaria')!=-1)	
			{	cant_unitaria=f.elements[k].value*1;
				stock_unitario=f.elements[k+1].value*1
				if(cant_unitaria > stock_unitario)
				{	alert('No puede sacar cantidades superiores a lo que se tiene en stock.');
					f.elements[k].focus();
					return(false);
				}
			}
		} 
		location.href='guarda_modisalidacicloentero.php?vector_material='+vector_material+'&vector_cantidadesplanificadas='+vector_cantidadesplanificadas+'&vector_cantidades='+vector_cantidades+'&fecha='+fecha+'&tipo_salida='+tipo_salida+'&nro_materiales='+nro_materiales+'&observaciones='+observaciones+'&codigo_ciclo='+codigo_ciclo+'&codigo_linea='+codigo_linea+'&cod_visitador='+cod_visitador+'&cod_territorio='+cod_territorio+'&codigo_salida_visitador='+codigo_salida_visitador+'&grupo_salida='+grupo_salida+'';
	}
	</script>";
require("conexion.inc");
require('estilos_almacenes_central.inc');
require('function_cantrequeridanacional.php');
$global_linea=$codigo_linea;
$global_visitador=$cod_visitador;
$rpt_territorio=$cod_territorio;
$ciclo_rpt=$codigo_ciclo;
$sql_gestion="select codigo_gestion from gestiones where estado='Activo' and codigo_linea='$global_linea'";
$resp_gestion=mysql_query($sql_gestion);
$dat_gestion=mysql_fetch_array($resp_gestion);
$gestion_rpt=$dat_gestion[0];
	$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
	$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
	$nombre_cab_gestion=$datos_cab_gestion[0];
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	

	$sql_visitador="select paterno, materno, nombres
	from funcionarios f	where codigo_funcionario='$global_visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	echo "<center><table border='0' class='textotit'><tr><th>Territorio: $nombre_territorio<br>Visitador <font color='#2299ff'>$nombre_visitador</font> Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	//echo "<center><table border='1' class='texto' width='35%' cellspacing='0'>";
	//echo "<tr><th>Producto</th><th>Cantidad</th></tr>";
	$sql_especialidad="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
	$resp_especialidad=mysql_query($sql_especialidad);
	$numero_total_medicos=0;
	//formamos la matriz de muestras y material promocional
	$indice_m_m=0;
	if($grupo_salida==1)
	{	$sql_muestras_material="select DISTINCT(pd.codigo_muestra) from parrilla p, parrilla_detalle pd, muestras_medicas m
	      where p.cod_ciclo='$ciclo_rpt' and p.codigo_linea='$global_linea' and p.codigo_parrilla=pd.codigo_parrilla and pd.codigo_muestra=m.codigo order by m.descripcion";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
			$indice_m_m++;
		}
	}
	if($grupo_salida==2)
	{	$sql_muestras_material="select DISTINCT(pd.codigo_material) from parrilla p, parrilla_detalle pd, material_apoyo m
	      where p.cod_ciclo='$ciclo_rpt' and p.codigo_linea='$global_linea' and p.codigo_parrilla=pd.codigo_parrilla and pd.codigo_material=m.codigo_material order by m.descripcion_material";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
			$indice_m_m++;
		}
	}	
	//fin formar matriz de muestras y material promocional
	while($dat_espe=mysql_fetch_array($resp_especialidad))
	{	$cod_especialidad=$dat_espe[0];
		$desc_especialidad=$dat_espe[1];
		$sql_medicos="select DISTINCT (rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med
		from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
		where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.estado_aprobado='1' and rmc.codigo_linea='$global_linea' and rmc.cod_visitador='$global_visitador' and rmd.cod_especialidad='$cod_especialidad' order by m.ap_pat_med, m.ap_mat_med, m.nom_med";
		$resp_medicos=mysql_query($sql_medicos);
		$num_filas=mysql_num_rows($resp_medicos);
		$numero_total_medicos=$numero_total_medicos+$num_filas;
		$numero_a=0;
		$numero_b=0;
		while($dat_medicos=mysql_fetch_array($resp_medicos))
		{	$cod_med=$dat_medicos[0];
			$sql_cant_contactos="select rmd.cod_med
			from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
			where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$global_linea' and rmc.cod_visitador='$global_visitador' and rmd.cod_especialidad='$cod_especialidad' and rmd.cod_med='$cod_med'";
			$resp_cant_contactos=mysql_query($sql_cant_contactos);
			$num_contactos=mysql_num_rows($resp_cant_contactos);
			$categoria_med=$dat_medicos[4];
			if($categoria_med=="A")
			{	$numero_a++;
			}
			if($categoria_med=="B")
			{	$numero_b++;
			}
		}
		if($num_filas!=0)
		{	$sql_parrilla="select pd.codigo_muestra, pd.cantidad_muestra, pd.codigo_material, pd.cantidad_material, p.categoria_med
			from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$global_linea' and p.cod_ciclo='$ciclo_rpt' and 
			p.cod_especialidad='$cod_especialidad'";
			$resp_parrilla=mysql_query($sql_parrilla);
			while($dat_parrilla=mysql_fetch_array($resp_parrilla))
			{	$muestra=$dat_parrilla[0]; $cant_muestra=$dat_parrilla[1];
				$material=$dat_parrilla[2];$cant_material=$dat_parrilla[3];
				$categoria_parrilla=$dat_parrilla[4];
				if($categoria_parrilla=="A")
				{	$cantidad_subtotal_muestra=$cant_muestra*$numero_a;
					$cantidad_subtotal_material=$cant_material*$numero_a;
				}
				else
				{	$cantidad_subtotal_muestra=$cant_muestra*$numero_b;
					$cantidad_subtotal_material=$cant_material*$numero_b;
				}
				for($i=0;$i<=$indice_m_m;$i++)
				{	if($matriz_m_m[$i][1]==$muestra)
					{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+$cantidad_subtotal_muestra;
					}
					if($matriz_m_m[$i][1]==$material)
					{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+$cantidad_subtotal_material;
					}
				}	
			}
		}
	}
	echo "</table>";
echo "<form action='' method='post'>";
echo "<input type='hidden' name='grupo_salida' value='$grupo_salida'>";
echo "<input type='hidden' name='codigo_ciclo' value='$codigo_ciclo'>";
echo "<input type='hidden' name='codigo_linea' value='$codigo_linea'>";
echo "<input type='hidden' name='cod_visitador' value='$cod_visitador'>";
echo "<input type='hidden' name='cod_territorio' value='$cod_territorio'>";
echo "<table border='1' class='texto' cellspacing='0' align='center' width='70%'>";
echo "<tr><th>Fecha</th><th>Tipo de Salida</th><th>Observaciones</th></tr>";
$fecha_sistema=date("d/m/Y");
echo "<tr><td>$fecha_sistema</td>";
echo "<input type='hidden' name='fecha' value='$fecha_sistema'>";
echo "<td align='center'>TRASPASO FUNCIONARIO</td>";
echo "<input name='tipo_salida' type='hidden' value='1000'>";
echo "<td align='center'><input type='text' class='texto' name='observaciones' value='$observaciones' size='60'></td></tr>";
echo "</table><br>";
echo "<table border=1 class='texto' width='80%' align='center'>";
$sql_detalle_salida="select * from salida_detalle_almacenes where cod_salida_almacen='$codigo_registro'";
$resp_detalle_salida=mysql_query($sql_detalle_salida);
$cantidad_materiales=mysql_num_rows($resp_detalle_salida);
echo "<input type='hidden' name='cantidad_material' value='$cantidad_materiales'>";
echo "<tr><th>&nbsp;</th><th>Material</th><th>Cantidad Planificada</th><th>Cantidad Sacada</th><th>Cantidad a sacar</th><th>Stock</th><th>Cantidad requerida Nacional</th></tr>";
$indice_detalle=1;
echo "<input type='hidden' name='nro_mat' value='$indice_m_m'>";
$sql_numero_codigo_salida="select codigo_salida_visitador from salidas_visitador_ciclo
							where codigo_funcionario='$cod_visitador' and codigo_linea='$codigo_linea' 
							and cod_gestion='$gestion_rpt' and cod_ciclo='$codigo_ciclo'";
$resp_numero_codigo_salida=mysql_query($sql_numero_codigo_salida);
$dat_codigo=mysql_fetch_array($resp_numero_codigo_salida);
$codigo_salida_visitador=$dat_codigo[0];
echo "<input type='hidden' name='codigo_salida_visitador' value='$codigo_salida_visitador'>";
for($i=0;$i<=$indice_m_m;$i++)
{	$cod_material=$matriz_m_m[$i][1];
	//$cantidad_nacional=cantidad_nacional($grupo_salida,$cod_material,$ciclo_rpt);
	$sql_nombre_material="select descripcion from muestras_medicas where codigo='$cod_material'";
	$resp_nombre_material=mysql_query($sql_nombre_material);
	$filas_material=mysql_num_rows($resp_nombre_material);
	$sql_cantidad_sacada="select cantidad_sacada from salidas_visitador_ciclodetalle
						where codigo_salida_visitador='$codigo_salida_visitador' and cod_material='$cod_material'";
	$resp_cantidad_sacada=mysql_query($sql_cantidad_sacada);
	$dat_cantidad_sacada=mysql_fetch_array($resp_cantidad_sacada);
	$cantidad_sacada=$dat_cantidad_sacada[0];
	$sql_stock="select SUM(cantidad_restante) from ingreso_detalle_almacenes where cod_material='$cod_material'";
	$resp_stock=mysql_query($sql_stock);
	$dat_stock=mysql_fetch_array($resp_stock);
	$stock_real=$dat_stock[0];
	if($filas_material==0)
	{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$sql_stock="select SUM(cantidad_restante) from ingreso_detalle_almacenes where cod_material='$cod_material'";
		$resp_stock=mysql_query($sql_stock);
		$dat_stock=mysql_fetch_array($resp_stock);
		$stock_real=$dat_stock[0];
	}
	$dat_material=mysql_fetch_array($resp_nombre_material);
	$nombre_material=$dat_material[0];
	$cantidad_material=$matriz_m_m[$i][2];
	if($cantidad_material!=0)
	{	//echo "<tr><td align='left'>$nombre_material</td><td align='rigth'>$cantidad_material</td></tr>";
		echo "<tr><td align='center'>$indice_detalle</td>";
		echo "<td>$nombre_material</td>";
		echo "<input type='hidden' value='$cod_material' name='materiales$indice_detalle'>";
		echo "<input type='hidden' value='$cantidad_material' name='cantidad_planificada$indice_detalle'>";
		echo "<td align='center'>$cantidad_material</td>";
		echo "<td align='center'>$cantidad_sacada</td>";
		$valor_a_sacar=$cantidad_material-$cantidad_sacada;
		if($valor_a_sacar==0)
		{	echo "<td align='center'>-<input type='hidden' name='cantidad_unitaria$indice_detalle' value='$valor_a_sacar' class='texto'></td>";	
		}
		else
		{	echo "<td align='center'><input type='text' name='cantidad_unitaria$indice_detalle' value='$valor_a_sacar' class='texto' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
		}
		if($stock_real=="")
		{	$stock_real=0;
		}
		echo "<td align='center'>$stock_real</td>";
		echo "<input type='hidden' name='stock$indice_detalle' value='$stock_real'>";
		echo "<td align='center'>&nbsp;$cantidad_nacional</td>";
		$indice_detalle++;
	}
	echo "</tr>";
}
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_salidaciclosenterosterritorios.php?codigo_ciclo=$codigo_ciclo'><img  border='0' src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "</div></body>";
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>