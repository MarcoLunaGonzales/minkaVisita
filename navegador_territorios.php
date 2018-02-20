<?php
/**
 * @autor: Marco Antonio Luna Gonzales
 * SISTEMA HERMES
 * * @copyright 2008 
*/ 
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_territorio.php';
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
			{	alert('Debe seleccionar al menos un territorio para proceder a su eliminación.');
			}
			else
			{	
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_territorios.php?datos='+datos+'';
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
			var j_territorio;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_territorio=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Territorio para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Territorio para editar sus datos.');
				}
				else
				{
					location.href='editar_territorio.php?cod_territorio='+j_territorio+'';
				}
			}
		}
		</script>";	
	require("conexion.inc");
	require("estilos_administracion.inc");
	echo "<form method='post' action=''>";
	$sql="select * from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Registro de Territorios</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='40%'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Territorio</th><th>&nbsp;</th></tr>";
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$cod_ciudad=$dat[0];
		$desc_ciudad=$dat[1];
		echo "<tr><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigo' value='$cod_ciudad'></td><td>&nbsp;$desc_ciudad</td><td align='center'><a href='navegador_distritos.php?cod_territorio=$cod_ciudad'>Ver Distritos >></a></td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>
