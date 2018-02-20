<?php
	require("conexion.inc");
	require('function_formatofecha.php');

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_ingresomateriales.php';
		}
		function editar_ingreso(f)
		{	var i;
			var j=0;
			var j_cod_registro;
			var fecha_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						fecha_registro=f.elements[i-1].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para anularlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para anularlo.');
				}
				else
				{	if(f.fecha_sistema.value==fecha_registro)
					{	location.href='editar_ingresomateriales.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1&valor_inicial=1';
					}
					else
					{	alert('Usted no esta autorizado(a) para anular el ingreso.');
					}
				}
			}
		}
		function anular_ingreso(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			var fecha_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						fecha_registro=f.elements[i-1].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro para anularlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para anularlo.');
				}
				else
				{	if(f.fecha_sistema.value==fecha_registro)
					{	window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=2','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
					}
					else
					{	alert('Usted no esta autorizado(a) para anular el ingreso.');
						return(false);
					}
				}
			}
		}
		</script>";
	if($global_tipoalmacen==1)
	{	require("estilos_almacenes_central.inc");
	}
	else
	{	require("estilos_almacenes.inc");
	}
	echo "<form method='post' action='navegador_ingresomateriales.php'>";
	echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";
	if($campo_busqueda=='nro_ingreso')
	{	$sql="select i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado
		FROM ingreso_almacenes i, tipos_ingreso ti
		where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.nro_correlativo='$parametro' and i.grupo_ingreso=2 order by nro_correlativo desc";
	}
	if($campo_busqueda=='nota_remision')
	{	$sql="select i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado
		FROM ingreso_almacenes i, tipos_ingreso ti
		where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.nota_entrega='$parametro' and i.grupo_ingreso=2 order by nro_correlativo desc";
	}
	if($campo_busqueda=='fecha')
	{	$fecha_sql=cambia_formatofecha($parametro);
		$sql="select i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado
		FROM ingreso_almacenes i, tipos_ingreso ti
		where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.fecha='$fecha_sql' and i.grupo_ingreso=2 order by nro_correlativo desc";
	}
	if($campo_busqueda=="")
	{	$sql="select i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, 
		i.nro_correlativo, i.ingreso_anulado
		FROM ingreso_almacenes i, tipos_ingreso ti
		where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.grupo_ingreso=2 
		order by i.nro_correlativo desc limit 0,50";
	}
    //echo $sql;
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Ingreso de Material de Apoyo</th></tr></table></center><br>";
	echo "<table border='1' cellspacing='0' class='textomini'><tr><th>Leyenda:</th><th>Ingresos Anulados</th><td bgcolor='#ff8080' width='10%'></td><th>Ingresos con movimiento</th><td bgcolor='#ffff99' width='10%'></td><th>Ingresos sin movimiento</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";
	require('home_almacen.php');
	
	
	if($global_usuario!=1062 and $global_usuario!=1120 and $global_usuario!=1129)
	{	echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Editar Ingreso' class='boton' onclick='editar_ingreso(this.form)'></td><td><input type='button' value='Anular Ingreso' name='adicionar' class='boton' onclick='anular_ingreso(this.form)'></td></tr></table></center>";
	}
	
	echo "<br><center><table border='1' class='texto' cellspacing='0' width='90%'>";
	echo "<tr><th>&nbsp;</th><th>N�mero Ingreso</th><th>Nota de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th>
	<th>Observaciones</th><th>Detalle</th><th>Editar<br>Etiquetas</th><th>Etiquetas</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_ingreso=$dat[1];
		$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
		$hora_ingreso=$dat[2];
		$nombre_tipoingreso=$dat[3];
		$obs_ingreso=$dat[4];
		$nota_entrega=$dat[5];
		$nro_correlativo=$dat[6];
		$anulado=$dat[7];
		echo "\n<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
		$sql_verifica_movimiento="select s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi
		where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
		$resp_verifica_movimiento=mysql_query($sql_verifica_movimiento);
		$num_filas_movimiento=mysql_num_rows($resp_verifica_movimiento);
		if($num_filas_movimiento!=0)
		{	$color_fondo="#ffff99";
			$chkbox="";
		}
		if($anulado==1)
		{	$color_fondo="#ff8080";
			$chkbox="";
		}
		if($num_filas_movimiento==0 and $anulado==0)
		{	$color_fondo="";
			$chkbox="<input type='checkbox' name='codigo' value='$codigo'>";
		}
//		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
		echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td>
		<td align='center'>&nbsp;$nota_entrega</td><td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td>
		<td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td align='center'>
		<a target='_BLANK' href='navegador_detalleingresomateriales.php?codigo_ingreso=$codigo'>
		<img src='imagenes/detalle.png' border='0' alt='Detalles' width='25px' height='25px'></a>		
		</td>
		
		<td align='center'><a target='_BLANK' href='modificarEtiquetas.php?codigo_ingreso=$codigo'>
		<img src='imagenes/editareti.png' border='0' alt='Modificar Etiquetas' width='25px' height='25px'></a></td>
		
		<td align='center'><a target='_BLANK' href='etiquetaIngreso.php?codigo_ingreso=$codigo'>
		<img src='imagenes/postit.jpg' border='0' alt='Etiquetas' width='25px' height='25px'></a></td></tr>";
	}
	echo "</table></center><br>";
	require('home_almacen.php');
	
	if($global_usuario!=1062 and $global_usuario!=1120 and $global_usuario!=1129)
	{	echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Editar Ingreso' class='boton' onclick='editar_ingreso(this.form)'></td><td><input type='button' value='Anular Ingreso' name='adicionar' class='boton' onclick='anular_ingreso(this.form)'></td></tr></table></center>";
	}
	
	echo "</form>";
?>