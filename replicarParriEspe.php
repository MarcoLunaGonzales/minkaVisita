<?php

require("conexion.inc");
require("estilos_vacio.inc");


?>

<script language='JavaScript'>

function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function ajaxGrupos(codigo){
	var codLinea=codigo.value;
	var contenedor;
	contenedor = document.getElementById('divGEOrigen');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxGruposGE.php?codLinea='+codLinea+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function ajaxGrupos1(codigo){
	var codLinea=codigo.value;
	var contenedor;
	contenedor = document.getElementById('divGEDestino');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxGruposGE1.php?codLinea='+codLinea+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

</script>
<?php


echo "<form name='form1' action='guardarReplicaParriEspe.php' method='post'>";

echo "<br><br>";
echo "<center><table border='0' class='textotit'><tr><td align='center'>Replicar Parrillas Grupos Especiales</td></tr></table></center><br>";
echo "<center><table border='1' width='80%' cellspacing='0' class='texto'>";

echo "<tr><th>Linea Origen</th>";
$sqlLinea="select l.codigo_linea, l.nombre_linea from lineas l where l.estado=1 and l.linea_promocion=1 order by l.nombre_linea";
$respLinea=mysql_query($sqlLinea);
echo "<td align='center'><select name='lineaOrigen' class='texto' onChange='ajaxGrupos(this);'>";
while($datLinea=mysql_fetch_array($respLinea)){
	$codLinea=$datLinea[0];
	$nombreLinea=$datLinea[1];	
	echo "<option value='$codLinea'>$nombreLinea</option>";
}
echo "</select></td>";

echo "<th>Linea Destino</th>";
$sqlLinea="select l.codigo_linea, l.nombre_linea from lineas l where l.estado=1 and l.linea_promocion=1 order by l.nombre_linea";
$respLinea=mysql_query($sqlLinea);
echo "<td align='center'><select name='lineaDestino' class='texto' onChange='ajaxGrupos1(this);'>";
while($datLinea=mysql_fetch_array($respLinea)){
	$codLinea=$datLinea[0];
	$nombreLinea=$datLinea[1];	
	echo "<option value='$codLinea'>$nombreLinea</option>";
}
echo "</select></td>";
echo "</tr>";

echo "<tr><th>G.E. Origen</th>";
echo "<td align='center'><div id='divGEOrigen'><select name='GEOrigen' class='texto'>";
echo "</select></div></td>";

echo "<th>G.E. del Producto</th>";
echo "<td align='center'><div id='divGEDestino'><select name='GEDestino' class='texto'>";
echo "</select></div></td>";
echo "</tr>";



echo "<tr><th>Ciclo Origen</th>";
$sqlCiclo="select g.codigo_gestion, g.nombre_gestion, c.cod_ciclo from ciclos c, gestiones g  
	where c.codigo_gestion=g.codigo_gestion group by g.codigo_gestion, g.codigo_gestion, c.cod_ciclo 
	order by g.codigo_gestion desc, c.cod_ciclo desc limit 0,10";
$respCiclo=mysql_query($sqlCiclo);
echo "<td align='center'><select name='cicloOrigen' class='texto'>";
while($datCiclo=mysql_fetch_array($respCiclo)){
	$codCiclo=$datCiclo[2]."|".$datCiclo[0];
	$nombreCiclo=$datCiclo[2]." / ".$datCiclo[1];	
	echo "<option value='$codCiclo'>$nombreCiclo</option>";
}
echo "</select></td>";

echo "<th>Ciclo Destino</th>";
$sqlCiclo="select g.codigo_gestion, g.nombre_gestion, c.cod_ciclo from ciclos c, gestiones g  
	where c.codigo_gestion=g.codigo_gestion group by g.codigo_gestion, g.codigo_gestion, c.cod_ciclo 
	order by g.codigo_gestion desc, c.cod_ciclo desc limit 0,10";
$respCiclo=mysql_query($sqlCiclo);
echo "<td align='center'><select name='cicloDestino' class='texto'>";
while($datCiclo=mysql_fetch_array($respCiclo)){
	$codCiclo=$datCiclo[2]."|".$datCiclo[0];
	$nombreCiclo=$datCiclo[2]." / ".$datCiclo[1];	
	echo "<option value='$codCiclo'>$nombreCiclo</option>";
}
echo "</select></td>";
echo "</tr>";




echo "</table></center><br>";
echo "<center><input type='submit' value='Replicar' class='boton'></center>";
echo "</form>";
echo "<center>Nota: Para realizar la réplica correctamente se borraran todas las parrillas registradas en la Línea de Destino.</center>";
?>