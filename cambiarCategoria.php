<?php
require("conexion.inc");
$sql="select ruc, esp, cat from medicoscat order by id";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$ruc=$dat[0];
	$esp=$dat[1];
	$cat=$dat[2];
	$deleteLineas="delete from categorias_lineas where codigo_linea=1021 and cod_med='$ruc'";
	$respDelete=mysql_query($deleteLineas);
	
	echo $deleteLineas;
	
	$sqlLineas="insert into categorias_lineas values(1021, '$ruc', '$esp', '$cat')";
	$respLineas=mysql_query($sqlLineas);
	
	echo $sqlLineas;
	
	$veriEspe="select * from especialidades_medicos where cod_med='$ruc' and cod_especialidad='$esp'";
	$respEspe=mysql_query($veriEspe);
	$numEspe=mysql_num_rows($respEspe);
	if($numEspe==0){
			$sqlInsert="insert into especialidades_medicos values($ruc, '$esp',1)";
			$respInsert=mysql_query($sqlInsert);
			
			echo $veriEspe;
			
		}
		

}
?>