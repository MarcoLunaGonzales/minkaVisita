<script type='text/javascript' language='javascript'>
  function validar(f)
{   if(f.nombre_grupo.value=='')
    {   alert('El campo Nombre de Grupo Especial esta vacio.');
        f.categoria.focus();
        return(false);
    }
	
	if(f.linea2.value!=0){
		if(f.linea1.value==f.linea2.value || f.linea2.value==f.linea3.value || f.linea2.value==f.linea4.value){
			alert('Las lineas no pueden repetirse.');
			return(false);
		}
	}
	
	if(f.linea3.value!=0){
		if(f.linea1.value==f.linea3.value || f.linea2.value==f.linea3.value || f.linea4.value==f.linea3.value){
			alert('Las lineas no pueden repetirse.');
			return(false);
		}
	}
	
	if(f.linea4.value!=0){
		if(f.linea1.value==f.linea4.value || f.linea2.value==f.linea4.value || f.linea3.value==f.linea4.value){
			alert('Las lineas no pueden repetirse.');
			return(false);
		}
	}
	
    f.submit();
}
</script>
<?php

require("conexion.inc");
require("estilos_gerencia.inc");

echo "<form action='guarda_modi_grupo_especial.php?' method='post'>";

echo "<input type='hidden' name='cod_grupo' value='$cod_grupo'>";

echo "<center><table border='0' class='textotit'><tr><td>Editar Grupo Especial</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Nombre</th><th>Ciudad</th><th>Producto</th></tr>";

$sql="SELECT g.nombre_grupo_especial, g.cod_muestra, g.agencia, g.codigo_linea, g.codigo_linea1, g.codigo_linea2, g.codigo_linea3 
	from grupo_especial g where g.codigo_grupo_especial='$cod_grupo'";
//echo $sql;
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$nombreX=$dat[0];
$muestraX=$dat[1];
$agenciaX=$dat[2];
$linea1X=$dat[3];
$linea2X=$dat[4];
$linea3X=$dat[5];
$linea4X=$dat[6];

echo "<tr><td align='center'><input type='text' size='50' class='texto' name='nombre_grupo' value='$nombreX'></td>";
$sql="SELECT c.cod_ciudad, c.descripcion FROM ciudades c where c.cod_ciudad<>115 ORDER BY 2";
$resp=mysql_query($sql);

echo "<td align='center'><select name='ciudad' class='texto'>";
while($registro=mysql_fetch_array($resp))
{   $codCiudad=$registro[0];
    $descCiudad=$registro[1];
	if($codCiudad==$agenciaX){
		echo "<option value='$codCiudad' selected>$descCiudad</option>";
	}else{
		echo "<option value='$codCiudad'>$descCiudad</option>";
	}    
}
echo "</select></td>";

$sql="SELECT m.codigo, m.descripcion, m.presentacion FROM muestras_medicas m where estado=1 ORDER BY m.descripcion, m.presentacion ASC";
$resp=mysql_query($sql);
echo "<td align='center'><select name='muestra' class='texto'>";
while($registro=mysql_fetch_array($resp))
{   $codMuestra=$registro[0];
    $nomMuestra=$registro[1];
    $nomPresent=$registro[2];
	if($muestraX==$codMuestra){
		echo "<option value='$codMuestra' selected>$nomMuestra $nomPresent</option>";
	}else{
		echo "<option value='$codMuestra'>$nomMuestra $nomPresent</option>";
	}
}
echo "</select></td>";

echo "<tr><th>Linea 1</th><th>Linea 2</th><th>Linea 3</th><th>Linea 4</th></tr>";
echo "<tr>";
$sql="SELECT l.codigo_linea, l.nombre_linea FROM lineas l where estado=1 and l.linea_promocion=1 ORDER BY 2";
$resp=mysql_query($sql);
echo "<td align='center'><select name='linea1' class='texto'>";
while($registro=mysql_fetch_array($resp))
{   $codLinea=$registro[0];
    $nomLinea=$registro[1];
	if($linea1X==$codLinea){
		echo "<option value='$codLinea' selected>$nomLinea</option>";
	}else{
		echo "<option value='$codLinea'>$nomLinea</option>";
	}
    
}
echo "</select></td>";

$resp=mysql_query($sql);
echo "<td align='center'><select name='linea2' class='texto'>";
echo "<option value='0'>-</option>";
while($registro=mysql_fetch_array($resp))
{   $codLinea=$registro[0];
    $nomLinea=$registro[1];
	if($linea2X==$codLinea){
		echo "<option value='$codLinea' selected>$nomLinea</option>";
	}else{
		echo "<option value='$codLinea'>$nomLinea</option>";
	}

}
echo "</select></td>";

$resp=mysql_query($sql);
echo "<td align='center'><select name='linea3' class='texto'>";
echo "<option value='0'>-</option>";
while($registro=mysql_fetch_array($resp))
{   $codLinea=$registro[0];
    $nomLinea=$registro[1];
	if($linea3X==$codLinea){
		echo "<option value='$codLinea' selected>$nomLinea</option>";	
	}else{
		echo "<option value='$codLinea'>$nomLinea</option>";
	}
    
}
echo "</select></td>";

$resp=mysql_query($sql);
echo "<td align='center'><select name='linea4' class='texto'>";
echo "<option value='0'>-</option>";
while($registro=mysql_fetch_array($resp))
{   $codLinea=$registro[0];
    $nomLinea=$registro[1];
	if($linea4X==$codLinea){
		echo "<option value='$codLinea' selected>$nomLinea</option>";
	}else{
		echo "<option value='$codLinea'>$nomLinea</option>";
	}
    
}
echo "</select></td>";
echo "</tr></table><br>";


echo "<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Modificar' onClick='validar(this.form)'></center>";
echo "</form>";
?>