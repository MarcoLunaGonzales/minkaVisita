<!DOCTYPE HTML>
<html lang="es-US">
<head>
	<title>Frecuencia</title>
</head>
<body>
	<?php  
	set_time_limit(0);
	require("../../conexion.inc");
	$ciclo   = 6;
	$gestion = 1010;

	$sql_med  = mysql_query("SELECT * from categorias_lineas where codigo_linea = 1021");
	while ($row_med = mysql_fetch_array($sql_med)) {
		$codigo_linea         = $row_med[0];
		$cod_med              = $row_med[1];
		$cod_especialidad     = $row_med[2];
		$categoria_med        = $row_med[3];
		$frecuencia_linea     = $row_med[4];
		$frecuencia_permitida = $row_med[5];

		if ($frecuencia_linea == '') {
			$frecuencia_linea = "' '";
		}
		if ($frecuencia_permitida == '') {
			$frecuencia_permitida = "' '";
		}

		$sql_med_ciud = mysql_query("SELECT cod_ciudad from medicos where cod_med = $cod_med");
		$ciudad_med   = mysql_result($sql_med_ciud, 0, 0);

		/*VISITADORES*/

		$sql_visitadores = mysql_query("SELECT DISTINCT rmd.cod_visitador from rutero_maestro_cab_aprobado rmc, rutero_maestro_detalle_aprobado rmd, rutero_maestro_aprobado rma WHERE rmc.cod_rutero = rma.cod_rutero and rma.cod_contacto = rmd.cod_contacto and rmc.codigo_ciclo = $ciclo and rmc.codigo_gestion = $gestion and rmd.cod_med = $cod_med");

		while ($row_visi = mysql_fetch_array($sql_visitadores)) {
			
			$sql_linea = mysql_query("SELECT lvv.codigo_l_visita from lineas_visita lv, lineas_visita_visitadores lvv, lineas_visita_especialidad lve where lv.codigo_l_visita = lvv.codigo_l_visita and lvv.codigo_l_visita = lve.codigo_l_visita and lvv.codigo_funcionario = $row_visi[0] and lvv.codigo_ciclo = $ciclo and lvv.codigo_gestion = $gestion and lve.cod_especialidad = '$cod_especialidad'");
			if(mysql_num_rows($sql_linea) > 0){

				$lin = mysql_result($sql_linea, 0,0);
			}else{

				echo ("SELECT lvv.codigo_l_visita from lineas_visita lv, lineas_visita_visitadores lvv, lineas_visita_especialidad lve where lv.codigo_l_visita = lvv.codigo_l_visita and lvv.codigo_l_visita = lve.codigo_l_visita and lvv.codigo_funcionario = $row_visi[0] and lvv.codigo_ciclo = $ciclo and lvv.codigo_gestion = $gestion and lve.cod_especialidad = '$cod_especialidad'");
				$lin = 0;

			}


			$sql_grilla = mysql_query("SELECT DISTINCT max(gd.frecuencia) from grilla g, grilla_detalle gd where g.codigo_grilla = gd.codigo_grilla and g.codigo_linea = $codigo_linea and gd.cod_especialidad = '$cod_especialidad' and gd.cod_categoria = '$categoria_med' and g.agencia = $ciudad_med and g.estado = 1 and gd.cod_linea_visita = $lin");
			$frec = mysql_result($sql_grilla, 0, 0);

			if($frec != 0 || $frec != ''){
				$frecuencia_actualizar = mysql_result($sql_grilla, 0, 0);
			// echo "tiene: ".$frecuencia_actualizar."<br />";
			}else{
		// if($num_grilla == '' or $num_grilla == 0){
				$frecuencia_actualizar = 1;
			// echo "No tiene: ".$frecuencia_actualizar."<br />";
			}

			$sql_update = mysql_query("UPDATE categorias_lineas set frecuencia_linea = $frecuencia_actualizar where codigo_linea = $codigo_linea and cod_med = $cod_med and cod_especialidad = '$cod_especialidad' and categoria_med = '$categoria_med' and frecuencia_linea = $frecuencia_linea and frecuencia_permitida = $frecuencia_permitida");

			if($sql_update == true){

				echo "ok"."<br /><br />";

			}else{

				echo("UPDATE categorias_lineas set frecuencia_linea = $frecuencia_actualizar where codigo_linea = $codigo_linea and cod_med = $cod_med and cod_especialidad = '$cod_especialidad' and categoria_med = '$categoria_med' and frecuencia_linea = $frecuencia_linea and frecuencia_permitida = $frecuencia_permitida")."<br /><br />";

			}


		}


		/*FIN VISITADORES*/

		// $sql_grilla = mysql_query("SELECT DISTINCT max(gd.frecuencia) from grilla g, grilla_detalle gd where g.codigo_grilla = gd.codigo_grilla and g.codigo_linea = $codigo_linea and gd.cod_especialidad = '$cod_especialidad' and gd.cod_categoria = '$categoria_med' and g.agencia = $ciudad_med and g.estado = 1 and gd.cod_linea_visita <> 0 and gd.frecuencia <> 0 and gd.frecuencia <=4 and gd.frecuencia >= 1");
		// $frec = mysql_result($sql_grilla, 0, 0);

		// if($frec != 0 || $frec != ''){
		// 	$frecuencia_actualizar = mysql_result($sql_grilla, 0, 0);
		// 	// echo "tiene: ".$frecuencia_actualizar."<br />";
		// }else{
		// // if($num_grilla == '' or $num_grilla == 0){
		// 	$frecuencia_actualizar = 1;
		// 	// echo "No tiene: ".$frecuencia_actualizar."<br />";
		// }

		// $sql_update = mysql_query("UPDATE categorias_lineas set frecuencia_linea = $frecuencia_actualizar where codigo_linea = $codigo_linea and cod_med = $cod_med and cod_especialidad = '$cod_especialidad' and categoria_med = '$categoria_med' and frecuencia_linea = $frecuencia_linea and frecuencia_permitida = $frecuencia_permitida");
		// if($sql_update == true){

		// 	echo "ok"."<br /><br />";

		// }else{

		// 	echo("UPDATE categorias_lineas set frecuencia_linea = $frecuencia_actualizar where codigo_linea = $codigo_linea and cod_med = $cod_med and cod_especialidad = '$cod_especialidad' and categoria_med = '$categoria_med' and frecuencia_linea = $frecuencia_linea and frecuencia_permitida = $frecuencia_permitida")."<br /><br />";

		// }

	}
	
	?>
</body>
</html>