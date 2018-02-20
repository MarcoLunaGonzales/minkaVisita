<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_gestion=$_GET["rpt_gestion"];
$rpt_ciclo=$_GET["rpt_ciclo"];
$rpt_territorio=$_GET["rpt_territorio"];

$colorNoApro="#FE9A2E";
$colorApro="#00FF00";
$colorEnApro="#FFFF00";
$colorSinRutero="#FE2E2E";


$sql_nombreGestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion=$rpt_gestion");
$dat_nombreGestion=mysql_fetch_array($sql_nombreGestion);
$nombreGestion=$dat_nombreGestion[0];
echo "<table border='0' class='textotit' align='center'><tr><th>Contactos Promedio/Dia en Rutero<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo
</th></tr></table></center><br>";
echo "<h3 align='center'><span style='color: black; font-size:8pt;'>(SOLO RUTEROS APROBADOS O EN APROBACION)</span></h3>";
echo "<h3 align='center'>Leyenda: <span style='color: black; font-size:10pt;'>X - Y -</span>
			<span style='color: blue; font-size:14pt;'>Z</span></h3>";
echo "<h3 align='center'>X: Nro. Dias   Y: Nro. Contactos   Z: Promedio de contactos por Dia</h2>";

			
echo "<table border=1 class='texto' cellspacing='0' id='main' align='center'><tr><th>&nbsp;</th><th>&nbsp;</th>";
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
			$sqlNumeroDias="select count(*), rm.dia_contacto from rutero_maestro_cab r, rutero_maestro rm
				where r.cod_rutero=rm.cod_rutero and r.cod_visitador=rm.cod_visitador and 
				r.codigo_gestion='$rpt_gestion' and r.codigo_ciclo='$rpt_ciclo' and r.codigo_linea='$codLinea' and 
				r.cod_visitador='$codVisitador' group by rm.dia_contacto";
			$respNumeroDias=mysql_query($sqlNumeroDias);
			$numeroDias=mysql_num_rows($respNumeroDias);
			
			$sqlContactos="select count(*) from rutero_maestro_cab r, rutero_maestro rm, rutero_maestro_detalle rd
				where r.cod_rutero=rm.cod_rutero and r.cod_visitador=rm.cod_visitador and rm.cod_contacto=rd.cod_contacto and rm.cod_visitador=rd.cod_visitador and 
				r.codigo_gestion='$rpt_gestion' and r.codigo_ciclo='$rpt_ciclo' and r.codigo_linea='$codLinea' and 
				r.cod_visitador='$codVisitador' and r.estado_aprobado != 0";
			$respContactos=mysql_query($sqlContactos);
			$numeroContactos=mysql_result($respContactos,0,0);
			$promedioContactos=0;
			if($numeroDias>0){
				$promedioContactos=round($numeroContactos/$numeroDias, 1);
			}
			if($promedioContactos>=16 || ($promedioContactos>=13 && $codLinea==1047) || ($promedioContactos>=13 && $codLinea==1041)){
				$colorOficial="#2EFE64";
			}else{
				$colorOficial="#FE2E2E";
			}
			$cadMostrar="<span style='color: black; font-size:10pt;'>$numeroDias - $numeroContactos -</span>
			<span style='color: blue; font-size:14pt;'>$promedioContactos</span>";
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