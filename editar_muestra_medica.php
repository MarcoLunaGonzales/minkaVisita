<?php

require("conexion.inc");
require("estilos_administracion.inc");


echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.muestra.value=='')
		{	alert('El campo Nombre esta vacio.');
			f.muestra.focus();
			return(false);
		}
		f.submit();
	}
	</script>";

$sql="select * from muestras_medicas where codigo='$cod_muestra'";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$codigo_muestra=$dat[0];
$descripcion_muestra=$dat[1];
$presentacion_muestra=$dat[2];
$estado_muestra=$dat[3];
$tipo_muestra=$dat[4];
$linea=$dat[5];
echo "<form action='guarda_modi_muestra_medica.php' method='get'>";

echo "<h1>Editar Muestra</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Nombre</th>
<td><input type='text' class='texto' value='$descripcion_muestra' name='muestra' size='40'></td></tr>";

echo "<tr><th align='left'>Tipo</th>";
$sql1="select * from tipos_muestra order by nombre_tipo_muestra";
$resp1=mysql_query($sql1);
echo "<td><select name='tipo_muestra' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tipomuestra=$dat1[0];
	$nombre_tipomuestra=$dat1[1];
	if($tipo_muestra==$cod_tipomuestra)
	{		echo "<option value='$cod_tipomuestra'>$nombre_tipomuestra</option>";
	}
	else
	{		echo "<option value='$cod_tipomuestra'>$nombre_tipomuestra</option>";
	}
}
echo "</select></td>";
echo "</tr>";
echo "<tr><th align='left'>Linea</th><td><select name='linea' class='texto'>";
$sql="select codigo_linea, nombre_linea from lineas where linea_inventarios=1 and estado=1 order by nombre_linea";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp))
{	$codigo_linea=$dat[0];
	$nombre_linea=$dat[1];
	if($linea==$codigo_linea)
	{	echo "<option value='$codigo_linea' selected>$nombre_linea</option>";
	}
	else
	{	echo "<option value='$codigo_linea'>$nombre_linea</option>";
	}
}
echo "</select></td></tr>";

echo "<input type='hidden' name='cod_muestra' value='$cod_muestra'>";
echo "</table><br></center>";

echo "<div class='divBotones'>
	<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
	<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_muestras_medicas.php\"'>
	</div>";

echo "</form>";
?>