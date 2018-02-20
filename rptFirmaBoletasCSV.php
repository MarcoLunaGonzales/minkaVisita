<?php
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"REPORTEBOLETASFIRMAS.csv\""); 

require("conexion.inc");
error_reporting(0);
//require("estilos_reportes.inc");
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
	$tabla="boletas_visita_cabXXX";
}else{
	$tabla="boletas_visita_cabanterior";
}

//fin sacar dia contacto actual
$sql_cabecera_gestion=mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion='$rpt_gestion' and codigo_linea='$rpt_linea'");
$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion=$datos_cab_gestion[0];
$sql_cab="SELECT cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
$dato=mysql_fetch_array($resp1);
$nombre_territorio=$dato[1];	


/*echo "<center><table border='0' class='textotit'><tr><th>Reporte Boletas de Visita (Firmas)<br>
Territorio: $nombre_territorio<br>Gestion: $nombreGestion Ciclo: $rpt_ciclo<br>
Visitador: $nombreVisitadorX</th></tr></table></center><br>";*/
$indice_tabla=1;

echo "Indice;Gestion;Ciclo;Visitador;Linea;RUC;Medico;Esp.;Cat;Nro. Boleta;Fecha Programada;Fecha de Visita;Hora Visita;Observaciones;Firma;GR";
echo "\r\n";


$sql="select b.id_boleta, b.linea, b.medico, b.especialidad, b.fecha, b.fecha_visita, b.observacion, b.firma, b.nro_boleta, b.estado, 
b.longitud, b.latitud, b.id_medico, b.visitador
from $tabla b where b.id_visitador_hermes in ($rpt_visitador) and b.id_gestion='$rpt_gestion' and b.id_ciclo='$rpt_ciclo' order by b.visitador, b.id_boleta";
$resp=mysql_query($sql);

$indice_tabla=1;

while($dat=mysql_fetch_array($resp)) {
    $idBoleta=$dat[0];
	$linea=$dat[1];
	$medico=$dat[2];
	$espe=$dat[3];
	list($especialidadX, $categoriaX)=explode(" ",$espe);
	$categoriaX=substr($categoriaX,0,-1);
	$categoriaX=substr($categoriaX,1);
	$fecha=$dat[4];
	$fechaVisita=$dat[5];
	list($fechaVisitaX,$horaVisitaX)=explode(" ",$fechaVisita);
	$obs=$dat[6];
	$firma=$dat[7];
	$nroBoleta=$dat[8];
	$estado=$dat[9];
	$longitud=$dat[10];
	$latitud=$dat[11];
	$idMedico=$dat[12];
	$nombreVisitador=$dat[13];
		
	$txtimprimir="$indice_tabla;$rpt_gestion;$rpt_ciclo;$nombreVisitador;$linea;$idMedico;$medico;$especialidadX;$categoriaX;$nroBoleta;$fecha;$fechaVisitaX;$horaVisitaX;$obs;";
//    echo "";
	
	
	if($estado==1){
		$txtimprimir=$txtimprimir."Firmado;";
	}else{
		$txtimprimir=$txtimprimir."-;";
	}
    
	if($longitud!=0 && $latitud!=0){
		$txtimprimir=$txtimprimir."Con GR";
	}else{
		$txtimprimir=$txtimprimir."-";
	}
	
	echo $txtimprimir;
	echo "\r\n";

	
    $indice_tabla++;
}