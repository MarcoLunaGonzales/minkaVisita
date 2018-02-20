<?php
require("conexion.inc");
$codCiclo=3;
$codGestion=1011;

$sqlGE="select g.codigo_grupo_especial, g.nombre_grupo_especial, g.agencia, g.codigo_linea, 
	l.nombre_linea, c.descripcion from grupo_especial g, ciudades c, lineas l
	where g.agencia=c.cod_ciudad and g.codigo_linea=l.codigo_linea order by c.descripcion, 
	l.nombre_linea, g.nombre_grupo_especial";
$respGE=mysql_query($sqlGE);
echo "<table border=1>";
	echo "<tr><td>Ciudad</td><td>Linea</td><td>CodGrupo</td><td>Grupo</td><td>Nro Medicos</td><td>Nro parrillas</td><td>Nro Vis</td></tr>";

while($datGE=mysql_fetch_array($respGE)){
	$codGrupo=$datGE[0];
	$nombreGrupo=$datGE[1];
	$codAgencia=$datGE[2];
	$codLinea=$datGE[3];
	$nombreLinea=$datGE[4];
	$nombreAgencia=$datGE[5];
	
	$sqlNumMed="select count(*) from grupo_especial_detalle g where g.codigo_grupo_especial=$codGrupo";
	$respNumMed=mysql_query($sqlNumMed);
	$numMedicos=mysql_result($respNumMed,0,0);
	
	$sqlNumParrilla="select count(*) from parrilla_especial p where p.codigo_gestion=$codGestion 
		and p.cod_ciclo=$codCiclo and p.codigo_grupo_especial=$codGrupo";
	$respNumParrilla=mysql_query($sqlNumParrilla);
	$numParrillas=mysql_result($respNumParrilla,0,0);
	
	$sqlNumVis="select count(*) from grupos_especiales g, grupos_especiales_detalle gd where g.id=gd.id 
		and g.codigo_grupo_especial in ($codGrupo) and g.gestion=$codGestion and g.ciclo=$codCiclo and gd.cod_visitador>0";
	$respNumVis=mysql_query($sqlNumVis);
	$numVisitadores=mysql_result($respNumVis,0,0);
	
	echo "<tr><td>$nombreAgencia</td><td>$nombreLinea</td><td>$codGrupo</td><td>$nombreGrupo</td><td>$numMedicos</td><td>$numParrillas</td><td>$numVisitadores</td></tr>";
}


?>