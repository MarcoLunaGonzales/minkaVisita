<?php
require("conexion.inc");
require("estilos_visitador.inc");

$cod_ciclo=$cod_ciclo;
$cod_gestion=$cod_gestion;
$cod_rutero=$cod_rutero;
$cod_med=$cod_med;
$cod_visitador=$global_visitador;
$num_registros=$num_registros;

//echo "$cod_ciclo $cod_gestion $cod_rutero $cod_med $cod_visitador";

$sqlVerificacion="select m.`cod_med_frec` from `medico_frec_especial` m where m.`cod_ciclo` = $cod_ciclo and 
		m.`cod_gestion` = $cod_gestion and m.`cod_med` = $cod_med and m.`cod_visitador`=$cod_visitador";
$respVerificacion=mysql_query($sqlVerificacion);
while($datVerificacion=mysql_fetch_array($respVerificacion)){
	$codFrec=$datVerificacion[0];
	mysql_query("delete from medico_frec_especial where cod_med_frec='$codFrec'");
	mysql_query("delete from medico_frec_especialdetalle where cod_med_frec='$codFrec'");
}

$sqlCodigo="select max(cod_med_frec) from medico_frec_especial";
$respCodigo=mysql_query($sqlCodigo);
$codigo=mysql_result($respCodigo,0,0);
$codigo++;

$sqlInsert="INSERT INTO  `medico_frec_especial` (`cod_med_frec`, `cod_ciclo`, `cod_gestion`, `cod_med`, `cod_visitador`, `cod_dia`)  
		VALUE($codigo, $cod_ciclo, $cod_gestion, $cod_med, $cod_visitador, 0)";
$respInsert=mysql_query($sqlInsert);

for($i=1;$i<=$num_registros;$i++){
	$diaContacto="diaContacto$i";
	$diaContactoAgru="diaContactoAgru$i";
	$valDiaContacto=$$diaContacto;
	$valDiaContactoAgru=$$diaContactoAgru;
	//echo " <br>".$valDiaContacto." ".$valDiaContactoAgru;
	$sqlInsertDet="INSERT INTO  `medico_frec_especialdetalle` ( `cod_med_frec`, `cod_dia`, `cod_dia_agrupado`, `nro_visita` ) 
				VALUE ($codigo, $valDiaContacto, $valDiaContactoAgru, $i)";
	$respInsertDet=mysql_query($sqlInsertDet);
}

?>
<script language=JavaScript>
	alert("Los datos se registraron satisfactoriamente.");
	location.href='navegador_rutero_maestro.php';
	</script>