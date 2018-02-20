<?php

set_time_limit(0);
require("../../conexion.inc");
require("../../funcion_nombres.php");

$date = date('Y-m-d');

$primera_cadena = $_POST['primera_cadena'];
$segunda_cadena = $_POST['segunda_cadena'];
$ciudades = $_POST['ciudades'];
$numeroContacto = $_POST['numeroContacto'];

if($ciudades!=""){
	$ciudades=substr($ciudades, 1);

	$ciudadesGlobal = explode("|", $ciudades);
	$cadenaCiudades="";

	$tamVector=sizeOf($ciudadesGlobal);
	$indice=0;

	while($indice < $tamVector){
		$cadenaCiudades=$cadenaCiudades.codigoTerritorio($ciudadesGlobal[$indice]).",";
		$indice++;
	}
	$cadenaCiudades=substr($cadenaCiudades,0,-1);
}else{
	$sqlCiu="SELECT cod_ciudad, descripcion from ciudades c where c.cod_ciudad<>115 order by 2";
	$respCiu=mysql_query($sqlCiu);
	$cadenaCiudades="";
	while($datCiudades=mysql_fetch_array($respCiu)){
		$cadenaCiudades=$cadenaCiudades.$datCiudades[0].",";
	}
	$cadenaCiudades=substr($cadenaCiudades,0,-1);
}

$primera_cadena_sub =  substr($primera_cadena, 0, -1);
$primera_cadena_explode = explode("@", $primera_cadena_sub);

$segunda_cadena_sub =  substr($segunda_cadena, 0, -1);
$segunda_cadena_explode = explode("@", $segunda_cadena_sub);

$sql_id = mysql_query("SELECT max(id) from asignacion_ma_excel");
$id = mysql_result($sql_id, 0, 0);

if($id == ''){
	$id = 1;
}else{
	$id = $id + 1;
}
$sql_id2 = mysql_query("SELECT max(id) from asignacion_ma_excel_detalle");
$id2 = mysql_result($sql_id2, 0, 0);

if($id2 == ''){
	$id2 = 1;
}else{
	$id2 = $id2 + 1;
}


foreach ($primera_cadena_explode as $value) {
	$value_explode = explode(",", $value);	

	$sql_verifica_mm = mysql_query("SELECT * from asignacion_ma_excel where codigo_mm = '$value_explode[0]' and ciclo = $value_explode[1] and gestion = $value_explode[2]");
	if(mysql_num_rows($sql_verifica_mm) == 0 ){
		$sql_insert_cab = mysql_query("INSERT into asignacion_ma_excel (id,codigo_mm,ciclo,gestion,fecha_guardado, ciudades, nro_contacto) values($id,'$value_explode[0]',$value_explode[1],$value_explode[2],'$date','$cadenaCiudades','$numeroContacto')");
	}else{
		$id = mysql_result($sql_verifica_mm, 0, 0);
		$sql_borrarDet="delete from asignacion_ma_excel_detalle where id_asignacion_ma='$id'";
		$resp_borrarDet=mysql_query($sql_borrarDet);
	}
	$aux = 1;
	$aux_linea = '';
	foreach ($segunda_cadena_explode as $valores_segunda_cadena) {
		$valores_segunda_cadena_explode = explode(",", $valores_segunda_cadena);
		if($value_explode[0] == $valores_segunda_cadena_explode[1]){
			$valor_a = $valores_segunda_cadena_explode[3];
			$valor_b = $valores_segunda_cadena_explode[4];
			$valor_c = $valores_segunda_cadena_explode[5];
			$va_muestra = $valores_segunda_cadena_explode[6];
			$espe_linea = $valores_segunda_cadena_explode[7];
			$cad_ciudad = $valores_segunda_cadena_explode[8];
			$codLineaMkt = $valores_segunda_cadena_explode[9];
			
			/*$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio where nom_generico = '$valores_segunda_cadena_explode[0]'");
			$codigo_l_visita = mysql_result($sql_codigo_l_visita, 0, 0);*/
			$codigo_l_visita=0;
			
			if($aux_linea == $codigo_l_visita){
				
			}else{
				$aux = 1;
			}
			$cad_ciudad_final = '';

			$cad_ciudad = explode("*", $cad_ciudad);
			foreach ($cad_ciudad as $key ) {
				$cad_ciudad_final .= $key . ",";
			}
			$cad_ciudad_final = substr($cad_ciudad_final, 0, -1);
			
			/*$sqlVeriTxt="SELECT DISTINCT  a.id, ad.id, ad.id_asignacion_ma from asignacion_ma_excel a, 
				asignacion_ma_excel_detalle ad , muestras_medicas m 
				where a.id = ad.id_asignacion_ma and m.codigo = a.codigo_mm and 
				a.ciclo = $value_explode[1] and a.gestion = $value_explode[2] and 
				a.codigo_mm = '$value_explode[0]' and ad.espe_linea = '$espe_linea' and 
				ad.codigo_ma = '$valores_segunda_cadena_explode[2]'";
			
			$sql_verifica_si_hay_registro = mysql_query($sqlVeriTxt);
			if (mysql_num_rows($sql_verifica_si_hay_registro) != 0) {
				$id_actualizar_2                   = mysql_result($sql_verifica_si_hay_registro, 0, 0);
				$id_actualizar_2_detalle           = mysql_result($sql_verifica_si_hay_registro, 0, 1);
				$id_actualizar_2_principal_detalle = mysql_result($sql_verifica_si_hay_registro, 0, 2);

				//$sql_borra_fila = mysql_query("DELETE from asignacion_ma_excel_detalle where id = $id_actualizar_2_detalle and id_asignacion_ma = $id_actualizar_2_principal_detalle and codigo_ma = '$valores_segunda_cadena_explode[2]' ");

				$sql_insert_det = mysql_query("INSERT into asignacion_ma_excel_detalle (id, id_asignacion_ma, codigo_l_visita, codigo_ma, dist_a, dist_b, dist_c, va_material, espe_linea,ciudades_pl, linea_mkt) values ($id_actualizar_2_detalle, $id, '$codigo_l_visita', '$valores_segunda_cadena_explode[2]', $valor_a, $valor_b, $valor_c, $va_muestra, '$espe_linea','$cad_ciudad_final','$codLineaMkt')");

			}else{
				$sql_insert_det = mysql_query("INSERT into asignacion_ma_excel_detalle (id, id_asignacion_ma, codigo_l_visita, codigo_ma, dist_a, dist_b, dist_c, va_material, espe_linea,ciudades_pl, linea_mkt) values ($id2, $id, '$codigo_l_visita', '$valores_segunda_cadena_explode[2]', $valor_a, $valor_b, $valor_c, $va_muestra, '$espe_linea','$cad_ciudad_final','$codLineaMkt')");

				$sql_insert_pos = mysql_query("INSERT into asignacion_ma_excel_posiciones (id,posicion) values ($id2,$aux)");
				$aux_linea = $codigo_l_visita;
				if($aux_linea == $codigo_l_visita){
					$aux++;	
				}else{
					$aux = 1;
				}
				$id2++;
			}*/
			$sqlInsertDetTxt="INSERT into asignacion_ma_excel_detalle 
				(id, id_asignacion_ma, codigo_l_visita, codigo_ma, dist_a, dist_b, dist_c, 
				va_material, espe_linea,contactoA, linea_mkt, contactoB, contactoC) values 
				($id2, $id, '$codigo_l_visita', '$valores_segunda_cadena_explode[2]', $valor_a, $valor_b, $valor_c, 
				$va_muestra, '$espe_linea','1','$codLineaMkt','1','1')";
			
			//echo $sqlInsertDetTxt."<br>";
			
			$sql_insert_det = mysql_query($sqlInsertDetTxt);
			$sql_insert_pos = mysql_query("INSERT into asignacion_ma_excel_posiciones (id,posicion) values ($id2,$aux)");
			$aux_linea = $codigo_l_visita;
			if($aux_linea == $codigo_l_visita){
				$aux++;	
			}else{
				$aux = 1;
			}
			$id2++;
		}
	}

	$id++;
}



$arr = array("mensaje" => "Guardado Satisfactoriamente");
echo json_encode($arr);
?>
