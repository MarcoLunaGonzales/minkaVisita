<?php
echo "<script language='Javascript'>
	function validar(f)
	{
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
//$sql="select m.codigo, m.descripcion, m.presentacion, m.estado, f.nombre_forma, tm.nombre_tipo_muestra, p.descripcion
//from muestras_medicas m, formas_farmaceuticas f, tipos_muestra tm, productos p
//where m.cod_forma=f.cod_forma and m.cod_tipo_muestra=tm.cod_tipo_muestra and p.cod_producto=m.cod_producto and m.codigo='$cod_muestra'";
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
echo "<center><table border='0' class='textotit'><tr><td>Editar Muestras</td></tr></table></center><br>";

echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th align='left'>Nombre Muestra</th><td><input type='text' class='texto' value='$descripcion_muestra' name='muestra' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "<tr><th align='left'>Presentación</th><td align='center'><input type='text' class='texto' value='$presentacion_muestra' name='presentacion' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "<tr><th align='left'>Tipo de Material</th>";
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
echo "<tr><th align='left'>Línea</th><td><select name='linea' class='texto'>";
$sql="select codigo_linea, nombre_linea from lineas where linea_inventarios=1 order by nombre_linea";
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

echo "<tr><th align='left'>Estado</th><td align='left'><select name='estado' class='texto'>";
	if($estado_muestra==1)
	{
	 	echo "<option value='1' selected>Activo</option><option value='0'>Retirado</option></select>";
	}
	if($estado_muestra==0)
	{
	  echo "<option value='1'>Activo</option><option value='0' selected>Retirado</option></select>";
	}
echo "</td></tr>";
echo "<input type='hidden' name='cod_muestra' value='$cod_muestra'>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_muestras_medicas.php'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>