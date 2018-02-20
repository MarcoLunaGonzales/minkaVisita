<?php
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"CUPMEDICOS.csv\""); 
require("conexion.inc");
$codGestion=1013;
$codCiclo=2;
$sql="select rc.codigo_linea, 
	(select l.nombre_linea from lineas l where l.codigo_linea=rc.codigo_linea), f.codigo_funcionario, concat(f.paterno, ' ', f.materno, ' ',f.nombres), 
	f.cod_ciudad,
	(select c.descripcion from ciudades c where c.cod_ciudad=f.cod_ciudad), rd.cod_med,
	(select concat(m.ap_pat_med, ' ', m.ap_mat_med,' ', m.nom_med) from medicos m where m.cod_med=rd.cod_med)medico,
	(select m.cod_closeup from medicos m where m.cod_med=rd.cod_med)codCloseUp
	from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd, funcionarios f
	where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_gestion=$codGestion and rc.codigo_ciclo=$codCiclo and 
	rc.estado_aprobado=1 and f.codigo_funcionario=rc.cod_visitador group by rc.codigo_linea, f.codigo_funcionario,  f.cod_ciudad, rd.cod_med,
	rd.cod_especialidad, rd.categoria_med";
$resp=mysql_query($sql);
	echo "codLinea;nombreLinea;codVisitador;nombreVisitador;codCiudad;ciudad;codMed;nombreMed;codCUP;codMM;muestra;cantidadMM";	
	echo "\r\n";

while($dat=mysql_fetch_array($resp)){
	$codLinea=$dat[0];
	$nombreLinea=$dat[1];
	$codVisitador=$dat[2];
	$nombreVisitador=$dat[3];
	$codCiudad=$dat[4];
	$ciudad=$dat[5];
	$codMed=$dat[6];
	$nombreMed=$dat[7];
	$codCUP=$dat[8];
	
	$sqlProd="select p.cod_mm,  
	(select m.descripcion from muestras_medicas m where m.codigo=p.cod_mm)muestra, 
	p.cantidad_mm from parrilla_personalizada p where p.cod_med=$codMed and p.cod_linea=$codLinea and p.cod_gestion=$codGestion and p.cod_ciclo=$codCiclo 
	GROUP BY p.cod_mm, muestra";
	$respProd=mysql_query($sqlProd);

	while($datProd=mysql_fetch_array($respProd)){
		$codMM=$datProd[0];
		$muestra=$datProd[1];
		$cantidadMM=$datProd[2];
		echo "$codLinea;$nombreLinea;$codVisitador;$nombreVisitador;$codCiudad;$ciudad;$codMed;$nombreMed;$codCUP;$codMM;$muestra;$cantidadMM";	
		echo "\r\n";
	}
	

}

?>