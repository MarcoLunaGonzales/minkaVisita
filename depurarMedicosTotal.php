<?php
$codVisitador=$codVisitador;
require("conexion.inc");
require("estilos_regional_pri.inc");

$sql="select m.`cod_med` from `medico_asignado_visitador` m 
			where m.`codigo_linea` = '$global_linea' and m.`codigo_visitador` = '$codVisitador'";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codMed=$dat[0];
	$sqlVeri="select rd.`cod_med` from `rutero_maestro_cab` rc, 
						`rutero_maestro` r, `rutero_maestro_detalle` rd where rc.`cod_rutero` = r.`cod_rutero` and 
						 r.`cod_contacto` = rd.`cod_contacto` and rc.`cod_visitador` = '$codVisitador' and rc.`codigo_linea` = '$global_linea'
						 and rd.cod_med='$codMed'";
	$respVeri=mysql_query($sqlVeri);
	$numFilas=mysql_num_rows($respVeri);
	if($numFilas==0){
		$sqlBorrar="delete from medico_asignado_visitador where codigo_visitador='$codVisitador' and
								codigo_linea='$global_linea' and cod_med='$codMed'";
		$respBorrar=mysql_query($sqlBorrar);
	}
}
?>
<script language=JavaScript>
	location.href='navegador_funcionarios_regional.php';	
</script>