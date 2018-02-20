<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
$ciclo_final   = $_POST['ciclo'];
$gestion_final = $_POST['gestion'];
$territorio = $_POST['territorio'];
$cadena = '';
$cat = array('A','B','C');
$linea = 1021;
$fechaInsercion = date('Y-m-d');
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");


$cadena  = '<table border="1">';
$sql_ciudades = mysql_query("SELECT cod_ciudad,descripcion from ciudades where cod_ciudad <> 115 and cod_ciudad in ($territorio)");
while ($row_cod_ciudad = mysql_fetch_array($sql_ciudades)) {
	
	$sql_asignacion_productos = mysql_query("SELECT DISTINCT CONCAT(ad.especialidad,' ',ad.linea), ad.especialidad, ad.linea from asignacion_productos_excel a, asignacion_productos_excel_detalle ad WHERE a.id = ad.id and ad.ciudad = $row_cod_ciudad[0] and a.ciclo = $ciclo_final and a.gestion = $gestion_final ORDER BY 2,3");
	
	while ($row_asignacion_productos = mysql_fetch_array($sql_asignacion_productos)) {
		$especialidad_linea = $row_asignacion_productos[0];
		$especialidad_final = $row_asignacion_productos[1];
		$linea_final = $row_asignacion_productos[2];
		$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio where nom_generico = '$especialidad_linea'");
		$codigo_l_visita = mysql_result($sql_codigo_l_visita, 0, 0);
		foreach ($cat as $key) {

			$sql_frecuencia = mysql_query("SELECT MAX(gd.frecuencia) from grilla g, grilla_detalle gd where g.codigo_grilla = gd.codigo_grilla and g.estado = 1 and g.agencia = $row_cod_ciudad[0] AND gd.cod_especialidad = '$especialidad_final' and gd.cod_linea_visita = $codigo_l_visita and gd.cod_categoria = '$key'");
			$cantidad_frecuencia = mysql_result($sql_frecuencia, 0,0);

			for($count = 1; $count <= $cantidad_frecuencia; $count++){

				$sql_asignacion_productos_grupos = mysql_query("SELECT ad.posicion,ad.producto from asignacion_productos_excel a, asignacion_productos_excel_detalle ad WHERE a.id = ad.id and ad.ciudad = $row_cod_ciudad[0] and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.especialidad = '$especialidad_final' and ad.linea = '$linea_final'");
				while ($row_asignacion_productos_grupos = mysql_fetch_array($sql_asignacion_productos_grupos)) {
					$posicion = $row_asignacion_productos_grupos[0];
					$producto = $row_asignacion_productos_grupos[1];
					$sql_nom_producto = mysql_query("SELECT concat(descripcion,' ',presentacion) from muestras_medicas where codigo = '$producto'");
					$nom_producto = mysql_result($sql_nom_producto, 0, 0);
					$sql_asignacion_mm = mysql_query("SELECT ad.cantidad FROM asignacion_mm_excel a, asignacion_mm_excel_detalle ad where a.id = ad.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final and CONCAT_WS(' ',ad.especialidad,ad.linea) = '$especialidad_linea' and producto = '$producto' and ad.categoria = '$key'");
					$cantidad = mysql_result($sql_asignacion_mm, 0, 0);

					if($posicion == 1){
						$cadena .= '<tr>'; $cadena .= '<th>Linea</th><th>Region</th>'; $cadena .= '<th>Especialidad</th>'; $cadena .= '<th>Linea</th>'; $cadena .= '<th>Cat</th>'; $cadena .= '<th>Visita</th>'; $cadena .= '<th>Pos</th>'; $cadena .= '<th>Codigo</th>'; $cadena .= '<th>Producto</th>'; $cadena .= '<th>Cantidad</th>'; $cadena .= '<th>Cod MA</th>'; $cadena .= '<th>MA</th>'; $cadena .= '<th>Cantidad</th><th>N°Medicos</th><th>Cantidad MM (P)</th><th>Cantidad MA (P)</th>'; $cadena .= '</tr>';
					}
					$cadena .= '<tr>';
					$cadena .= '<td>BPH General</td>';
					$cadena .= '<td>'.$row_cod_ciudad[1].'</td>';
					$cadena .= '<td>'.$especialidad_final.'</td>';
					$cadena .= '<td>'.$linea_final.'</td>';
					$cadena .= '<td>'.$key.'</td>';
					$cadena .= '<td>'.$count.'</td>';
					$cadena .= '<td>'.$posicion.'</td>';
					$cadena .= '<td>'.$producto.'</td>';
					$cadena .= '<td>'.$nom_producto.'</td>';
					$cadena .= '<td>'.$cantidad.'</td>';
					$sql_asignacion_ma = mysql_query("SELECT DISTINCT ad.id_asignacion_ma, ad.codigo_ma, ad.dist_a, ad.dist_b, ad.dist_c  FROM asignacion_ma_excel a, asignacion_ma_excel_detalle ad where a.id = ad.id_asignacion_ma and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.codigo_l_visita = $codigo_l_visita and a.codigo_mm = '$producto' ");
					$cantidad_ma = 0;
					$num_rows = mysql_num_rows($sql_asignacion_ma);
					if($num_rows == ''){
						$codigo_ma = '0';
					}else{
						if($key == 'A'){$cantidad_ma = mysql_result($sql_asignacion_ma, 0, 2);}
						if($key == 'B'){$cantidad_ma = mysql_result($sql_asignacion_ma, 0, 3);}
						if($key == 'C'){$cantidad_ma = mysql_result($sql_asignacion_ma, 0, 4);}
						if($cantidad_ma > 0){
							$codigo_ma = mysql_result($sql_asignacion_ma, 0, 1);
						}else{
							$codigo_ma = '0';
						}
					}
					$sql_nom_ma = mysql_query("SELECT descripcion_material from material_apoyo where codigo_material = $codigo_ma");
					$nom_ma  = mysql_result($sql_nom_ma, 0,0); 
					$cadena .= "<td>".$codigo_ma."</td>";
					$cadena .= "<td>".$nom_ma."</td>";
					$cadena .= "<td>".$cantidad_ma."</td>";
					$sql_cantidad_medicos = mysql_query("SELECT COUNT(DISTINCT rmd.cod_med) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med AND rmd.cod_especialidad = '$especialidad_final' and rmd.categoria_med = '$key' and m.cod_ciudad = $row_cod_ciudad[0] ORDER BY rmd.cod_especialidad, rmd.categoria_med, m.ap_pat_med");
					$cantidad_medicos = mysql_result($sql_cantidad_medicos, 0, 0);
					$cadena .= "<td>".$cantidad_medicos."</td>";
					$cantidad_mm_p = $cantidad_medicos * $cantidad;
					$cantidad_ma_p = $cantidad_medicos * $cantidad_ma;
					$cadena .= "<td>".$cantidad_mm_p."</td>";
					$cadena .= "<td>".$cantidad_ma_p."</td>";
					$cadena .= '</tr>';	
				}
			}
		}
	}
}
$cadena .= '</table>';

// echo $cadena;
$arr = array("cadena" => $cadena, "mensaje" => 'Guardado Satisfactoriamente');
echo json_encode($arr);

?>