<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_ingresoalmacenes.php';
		}
		function eliminar_nav(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un Material para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_materiales.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}

		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_cod_registro;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cod_registro=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Material para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Material para editar sus datos.');
				}
				else
				{
					location.href='editar_materiales.php?codigo_registro='+j_cod_registro+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_almacenes.inc");
	echo "<form method='post' action=''>";
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones 
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen'";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Ingreso de Material</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='90%'>";
	echo "<tr><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th><th><table border='1' class='texto' width='100%'><tr><th colspan='3'>Detalle del Ingreso</th></tr><tr><th width='50%'>Material</th><th width='30%'>Fecha Vencimiento</th><th width='20%'>Cantidad</th></tr></table></th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_ingreso=$dat[1];
		$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
		$nombre_tipoingreso=$dat[2];
		$obs_ingreso=$dat[3];
		$sql_detalle="select i.cod_material, i.fecha_vencimiento, i.cantidad_unitaria, i.tipo_material from ingreso_detalle_almacenes i 
		where i.cod_ingreso_almacen='$codigo'";
		$resp_detalle=mysql_query($sql_detalle);
		$txt_detalle="<table border=1 class='textomini' width='100%'>";
		while($dat_detalle=mysql_fetch_array($resp_detalle))
		{	$cod_material=$dat_detalle[0];
			$fecha_vencimiento=$dat_detalle[1];
			$fecha_vencimiento_mostrar="$fecha_vencimiento[8]$fecha_vencimiento[9]-$fecha_vencimiento[5]$fecha_vencimiento[6]-$fecha_vencimiento[0]$fecha_vencimiento[1]$fecha_vencimiento[2]$fecha_vencimiento[3]";
			$cantidad_unitaria=$dat_detalle[2];
			$tipo_material=$dat_detalle[3];
			if($tipo_material==1)
			{	$sql_nombre_material="select descripcion from muestras_medicas where codigo='$cod_material'";
			}
			else
			{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
			}
			$resp_nombre_material=mysql_query($sql_nombre_material);
			$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
			$nombre_material=$dat_nombre_material[0];
			$txt_detalle="$txt_detalle<tr><td width='50%'>$nombre_material</td><td width='30%' align='center'>$fecha_vencimiento_mostrar</td><td width='20%'>$cantidad_unitaria</td></tr>";
		}
		$txt_detalle="$txt_detalle</table>";
//		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
		echo "<tr><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
	}
	echo "</table></center><br>";
	require('home_almacen.php');
	echo "<center><table border='0' class='texto'>";
//	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td></tr></table></center>";
	echo "</form>";
?>