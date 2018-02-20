<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_territorio=$_GET["rpt_territorio"];
$rpt_nombreTerritorio=$_GET['rpt_nombreTerritorio'];
$rpt_espe=$_GET['rpt_espe'];
$rpt_nombreEspe=$_GET['rpt_espe1'];
$rpt_producto=$_GET['rpt_producto'];
$rpt_espe1=str_replace("`","'",$rpt_espe);
$rpt_cat=$_GET['rpt_cat'];
$rpt_nombreCat1=$_GET['rpt_cat1'];
$rpt_cat1=str_replace("`","'",$rpt_cat);


echo "<table border='0' class='textotit' align='center'><tr><th>Detalle por Producto, Territorio, Especialidad y Categoria de Medico<br>
Territorio: $rpt_nombreTerritorio  Especialidad: $rpt_nombreEspe Categoria: $rpt_nombreCat1
</th></tr></table></center><br>";

echo "<table border=1 class='texto' cellspacing='0' width='55%' align='center'>";
echo "<tr><th>Territorio</th><th>Medico</th><th>Especialidad</th><th>Categoria</th><th>Frecuencia de Uso</th></tr>";
	
$sql="select concat(m.`ap_pat_med`, ' ', m.`ap_mat_med`, ' ', m.`nom_med`) as medico,
	(select c.descripcion from `ciudades` c where c.`cod_ciudad`=m.cod_ciudad) as ciudad,
	me.`cod_espe`, me.`cod_cat`, e.`frecuencia`
	from `encuestamedicos2` e, `medicos_a_encuestar2` me, medicos m where e.`cod_med` = me.`cod_med` and 
	me.`cod_espe` in ($rpt_espe1) and e.cod_med = m.cod_med and me.`cod_med` = m.cod_med and m.`cod_ciudad` in ($rpt_territorio) and 
	e.`frecuencia` in (1,2,3,4) and e.`cod_prod` = '$rpt_producto' and me.`cod_cat` in ($rpt_cat1) 
	order by ciudad, me.`cod_cat`, medico;";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$nombreMedico=$dat[0];
	$nombreTerritorio=$dat[1];
	$codEspe=$dat[2];
	$codCat=$dat[3];
	$frecuenciaUso=$dat[4];
	if($frecuenciaUso==1){
		$nombreFrecuencia="Alta";
	}
	if($frecuenciaUso==2){
		$nombreFrecuencia="Media";
	}
	if($frecuenciaUso==3){
		$nombreFrecuencia="Baja";
	}
	if($frecuenciaUso==4){
		$nombreFrecuencia="No Utiliza";
	}
	echo "<tr><td>$nombreTerritorio</td><td>$nombreMedico</td><td>$codEspe</td><td>$codCat</td><td>$nombreFrecuencia</td></tr>";	
}	
echo "</table>";
?>