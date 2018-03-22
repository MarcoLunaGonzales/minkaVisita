<?php
error_reporting(0);
require("conexion.inc");
require("estilos_visitador_sincab.inc");
require("function_formatofecha.php");
$sug = substr($sug, 1);
$vector_constancia              = explode(",", $constancia); /* Cuantos estan con checkbox */
$vector_muestras                = explode(",", $muestras); /* Nombre de las muestras */
$vector_material                = explode(",", $material); /* Codigo Material de apoyo */
$vector_cantidad_muestras       = explode(",", $cantidad_muestras); /* Cantidades */
$vector_sug                     = explode(",", $sug);
$vector_cantidad_apoyo          = explode(",", $cantidad_apoyo); /* Cantidades de material de apoyo */
$vector_prod_extra              = explode(",", $prod_extra); /* productos extras */
$vector_mat_extra               = explode(",", $mat_extra); /* Material extra */
$vector_cant_ent_extra          = explode(",", $cant_entregada_extra);
$vector_cant_ent_apoyo_extra    = explode(",", $cant_entregada_apoyo_extra);
$vector_cantidad_extraentregada = explode(",", $cantidad_extraentregada);
$vector_cantidad_extraapoyo     = explode(",", $cantidad_extraapoyo);
$vector_obs                     = explode(",", $obs);
$vector                         = explode("-", $valores);
$prod_agregado                  = explode(",", $prod_agregado);
$contacto                       = $vector[0];
$orden_visita                   = $vector[1];
$parrilla                       = $vector[2];
$num_elementos                  = $vector[3] - 1;
$fecha_registro                 = date("Y-m-d");
$hora_registro                  = date("H:i:s");
$cod_med 						= $cod_med;
$cod_espe 						= $cod_espe;
$cat_med 						= $cat_med;
$cod_dia 						= $cod_dia;

$codigo_parrilla = $_GET['codigo_parrilla'];
$fechaVisitaReal = $_GET['fechaVisitaReal'];
$fechaVisitaReal = cambia_formatofecha($fechaVisitaReal);

//GENERAMOS EL CODIGO CORRELATIVO
$sql  = "SELECT max(cod_reg_visita) from reg_visita_cab";
$resp = mysql_query($sql);
$dat  = mysql_fetch_array($resp);
$num_filas = mysql_num_rows($resp);
if ($num_filas == 0) {
	$codigo = 1;
} else {
	$codigo = $dat[0];
	$codigo++;
}
$sqlInsertCab  = "INSERT into reg_visita_cab(cod_reg_visita, cod_contacto, cod_visitador, cod_med, cod_espe, cod_cat, 
cod_gestion, cod_ciclo, cod_dia_contacto, fecha_registro, hora_registro, fecha_visita, cod_parrilla) values 
($codigo, $contacto, $global_usuario, $cod_med, '$cod_espe', '$cat_med', $codigo_gestion, $ciclo_global, $cod_dia, 
'$fecha_registro', '$hora_registro', '$fechaVisitaReal', '$parrilla')";
//echo $sqlInsertCab;
$respInsertCab = mysql_query($sqlInsertCab);

if($respInsertCab==1){
	for ($j = 0; $j <= $num_elementos; $j++) {
		$prioridad                     = $j + 1;
		$valor_muestra                 = $vector_muestras[$j];
		$valor_cant_muestra            = $vector_cantidad_muestras[$j];
		$valor_apoyo                   = $vector_material[$j];
		$valor_cant_material           = $vector_cantidad_apoyo[$j];
		$valor_cantidad_extraentregada = $vector_cantidad_extraentregada[$j];
		$valor_cantidad_extraapoyo     = $vector_cantidad_extraapoyo[$j];
		$valor_obs                     = $vector_obs[$j];
		$sugerencia_mmuestra           = $vector_sug[$j];

		if ($valor_cantidad_extraapoyo == "") {
			$valor_cantidad_extraapoyo = 0;
		}
		if ($valor_cantidad_extraentregada == "") {
			$valor_cantidad_extraentregada = 0;
		}
		if ($vector_constancia[$j] == 1) {
			$sql = "INSERT into reg_visita_detalle values ($codigo, '$valor_muestra', $valor_cant_muestra,$valor_cantidad_extraentregada,$valor_apoyo,$valor_cant_material,$valor_cantidad_extraapoyo,'$valor_obs',1)";
			$resp = mysql_query($sql);
		}
		
		/*if($sugerencia_mmuestra == 'true'){
			$sql_insert_sug = mysql_query("INSERT into muestras_quitadas_sugeridas (muestra_mm,cod_med,cod_visitador,estado) values ('$valor_muestra',$cod_med,$global_usuario,0)");
		}*/
		
		
		$sql_upd = "UPDATE rutero_maestro_detalle_aprobado 
			set estado = 1 where cod_contacto = $contacto and orden_visita = $orden_visita 
			and cod_visitador='$global_usuario'";
		$resp_upd = mysql_query($sql_upd);
	}
	echo "<script language='Javascript'>
			alert('Los datos se registraron satisfactoriamente');
			opener.location.reload();
			window.close();
			</script>";
}else{
	echo "<script language='Javascript'>
		alert('Hubo un ERROR en la transaccion. Consulte con administracion.');
		opener.location.reload();
		window.close();
		</script>";
}


/*
for ($j = 0; $j < $num_extras; $j++) {

	$prioridad 				= $j + $num_elementos;
	$valor_muestra 			= $vector_prod_extra[$prioridad];
	$valor_cant_muestra 	= $vector_cant_ent_extra[$prioridad];
	$valor_apoyo 			= $vector_mat_extra[$prioridad];
	$valor_cant_material 	= $vector_cant_ent_apoyo_extra[$prioridad];
//		if($vector_constancia[$j]==1)
//		{	
	$sql  = "INSERT into reg_visita_detalle values ($codigo, '$valor_muestra', $valor_cant_muestra,$valor_cantidad_extraentregada,$valor_apoyo,$valor_cant_material,$valor_cantidad_extraapoyo,'$valor_obs',1)";
	$resp = mysql_query($sql);
//		}
}
for($j = 0; $j < $num_muestra_agregar; $j++){
	$cod_muestra_agregada  =  $prod_agregado[$j]; 
	$sql_insert_sug_agre = mysql_query("INSERT into muestras_agregadas_sugeridas (muestra_mm,cod_med,cod_visitador,estado) values ('$cod_muestra_agregada',$cod_med,$global_usuario,0)");
}
$sql_upd_parrilla = "UPDATE parrilla set estado_aprobacion = 1 where codigo_parrilla = $codigo_parrilla";
$resp_upd         = mysql_query($sql_upd_parrilla);
*/


?>