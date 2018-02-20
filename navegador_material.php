<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_material_apoyo.php';
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
			{	alert('Debe seleccionar al menos un material de apoyo para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_material_apoyo.php?datos='+datos+'';
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
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un material de apoyo para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un material de apoyo para editar sus datos.');
				}
				else
				{
					location.href='editar_material_apoyo.php?cod_material='+j_ciclo+'';
				}
			}
		}
		function cambiar_vista(sel_vista, f)
		{
			var modo_vista;
			modo_vista=sel_vista.value;
			location.href='navegador_material.php?vista='+modo_vista+'';
		}
		</script>";
	require("conexion.inc");
	require('estilos_administracion.inc');
	echo "<center><table border='0' class='textotit'><tr><th>Registro de Material de Apoyo</th></tr></table></center><br>";
	echo "<form method='post' action=''>";
	$sql="select m.codigo_material, m.descripcion_material, m.estado, t.nombre_tipomaterial, m.codigo_linea, m.fecha_expiracion from material_apoyo m, tipos_material t where m.codigo_material<>0 and m.cod_tipo_material=t.cod_tipomaterial and m.estado='Activo' order by m.descripcion_material";
	if($vista==1)
	{	$sql="select m.codigo_material, m.descripcion_material, m.estado, t.nombre_tipomaterial, m.codigo_linea, m.fecha_expiracion from material_apoyo m, tipos_material t where m.codigo_material<>0 and m.cod_tipo_material=t.cod_tipomaterial and m.estado='Retirado' order by m.descripcion_material";
	}
	if($vista==2)
	{
	 	$sql="select m.codigo_material, m.descripcion_material, m.estado, t.nombre_tipomaterial, m.codigo_linea, m.fecha_expiracion from material_apoyo m, tipos_material t where m.codigo_material<>0 and m.cod_tipo_material=t.cod_tipomaterial order by m.descripcion_material";
	}
	$resp=mysql_query($sql);
	echo "<table align='center' class='texto'><tr><th>Ver Material de Apoyo:</th><td><select name='vista' class='texto' onChange='cambiar_vista(this, this.form)'>";
	if($vista==0)	echo "<option value='0' selected>Activos</option><option value='1'>Retirados</option><option value='2'>Todo</option>";
	if($vista==1)	echo "<option value='0'>Activos</option><option value='1' selected>Retirados</option><option value='2'>Todo</option>";
	if($vista==2)	echo "<option value='0'>Activos</option><option value='1'>Retirados</option><option value='2' selected>Todo</option>";
	echo "</select>";
	echo "</td></tr></table><br>";
	echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Productos Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";
	require('home_administracion.php');
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "<br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='95%'>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Material de Apoyo</th><th>Tipo de Material de Apoyo</th><th>Línea</th><th>Fecha de Expiración</th></tr>";
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$desc=$dat[1];
		$estado=$dat[2];
		$tipo_material=$dat[3];
		$linea=$dat[4];
		$fecha_expiracion=$dat[5];
		$sql_linea="select nombre_linea from lineas where codigo_linea='$linea'";
		$resp_linea=mysql_query($sql_linea);
		$dat_linea=mysql_fetch_array($resp_linea);
		$nombre_linea=$dat_linea[0];
		if($estado=='Retirado')
		{
			$fondo_fila="#ff6666";
		}
		else
		{
		 	$fondo_fila="";
		}
		echo "<tr bgcolor='$fondo_fila'><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigo' value='$codigo'></td><td>$desc</td><td>$tipo_material</td><td>&nbsp;$nombre_linea</td><td align='center'>&nbsp;$fecha_expiracion</td></tr>";
		//echo "<tr bgcolor='$fondo_fila'><td align='center'>$indice_tabla</td><td>$desc</td><td>$tipo_material</td><td>&nbsp;$nombre_linea</td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	require('home_administracion.php');
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>
