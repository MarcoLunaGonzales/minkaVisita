<?php
require("conexion.inc");
error_reporting(0);
require("estilos_reportes.inc");
require("funcion_nombres.php");
$rpt_ciclo=$rpt_ciclo;
$rpt_gestion=$rpt_gestion;
$rpt_territorio=$rpt_territorio;
$rpt_visitador=$rpt_visitador;
$nombreVisitadorX=nombreVisitador($rpt_visitador);
$rpt_linea=$rpt_linea;
$nombreGestion=nombreGestion($rpt_gestion);

$sqlAux="select c.estado from ciclos c where c.codigo_gestion=$rpt_gestion and c.cod_ciclo=$rpt_ciclo";
$respAux=mysql_query($sqlAux);
$estadoAux=mysql_result($respAux,0,0);

if($estadoAux=="Activo"){
	$tabla="boletas_visita_cab";
}else{
	$tabla="boletas_visita_cab_copy";
}

//fin sacar dia contacto actual
$sql_cabecera_gestion=mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion='$rpt_gestion' and codigo_linea='$rpt_linea'");
$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion=$datos_cab_gestion[0];
$sql_cab="SELECT cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
$dato=mysql_fetch_array($resp1);
$nombre_territorio=$dato[1];	


echo "<center><table border='0' class='textotit'><tr><th>Reporte Boletas de Visita (Firmas)<br>
Territorio: $nombre_territorio<br>Gestion: $nombreGestion Ciclo: $rpt_ciclo<br>
Visitador: $nombreVisitadorX</th></tr></table></center><br>";
$indice_tabla=1;

echo "<center><table border='1' class='texto' width='100%' cellspacing='0' id='main'>";

echo "<tr><th>&nbsp;</th>
<th>Linea</th>
<th>Medico</th>
<th>Espe/Cat</th>
<th>Nro. Boleta</th>
<th>Fecha Programada</th>
<th>Fecha de Visita</th>
<th>Observaciones</th>
<th>Firma</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
</tr>";

$sql="select b.id_boleta, b.linea, b.medico, b.especialidad, b.fecha, b.fecha_visita, b.observacion, b.firma, b.nro_boleta, b.estado, 
b.longitud, b.latitud
from $tabla b where b.id_visitador_hermes=$rpt_visitador and b.id_gestion='$rpt_gestion' and b.id_ciclo='$rpt_ciclo'";
$resp=mysql_query($sql);

$indice_tabla=1;

while($dat=mysql_fetch_array($resp)) {
    $idBoleta=$dat[0];
	$linea=$dat[1];
	$medico=$dat[2];
	$espe=$dat[3];
	$fecha=$dat[4];
	$fechaVisita=$dat[5];
	$obs=$dat[6];
	$firma=$dat[7];
	$nroBoleta=$dat[8];
	$estado=$dat[9];
	$longitud=$dat[10];
	$latitud=$dat[11];
		
    echo "<tr>
    <td align='center'>$indice_tabla</td>
    <td>$linea</td>
    <td align='center'>$medico</td>
    <td align='center'>$espe</td>
    <td align='center'>$nroBoleta</td>
    <td align='center'>$fecha</td>
    <td align='center'>$fechaVisita</td>
    <td align='center'>$obs</td>";
	
	
	if($estado==1){
		echo "<td align='center'><img src='decoImage.php?id=$idBoleta&tabla=$tabla'></td>";
	}else{
		echo "<td>&nbsp;</td>";
	}
    
	if($longitud!=0 && $latitud!=0){
		echo "<td align='center'><a href='puntoVisita.php?id=$idBoleta&longitud=$longitud&latitud=$latitud&medico=$medico' target='_blank'><img width='40px' src='imagenes\googlemaps.png'></a></td>";
	}else{
		echo "<td>&nbsp;</td>";
	}
	
	echo "</tr>";
    $indice_tabla++;
}

echo "</table></center><br>";
echo "</body></html>";
