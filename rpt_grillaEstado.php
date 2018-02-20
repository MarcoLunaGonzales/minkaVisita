<?php
	require("conexion.inc");
	require("estilos_reportes.inc");
	require("funcion_nombres.php");
	$rpt_territorio=$_GET['rpt_territorio'];
	$rpt_nombreTerritorio=$_GET['rpt_nombreTerritorio'];
	
	echo "<html><body>";
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td align='center'>Grillas<br>Territorio: $rpt_nombreTerritorio</center></td></tr></table><br>";

echo "<table border=1 class='texto' cellspacing='0' id='main'><tr><th>&nbsp;</th><th>&nbsp;</th>";
$sqlLinea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 and estado=1 order by 2";
$respLinea=mysql_query($sqlLinea);
while($datLinea=mysql_fetch_array($respLinea)){
	$codLinea=$datLinea[0];
	$nombreLinea=$datLinea[1];
	echo "<th>$nombreLinea</th>";
}

$sqlTerritorio="select cod_ciudad, descripcion from ciudades where cod_ciudad in ($rpt_territorio) order by descripcion";
$respTerritorio=mysql_query($sqlTerritorio);
while($datTerritorio=mysql_fetch_array($respTerritorio)){
	$codTerritorio=$datTerritorio[0];
	$nombreTerritorio=$datTerritorio[1];
	echo "<tr><td>$codTerritorio</td><th>$nombreTerritorio</th>";
	
	$respLinea=mysql_query($sqlLinea);
	while($datLinea=mysql_fetch_array($respLinea)){
		$codLinea=$datLinea[0];

		$sqlGrilla="select g.`nombre_grilla`, g.`estado` from `grilla` g where g.`codigo_linea`='$codLinea' and g.`agencia`='$codTerritorio'";
		$respGrilla=mysql_query($sqlGrilla);
		$cadGrilla="";
		while($datGrilla=mysql_fetch_array($respGrilla)){
			$nombreGrilla=$datGrilla[0];
			$estado=$datGrilla[1];
			if($estado==0){
				$nombreEstado="No Vigente";
			}else{
				$nombreEstado="Vigente";
			}
			$cadGrilla.=$nombreGrilla." ($nombreEstado)<br>";
		}	
		echo "<td>&nbsp; $cadGrilla</td>";
	}
}

echo "</table>";

?>