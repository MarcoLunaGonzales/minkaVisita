<?php
require("../conexion.inc");

$sql="select rd.cod_med, rd.cod_especialidad, rc.codigo_linea from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, 
rutero_maestro_detalle_aprobado rd
where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_gestion=1010 and rc.codigo_ciclo in (10,11) 
group by rd.cod_med, rd.cod_especialidad, rc.codigo_linea";

	$indice=0;
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codMed=$dat[0];
	$codEspe=$dat[1];
	$codLinea=$dat[2];
	
	$sqlVeri="select codigo_linea, cod_med, cod_especialidad, categoria_med from categorias_lineas
				where cod_med=$codMed and codigo_linea<>'$codLinea'";
	$respVeri=mysql_query($sqlVeri);
	$numFilas=mysql_num_rows($respVeri);

	while($datVeri=mysql_fetch_array($respVeri)){
		$codLineaVeri=$datVeri[0];
		$codEspeVeri=$datVeri[1];
		$codCatVeri=$datVeri[2];
		
		echo "<br>delete from categorias_lineas where cod_med='$codMed' and codigo_linea='$codLineaVeri';";
		$indice++;
	}
}

//segunda verificacion
$sql1="select cod_med, cod_especialidad, codigo_linea from categorias_lineas";
$resp1=mysql_query($sql1);
while($dat1=mysql_fetch_array($resp1)){
	$codMedS=$dat1[0];
	$sqlVeri1="select * from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, 
		rutero_maestro_detalle_aprobado rd
		where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_gestion=1010 and rc.codigo_ciclo in (10,11) 
		and rd.cod_med in ($codMedS)";
	$respVeri1=mysql_query($sqlVeri1);
	$numFilasVeri1=mysql_num_rows($respVeri1);
	if($numFilasVeri1==0){
		echo "<br>delete from categorias_lineas where cod_med='$codMedS';";
	}
	
}


?>
