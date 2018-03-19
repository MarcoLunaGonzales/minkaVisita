<?php
	require("conexion.inc");
	require("estilos_administracion.inc");


	
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
	echo "<h1>Registro de Zonas<br>Territorio $nombre_ciudad<br>Distrito $nombre_distrito</h1>";

	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Zonas</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_zona=$dat[2];
		$desc_zona=$dat[3];
		echo "<tr><td align='center'><input type='checkbox' name='codigo' value='$cod_zona'></td><td align='center'>$desc_zona</td></tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>
			<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
			<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
			<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
			<input type='button' value='Cancelar-' name='cancelar' class='boton2' onclick='location.href=\"navegador_distritos.php?cod_territorio=$cod_territorio\"'>
		</div>";
		
	echo "</form>";
?>