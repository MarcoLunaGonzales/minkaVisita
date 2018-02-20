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

//echo $global_linea;
//echo $global_agencia;
$global_agencia=$_GET["global_agencia"];

echo "<form action='guarda_grupo_especial.php' method='post'>";


echo "<center><table border='0' class='textotit'><tr><td>Adicionar Grupo Especial</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Nombre</th><th>Ciudad</th><th>Producto</th></tr>";

echo "<tr><td align='center'><input type='text' size='50' class='texto' name='nombre_grupo'></td>";
$sql="SELECT c.cod_ciudad, c.descripcion FROM ciudades c where c.cod_ciudad<>115 ORDER BY 2";
$resp=mysql_query($sql);
echo "<td align='center'><select name='ciudad' class='texto'>";
while($registro=mysql_fetch_array($resp))
{   $codCiudad=$registro[0];
    $descCiudad=$registro[1];
    echo "<option value='$codCiudad'>$descCiudad</option>";
}
echo "</select></td>";

$sql="SELECT m.codigo, m.descripcion, m.presentacion FROM muestras_medicas m where estado=1 ORDER BY m.descripcion, m.presentacion ASC";
$resp=mysql_query($sql);
echo "<td align='center'><select name='muestra' class='texto'>";
while($registro=mysql_fetch_array($resp))
{   $codMuestra=$registro[0];
    $nomMuestra=$registro[1];
    $nomPresent=$registro[2];
    echo "<option value='$codMuestra'>$nomMuestra $nomPresent</option>";
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
    echo "<option value='$codLinea'>$nomLinea</option>";
}
echo "</select></td>";

$resp=mysql_query($sql);
echo "<td align='center'><select name='linea2' class='texto'>";
echo "<option value='0'>-</option>";
while($registro=mysql_fetch_array($resp))
{   $codLinea=$registro[0];
    $nomLinea=$registro[1];
    echo "<option value='$codLinea'>$nomLinea</option>";
}
echo "</select></td>";

$resp=mysql_query($sql);
echo "<td align='center'><select name='linea3' class='texto'>";
echo "<option value='0'>-</option>";
while($registro=mysql_fetch_array($resp))
{   $codLinea=$registro[0];
    $nomLinea=$registro[1];
    echo "<option value='$codLinea'>$nomLinea</option>";
}
echo "</select></td>";

$resp=mysql_query($sql);
echo "<td align='center'><select name='linea4' class='texto'>";
echo "<option value='0'>-</option>";
while($registro=mysql_fetch_array($resp))
{   $codLinea=$registro[0];
    $nomLinea=$registro[1];
    echo "<option value='$codLinea'>$nomLinea</option>";
}
echo "</select></td>";
echo "</tr></table><br>";
echo "<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";

?>
