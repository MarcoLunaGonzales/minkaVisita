<?php
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"RUTERO.csv\""); 

require("../conexion.inc");
//require("../estilos_inicio_adm.inc");
set_time_limit(0);

$url = $_GET['gestionCicloRpt'];
$url1 = explode("|", $url);

$codCiclo = $_GET['ciclo'];
$codGestion = $_GET['gestion'];

$sql= "select rc.codigo_gestion, rc.codigo_ciclo, rc.codigo_linea, 
	(select l.nombre_linea from lineas l where l.codigo_linea=rc.codigo_linea)linea, 
	f.codigo_funcionario, concat(f.paterno, ' ', f.materno, ' ',f.nombres)visitador, 
	f.cod_ciudad,
	(select c.descripcion from ciudades c where c.cod_ciudad=f.cod_ciudad)ciudad, rd.cod_especialidad, rd.categoria_med, rd.cod_med,
	(select concat(m.ap_pat_med, ' ', m.ap_mat_med,' ', m.nom_med) from medicos m where m.cod_med=rd.cod_med)medico,
	(select m.cod_closeup from medicos m where m.cod_med=rd.cod_med)codCloseUp
	from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd, funcionarios f
	where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto 
	and rc.cod_visitador=rm.cod_visitador and rc.cod_visitador=rd.cod_visitador and rm.cod_visitador=rd.cod_visitador
	and rc.codigo_gestion=$codGestion and rc.codigo_ciclo in ($codCiclo) and f.estado=1 and 
	rc.estado_aprobado=1 and f.codigo_funcionario=rc.cod_visitador and rd.cod_especialidad<>'' and rd.categoria_med<>'' and rd.cod_med<>0
	group by rc.codigo_linea, f.codigo_funcionario,  f.cod_ciudad, rd.cod_med,
	rd.cod_especialidad, rd.categoria_med;";
	
$resp = mysql_query($sql);
echo "codGestion;codCiclo;codLinea;nombreLinea;codVisitador;nombreVisitador;codCiudad;nombreCiudad;codEspecialidad;categoria;codMed;nombreMedico;codCUP";
echo "\r\n";

while ($dat = mysql_fetch_array($resp)) {
	$codGestionX=$dat[0];
	$codCicloX=$dat[1];
	$codLinea=$dat[2];
	$nombreLinea=$dat[3];
	$codVisitador=$dat[4];
	$nombreVisitador=$dat[5];
	$codCiudad=$dat[6];
	$nombreCiudad=$dat[7];
	$codEspe=$dat[8];
	$codCat=$dat[9];
	$codMed=$dat[10];
	$nombreMed=$dat[11];
	$codCup=$dat[12];
	
	echo "$codGestionX;$codCicloX;$codLinea;$nombreLinea;$codVisitador;$nombreVisitador;$codCiudad;$nombreCiudad;$codEspe;$codCat;$codMed;$nombreMed;$codCup";
	echo "\r\n";
}
?>