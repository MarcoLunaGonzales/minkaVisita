<?php

	require("conexion.inc");
	require("estilos_administracion.inc");

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_lineas.php';
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
			{	alert('Debe seleccionar al menos una linea para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_lineas.php?datos='+datos+'';
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
			{	alert('Debe seleccionar solamente una linea para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar una linea para editar sus datos.');
				}
				else
				{
					location.href='editar_lineas.php?cod_linea='+j_linea+'';
				}
			}
		}
		</script>
	";
	echo "<form method='post' action=''>";
	$sql="select * from lineas where estado=1 and linea_promocion=1 order by nombre_linea";
	$resp=mysql_query($sql);

	echo "<h1>Registro de Lineas</h1>";

	echo "<center><table class='texto'>";
	//echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Linea</th><th>Funcionarios</th></tr>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Linea</th></tr>";
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<tr>
		<td align='center'>$indice_tabla</td>
		<td align='center'><input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$nombre</td>
		<!--td align='center'><a href='navLineasFuncionarios.php?codigoLinea=$codigo'><img src='imagenes/personal.png' width='40' title='Funcionarios en la Linea'></a></td-->
		</tr>";
	$indice_tabla++;
	}
	echo "</table></center><br>";

	echo "<div class='divBotones'>
	<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";

	echo "</form>";
?>
