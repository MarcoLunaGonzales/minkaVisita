<?php

require("conexion.inc");
require("funcion_nombres.php");
require("estilos_reportes_central.inc");

$codGestion=$_GET['rpt_gestion'];
$codCiclo=$_GET['rpt_ciclo'];
$codTerritorio=$_GET['rpt_territorio'];
$codLinea=$_GET['rpt_linea'];

$sql_cabecera_gestion = mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion = '$codGestion'");
$datos_cab_gestion    = mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion   = $datos_cab_gestion[0];

echo "<form method='post' action='opciones_medico.php'>";

echo "<center><table border='0' class='textotit'><tr><th>Parrillas Personalizadas
	Gestion: $nombre_cab_gestion Ciclo: $codCiclo</th></tr></table></center><br>";

echo "<center><table border='0' class='texto' cellspacing='0' width='100%'>";
echo "<tr><th>Territorio</th><th>Linea</th><th>Medico</th><th>Especialidad</th><th>Categoria</th><th>Numero Visita</th><th>Parrilla Promocional</th></tr>";

$sql="select p.cod_linea, (select l.nombre_linea from lineas l where l.codigo_linea=p.cod_linea)linea, 
p.cod_med, (select concat(m.ap_pat_med,' ',m.ap_mat_med,' ', m.nom_med) from medicos m where m.cod_med=p.cod_med)medico, p.numero_visita, 
(select c.descripcion from ciudades c where c.cod_ciudad=m.cod_ciudad)ciudad,
(select cl.cod_especialidad from categorias_lineas cl where cl.cod_med=p.cod_med and cl.codigo_linea=p.cod_linea limit 0,1),
(select cl.categoria_med from categorias_lineas cl where cl.cod_med=p.cod_med and cl.codigo_linea=p.cod_linea limit 0,1)
from parrilla_personalizada p, medicos m where p.cod_med=m.cod_med and p.cod_gestion=$codGestion and p.cod_ciclo=$codCiclo and 
m.cod_ciudad in ($codTerritorio) and p.cod_linea in ($codLinea)
GROUP BY ciudad, cod_gestion, cod_ciclo, cod_linea, cod_med, p.numero_visita
order by ciudad, linea, medico, p.numero_visita";

//echo $sql;

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codigoLinea=$dat[0];
	$nombreLinea=$dat[1];
	$codMed=$dat[2];
	$nombreMed=$dat[3];
	$numeroVisita=$dat[4];
	$nombreTerritorio=$dat[5];
	$codEspecialidad=$dat[6];
	$categoriaMed=$dat[7];
	
	$sql1 = "select p.orden_visita, (select concat(descripcion,' ', presentacion) from muestras_medicas m where m.codigo=p.cod_mm), 
		p.cantidad_mm, (select ma.descripcion_material from material_apoyo ma where ma.codigo_material=p.cod_ma), p.cantidad_ma
		from parrilla_personalizada p where p.cod_gestion=$codGestion and p.cod_ciclo=$codCiclo and p.cod_med=$codMed and p.numero_visita=$numeroVisita and 
		p.cod_linea=$codigoLinea order by p.orden_visita";
		
	$resp1 = mysql_query($sql1);
	$parrilla_medica = "<table class='texto' width='100%' border='0'>";
	$parrilla_medica = $parrilla_medica . "<tr><th>Orden</th><th>Producto</th><th>Cantidad</th><th>Material de Apoyo</th><th>Cantidad</td><th>Obs.</th></tr>";
	while ($dat1 = mysql_fetch_array($resp1)) {
		$prioridad     = $dat1[0];
		$muestra       = $dat1[1];
		$cant_muestra  = $dat1[2];
		$material      = $dat1[3];
		$cant_material = $dat1[4];
		
		$parrilla_medica = $parrilla_medica . "<td align='center'>$prioridad</td><td align='left' width='35%'>$muestra</td>
		<td align='center' width='10%'>$cant_muestra</td>
		<td align='left' width='35%'>$material</td>
		<td align='center' width='10%'>$cant_material</td>
		</tr>";
	}
	$parrilla_medica = $parrilla_medica . "</table>";
	
	echo "<tr><td>$nombreTerritorio</td><td>$nombreLinea</td><td>$nombreMed ($codMed)</td><td>$codEspecialidad</td><td>$categoriaMed</td><td>$numeroVisita</td><td>$parrilla_medica</td></tr>";
}
echo "</table></center><br>";

?>