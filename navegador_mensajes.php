<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_mensaje.php';
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
			{	alert('Debe seleccionar al menos un registro para proceder a su eliminación.');
			}
			else
			{	
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_mensajes.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		</script>";	
	require("conexion.inc");
	require("estilos_gerencia.inc");
	echo "<form method='post' action=''>";
	
	echo "<h1>Mensajes Hermes</h1>";
	
	echo "<table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Mensaje</th><th>Fecha</th></tr>";
	$sql="select cod_mensaje, mensaje, fecha_mensaje from mensajes order by fecha_mensaje desc";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$mensaje=$dat[1];
		$fecha=$dat[2];
		echo "<tr>
		<td><input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$mensaje</td>
		<td>$fecha</td></tr>";
	}
	echo "</table><br>";
	echo "<div id='divBotones'>
			<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
			<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		</div>";
	echo "</form>";
?>