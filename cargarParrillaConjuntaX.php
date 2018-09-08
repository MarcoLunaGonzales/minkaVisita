<?php

require("conexion.inc");

$codigoCiclo=1;
$codigoGestion=1000;


$sqlLineas="select l.codigo_linea, l.nombre_linea from lineas l where l.estado=1 and l.linea_promocion=1
	and l.codigo_linea=1007";
$respLineas=mysql_query($sqlLineas);

while($datLineas=mysql_fetch_array($respLineas)){
	
	$codLinea=$datLineas[0];
	$nombreLinea=$datLineas[1];
	
	$sqlMedicos="SELECT distinct(rd.cod_med), rd.cod_especialidad, rd.categoria_med, f.cod_ciudad 
	from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd, funcionarios f
	where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.cod_visitador=rm.cod_visitador and rm.cod_visitador=rd.cod_visitador 
	and rc.cod_visitador=rd.cod_visitador and rc.cod_visitador=f.codigo_funcionario and rc.codigo_gestion=$codigoGestion and 
	rc.codigo_ciclo=$codigoCiclo and rc.codigo_linea=$codLinea and 
	f.cod_ciudad=116";
	
	$respMedicos=mysql_query($sqlMedicos);
	while($datMedicos=mysql_fetch_array($respMedicos)){
		$codMed=$datMedicos[0];
		$codEspe=$datMedicos[1];
		$catMed=$datMedicos[2];
		$codCiudad=$datMedicos[3];

		echo "$codMed $codEspe $catMed<br>";

		
		//valida que no este en las personalizadas
		$sqlValida="select * from medicos_parrillapersonalizada where cod_med=$codMed and cod_linea=$codLinea and 
		cod_gestion=$codigoGestion and cod_ciclo=$codigoCiclo";
		//echo "<br>".$sqlValida."<br>";
		$respValida=mysql_query($sqlValida);
		$numFilas=mysql_num_rows($respValida);
		
		if($numFilas==0){
		
			$sqlParrilla="select p.codigoprod, a1, a2, a3, a4, b1, b2, b3, b4, c1, c2, c3, c4 from parrillaconjunta p where p.linea='$codLinea' and 
				p.especialidad='$codEspe' and p.codciudad='$codCiudad' order by indice";
			$respParrilla=mysql_query($sqlParrilla);
			$ordenVisita1=1;
			$ordenVisita2=1;
			$ordenVisita3=1;
			$ordenVisita4=1;
			while($datParrilla=mysql_fetch_array($respParrilla)){
				$codProd=$datParrilla[0];
			
				if($catMed=="A"){
					$a1=$datParrilla[1];
					if($a1>0){ 
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '1', $ordenVisita1, '$codProd', $a1, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita1++;
					}
					
					$a2=$datParrilla[2];
					if($a2>0){ 
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '2', $ordenVisita2, '$codProd', $a2, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita2++;
					}
					
					$a3=$datParrilla[3];
					if($a3>0){ 
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '3', $ordenVisita3, '$codProd', $a3, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita3++;
					}

					$a4=$datParrilla[4];
					if($a4>0){ 
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '4', $ordenVisita4, '$codProd', $a4, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita4++;
					}
				}
				
				if($catMed=="B"){

					$b1=$datParrilla[5];
					if($b1>0){
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '1', $ordenVisita1, '$codProd', $b1, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita1++;
					}
					
					$b2=$datParrilla[6];
					if($b2>0){
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '2', $ordenVisita2, '$codProd', $b2, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita2++;
					}
					
					$b3=$datParrilla[7];
					if($b3>0){
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '3', $ordenVisita3, '$codProd', $b3, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita3++;
					}
					
					$b4=$datParrilla[8];
					if($b4>0){
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '4', $ordenVisita4, '$codProd', $b4, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita4++;
					}
					
				}
				
				if($catMed=="C"){
					$c1=$datParrilla[9];
					if($c1>0){
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '1', $ordenVisita1, '$codProd', $c1, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita1++;
					}
					
					$c2=$datParrilla[10];
					if($c2>0){
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '2', $ordenVisita2, '$codProd', $c2, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita2++;
					}
					
					$c3=$datParrilla[11];
					if($c3>0){
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '3', $ordenVisita3, '$codProd', $c3, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita3++;
					}
				
					$c4=$datParrilla[12];
					if($c4>0){
						$sqlInsert="insert into parrilla_personalizada values($codigoGestion, $codigoCiclo, $codLinea, $codMed, '4', $ordenVisita4, '$codProd', $c4, 0, 0)";	
						mysql_query($sqlInsert);
						$ordenVisita4++;
					
					}
				}		
			}
		}else{
			echo "TIENE PARRILLA PERSONALIZADA<br>";
		}
		

	}
	
	echo "CARGADO LINEA $nombreLinea";
	
}



?>