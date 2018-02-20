<?php

set_time_limit(0);
require("../../conexion.inc");
require("../../funcion_nombres.php");

$ciclo = $_POST['ciclo'];
$gestion = $_POST['gestion'];
$cadena = $_POST['cadena'];
$ciudades = $_POST['ciudades'];
$material_apoyo = $_POST['ma'];
$cadena_explode = explode("@",$cadena);
$especialidad = $cadena_explode[0];
$linea = $cadena_explode[1];
$producto = $cadena_explode[2];
$linea_mkt=$_POST['linea_mkt'];

$material_apoyo_explode = explode("@", $material_apoyo);
$nombre_material_apoyo = $material_apoyo_explode[0];
$codigo_material_apoyo = $material_apoyo_explode[1];

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
}


$txt_a="SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, 
	rutero_maestro_detalle_aprobado rmd, medicos m, parrilla_personalizada p WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto 
	AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion and rmc.codigo_ciclo = $ciclo and rmd.categoria_med = 'A' 
	and rmd.cod_especialidad = '$especialidad' and rmc.codigo_linea in ($linea_mkt) and p.cod_gestion=rmc.codigo_gestion and p.cod_ciclo=rmc.codigo_ciclo 
	and p.cod_linea=rmc.codigo_linea and p.cod_med=m.cod_med and p.cod_mm='$producto' 
	and rmd.cod_visitador=rmc.cod_visitador and rm.cod_visitador=rmd.cod_visitador and rmd.cod_visitador=rmc.cod_visitador";

if($ciudades!=""){
	$txt_a=$txt_a." and rmc.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad in ($cadenaCiudades))";
}
	
//echo $txt_a;

$sql_cantidad_a = mysql_query($txt_a);
$cantidad_final_a = mysql_result($sql_cantidad_a, 0,0);

$txt_b="SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, 
	rutero_maestro_detalle_aprobado rmd, medicos m, parrilla_personalizada p WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto 
	AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion and rmc.codigo_ciclo = $ciclo and rmd.categoria_med = 'B' 
	and rmd.cod_especialidad = '$especialidad' and rmc.codigo_linea in ($linea_mkt) and p.cod_gestion=rmc.codigo_gestion and p.cod_ciclo=rmc.codigo_ciclo 
	and p.cod_linea=rmc.codigo_linea and p.cod_med=m.cod_med and p.cod_mm='$producto' 
	and rmd.cod_visitador=rmc.cod_visitador and rm.cod_visitador=rmd.cod_visitador and rmd.cod_visitador=rmc.cod_visitador";
if($ciudades!=""){
	$txt_b=$txt_b." and rmc.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad in ($cadenaCiudades))";
}		
$sql_cantidad_b = mysql_query($txt_b);
$cantidad_final_b = mysql_result($sql_cantidad_b, 0,0);

$txt_c="SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, 
	rutero_maestro_detalle_aprobado rmd, medicos m, parrilla_personalizada p WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto 
	AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion and rmc.codigo_ciclo = $ciclo and rmd.categoria_med = 'C' 
	and rmd.cod_especialidad = '$especialidad' and rmc.codigo_linea in ($linea_mkt) and p.cod_gestion=rmc.codigo_gestion and p.cod_ciclo=rmc.codigo_ciclo 
	and p.cod_linea=rmc.codigo_linea and p.cod_med=m.cod_med and p.cod_mm='$producto' 
	and rmd.cod_visitador=rmc.cod_visitador and rm.cod_visitador=rmd.cod_visitador and rmd.cod_visitador=rmc.cod_visitador";
if($ciudades!=""){
	$txt_c=$txt_c." and rmc.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad in ($cadenaCiudades))";
}
$sql_cantidad_c = mysql_query($txt_c);
$cantidad_final_c = mysql_result($sql_cantidad_c, 0,0);


/*Existencias segun Kardex*/

$sql_fechas_ingresos="SELECT sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='1000' and 
i.grupo_ingreso=2 and i.ingreso_anulado=0 and id.cod_material='$codigo_material_apoyo'";
// echo $sql_fechas_ingresos;
$resp_fechas_ingresos=mysql_query($sql_fechas_ingresos);
$dat_kardex_ingresos=mysql_fetch_array($resp_fechas_ingresos);
$cantidad_ingreso_kardex=$dat_kardex_ingresos[0];

$sql_fechas_salidas="SELECT sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='1000' and 
s.grupo_salida=2 and s.salida_anulada=0 and sd.cod_material='$codigo_material_apoyo'";
// echo $sql_fechas_salidas;
$resp_fechas_salidas=mysql_query($sql_fechas_salidas);
$dat_kardex_salidas=mysql_fetch_array($resp_fechas_salidas);
$cantidad_salida_kardex=$dat_kardex_salidas[0];

$existencia_final=$cantidad_ingreso_kardex-$cantidad_salida_kardex;

/*Fin Existencias*/

//$cantidad_final_a=4; $cantidad_final_b=9; $cantidad_final_c=11;
$arr = array("cantidadd_a" => $cantidad_final_a, "cantidadd_b" => $cantidad_final_b,  "cantidadd_c" => $cantidad_final_c, "existencia" => $existencia_final);
echo json_encode($arr);

?>