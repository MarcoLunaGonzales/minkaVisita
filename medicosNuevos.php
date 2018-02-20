<?php
require("conexion.inc");
$sql="select paterno, materno, nombre, cod_agencia, cod_zona, especialidad, categoria from mednuevos";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$paterno=$dat[0];
	$materno=$dat[1];
	$nombre=$dat[2];
	$cod_agencia=$dat[3];
	$cod_zona=$dat[4];
	$espe=$dat[5];
	$cat=$dat[6];


	$sql_aux = "select cod_med from medicos order by cod_med desc";
	$resp_aux = mysql_query($sql_aux);
	$num_filas = mysql_num_rows($resp_aux);
	if ($num_filas == 0) {
	    $cod_med = 1000;
	} else {
	    $dat = mysql_fetch_array($resp_aux);
	    $codigo = $dat[0];
	    $cod_med = $codigo + 1;
	} 

	$insert1="insert into medicos (cod_med, ap_pat_med, ap_mat_med, nom_med, cod_ciudad) 
					values ($cod_med, '$paterno','$materno','$nombre','$cod_agencia')";
  $resp1=mysql_query($insert1);
  
 	$insert2 = "insert into direcciones_medicos (cod_med, cod_zona, direccion, numero_direccion, cod_tipo_consultorio) 
			values('$cod_med','$cod_zona','S/D', 1, 1)";
	$resp2 = mysql_query($insert2);

 	$insert3 = "insert into especialidades_medicos (cod_med, cod_especialidad, cod_tipo_especialidad) 
				values ($cod_med, '$espe', 1)";
	$resp3 = mysql_query($insert3);
	
	$insert4="insert into categorias_lineas (codigo_linea, cod_med, cod_especialidad, categoria_med)
				values (1021, $cod_med, '$espe', '$cat')";
	$resp4= mysql_query($insert4);
	
	echo "$insert1 $insert2 $insert3 $insert4";

}
?>