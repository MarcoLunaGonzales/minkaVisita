<?php
require('conexion.inc');

$sql="select rd.`cod_med`, rd.`cod_zona`, rd.`cod_contacto`, rd.cod_visitador
	from `rutero_maestro_cab_aprobado` rc,
  	   `rutero_maestro_aprobado` r,
   	 	 `rutero_maestro_detalle_aprobado` rd
	where rc.`cod_rutero` = r.`cod_rutero` and
  	    rd.`cod_contacto` = r.`cod_contacto` and
    	  rc.`codigo_ciclo` = 4 and
      	rc.`codigo_gestion` = 1013";
		
		
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codMed=$dat[0];
	$codZona=$dat[1];
	$codContacto=$dat[2];
	$codVisitador=$dat[3];
	$sqlVeri="select * from direcciones_medicos where cod_med='$codMed' and numero_direccion='$codZona'";
	$respVeri=mysql_query($sqlVeri);
	$numFilasVeri=mysql_num_rows($respVeri);
	if($numFilasVeri==0){
		echo "$codMed $codZona $codVisitador $codContacto<br>";
	}
	
}
?>