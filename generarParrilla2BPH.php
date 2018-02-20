<?php
require("conexion.inc");
require("funciones.php");

$fechaInsercion = date('Y-m-d');
$codCicloInsert=11;

$sql_ciudades = mysql_query("SELECT cod_ciudad from ciudades where cod_ciudad <> 115");

//$sql_ciudades = mysql_query("SELECT cod_ciudad from ciudades where cod_ciudad in (121, 122, 123)");

echo "<table border=1>";
echo "<tr><td>ciudad</td><td>linea</td><td>espe</td><td>Cat</td><td>grilla</td><td>parrilla</td><td>numMedicos</td></tr>";
while ($row_cod_ciudad = mysql_fetch_array($sql_ciudades)) {
	$codCiudad=$row_cod_ciudad[0];
	
	$sqlLinea="select l.codigo_linea from lineas l where l.estado=1 and l.linea_promocion=1 and codigo_linea=1021";
	$respLinea=mysql_query($sqlLinea);
	while($datLinea=mysql_fetch_array($respLinea)){
		$codigoLinea=$datLinea[0];
		
		$sqlGrilla="select gd.cod_especialidad, gd.cod_linea_visita, gd.cod_categoria, gd.frecuencia from grilla g, grilla_detalle gd 
			where g.codigo_grilla=gd.codigo_grilla and g.agencia='$codCiudad' and g.codigo_linea='$codigoLinea' and  
			g.estado=1 and gd.frecuencia>0  and cod_categoria in ('A','B','C')";
		
		//echo "sqlGrilla ".$sqlGrilla."<br>";
		
		$respGrilla=mysql_query($sqlGrilla);
		while($datGrilla=mysql_fetch_array($respGrilla)){
			$codEspe=$datGrilla[0];
			$codLineaVisita=$datGrilla[1];
			$catMed=$datGrilla[2];
			$frecuencia=$datGrilla[3];
			
			if($codLineaVisita!=0){
				$sqlLinVis="select l.orden from lineas_visita l where l.codigo_l_visita=$codLineaVisita";
				echo $sqlLinVis."<br>";
				$respLinVis=mysql_query($sqlLinVis);
				$codLineaVisitaOrden=mysql_result($respLinVis,0,0);
			}else{
				$codLineaVisitaOrden=0;
			}
			$sqlAsig="select ifnull(max(a.contacto),0) from asignacion_productos_excel_detalle a where a.especialidad='$codEspe' 
				and a.linea_mkt='$codigoLinea' and a.ciudad='$codCiudad' and a.linea='$codLineaVisitaOrden'";
			
			//echo "sqlAsig ".$sqlAsig."<br>";
			
			$respAsig=mysql_query($sqlAsig);
			$nroContactosAsig=0;
			while($datAsig=mysql_fetch_array($respAsig)){
				$nroContactosAsig=$datAsig[0];
			}
			
			$numeroMed=0;
			$sqlNumMed="SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, 
				rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero 
				AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = 1011  
				and rmc.codigo_ciclo = $codCicloInsert and rmd.categoria_med = '$catMed' and rmd.cod_especialidad = '$codEspe' and 
				rmc.codigo_linea in ($codigoLinea) 
				and rmc.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad in ($codCiudad))";
			
			//echo "sqlNumMed ".$sqlNumMed."<br>";
			
			$respNumMed=mysql_query($sqlNumMed);
			$numeroMed=mysql_result($respNumMed,0,0);
			
			/*if($nroContactosAsig!=$frecuencia){
				echo "<tr><td>$codCiudad</td><td>$codigoLinea</td><td>$codEspe</td><td>$catMed</td><td>$frecuencia</td><td>$nroContactosAsig</td><td>$numeroMed</td></tr>";			
			}*/
			
			
			//EMPEZAMOS A REALIZAR EL CARGADO DE LA PARRILLA
			if($numeroMed>0){
				for($ii=1; $ii<=$frecuencia; $ii++){
				
					$codigoParr=codigoParrilla();
					$sqlInsert="INSERT INTO parrilla (codigo_parrilla,cod_ciclo,cod_especialidad,categoria_med,
						codigo_linea, fecha_creacion,fecha_modificacion,numero_visita,agencia,
						codigo_l_visita,muestras_extra,codigo_gestion) 
						VALUES ($codigoParr, $codCicloInsert, '$codEspe','$catMed',$codigoLinea, '$fechaInsercion', '$fechaInsercion', 
						$ii, $codCiudad, $codLineaVisita, 0, 1011)";
					
					echo $sqlInsert."<br>";
					$respInsert=mysql_query($sqlInsert);
					
					$sqlDet="select p.posicion, p.producto from asignacion_productos_excel_detalle p where p.ciudad=$codCiudad and 
						p.especialidad='$codEspe' and p.linea_mkt=$codigoLinea and p.linea='$codLineaVisitaOrden' and contacto=$ii order by posicion";
					echo "<br>".$sqlDet."<br>";
					$respDet=mysql_query($sqlDet);
					$numeroCont=mysql_result($respDet, 0, 0);
					if($numeroCont>0){
						$respDet=mysql_query($sqlDet);
						while($datDet=mysql_fetch_array($respDet)){
							$posicionIns=$datDet[0];
							$productoIns=$datDet[1];
							
							$sqlCantidad="select cantidad from asignacion_mm_excel_detalle m where m.linea_mkt=$codigoLinea 
								and m.especialidad='$codEspe' and m.categoria='$catMed' and m.producto='$productoIns'";
							$respCantidad=mysql_query($sqlCantidad);
							$cantidadIns=mysql_result($respCantidad, 0,0);
							
							$sqlInsertDet="insert into parrilla_detalle (codigo_parrilla, codigo_muestra, cantidad_muestra, codigo_material, 
								cantidad_material, prioridad, observaciones, extra) 
								values ($codigoParr, '$productoIns', $cantidadIns, 0, 0,$posicionIns, '',0)";
							$respInsertDet=mysql_query($sqlInsertDet);	
						}
					}else{
						echo "no hay parrilla para $ii $codCiudad $codigoLinea $codEspe $codCat LinVis $codLineaVisitaOrden";
					}
				}
			}
		}
	}
}
echo "</table>";
?>