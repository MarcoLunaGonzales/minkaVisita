<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_territorio=$_GET["rpt_territorio"];
$rpt_nombreTerritorio=$_GET['rpt_nombreTerritorio'];
$rpt_espe=$_GET['rpt_espe'];
$rpt_nombreEspe=$_GET['rpt_espe1'];
$rpt_espe1=str_replace("`","'",$rpt_espe);


echo "<table border='0' class='textotit' align='center'><tr><th>Cantidad de Medicos x Distrito y Zona<br>
Territorio: $rpt_nombreTerritorio  Especialidad: $rpt_nombreEspe
</th></tr></table></center><br>";

echo "<table border=1 class='texto' cellspacing='0' id='main' align='center'>";
echo "<tr><th>Territorio</th><th>Distrito</th><th>Zona</th><th>Cantidad</th></tr>";
$sql="select c.`descripcion`, d.`descripcion`, z.`zona`, z.`cod_zona` from ciudades c, 
		`distritos` d, `zonas` z where c.`cod_ciudad` = d.`cod_ciudad` and d.`cod_dist` = z.`cod_dist` 
      and c.`cod_ciudad` in ($rpt_territorio) order by 1, 2, 3";
$resp=mysql_query($sql);

$distrito=mysql_result($resp,0,1);;
$sumaDistrito=0;
$sumaTerritorio=0;
$bandera=0;
while($dat=mysql_fetch_array($resp)){
	$nombreTerritorio=$dat[0];
	$nombreDistrito=$dat[1];
	$nombreZona=$dat[2];
	$codZona=$dat[3];
	
	$sqlCant="select count(DISTINCT(dm.cod_med)) as cantidad from `direcciones_medicos` dm, 
		`especialidades_medicos` e where e.`cod_med`=dm.`cod_med` and dm.`cod_zona`=$codZona and 
		 e.`cod_especialidad` in ($rpt_espe1)";	
	$respCant=mysql_query($sqlCant);
	$cantidadMedicos=mysql_result($respCant,0,0);	
	
	if($distrito!=$nombreDistrito){
		echo "<tr><th colspan='3'>Total $distrito</th><th>$sumaDistrito</th></tr>";
		$distrito=$nombreDistrito;
		$sumaDistrito=0;
		$bandera=1;
	}
	$sumaDistrito=$sumaDistrito+$cantidadMedicos;
	$sumaTerritorio=$sumaTerritorio+$cantidadMedicos;
	
	echo "<tr><td>$nombreTerritorio</td><td>$nombreDistrito</td><td>$nombreZona</td><td>$cantidadMedicos</td></tr>";

}
	echo "<tr><th colspan='3'>Total $distrito</th><th>$sumaDistrito</th></tr>";
	echo "<tr><th colspan='3'>Total Territorio: $nombreTerritorio</th><th>$sumaTerritorio</th></tr>";

echo "</table>";

?>