<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_ingresomuestrasregional.php';
		}
		function anular_ingreso(f)
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
			{	alert('Debe seleccionar solamente un registro para anularlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para anularlo.');
				}
				else
				{
					location.href='anular_ingresoregional.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_almacenes.inc");
	echo "<form method='post' action=''>";
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.grupo_ingreso=1 order by nro_correlativo";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Ingreso de Muestras</th></tr></table></center><br>";
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Ingresos Anulados</td><td bgcolor='#ff8080' width='20%'></td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='90%'>";
	echo "<tr><th>&nbsp;</th><th>Número Ingreso</th><th>Nota de Entrega</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_ingreso=$dat[1];
		$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
		$nombre_tipoingreso=$dat[2];
		$obs_ingreso=$dat[3];
		$nota_entrega=$dat[4];
		$nro_correlativo=$dat[5];
		$anulado=$dat[6];
		if($anulado==1)
		{	$color_anulado="#ff8080";
		}
		else
		{	$color_anulado="";
		}
//		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
		echo "<tr bgcolor='$color_anulado'><td align='center'><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td align='center'><a target='_BLANK' href='navegador_detalleingresomuestras.php?codigo_ingreso=$codigo'><img src='imagenes/detalles.gif' border='0' alt='Ver Detalles del Ingreso'> Detalles</a></td></tr>";
	}
	echo "</table></center><br>";
	require('home_almacen.php');
	echo "<center><table border='0' class='texto'>";
//	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "<tr><td><input type='button' value='Registrar Ingreso' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Anular Ingreso' name='adicionar' class='boton' onclick='anular_ingreso(this.form)'></td></tr></table></center>";
	echo "</form>";
?>