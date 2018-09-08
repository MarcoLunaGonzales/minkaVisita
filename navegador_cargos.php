<?php

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_cargos.php';
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
			{	alert('Debe seleccionar al menos un Cargo para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_cargos.php?datos='+datos+'';
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
			var j_cargo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cargo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Cargo para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Cargo para editar sus datos.');
				}
				else
				{
					location.href='editar_cargos.php?cod_cargo='+j_cargo+'';
				}
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_administracion.inc");
	echo "<form method='post' action=''>";
	$sql="select cod_cargo, cargo from cargos order by cargo";
	$resp=mysql_query($sql);
	echo "<h1>Registro de Cargos</h1>";
	
	echo "<center><table class='texto' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Cargos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$cargo=$dat[1];
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td>$cargo</td></tr>";
	}
	echo "</table></center><br>";
	
	echo "<div class='divBotones'>";
	echo "<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
	<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";

	echo "</form>";
?>