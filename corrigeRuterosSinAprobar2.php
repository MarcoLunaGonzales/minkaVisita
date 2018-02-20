<?php
require("conexion.inc");
$codigoCiclo=7;
$codigoGestion=1014;

$sql="select rc.cod_rutero, rm.cod_contacto, rd.cod_med, rd.cod_especialidad, rd.categoria_med, rm.cod_visitador, rc.codigo_linea
 from rutero_maestro_cab rc, 
rutero_maestro rm, rutero_maestro_detalle rd
where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_gestion=$codigoGestion and rc.codigo_ciclo=$codigoCiclo and
rd.cod_med not in (select c.cod_med from medico_asignado_visitador c where c.cod_med=rd.cod_med and c.codigo_linea=rc.codigo_linea and 
c.codigo_visitador=rc.cod_visitador)";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codRutero=$dat[0];
	$codContacto=$dat[1];
	$codMed=$dat[2];
	$codEspe=$dat[3];
	$codCat=$dat[4];
	$codVisitador=$dat[5];
	$codLinea=$dat[6];
	
	echo "$codVisitador $codMed $codEspe $codCat <br>";
	
	if($codEspe!="" and $codCat!=""){
		$sqlDel="delete from medico_asignado_visitador where codigo_linea=$codLinea and cod_med=$codMed and codigo_visitador=$codVisitador";
		$respDel=mysql_query($sqlDel);		
		
		$sqlInsert2="insert into medico_asignado_visitador values($codMed, $codVisitador, $codLinea)";
		echo $sqlInsert2;
		$respInsert2=mysql_query($sqlInsert2);
		
	}
	
}
?>