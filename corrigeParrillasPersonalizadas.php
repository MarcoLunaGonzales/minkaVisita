<?php
require("conexion.inc");
$codItem="'M124'";
$codItemReemplazo="'23082'";
$codLinea="1032";
$codEspeOmitir="";
$codCiclo=7;
$codGestion=1014;

$sqlVeri="select cod_linea, cod_med, numero_visita from parrilla_personalizada p where p.cod_gestion=$codGestion and p.cod_ciclo=$codCiclo 
and p.cod_mm in ($codItem)";
echo $sqlVeri;
$respVeri=mysql_query($sqlVeri);
while($datVeri=mysql_fetch_array($respVeri)){
	$codLinea=$datVeri[0];
	$codMed=$datVeri[1];
	$nroVisita=$datVeri[2];

	//verificamos las especialidades
	$sqlVeriEspe="select count(*) from categorias_lineas c where c.codigo_linea in ($codLinea) and c.cod_med in ($codMed) and 
				c.cod_especialidad in ($codEspeOmitir)";
	$respVeriEspe=mysql_query($sqlVeriEspe);
	$banderaEspe=mysql_result($respVeriEspe,0,0);
	
	if($banderaEspe>0){
		echo "$codLinea $codMed $nroVisita ****NO BORRAMOS***<br>";		
	}else{
		$sqlVeriProd="select count(*) from parrilla_personalizada p where p.cod_gestion=$codGestion and p.cod_ciclo=$codCiclo and 
			p.cod_mm in ($codItemReemplazo) and p.cod_linea=$codLinea and p.cod_med in ($codMed) and p.numero_visita='$nroVisita'";
		$respVeriProd=mysql_query($sqlVeriProd);
		$banderaProducto=mysql_result($respVeriProd,0,0);
		
		if($banderaProducto>0){
			echo "$codLinea $codMed $nroVisita BORRAMOS PRODUCTO<br>";
			$sqlUpd="delete from parrilla_personalizada where cod_mm in ($codItem) and cod_med='$codMed' and cod_linea='$codLinea' and 
			numero_visita='$nroVisita' and cod_gestion=$codGestion and cod_ciclo=$codCiclo";
			$respUpd=mysql_query($sqlUpd);
			
		}else{
			echo "$codLinea $codMed $nroVisita UPD PRODUCTO<br>";
			$sqlUpd="update parrilla_personalizada set cod_mm=$codItemReemplazo where cod_mm in ($codItem) and cod_med='$codMed' and 
				cod_linea='$codLinea' and numero_visita='$nroVisita' and cod_gestion=$codGestion and cod_ciclo=$codCiclo";
			$respUpd=mysql_query($sqlUpd);
		}
	}
	
}

?>