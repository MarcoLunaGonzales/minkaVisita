<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../conexion.inc");
$ciclo_final   = $_GET['ciclo'];
$gestion_final = $_GET['gestion'];
$cadena = '';
$cat = array('A','B','C');
$linea = 1021;
$fechaInsercion = date('Y-m-d');
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

// $sql = "select max(p.codigo) from parrillas_automatica p";
// $resp = mysql_query($sql);
// $dat = mysql_fetch_array($resp);
// $num_filas = mysql_num_rows($resp);
// if ($num_filas == 0) {
// 	$codigo_id_parrilla = 1;
// } else {
// 	$codigo_id_parrilla = $dat[0];
// 	$codigo_id_parrilla++;
// }


$cadena  = '<table border="1">';
// $sql_ciudades = mysql_query("SELECT cod_ciudad from ciudades where cod_ciudad <> 115");
// while ($row_cod_ciudad = mysql_fetch_array($sql_ciudades)) {

// 	$sql_asignacion_productos = mysql_query("SELECT DISTINCT CONCAT(ad.especialidad,' ',ad.linea), ad.especialidad, ad.linea from asignacion_productos_excel a, asignacion_productos_excel_detalle ad WHERE a.id = ad.id and ad.ciudad = $row_cod_ciudad[0] and a.ciclo = $ciclo_final and a.gestion = $gestion_final ORDER BY 2,3");

// 	while ($row_asignacion_productos = mysql_fetch_array($sql_asignacion_productos)) {
// 		$especialidad_linea = $row_asignacion_productos[0];
// 		$especialidad_final = $row_asignacion_productos[1];
// 		$linea_final = $row_asignacion_productos[2];
// 		$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio where nom_generico = '$especialidad_linea'");
// 		$codigo_l_visita = mysql_result($sql_codigo_l_visita, 0, 0);
// 		foreach ($cat as $key) {

// 			if($especialidad_final == 'ONC'){
// 				$especialidad_final = 'ONCO';
// 			}

// 			$sql_frecuencia = mysql_query("SELECT MAX(gd.frecuencia) from grilla g, grilla_detalle gd where g.codigo_grilla = gd.codigo_grilla and g.estado = 1 and g.agencia = $row_cod_ciudad[0] AND gd.cod_especialidad = '$especialidad_final' and gd.cod_linea_visita = $codigo_l_visita and gd.cod_categoria = '$key'");
// 			$cantidad_frecuencia = mysql_result($sql_frecuencia, 0,0);

// 			for($count = 1; $count <= $cantidad_frecuencia; $count++){

// 				if($especialidad_final == 'ONCO'){
// 					$especialidad_final = 'ONC';
// 				}

// 				$sql_asignacion_productos_grupos = mysql_query("SELECT ad.posicion,ad.producto from asignacion_productos_excel a, asignacion_productos_excel_detalle ad WHERE a.id = ad.id and ad.ciudad = $row_cod_ciudad[0] and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.especialidad = '$especialidad_final' and ad.linea = '$linea_final'");
// 				while ($row_asignacion_productos_grupos = mysql_fetch_array($sql_asignacion_productos_grupos)) {
// 					$posicion = $row_asignacion_productos_grupos[0];
// 					$producto = $row_asignacion_productos_grupos[1];
// 					$sql_nom_producto = mysql_query("SELECT concat(descripcion,' ',presentacion) from muestras_medicas where codigo = '$producto'");
// 					$nom_producto = mysql_result($sql_nom_producto, 0, 0);
// 					$sql_asignacion_mm = mysql_query("SELECT ad.cantidad FROM asignacion_mm_excel a, asignacion_mm_excel_detalle ad where a.id = ad.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final and CONCAT_WS(' ',ad.especialidad,ad.linea) = '$especialidad_linea' and producto = '$producto' and ad.categoria = '$key'");
// 					$cantidad = mysql_result($sql_asignacion_mm, 0, 0);

// 					if($posicion == 1){
// 						$cadena .= '<tr>'; $cadena .= '<th>Region</th>'; $cadena .= '<th>Especialidad</th>'; $cadena .= '<th>Linea</th>'; $cadena .= '<th>Cat</th>'; $cadena .= '<th>Visita</th>'; $cadena .= '<th>Pos</th>'; $cadena .= '<th>Codigo</th>'; $cadena .= '<th>Producto</th>'; $cadena .= '<th>Cantidad</th>'; $cadena .= '<th>Cod MA</th>'; $cadena .= '<th>MA</th>'; $cadena .= '<th>Cantidad</th>'; $cadena .= '</tr>';
// 					}
// 					$cadena .= '<tr>';
// 					$cadena .= '<td>'.$row_cod_ciudad[0].'</td>';
// 					$cadena .= '<td>'.$especialidad_final.'</td>';
// 					$cadena .= '<td>'.$linea_final.'</td>';
// 					$cadena .= '<td>'.$key.'</td>';
// 					$cadena .= '<td>'.$count.'</td>';
// 					$cadena .= '<td>'.$posicion.'</td>';
// 					$cadena .= '<td>'.$producto.'</td>';
// 					$cadena .= '<td>'.$nom_producto.'</td>';
// 					$cadena .= '<td>'.$cantidad.'</td>';
// 					$sql_asignacion_ma = mysql_query("SELECT DISTINCT ad.id_asignacion_ma, ad.codigo_ma, ad.dist_a, ad.dist_b, ad.dist_c , ap.posicion, ad.va_material FROM asignacion_ma_excel a, asignacion_ma_excel_detalle ad, asignacion_ma_excel_posiciones ap where a.id = ad.id_asignacion_ma and ap.id = ad.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.codigo_l_visita = $codigo_l_visita and a.codigo_mm = '$producto' and ap.posicion = $count ");
// 					$cantidad_ma = 0;
// 					$num_rows = mysql_num_rows($sql_asignacion_ma);
// 					if($num_rows == ''){
// 						$codigo_ma = '0';
// 					}else{
// 						$va_muestra = mysql_result($sql_asignacion_ma, 0, 5);
// 						if($key == 'A'){$cantidad_ma = mysql_result($sql_asignacion_ma, 0, 2);}
// 						if($key == 'B'){$cantidad_ma = mysql_result($sql_asignacion_ma, 0, 3);}
// 						if($key == 'C'){$cantidad_ma = mysql_result($sql_asignacion_ma, 0, 4);}
// 						if($cantidad_ma > 0){
// 							$codigo_ma = mysql_result($sql_asignacion_ma, 0, 1);
// 						}else{
// 							$codigo_ma = '0';
// 						}
// 						if($key == 'A'){
// 							if($va_muestra == 1){
// 								$codigo_ma = mysql_result($sql_asignacion_ma, 0, 1);
// 							}
// 						}
// 					}
// 					$sql_nom_ma = mysql_query("SELECT descripcion_material from material_apoyo where codigo_material = $codigo_ma");
// 					$nom_ma  = mysql_result($sql_nom_ma, 0,0); 
// 					$cadena .= "<td>".$codigo_ma."</td>";
// 					$cadena .= "<td>".$nom_ma."</td>";
// 					$cadena .= "<td>".$cantidad_ma."</td>";
// 					$cadena .= '</tr>';	

// 					/* Aqui guardamos */

// 					if($especialidad_final == 'ONC'){
// 						$especialidad_final = 'ONCO';
// 					}

// 					$sqlInsert = "INSERT INTO parrillas_automatica (linea, agencia, espe, cat, nro_visita, lineavisita, orden, cod_prod, prod, cant_prod, cod_mat, material, cant_material,ciclo,gestion) VALUES ('$linea', '$row_cod_ciudad[0]', '$especialidad_final','$key', '$count', '$codigo_l_visita', '$posicion', '$producto', '$nom_producto', '$cantidad', '$codigo_ma', '$nom_ma', '$cantidad_ma','$ciclo_final','$gestion_final')";
// 						$respInsert = mysql_query($sqlInsert);
// 					// echo $sqlInsert.";";
// 					if($especialidad_final == 'ONCO'){
// 						$especialidad_final = 'ONC';
// 					}	


// 					/* Fin del Guardado */
// 				}
// 			}
// 		}
// 	}
// }
/*Para las otras lineas que no sean la 1021*/
$sql_linea = mysql_query("SELECT codigo_linea from lineas where linea_promocion = 1 and estado = 1 and codigo_linea = 1031");
while ($row_linea = mysql_fetch_array($sql_linea)) {
	if($row_linea[0] == 1009){
		$ciudad_linea = 114;
	}
	if($row_linea[0] == 1023){
		$ciudad_linea = 118;	
	}
	if($row_linea[0] == 1022){
		$ciudad_linea = 118;		
	}
	if($row_linea[0] == 1031){
		$ciudad_linea = 120;	
	}
	
	$sql_asignacion_productos_l = mysql_query("SELECT DISTINCT CONCAT(ad.especialidad,' ',ad.linea), ad.especialidad, ad.linea from asignacion_productos_excel a, asignacion_productos_excel_detalle ad WHERE a.id = ad.id and ad.ciudad = $row_linea[0] and a.ciclo = $ciclo_final and a.gestion = $gestion_final ORDER BY 2,3");
	while ($row_asignacion_productos_l = mysql_fetch_array($sql_asignacion_productos_l)) {
		$especialidad_linea = $row_asignacion_productos_l[0];
		$especialidad_final = $row_asignacion_productos_l[1];
		$linea_final = $row_asignacion_productos_l[2];
		if($row_linea[0] == 1031){
			$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio_sacpun where nom_generico = '$especialidad_linea'");	
		}else{
			$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio where nom_generico = '$especialidad_linea'");	
		}
		$codigo_l_visita = mysql_result($sql_codigo_l_visita, 0, 0);
		foreach ($cat as $key) {

			if($especialidad_final == 'ONC'){
				$especialidad_final = 'ONCO';
			}

			$sql_frecuencia = mysql_query("SELECT MAX(gd.frecuencia) from grilla g, grilla_detalle gd where g.codigo_grilla = gd.codigo_grilla and g.estado = 1 and g.agencia = $ciudad_linea AND gd.cod_especialidad = '$especialidad_final' and gd.cod_linea_visita = $codigo_l_visita and gd.cod_categoria = '$key' and g.codigo_linea = '$row_linea[0]'");
			// echo("SELECT MAX(gd.frecuencia) from grilla g, grilla_detalle gd where g.codigo_grilla = gd.codigo_grilla and g.estado = 1 and g.agencia = $ciudad_linea AND gd.cod_especialidad = '$especialidad_final' and gd.cod_linea_visita = $codigo_l_visita and gd.cod_categoria = '$key' and g.codigo_linea = '$row_linea[0]'");
			$cantidad_frecuencia = mysql_result($sql_frecuencia, 0,0);
			// $cantidad_frecuencia = 1;

			for($count = 1; $count <= $cantidad_frecuencia; $count++){

				if($especialidad_final == 'ONCO'){
					$especialidad_final = 'ONC';
				}

				$sql_asignacion_productos_grupos = mysql_query("SELECT ad.posicion,ad.producto from asignacion_productos_excel a, asignacion_productos_excel_detalle ad WHERE a.id = ad.id and ad.ciudad = $row_linea[0] and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.especialidad = '$especialidad_final' and ad.linea = '$linea_final'");
				while ($row_asignacion_productos_grupos = mysql_fetch_array($sql_asignacion_productos_grupos)) {
					$posicion = $row_asignacion_productos_grupos[0];
					$producto = $row_asignacion_productos_grupos[1];
					$sql_nom_producto = mysql_query("SELECT concat(descripcion,' ',presentacion) from muestras_medicas where codigo = '$producto'");
					$nom_producto = mysql_result($sql_nom_producto, 0, 0);
					$sql_asignacion_mm = mysql_query("SELECT ad.cantidad FROM asignacion_mm_excel a, asignacion_mm_excel_detalle ad where a.id = ad.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final and CONCAT_WS(' ',ad.especialidad,ad.linea) = '$especialidad_linea' and producto = '$producto' and ad.categoria = '$key'");
					$cantidad = mysql_result($sql_asignacion_mm, 0, 0);

					if($posicion == 1){
						$cadena .= '<tr>'; $cadena .= '<th>Region</th>'; $cadena .= '<th>Especialidad</th>'; $cadena .= '<th>Linea</th>'; $cadena .= '<th>Cat</th>'; $cadena .= '<th>Visita</th>'; $cadena .= '<th>Pos</th>'; $cadena .= '<th>Codigo</th>'; $cadena .= '<th>Producto</th>'; $cadena .= '<th>Cantidad</th>'; $cadena .= '<th>Cod MA</th>'; $cadena .= '<th>MA</th>'; $cadena .= '<th>Cantidad</th>'; $cadena .= '</tr>';
					}
					$cadena .= '<tr>';
					$cadena .= '<td>'.$ciudad_linea.'</td>';
					$cadena .= '<td>'.$especialidad_final.'</td>';
					$cadena .= '<td>'.$linea_final.'</td>';
					$cadena .= '<td>'.$key.'</td>';
					$cadena .= '<td>'.$count.'</td>';
					$cadena .= '<td>'.$posicion.'</td>';
					$cadena .= '<td>'.$producto.'</td>';
					$cadena .= '<td>'.$nom_producto.'</td>';
					$cadena .= '<td>'.$cantidad.'</td>';
					if($row_linea[0] == 1031){
						$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio where nom_generico = '$especialidad_linea'");	
					}
					$codigo_l_visita = mysql_result($sql_codigo_l_visita, 0, 0);
					$sql_asignacion_ma = mysql_query("SELECT DISTINCT ad.id_asignacion_ma, ad.codigo_ma, ad.dist_a, ad.dist_b, ad.dist_c , ap.posicion, ad.va_material FROM asignacion_ma_excel a, asignacion_ma_excel_detalle ad, asignacion_ma_excel_posiciones ap where a.id = ad.id_asignacion_ma and ap.id = ad.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.codigo_l_visita = $codigo_l_visita and a.codigo_mm = '$producto' and ap.posicion = $count ");
					if($row_linea[0] == 1031){
						$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio_sacpun where nom_generico = '$especialidad_linea'");	
					}else{
						$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio where nom_generico = '$especialidad_linea'");	
					}
					$codigo_l_visita = mysql_result($sql_codigo_l_visita, 0, 0);
					$cantidad_ma = 0;
					$num_rows = mysql_num_rows($sql_asignacion_ma);
					if($num_rows == ''){
						$codigo_ma = '0';
					}else{
						$va_muestra = mysql_result($sql_asignacion_ma, 0, 5);
						if($key == 'A'){$cantidad_ma = mysql_result($sql_asignacion_ma, 0, 2);}
						if($key == 'B'){$cantidad_ma = mysql_result($sql_asignacion_ma, 0, 3);}
						if($key == 'C'){$cantidad_ma = mysql_result($sql_asignacion_ma, 0, 4);}
						if($cantidad_ma > 0){
							$codigo_ma = mysql_result($sql_asignacion_ma, 0, 1);
						}else{
							$codigo_ma = '0';
						}
						if($key == 'A'){
							if($va_muestra == 1){
								$codigo_ma = mysql_result($sql_asignacion_ma, 0, 1);
							}
						}
					}
					$sql_nom_ma = mysql_query("SELECT descripcion_material from material_apoyo where codigo_material = $codigo_ma");
					$nom_ma  = mysql_result($sql_nom_ma, 0,0); 
					$cadena .= "<td>".$codigo_ma."</td>";
					$cadena .= "<td>".$nom_ma."</td>";
					$cadena .= "<td>".$cantidad_ma."</td>";
					$cadena .= '</tr>';	

					/* Aqui guardamos */

					$sql = "select max(p.codigo) from parrillas_automatica p";
					$resp = mysql_query($sql);
					$dat = mysql_fetch_array($resp);
					$num_filas = mysql_num_rows($resp);
					if ($num_filas == 0) {
						$codigo_id_parrilla = 1;
					} else {
						$codigo_id_parrilla = $dat[0];
						$codigo_id_parrilla++;
					}

					if($especialidad_final == 'ONC'){
						$especialidad_final = 'ONCO';
					}
					if($row_linea[0] == 1031){
						$codigo_l_visita = $codigo_l_visita;
					}else{
						$codigo_l_visita = 0;
					}

					$sqlInsert = "INSERT INTO parrillas_automatica (linea, agencia, espe, cat, nro_visita, lineavisita, orden, cod_prod, prod, cant_prod, cod_mat, material, cant_material,ciclo,gestion) VALUES ('$row_linea[0]', '$ciudad_linea', '$especialidad_final','$key', '$count', '$codigo_l_visita', '$posicion', '$producto', '$nom_producto', '$cantidad', '$codigo_ma', '$nom_ma', '$cantidad_ma','$ciclo_final','$gestion_final')";
    				// $respInsert = mysql_query($sqlInsert);

    				if($especialidad_final == 'ONCO'){
						$especialidad_final = 'ONC';
					}

					/* Fin del Guardado */
				}
			}
		}
	}
}

/*FIN*/


$cadena .= '</table>';


// $sqlParrilla = "SELECT CODIGO,LINEA,AGENCIA,ESPE,CAT,NRO_VISITA,LINEAVISITA, ORDEN,COD_PROD,PROD,CANT_PROD,COD_MAT,MATERIAL,CANT_MATERIAL from parrillas_automatica p where p.ciclo = $ciclo_final and p.gestion = $gestion_final group by p.AGENCIA,p.LINEA, p.ESPE, p.LINEAVISITA, p.CAT, p.nro_visita order by p.AGENCIA,p.LINEA, p.ESPE, p.LINEAVISITA,p.CAT, p.nro_visita";
// $respParrilla = mysql_query($sqlParrilla);
// while ($datParrilla = mysql_fetch_array($respParrilla)) {
//     $codigo = $datParrilla[0];
//     $linea = $datParrilla[1];
//     $agencia = $datParrilla[2];
//     $espe = $datParrilla[3];
//     $cat = $datParrilla[4];
//     $nroVisita = $datParrilla[5];
//     $lineaVisita = $datParrilla[6];

//     $sql = "SELECT max(p.codigo_parrilla) from parrilla p";
//     $resp = mysql_query($sql);
//     $dat = mysql_fetch_array($resp);
//     $num_filas = mysql_num_rows($resp);
//     if ($num_filas == 0) {
//         $codigo = 1000;
//     } else {
//         $codigo = $dat[0];
//         $codigo++;
//     }
//     $sqlInsert2 = "INSERT INTO parrilla (codigo_parrilla,cod_ciclo,cod_especialidad,categoria_med,codigo_linea, fecha_creacion,fecha_modificacion,numero_visita,agencia,codigo_l_visita,muestras_extra,codigo_gestion) VALUES ($codigo, $ciclo_final, '$espe','$cat',$linea, '$fechaInsercion', '$fechaInsercion', $nroVisita, $agencia, $lineaVisita, 0,$gestion_final)";
//     // echo $sqlInsert2;
//     $respInsert = mysql_query($sqlInsert2);

//     $sqlDetalle = "SELECT CODIGO,ORDEN,COD_PROD,CANT_PROD,COD_MAT,CANT_MATERIAL from parrillas_automatica p where p.LINEA=$linea and p.AGENCIA=$agencia and p.ESPE='$espe'and p.CAT='$cat' and p.NRO_VISITA=$nroVisita and p.LINEAVISITA=$lineaVisita and p.ciclo = $ciclo_final and p.gestion = $gestion_final";
//     $respDetalle = mysql_query($sqlDetalle);
//     while ($datDetalle = mysql_fetch_array($respDetalle)) {
//         $orden = $datDetalle[1];
//         $codProd = $datDetalle[2];
//         $cantProd = $datDetalle[3];
//         $codMaterial = $datDetalle[4];
//         $cantMaterial = $datDetalle[5];

//         $sqlInsertDetalle = "INSERT INTO parrilla_detalle (codigo_parrilla, codigo_muestra, cantidad_muestra, codigo_material, cantidad_material, prioridad, observaciones, extra) VALUES ($codigo, '$codProd', $cantProd, '$codMaterial', $cantMaterial, $orden,'',0)";
//         // echo $sqlInsertDetalle;
//         $respInsertDetalle = mysql_query($sqlInsertDetalle);
//     }

// }

// echo $cadena;
echo $cadena;

?>