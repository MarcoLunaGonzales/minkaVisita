<?php
require("conexion.inc");

$codVisitador=$_GET['codVisitador'];
$codCiclo=11;
$codGestion=1007;

$sql="select rm.cod_contacto, rm.dia_contacto, rm.turno, rd.orden_visita, rd.cod_med, rd.cod_especialidad, rd.categoria_med 
	from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd, orden_dias o
	where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rm.dia_contacto=o.dia_contacto and 
	rc.codigo_ciclo=$codCiclo and rc.codigo_gestion=$codGestion and rc.cod_visitador=$codVisitador order by o.id, rm.turno, rd.orden_visita";
$resp=mysql_query($sql);

echo "<table border=1>";
while($dat=mysql_fetch_array($resp)){
	$diaContacto=$dat[1];
	$turno=$dat[2];
	$ordenVisita=$dat[3];
	$codMed=$dat[4];
	$codEspecialidad=$dat[5];
	$codCategoria=$dat[6];
	
	$sqlVeri="select rd.cod_med from rutero r, rutero_detalle rd 
		where r.cod_contacto=rd.cod_contacto and r.cod_ciclo=$codCiclo and r.codigo_gestion=$codGestion and 
		r.cod_visitador=$codVisitador and r.dia_contacto='$diaContacto' 
		and r.turno='$turno' and rd.orden_visita=$ordenVisita";
	$respVeri=mysql_query($sqlVeri);
	$numFilas=mysql_num_rows($respVeri);
	if($numFilas==1){
		$codMedVeri=mysql_result($respVeri,0,0);
	}
	if($codMed==$codMedVeri){
		$color="#ffffff";
	}else{
		$color="#ff0000";
	}
	echo "<tr bgcolor='$color'><td>$diaContacto</td><td>$turno</td><td>$ordenVisita</td><td>$codMed</td><td>$codEspecialidad</td>
	<td>$codCategoria</td><td>$codMedVeri</td></tr>";
	
}
echo "</table>";

?>