<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.material.value=='')
		{	alert('El campo Material de Apoyo esta vacio.');
			f.material.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require('estilos_administracion.inc');
$sql=mysql_query("select descripcion_material, estado, cod_tipo_material, codigo_linea, fecha_expiracion from material_apoyo where codigo_material=$cod_material");
$dat=mysql_fetch_array($sql);
$material=$dat[0];
$estado=$dat[1];
$cod_tipo_material=$dat[2];
$linea_modi=$dat[3];
$fecha_expiracion=$dat[4];
$exafinicial="$fecha_expiracion[8]$fecha_expiracion[9]/$fecha_expiracion[5]$fecha_expiracion[6]/$fecha_expiracion[0]$fecha_expiracion[1]$fecha_expiracion[2]$fecha_expiracion[3]";

echo "<form action='guarda_modi_material_apoyo.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Editar Material de Apoyo</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th align='left'>Descripción Material de Apoyo</th>";
echo "<input type='hidden' name='codigo' value='$cod_material'>";
echo "<td align='left'><input type='text' class='texto' name='material' value='$material' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "<tr><th align='left'>Tipo de Material</th>";
$sql1="select * from tipos_material order by nombre_tipomaterial";
$resp1=mysql_query($sql1);
echo "<td align='left'><select name='tipo_material' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tipomaterial=$dat1[0];
	$nombre_tipomaterial=$dat1[1];
	if($cod_tipomaterial==$cod_tipo_material)
	{	echo "<option value='$cod_tipomaterial' selected>$nombre_tipomaterial</option>";
	}
	else
	{	echo "<option value='$cod_tipomaterial'>$nombre_tipomaterial</option>";
	}
}
echo "</select></td></tr>";
echo "<tr><th align='left'>Estado</th>";
echo "<td align='left'><select name='estado' class='texto'>";
	if($estado=='Activo')
	{
	 	echo "<option value='Activo' selected>Activo</option><option value='Retirado'>Retirado</option></select>";
	}
	if($estado=='Retirado')
	{
	  echo "<option value='Activo'>Activo</option><option value='Retirado' selected>Retirado</option></select>";
	}
echo "</td></tr>";
echo "<tr><th align='left'>Línea</th><td><select name='linea' class='texto'>";
$sql="select codigo_linea, nombre_linea from lineas order by nombre_linea";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp))
{	$codigo_linea=$dat[0];
	$nombre_linea=$dat[1];
	if($linea_modi==$codigo_linea)
	{	echo "<option value='$codigo_linea' selected>$nombre_linea</option>";
	}
	else
	{	echo "<option value='$codigo_linea'>$nombre_linea</option>";
	}
}
echo "</select></td></tr>";
echo "<tr><th align='left'>Fecha de Expiración</th><td><INPUT  type='text' class='texto' id='exafinicial' size='10' value='$exafinicial' name='exafinicial'>";
echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
echo" input_element_id='exafinicial' ";
echo" click_element_id='imagenFecha'></DLCALENDAR></td></tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_material.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>