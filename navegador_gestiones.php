<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_gestiones.php';
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
			{	alert('Debe seleccionar al menos una Gesti�n para proceder a su eliminaci�n.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_gestiones.php?datos='+datos+'';
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
			var j_gestion;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_gestion=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente una Gesti�n para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar una Gesti�n para editar sus datos.');
				}
				else
				{
					location.href='editar_gestiones.php?cod_gestion='+j_gestion+'';
				}
			}
		}
		function activar(f)
		{
			var i;
			var j=0;
			var j_gestion;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_gestion=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente una Gesti�n para activarla.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar una Gesti�n para activarla.');
				}
				else
				{
					location.href='activar_gestiones.php?cod_gestion='+j_gestion+'';
				}
			}
		}
		</script>
	";
	require("conexion.inc");
	require("estilos_administracion.inc");
	echo "<form method='post' action=''>";
	$sql="select distinct(codigo_gestion), nombre_gestion, estado  from gestiones order by nombre_gestion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Gestiones</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='30%'>";
	echo "<tr><th>&nbsp;</th><th>Gesti�n</th><th>Estado</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$gestion=$dat[1];
		$estado=$dat[2];
		echo "<tr><td align='center'><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$gestion</td><td align='center'>$estado</td></tr>";
	}
	echo "</table></center><br>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' class='boton' onclick='editar_nav(this.form)'></td><td><input type='button' value='Activar Gesti�n' class='boton' onclick='activar(this.form)'></td></tr></table></center>";
	echo "</form>";
?>