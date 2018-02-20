<?php 

require("conexion.inc");
$sql_grilla = mysql_query("SELECT * from grilla_detalle where codigo_grilla = 1049");
while ($row = mysql_fetch_array($sql_grilla)) {
	$codigo_grilla    = $row[0];
	$cod_especialidad = $row[1];
	$cod_categoria    = $row[2];
	$frecuencia       = $row[3];
	$cod_l_visita     = $row[4];
	$sql_nueva_linea  = mysql_query("SELECT * from lineas_visita_trans_sac_pun where codigo_l_visita1021 = $cod_l_visita");
	$num_rows         = mysql_num_rows($sql_nueva_linea);

	if($num_rows == 0){
		$nueval_linea = 0;
	}else{
		$nueval_linea = mysql_result($sql_nueva_linea, 0, 1);
		$update = mysql_query("UPDATE grilla_detalle set cod_linea_visita = $nueval_linea where codigo_grilla = $codigo_grilla and cod_especialidad = '$cod_especialidad' and cod_categoria = '$cod_categoria' and frecuencia = '$frecuencia' and cod_linea_visita = $cod_l_visita");
		echo("UPDATE grilla_detalle set cod_linea_visita = $nueval_linea where codigo_grilla = $codigo_grilla and cod_especialidad = '$cod_especialidad' and cod_categoria = '$cod_categoria' and frecuencia = '$frecuencia' and cod_linea_visita = $cod_l_visita")."<br />";
	}
}
?>