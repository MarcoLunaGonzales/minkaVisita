<?php
require('conexion.inc');
require('estilos_visitador.inc');
$cod_rutero=$cod_rutero;

$semana1=$semana1;
$semana2=$semana2;

if($semana1==1){
	$idDiaIni=1;
	$idDiaFin=5;
}
if($semana1==2){
	$idDiaIni=6;
	$idDiaFin=10;
}
if($semana1==3){
	$idDiaIni=11;
	$idDiaFin=15;
}
if($semana1==4){
	$idDiaIni=16;
	$idDiaFin=20;
}

if($semana2==1){
	$idDiaIni2=1;
	$idDiaFin2=5;
}
if($semana2==2){
	$idDiaIni2=6;
	$idDiaFin2=10;
}
if($semana2==3){
	$idDiaIni2=11;
	$idDiaFin2=15;
}
if($semana2==4){
	$idDiaIni2=16;
	$idDiaFin2=20;
}

$difDias=$idDiaIni2-$idDiaIni;

for($ii=$idDiaIni;$ii<=$idDiaFin;$ii++){
	$sql="select r.`cod_contacto`, o.`id`, o.dia_contacto from `rutero_maestro` r, 
			`orden_dias` o  where r.`cod_rutero`=$cod_rutero and 
			r.`dia_contacto`=o.`dia_contacto` and o.id=$ii and r.turno='Am'";
	$resp=mysql_query($sql);
	$codContacto1=mysql_result($resp,0,0);
	$diaContacto1=mysql_result($resp,0,2);

	$sql="select r.`cod_contacto`, o.`id`, o.dia_contacto from `rutero_maestro` r, 
			`orden_dias` o  where r.`cod_rutero`=$cod_rutero and 
			r.`dia_contacto`=o.`dia_contacto` and o.id=$ii and r.turno='Pm'";
	$resp=mysql_query($sql);
	$codContacto2=mysql_result($resp,0,0);
	$diaContacto2=mysql_result($resp,0,2);
	
	$jj=$ii+$difDias;
	
	$sql="select r.`cod_contacto`, o.`id`, o.dia_contacto from `rutero_maestro` r, 
			`orden_dias` o  where r.`cod_rutero`=$cod_rutero and 
			r.`dia_contacto`=o.`dia_contacto` and o.id=$jj and r.turno='Am'";
	$resp=mysql_query($sql);
	$codContactoReem1=mysql_result($resp,0,0);
	$diaContactoReem1=mysql_result($resp,0,2);


	$sql="select r.`cod_contacto`, o.`id`, o.dia_contacto from `rutero_maestro` r, 
			`orden_dias` o  where r.`cod_rutero`=$cod_rutero and 
			r.`dia_contacto`=o.`dia_contacto` and o.id=$jj and r.turno='Pm'";
	$resp=mysql_query($sql);
	$codContactoReem2=mysql_result($resp,0,0);
	$diaContactoReem2=mysql_result($resp,0,2);
	
	$sqlUpd="update rutero_maestro set dia_contacto='$diaContactoReem1' where cod_contacto='$codContacto1'";
	$respUpd=mysql_query($sqlUpd);
	
	$sqlUpd="update rutero_maestro set dia_contacto='$diaContacto1' where cod_contacto='$codContactoReem1'";
	$respUpd=mysql_query($sqlUpd);

	$sqlUpd="update rutero_maestro set dia_contacto='$diaContactoReem2' where cod_contacto='$codContacto2'";
	$respUpd=mysql_query($sqlUpd);
	
	$sqlUpd="update rutero_maestro set dia_contacto='$diaContacto2' where cod_contacto='$codContactoReem2'";
	$respUpd=mysql_query($sqlUpd);	

}
?>
<script language=JavaScript>
	alert("Las semanas se intercambiaron satisfactoriamente.");
	window.close();
</script>