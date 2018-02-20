<?php

function saca_nombre_muestra($codigo)

{	$sql="select descripcion from muestras_medicas where codigo='$codigo'";

	$resp=mysql_query($sql);

	$dat=mysql_fetch_array($resp);

	$nombre_muestra=$dat[0];

	return($nombre_muestra);

}

function nombreProducto($codigo)

{	$sql="select concat(descripcion, ' ',presentacion) from muestras_medicas where codigo='$codigo'";

	$resp=mysql_query($sql);

	$dat=mysql_fetch_array($resp);

	$nombre_muestra=$dat[0];

	return($nombre_muestra);

}



function nombreGestion($codigo)

{	$sql="select g.`nombre_gestion` from `gestiones` g where g.`codigo_gestion`='$codigo'";

	$resp=mysql_query($sql);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}



function nombreLinea($codigo)

{	$sql="select nombre_linea from lineas where codigo_linea='$codigo'";

	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);

	return($nombre);

}



function nombreVisitador($codigo)

{	$sql="select concat(paterno,' ',nombres) from funcionarios where codigo_funcionario='$codigo'";

	$resp=mysql_query($sql);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}

function nombreSupervisor($codigoTerritorio){
	$sql_supervisor = "SELECT f.paterno, f.materno, f.nombres from funcionarios f, funcionarios_lineas fl where f.cod_cargo='1001' and 
	f.codigo_funcionario=fl.codigo_funcionario and f.cod_ciudad='$codigoTerritorio' and f.estado=1";
	$resp_supervisor = mysql_query($sql_supervisor);
	$dat_supervisor = mysql_fetch_array($resp_supervisor);
	$nombre_supervisor = "$dat_supervisor[0] $dat_supervisor[2]";
	return($nombre_supervisor);
	
}

function nombreCompletoVisitador($codigo)

{	$sql="select concat(paterno,' ',materno,' ',nombres) from funcionarios where codigo_funcionario='$codigo'";

	$resp=mysql_query($sql);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}



function nombreTerritorio($codigo)

{	$sql="select descripcion from ciudades where cod_ciudad='$codigo'";

	$resp=mysql_query($sql);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}


function codigoTerritorio($codigo)

{	$sql="select cod_ciudad from ciudades where descripcion='$codigo'";

	$resp=mysql_query($sql);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}



function nombreMedico($codigo)

{	$sql="select concat(ap_pat_med,' ', nom_med) from medicos where cod_med='$codigo'";

	$resp=mysql_query($sql);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}



function nombreDia($codigo)

{	$sql="select dia_contacto from orden_dias where id='$codigo'";

	$resp=mysql_query($sql);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}





function nombreRutero($codigo)

{	$sql="select nombre_rutero from rutero_maestro_cab where cod_rutero='$codigo'";

	$resp=mysql_query($sql);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}



function nombreZona($codigo)

{	
$sql="select zona from zonas where cod_zona='$codigo'";
	$resp=mysql_query($sql);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}


function nombreGE($codigo)

{	
	$sql="select nombre_grupo_especial from grupo_especial where codigo_grupo_especial='$codigo'";
	$resp=mysql_query($sql);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}

function nombreCategoria($codigo, $link)

{	$sql="select nombre_categoria from categorias_producto where cod_categoria='$codigo'";

	$resp=mysql_query($sql, $link);

	$nombre=mysql_result($resp,0,0);

	return($nombre);

}

function nombreLineaVisita($codigo)
{	$sql="select nombre_l_visita from lineas_visita where codigo_l_visita='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	if($codigo==0){
		$nombre="-";
	}
	return($nombre);
}?>