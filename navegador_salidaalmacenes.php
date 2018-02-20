<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_salidaalmacenes.php';
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
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones 
	FROM salida_almacenes s, tipos_salida ts, ciudades c, almacenes a 
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and c.cod_ciudad=s.territorio_destino and a.cod_almacen=s.cod_almacen";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Salidas de Material</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='100%'>";
	echo "<tr><th>Fecha</th><th>Tipo de Salida</th><th>Territorio<br>Destino</th><th>Almacen Destino</th><th>Funcionario Destino</th><th>Observaciones</th><th><table border='1' class='texto' width='100%'><tr><th colspan='3'>Detalle de la Salida</th></tr><tr><th width='80%'>Material</th><th width='20%'>Cantidad</th></tr></table></th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_salida=$dat[1];
		$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
		$nombre_tiposalida=$dat[2];
		$nombre_ciudad=$dat[3];
		$nombre_almacen=$dat[4];
		$obs_salida=$dat[5];
		$sql_funcionario="select f.paterno, f.materno, f.nombres from funcionarios f, salida_detalle_visitador sv
		where sv.cod_salida_almacen='$codigo' and f.codigo_funcionario=sv.codigo_funcionario";
		$resp_funcionario=mysql_query($sql_funcionario);
		$dat_funcionario=mysql_fetch_array($resp_funcionario);
		$nombre_funcionario="$dat_funcionario[0] $dat_funcionario[1] $dat_funcionario[2]";
		$sql_detalle="select s.cod_material, s.cantidad_unitaria, s.tipo_material from salida_detalle_almacenes s 
		where s.cod_salida_almacen='$codigo'";
		$resp_detalle=mysql_query($sql_detalle);
		$txt_detalle="<table border=1 class='textomini' width='100%'>";
		while($dat_detalle=mysql_fetch_array($resp_detalle))
		{	$cod_material=$dat_detalle[0];
			$cantidad_unitaria=$dat_detalle[1];
			$tipo_material=$dat_detalle[2];
			if($tipo_material==1)
			{	$sql_nombre_material="select descripcion from muestras_medicas where codigo='$cod_material'";
			}
			else
			{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
			}
			$resp_nombre_material=mysql_query($sql_nombre_material);
			$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
			$nombre_material=$dat_nombre_material[0];
			$txt_detalle="$txt_detalle<tr><td width='80%'>$nombre_material</td><td width='20%'>$cantidad_unitaria</td></tr>";
		}
		$txt_detalle="$txt_detalle</table>";
		//echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
		echo "<tr><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>&nbsp;$nombre_almacen</td><td>&nbsp;$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
	}
	echo "</table></center><br>";
	require('home_almacen.php');
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td></tr></table></center>";
	//echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>