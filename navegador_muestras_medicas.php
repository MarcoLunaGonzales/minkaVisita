<?php

	require("conexion.inc");
	require("estilos_administracion.inc");

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_muestra_medica.php';
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
			{	alert('Debe seleccionar al menos un producto proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_muestras_medicas.php?datos='+datos+'';
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
			var j_muestra;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_muestra=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un producto para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un producto para editar sus datos.');
				}
				else
				{
					location.href='editar_muestra_medica.php?cod_muestra='+j_muestra+'';
				}
			}
		}
		function cambiar_vista(sel_vista, f)
		{
			var modo_vista;
			modo_vista=sel_vista.value;
			location.href='navegador_muestras_medicas.php?vista='+modo_vista+'';
		}
		</script>
	";

	
	echo "<form method='post' action=''>";
	
	$sql="select m.codigo, m.descripcion, m.presentacion, m.estado, tm.nombre_tipo_muestra, m.codigo_linea
from muestras_medicas m, tipos_muestra tm
where m.cod_tipo_muestra=tm.cod_tipo_muestra and m.estado=1 order by m.descripcion";


	echo "<h1>Registro de Muestras</h1>";


	if($vista==1)
	{
		$sql="select m.codigo, m.descripcion, m.presentacion, m.estado, tm.nombre_tipo_muestra, m.codigo_linea
from muestras_medicas m, tipos_muestra tm
where m.cod_tipo_muestra=tm.cod_tipo_muestra and m.estado=0 order by m.descripcion";
	}
	if($vista==2)
	{
	 	$sql="select m.codigo, m.descripcion, m.presentacion, m.estado, tm.nombre_tipo_muestra, m.codigo_linea
from muestras_medicas m, tipos_muestra tm
where m.cod_tipo_muestra=tm.cod_tipo_muestra order by m.descripcion";
	}
	
	//echo $sql;
	
	$resp=mysql_query($sql);
	echo "<table align='center' class='texto'><tr><th>Ver productos: </th>
		<th><select name='vista' class='texto' onChange='cambiar_vista(this, this.form)'>";
	if($vista==0)	echo "<option value='0' selected>Activos</option><option value='1'>Retirados</option><option value='2'>Todo</option>";
	if($vista==1)	echo "<option value='0'>Activos</option><option value='1' selected>Retirados</option><option value='2'>Todo</option>";
	if($vista==2)	echo "<option value='0'>Activos</option><option value='1'>Retirados</option><option value='2' selected>Todo</option>";
	echo "</select>";
	echo "</th></tr></table><br>";

	echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Productos Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";

	echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		</div>";
		
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Producto</th><th>Tipo</th><th>Linea</th></tr>";
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$muestra=$dat[1];
		$presentacion=$dat[2];
		$estado=$dat[3];
		$tipo_muestra=$dat[4];
		$codigo_linea=$dat[5];
		$sql_linea="select nombre_linea from lineas where codigo_linea='$codigo_linea'";
		$resp_linea=mysql_query($sql_linea);
		$dat_linea=mysql_fetch_array($resp_linea);
		$nombre_linea=$dat_linea[0];
		if($estado==0)
		{
			$fondo_fila="#ff6666";
		}
		else
		{
		 	$fondo_fila="";
		}
		echo "<tr bgcolor=$fondo_fila><td align='center'>$indice_tabla</td>
		<td><input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$muestra</td>
		<td>$tipo_muestra</td><td>$nombre_linea</td></tr>";
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
