<?php
require("conexion.inc");


$codigoGestion=1014;
$codigoCiclo=9;

$codigoMA="21112,21156,21318,21467,21640,21762,21764,21805,21868,21916,21976";
//$codigoMA="20472";

echo $codigoMA;

	$sqlX="update parrilla_personalizada set cod_ma='0', cantidad_ma=0
		where cod_gestion='$codigoGestion' and cod_ciclo='$codigoCiclo' and cod_ma in ($codigoMA)";
	
	echo $sqlX;
	$respX=mysql_query($sqlX);

$sql="select a.codigo_mm, ad.dist_a, ad.dist_b, ad.dist_c, ad.contactoA, ad.espe_linea, ad.linea_mkt, ad.codigo_ma, ad.contactoB, ad.contactoC,
	a.ciudades, a.nro_contacto
	from asignacion_ma_excel a, asignacion_ma_excel_detalle ad 
	where a.id=ad.id_asignacion_ma and ad.codigo_ma in ($codigoMA) 
	order by a.codigo_mm, ad.espe_linea, ad.linea_mkt";
	
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codMM=$dat[0];
	$cantA=$dat[1];
	$cantB=$dat[2];
	$cantC=$dat[3];
	$numContactoA=$dat[4];
	$codEspe=$dat[5];
	$codLinea=$dat[6];
	$codMaterial=$dat[7];	
	$numContactoB=$dat[8];
	$numContactoC=$dat[9];
	$ciudades=$dat[10];
	$nroContactoMA=$dat[11];
	
	if($cantA>0){
	
		$sqlMed="select DISTINCT(p.cod_med) from parrilla_personalizada p, categorias_lineas cl
			where p.cod_linea=cl.codigo_linea and p.cod_med=cl.cod_med and 
			p.cod_mm='$codMM' and cl.cod_especialidad='$codEspe' and cl.categoria_med='A' and 
			cl.codigo_linea='$codLinea' and p.cod_gestion='$codigoGestion' and p.cod_ciclo='$codigoCiclo' and 
			p.cod_med in (select m.cod_med from medicos m where m.cod_ciudad in ($ciudades))";
		echo $sqlMed."<br>";
		$respMed=mysql_query($sqlMed);
		while($datMed=mysql_fetch_array($respMed)){
			$codMedico=$datMed[0];
			
			//hacemos la prueba por si existe la parrilla personalizada en el contacto
			$sqlUpd="update parrilla_personalizada set cod_ma='$codMaterial', cantidad_ma=$cantA
				where cod_mm='$codMM' and cod_med in (
				select distinct(cod_med) from categorias_lineas where codigo_linea='$codLinea' and 
				cod_especialidad='$codEspe' and categoria_med='A')  
				and cod_linea='$codLinea' and numero_visita=$nroContactoMA and cod_med='$codMedico'";	
			$respUpd=mysql_query($sqlUpd);
			
			if(mysql_affected_rows()>0){
				echo mysql_affected_rows()." CONTACTO ORIGINAL <BR>"; 
			}else{
				$ii=1;
				$bandera=0;
				while($ii<=4 && $bandera==0){
					$sqlUpd="update parrilla_personalizada set cod_ma='$codMaterial', cantidad_ma=$cantA
					where cod_mm='$codMM' and cod_med in (
					select distinct(cod_med) from categorias_lineas where codigo_linea='$codLinea' and 
					cod_especialidad='$codEspe' and categoria_med='A')  
					and cod_linea='$codLinea' and numero_visita=$ii and cod_med='$codMedico'";
					
					$respUpd=mysql_query($sqlUpd);
					echo mysql_affected_rows()." CONTACTO SUPLENTE <BR>";
					if(mysql_affected_rows()>0){
						$bandera=1;
						echo $sqlUpd."<br>";
					}
					$ii++;
				}
			}			
		}		
	}
	if($cantB>0){
		$sqlMed="select DISTINCT(p.cod_med) from parrilla_personalizada p, categorias_lineas cl
			where p.cod_linea=cl.codigo_linea and p.cod_med=cl.cod_med and 
			p.cod_mm='$codMM' and cl.cod_especialidad='$codEspe' and cl.categoria_med='B' and 
			cl.codigo_linea='$codLinea' and p.cod_gestion='$codigoGestion' and p.cod_ciclo='$codigoCiclo' and 
			p.cod_med in (select m.cod_med from medicos m where m.cod_ciudad in ($ciudades))";
		echo $sqlMed."<br>";	
		$respMed=mysql_query($sqlMed);
		while($datMed=mysql_fetch_array($respMed)){
			$codMedico=$datMed[0];
			
			//hacemos la prueba por si existe la parrilla personalizada en el contacto
			$sqlUpd="update parrilla_personalizada set cod_ma='$codMaterial', cantidad_ma=$cantB
				where cod_mm='$codMM' and cod_med in (
				select distinct(cod_med) from categorias_lineas where codigo_linea='$codLinea' and 
				cod_especialidad='$codEspe' and categoria_med='B')  
				and cod_linea='$codLinea' and numero_visita=$nroContactoMA and cod_med='$codMedico'";	
			$respUpd=mysql_query($sqlUpd);
			
			if(mysql_affected_rows()>0){
				echo mysql_affected_rows()." CONTACTO ORIGINAL <BR>"; 
			}else{
				$ii=1;
				$bandera=0;
				while($ii<=4 && $bandera==0){
					$sqlUpd="update parrilla_personalizada set cod_ma='$codMaterial', cantidad_ma=$cantB
					where cod_mm='$codMM' and cod_med in (
					select distinct(cod_med) from categorias_lineas where codigo_linea='$codLinea' and 
					cod_especialidad='$codEspe' and categoria_med='B')  
					and cod_linea='$codLinea' and numero_visita=$ii and cod_med='$codMedico'";
					
					$respUpd=mysql_query($sqlUpd);
					echo mysql_affected_rows()." CONTACTO SUPLENTE <BR>";
					if(mysql_affected_rows()>0){
						$bandera=1;
						echo $sqlUpd."<br>";
					}
					$ii++;
				}
			}	
				
		}
	}
	if($cantC>0){
		$sqlMed="select DISTINCT(p.cod_med) from parrilla_personalizada p, categorias_lineas cl
			where p.cod_linea=cl.codigo_linea and p.cod_med=cl.cod_med and 
			p.cod_mm='$codMM' and cl.cod_especialidad='$codEspe' and cl.categoria_med='C' and 
			cl.codigo_linea='$codLinea' and p.cod_gestion='$codigoGestion' and p.cod_ciclo='$codigoCiclo' and 
			p.cod_med in (select m.cod_med from medicos m where m.cod_ciudad in ($ciudades))";
		
		echo $sqlMed."<br>";
		
		$respMed=mysql_query($sqlMed);
		while($datMed=mysql_fetch_array($respMed)){
			$codMedico=$datMed[0];
			
			//hacemos la prueba por si existe la parrilla personalizada en el contacto
			$sqlUpd="update parrilla_personalizada set cod_ma='$codMaterial', cantidad_ma=$cantC
				where cod_mm='$codMM' and cod_med in (
				select distinct(cod_med) from categorias_lineas where codigo_linea='$codLinea' and 
				cod_especialidad='$codEspe' and categoria_med='C')  
				and cod_linea='$codLinea' and numero_visita=$nroContactoMA and cod_med='$codMedico'";	
			$respUpd=mysql_query($sqlUpd);
			
			if(mysql_affected_rows()>0){
				echo mysql_affected_rows()." CONTACTO ORIGINAL <BR>"; 
			}else{
				$ii=1;
				$bandera=0;
				while($ii<=4 && $bandera==0){
					$sqlUpd="update parrilla_personalizada set cod_ma='$codMaterial', cantidad_ma=$cantC
					where cod_mm='$codMM' and cod_med in (
					select distinct(cod_med) from categorias_lineas where codigo_linea='$codLinea' and 
					cod_especialidad='$codEspe' and categoria_med='C')  
					and cod_linea='$codLinea' and numero_visita=$ii and cod_med='$codMedico'";
					
					$respUpd=mysql_query($sqlUpd);
					echo mysql_affected_rows()." CONTACTO SUPLENTE <BR>";
					if(mysql_affected_rows()>0){
						$bandera=1;
						echo $sqlUpd."<br>";
					}
					$ii++;
				}
			}	
		}
	}	
}

echo "<br><br>terminado $codigoMA";
?>