<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_gestion=$_GET["rpt_gestion"];
$rpt_ciclo=$_GET["rpt_ciclo"];
$rpt_territorio=$_GET["rpt_territorio"];

$colorNoApro="#FE9A2E";
$colorApro="#69f0ae";
$colorEnApro="#FFFF00";
$colorSinRutero="#e57373";


$sql_nombreGestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion=$rpt_gestion");
$dat_nombreGestion=mysql_fetch_array($sql_nombreGestion);
$nombreGestion=$dat_nombreGestion[0];
echo "<table border='0' class='textotit' align='center'><tr><th>Ruteros en Elaboracion<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo
</th></tr></table></center><br>";

echo "<table border=0 class='texto' cellspacing='0' id='main' align='center'><tr><th>&nbsp;</th><th>&nbsp;</th>";
$sqlLinea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 and estado=1 order by 2";
$respLinea=mysql_query($sqlLinea);
while($datLinea=mysql_fetch_array($respLinea)){
	$codLinea=$datLinea[0];
	$nombreLinea=$datLinea[1];
	echo "<th>$nombreLinea</th>";
}

$sqlVis="select f.codigo_funcionario, CONCAT(f.`paterno`,' ',f.materno, ' ',f.`nombres`), c.`descripcion` from `funcionarios` f, `ciudades` c 
				where c.`cod_ciudad` = f.`cod_ciudad` and c.cod_ciudad in ($rpt_territorio) and f.estado=1 and f.cod_cargo=1011 
				order by 3,2";
$respVis=mysql_query($sqlVis);
while($datVis=mysql_fetch_array($respVis)){
	$codVisitador=$datVis[0];
	$nombreVis=$datVis[1];
	$nombreTerritorio=$datVis[2];
	echo "<tr><th>$nombreTerritorio</th><th align='left'>$nombreVis</th>";
	
	$respLinea=mysql_query($sqlLinea);
	while($datLinea=mysql_fetch_array($respLinea)){
		$codLinea=$datLinea[0];

		$sql="select * from `funcionarios_lineas` fl where fl.`codigo_linea`=$codLinea and fl.`codigo_funcionario`=$codVisitador";
		$resp=mysql_query($sql);
		$numFilas=mysql_num_rows($resp);
		
		$colorOficial="#ffffff";

		
		if($numFilas>0){
			$sqlVeri="select r.`cod_visitador`, r.estado_aprobado from `rutero_maestro_cab_aprobado` r where r.`codigo_linea` in ($codLinea) and 
				  r.`codigo_ciclo` = $rpt_ciclo and r.`codigo_gestion` = $rpt_gestion and r.`cod_visitador`=$codVisitador";
			$respVeri=mysql_query($sqlVeri);
			$numFilasVerifica=mysql_num_rows($respVeri);
			
			if($numFilasVerifica==1){
				$estadoRutero=mysql_result($respVeri,0,1);
				if($estadoRutero==1){	$cadMostrar="Aprobado"; $colorOficial=$colorApro;}
				if($estadoRutero==2){	$cadMostrar="En Aprobacion"; $colorOficial=$colorEnApro;}
				if($estadoRutero==0){	$cadMostrar="No Aprobado"; $colorOficial=$colorNoApro;}
			}
			
			if($numFilasVerifica==0 || $estadoRutero==0){
//			if($numFilasVerifica==0){
				//VERIFICAMOS EL RUTERO EN LAS OTRAS TABLAS
				$sqlVeri1="select r.`cod_visitador`, r.estado_aprobado from `rutero_maestro_cab` r where r.`codigo_linea` in ($codLinea) and 
				  r.`codigo_ciclo` = $rpt_ciclo and r.`codigo_gestion` = $rpt_gestion and r.`cod_visitador`=$codVisitador";
				$respVeri1=mysql_query($sqlVeri1);
				$numFilasVerifica1=mysql_num_rows($respVeri1);
				if($numFilasVerifica1==0){
					$cadMostrar="Sin Rutero";
					$colorOficial=$colorSinRutero;
				}else{
					$estadoRutero=mysql_result($respVeri1,0,1);
					if($estadoRutero==1){	$cadMostrar="Aprobado"; $colorOficial=$colorApro;}
					if($estadoRutero==2){	$cadMostrar="En Aprobacion"; $colorOficial=$colorEnApro;}
					if($estadoRutero==0){	$cadMostrar="No Aprobado";  $colorOficial=$colorNoApro;}
				}
				
			}
		}else{
				$cadMostrar="";
		}
		echo "<td bgcolor='$colorOficial'>&nbsp; $cadMostrar</td>";
	}
}

echo "</table>";


/*$colorNoApro="#FE9A2E";
$colorApro="#00FF00";
$colorEnApro="#FFFF00";
$colorSinRutero="#FE2E2E";*/



?>