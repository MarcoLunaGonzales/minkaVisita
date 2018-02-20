<?php

require("conexion.inc");
require("estilos_gerencia.inc");

/*$global_linea=$codigo_linea;
$global_agencia=$cod_ciudad;*/

echo "<script language='javascript'>
function enviar_nav(){   
	location.href='registrar_grupo_especial.php';
}
function editar_nav(f){   
	var i;
    var j=0;
    var j_linea;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_linea=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {	alert('Debe seleccionar solamente un Grupo Especial para editar sus datos.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un Grupo Especial para editar sus datos.');
        }
        else
        {   location.href='editar_grupoespe.php?cod_grupo='+j_linea;
        }
    }
}
function eliminar_nav(f)
{   var i;
    var j=0;
    datos=new Array();
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   datos[j]=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j==0)
    {   alert('Debe seleccionar al menos un Grupo Especial para proceder a su eliminaci�n.');
    }
    else
    {   if(confirm('Esta seguro de eliminar los datos.'))
        {   location.href='eliminar_grupo_especial.php?datos='+datos;
        }
        else
        {   return(false);
        }
    }
}
</script>";

echo "<form>";
$sql = "select g.codigo_grupo_especial, g.nombre_grupo_especial, (select descripcion from ciudades c where c.cod_ciudad=g.agencia)ciudad,
(select l.nombre_linea from lineas l where l.codigo_linea=g.codigo_linea)linea1,
(select l.nombre_linea from lineas l where l.codigo_linea=g.codigo_linea1)linea2,
(select l.nombre_linea from lineas l where l.codigo_linea=g.codigo_linea2)linea3,
(select l.nombre_linea from lineas l where l.codigo_linea=g.codigo_linea3)linea4
	from grupo_especial g 
	order by g.nombre_grupo_especial, ciudad";
//echo "$sql";
$resp = mysql_query($sql);
echo "<h1>Registro de Grupos Especiales</h1>";
$indice_tabla=1;
echo "<center><table class='texto'>";
//echo "<tr><td>&nbsp;</td><td>&nbsp;</td><th>Nombre Grupo Especial</th><th>Especialidad</th><th>Visitadores Grupo Especial</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
//echo "<tr><td>&nbsp;</td><td>&nbsp;</td><th>Nombre Grupo Especial</th><th>&nbsp;</th><th>Lineas de Visita</th></tr>";
echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Grupo Especial</th><th>Ciudad</th><th>Lineas Asociadas</th><th>&nbsp;</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $cod_grupo = $dat[0];
    $nombre_grupo = $dat[1];
	$nombreCiudad=$dat[2];
	$linea1=$dat[3];
	$linea2=$dat[4];
	$linea3=$dat[5];
	$linea4=$dat[6];
	
    echo "<tr>";
    echo "<td align='center'>$indice_tabla</td>";
    echo "<td align='center'><input type='checkbox' name='codigo' value='$cod_grupo'></td>";
    echo "<td align='left'>$nombre_grupo</td>";
	echo "<td align='left'>$nombreCiudad</td>";
	echo "<td align='left'>$linea1 $linea2 $linea3 $linea4</td>";

    echo "<td align='center'>
	<a href='navegador_grupoespe_detalle.php?cod_grupo=$cod_grupo'>
	<img src='imagenes/medico.png' width='40px'>
	</a></td>";
//    echo "<td align='center'><a href='programas/grupoespecial/navegadorLineasVisita.php?cod_grupo=$cod_grupo&codigo_linea=$global_linea&cod_ciudad=$global_agencia'>Ver lineas de visita >></a></td>";
    echo "</tr>";
    $indice_tabla++;
} 
echo "</table></center><br>";

//require("home_regional1.inc");
echo "<center><table border='0' class='texto'><tr>
    <td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav();'></td>
    <td><input type='button' value='Editar' class='boton' onclick='editar_nav(this.form);'></td>
    <td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form);'></td>";
echo "</tr></table></center>";
echo "</form>";

?>
