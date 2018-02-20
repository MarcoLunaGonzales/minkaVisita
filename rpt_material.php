<?php
	require("conexion.inc");
	//require('estilos_administracion.inc');
	echo "<center><table border='0' class='textotit'><tr><th>Registro de Material de Apoyo</th></tr></table></center><br>";
	echo "<form method='post' action=''>";
	$sql="select m.codigo_material, m.descripcion_material, m.estado, t.nombre_tipomaterial, m.codigo_linea from material_apoyo m, tipos_material t where m.codigo_material<>0 and m.cod_tipo_material=t.cod_tipomaterial and m.estado='Activo' order by m.descripcion_material";
	/*if($vista==1)
	{	$sql="select m.codigo_material, m.descripcion_material, m.estado, t.nombre_tipomaterial, m.codigo_linea from material_apoyo m, tipos_material t where m.codigo_material<>0 and m.cod_tipo_material=t.cod_tipomaterial and m.estado='Retirado' order by m.descripcion_material";
	}
	if($vista==2)
	{
	 	$sql="select m.codigo_material, m.descripcion_material, m.estado, t.nombre_tipomaterial, m.codigo_linea from material_apoyo m, tipos_material t where m.codigo_material<>0 and m.cod_tipo_material=t.cod_tipomaterial order by m.descripcion_material";
	}

	echo "<table align='center' class='texto'><tr><th>Ver Material de Apoyo:</th><td><select name='vista' class='texto' onChange='cambiar_vista(this, this.form)'>";
	if($vista==0)	echo "<option value='0' selected>Activos</option><option value='1'>Retirados</option><option value='2'>Todo</option>";
	if($vista==1)	echo "<option value='0'>Activos</option><option value='1' selected>Retirados</option><option value='2'>Todo</option>";
	if($vista==2)	echo "<option value='0'>Activos</option><option value='1'>Retirados</option><option value='2' selected>Todo</option>";
	echo "</select>";
	echo "</td></tr></table><br>";*/
	//echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Productos Retirados</th><td bgcolor='#ff6666' width='30%'></td></tr></table></center><br>";
	//require('home_administracion.php');
	//echo "<center><table border='0' class='texto'>";
	//echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	$resp=mysql_query($sql);
	echo "<br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='95%'>";
	echo "<tr><th>Código</th><th>Material de Apoyo</th><th>Tipo de Material de Apoyo</th><th>Línea</th></tr>";
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$desc=$dat[1];
		$estado=$dat[2];
		$tipo_material=$dat[3];
		$linea=$dat[4];
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
		echo "<tr bgcolor='$fondo_fila'><td>$codigo</td><td>$desc</td><td>$tipo_material</td><td>&nbsp;$nombre_linea</td></tr>";
		//echo "<tr bgcolor='$fondo_fila'><td align='center'>$indice_tabla</td><td>$desc</td><td>$tipo_material</td><td>&nbsp;$nombre_linea</td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	require('home_administracion.php');
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";
	echo "</form>";
?>
