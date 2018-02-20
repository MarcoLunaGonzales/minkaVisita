<?php
require("conexion.inc");
require("funciones.php");
require("estilos_reportes.inc");

$rpt_territorio=$_GET["rpt_territorio"];
$rpt_nombreTerritorio=$_GET['rpt_nombreTerritorio'];
$rpt_espe=$_GET['rpt_espe'];
$rpt_nombreEspe=$_GET['rpt_espe1'];
$rpt_espe1=str_replace("`","'",$rpt_espe);
$rpt_cat=$_GET['rpt_cat'];
$rpt_nombreCat1=$_GET['rpt_cat1'];
$rpt_cat1=str_replace("`","'",$rpt_cat);
$rpt_frecuencia=$_GET['rpt_frecuencia'];
if($rpt_frecuencia==1){
	$nombreFrecuencia="Alta";
}
if($rpt_frecuencia==2){
	$nombreFrecuencia="Media";
}
if($rpt_frecuencia==3){
	$nombreFrecuencia="Baja";
}
if($rpt_frecuencia==4){
	$nombreFrecuencia="No Utiliza";
}


echo "<table border='0' class='textotit' align='center'><tr><th>Detalle de Medicos x Frecuencia de Uso de Productos<br>
Territorio: $rpt_nombreTerritorio  Especialidad: $rpt_nombreEspe Categoria: $rpt_cat1
Frecuencia de Uso:	$nombreFrecuencia
</th></tr></table></center><br>";



	echo "<table border=1 class='texto' cellspacing='0' id='main' align='center' width='55%'>";
	echo "<tr><th>Ciudad</th><th>Medico</th><th>Espe</th><th>Categoria</th><th>Producto</th></tr>";

	$sql="select concat(m.`ap_pat_med`,' ', m.`ap_mat_med`,' ', m.`nom_med`) as medico, me.`cod_espe`, me.`cod_cat`,
	concat(mm.descripcion,' ',mm.presentacion) as producto,
	(select c.descripcion from ciudades c where c.cod_ciudad=m.cod_ciudad) as ciudad
	from `medicos_a_encuestar2` me, `encuestamedicos2` em, medicos m, `muestras_medicas` mm
	where me.`cod_med`=em.`cod_med` and mm.`codigo`=em.`cod_prod`
	and em.`frecuencia`=$rpt_frecuencia and 
	me.`cod_med`=m.`cod_med` and em.`cod_med`=m.`cod_med` and
	m.`cod_ciudad` in ($rpt_territorio) and me.`cod_espe` in ($rpt_espe1) and me.`cod_cat` in ($rpt_cat1) order by ciudad, medico, producto";
	
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp)){
		$nombreMedico=$dat[0];
		$codEspe=$dat[1];
		$codCat=$dat[2];
		$nombreProducto=$dat[3];
		$nombreCiudad=$dat[4];
		
		
		echo "<tr><td>$nombreCiudad</td><td>$nombreMedico</td><td>$codEspe</td><td>$codCat</td><td>$nombreProducto</td></tr>";
	}	
	echo "</table><br>";

?>