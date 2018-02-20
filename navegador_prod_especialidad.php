<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_linea_visita.php';
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_linea;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_linea=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente una Línea de Visita para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar una Línea de Visita para editar sus datos.');
				}
				else
				{
					location.href='navegador_l_visita_detalle.php?cod_linea_vis='+j_linea+'';
				}
			}
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
			{	alert('Debe seleccionar al menos un Producto para eliminarlo de la Línea de Visita.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_prod_linea.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		</script>
	";
	require("conexion.inc");
	require("estilos.inc");
	echo "<form>";
	$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Productos por Especialidades</td></tr></table></center><br>";
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' cellspacing='0' width='50%'>";
	echo "<tr><td>&nbsp;</td><th>Especialidad</th><th>Productos Asignados</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_espe=$dat[0];
		$nom_espe=$dat[1];
		$sql_productos_asignados="select * from producto_especialidad where cod_especialidad='$cod_espe' and codigo_linea='$global_linea'";
		$resp_productos_asignados=mysql_query($sql_productos_asignados);
		$nro_productos_asignados=mysql_num_rows($resp_productos_asignados);
		echo "<tr><td align='center'>$indice_tabla</td><td>&nbsp;$nom_espe</td><td align='center'>$nro_productos_asignados</td><td align='center'><a href='ver_prod_especialidad.php?cod_espe=$cod_espe'>Ver Productos >></a></td></tr>";
			$indice_tabla++;
		}
	echo "</table></center><br>";
	require("home_central1.inc");
	echo "</form>";
?>