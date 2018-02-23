<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.muestra.value=='')
		{	alert('El campo Producto esta vacio.');
			f.muestra.focus();
			return(false);
		}
		if(f.presentacion.value=='')
		{	alert('El campo Presentación esta vacio.');
			f.presentacion.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
echo "<form action='guarda_muestra_medica.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><th>Adicionar Muestras</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
/*echo "<tr><th align='left'>Producto</th>";
$sql2="select cod_producto, descripcion from productos order by descripcion";
$resp2=mysql_query($sql2);
echo "<td><select name='producto' class='texto'>";
while($dat2=mysql_fetch_array($resp2))
{	$cod_producto=$dat2[0];
	$nombre_producto=$dat2[1];
	echo "<option value='$cod_producto'>$nombre_producto</option>";
}
echo "</select></td>";
echo "</tr>";*/
echo "<tr><th align='left'>Nombre Muestra</th><td><input type='text' class='texto' name='muestra' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "<tr><th align='left'>Presentación</th><td align='center'><input type='text' class='texto' name='presentacion' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "<tr><th align='left'>Tipo de Muestra</th>";
$sql1="select * from tipos_muestra order by nombre_tipo_muestra";
$resp1=mysql_query($sql1);
echo "<td><select name='tipo_muestra' class='texto'>";
while($dat1=mysql_fetch_array($resp1))
{	$cod_tipomuestra=$dat1[0];
	$nombre_tipomuestra=$dat1[1];
	echo "<option value='$cod_tipomuestra'>$nombre_tipomuestra</option>";
}
echo "</select></td>";
echo "</tr>";
echo "<tr><th align='left'>Línea</th><td><select name='linea' class='texto'>";
$sql="select codigo_linea, nombre_linea from lineas where linea_inventarios=1 order by nombre_linea";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp))
{	$codigo_linea=$dat[0];
	$nombre_linea=$dat[1];
	if($rpt_linea==$codigo_linea)
	{	echo "<option value='$codigo_linea' selected>$nombre_linea</option>";
	}
	else
	{	echo "<option value='$codigo_linea'>$nombre_linea</option>";
	}
}
echo "</select></td></tr>";
echo "</tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_muestras_medicas.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>