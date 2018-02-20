<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	echo "<script language='JavaScript'>
		function eliminarProductos(f)
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
			{	alert('Debe seleccionar al menos un Producto.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='guardaEliminarProductoParrilla.php?datos='+datos+'&ciclo_trabajo=$ciclo_trabajo&codigo_linea=$global_linea';
				}
				else
				{
					return(false);
				}
			}
		}
	</script>";
	require("conexion.inc");
	require("estilos.inc");
	$codigo_gestion=$global_gestion;
	echo "<form name='form1' action=''>";
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td align='center'>Eliminar Productos de Parrilla</center></td></tr></table><br>";
	$sql="select distinct(pd.codigo_muestra), concat(m.descripcion,' ',m.presentacion) as muestra 
			from parrilla p, parrilla_detalle pd, muestras_medicas m 
			where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_gestion='$global_gestion' and pd.codigo_muestra=m.codigo 
			and p.cod_ciclo='$ciclo_trabajo' and p.codigo_linea='$global_linea' order by descripcion";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	echo "<table border='1' class='texto' cellspacing='0' width='80%' align='center'>";
	echo "<tr><th>Producto</th><th>Territorios</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigoMuestra=$dat[0];
		$descripcionMuestra=$dat[1];
		$sqlAgencia="select distinct(p.agencia), p.numero_visita, 
					(select c.descripcion from ciudades c where c.cod_ciudad=p.agencia) as nombreAgencia
					from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_gestion='$global_gestion' 
					and p.cod_ciclo='$ciclo_trabajo' and p.codigo_linea='$global_linea' and pd.codigo_muestra='$codigoMuestra' 
					order by p.agencia, p.numero_visita";
		$respAgencia=mysql_query($sqlAgencia);
		echo "<tr><td>$descripcionMuestra</td><td><table border=0 class='texto'>";	
		while($datAgencia=mysql_fetch_array($respAgencia)){
			$codAgencia=$datAgencia[0];
			$numVisita=$datAgencia[1];
			$nombreAgencia=$datAgencia[2];	
			if($codAgencia==0){$nombreAgencia="Nacional";}
			echo "<tr><td><strong>$nombreAgencia - </strong></td><td>Semana $numVisita<input type='checkbox' name='$codigoMuestra|$codAgencia|$numVisita|$global_gestion' value='$codigoMuestra|$codAgencia|$numVisita|$global_gestion'></td></tr>";
		}				
		
		echo "</table></td></tr>";
	}
	echo "</table>";
	echo "<br>";
	require('home_central1.inc');
	echo "<br>";
	echo "<tr><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminarProductos(this.form)'></td></tr></center>";
	echo "</form>";
?>