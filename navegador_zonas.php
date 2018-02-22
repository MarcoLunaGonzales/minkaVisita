<?php
	require("conexion.inc");
	require("estilos_administracion.inc");

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006 
*/ 
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_zona.php?cod_territorio=$cod_territorio&cod_distrito=$cod_distrito';
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
			{	alert('Debe seleccionar al menos una zona para proceder a su eliminación.');
			}
			else
			{	
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_zonas.php?datos='+datos+'&cod_territorio=$cod_territorio&cod_distrito=$cod_distrito';
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
			var j_zona;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_zona=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente una zona para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar una zona para editar sus datos.');
				}
				else
				{
					location.href='editar_zona.php?cod_zona='+j_zona+'&cod_territorio=$cod_territorio&cod_distrito=$cod_distrito';
				}
			}
		}
		</script>";	
		$sql_cab="select descripcion from ciudades where cod_ciudad=$cod_territorio";
		$resp_cab=mysql_query($sql_cab);
		$dat_cab=mysql_fetch_array($resp_cab);
		$nombre_ciudad=$dat_cab[0];
		$sql_cab1="select descripcion from distritos where cod_dist=$cod_distrito";
		$resp_cab1=mysql_query($sql_cab1);
		$dat_cab1=mysql_fetch_array($resp_cab1);
		$nombre_distrito=$dat_cab1[0];

	echo "<form method='post' action=''>";
	$sql="select * from zonas where cod_ciudad=$cod_territorio and cod_dist=$cod_distrito order by zona";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Registro de Zonas<br>Territorio $nombre_ciudad<br>Distrito $nombre_distrito</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='40%'>";
	echo "<tr><th>&nbsp;</th><th>Zonas</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_zona=$dat[2];
		$desc_zona=$dat[3];
		echo "<tr><td align='center'><input type='checkbox' name='codigo' value='$cod_zona'></td><td align='center'>$desc_zona</td></tr>";
	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='navegador_distritos.php?cod_territorio=$cod_territorio'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>