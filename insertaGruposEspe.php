<?php
require("conexion.inc");
if($global_usuario==1052)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
	/*$global_linea_distribucion=1021;
	$global_gestion_distribucion=1007;
	$global_ciclo_distribucion=6;*/
	
	$global_linea=$global_linea_distribucion;
	$gestionDistribucion=$global_gestion_distribucion;
	$cicloDistribucion=$global_ciclo_distribucion;

			//GRUPOS ESPECIALES
			$sqlGrupos="select g.`codigo_grupo_especial`, g.agencia from `grupo_especial` g where
       g.`codigo_linea` = $global_linea and g.`agencia` ";
       
      echo $sqlGrupos;
       
			$respGrupos=mysql_query($sqlGrupos);
			while($datGrupos=mysql_fetch_array($respGrupos)){
				$codGrupo=$datGrupos[0];
				$codTerritorio=$datGrupos[1];
				
				$sqlMedicosEspe="select distinct(rd.cod_med), rd.cod_visitador 
					from rutero_maestro_cab_aprobado rc, `rutero_maestro_aprobado` r, `rutero_maestro_detalle_aprobado` rd, 
					`funcionarios` f where rc.`cod_rutero` = r.`cod_rutero` and r.`cod_contacto` = rd.`cod_contacto` and 
					rc.`cod_visitador` = f.`codigo_funcionario` and rc.`codigo_ciclo` = $cicloDistribucion and rc.`codigo_gestion` = $gestionDistribucion and 
					rc.`codigo_linea` = $global_linea and f.`cod_ciudad` = $codTerritorio and 
					rd.`cod_med` in (select gd.`cod_med` from `grupo_especial` g, 
					`grupo_especial_detalle` gd where g.`codigo_grupo_especial` = gd.`codigo_grupo_especial` 
					and g.`codigo_grupo_especial` = $codGrupo) group by rd.`cod_med`";
					
				echo $sqlMedicosEspe;	
					
				$respMedicosEspe=mysql_query($sqlMedicosEspe);
				while($datMedicosEspe=mysql_fetch_array($respMedicosEspe)){
					$codVisitador=$datMedicosEspe[1];
					//PARRILLA MUESTRAS
					$sqlParrilla="select pd.`codigo_muestra`,  SUM(pd.`cantidad_muestra`) from `parrilla_especial` p, `parrilla_detalle_especial` pd
						where p.`cod_ciclo` = $cicloDistribucion and p.`codigo_gestion` = $gestionDistribucion and p.`codigo_grupo_especial` = $codGrupo and 
						p.`codigo_linea` = $global_linea_distribucion and p.`codigo_parrilla_especial`=pd.`codigo_parrilla_especial` 
						group by pd.`codigo_muestra`";
						
					echo $sqlParrilla;	
						
					$respParrilla=mysql_query($sqlParrilla);
					while($datParrilla=mysql_fetch_array($respParrilla)){
						$codProducto=$datParrilla[0];
						$cantProducto=$datParrilla[1];
					
						$sqlVeriDist="select * from distribucion_productos_visitadores where codigo_gestion='$gestionDistribucion' and
						cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and codigo_linea='$global_linea'
						and grupo_salida=1";
						$respVeriDist=mysql_query($sqlVeriDist);
						$filasVeriDist=mysql_num_rows($respVeriDist);
						if($filasVeriDist==0){
							$sqlActProductos="insert into distribucion_productos_visitadores values('$gestionDistribucion',
							'$cicloDistribucion','$codTerritorio','$global_linea','$codVisitador','$codProducto','$cantProducto',
							0,'1',0)";
							$respActProductos=mysql_query($sqlActProductos);
						}else{
							$sqlActProductos="update distribucion_productos_visitadores set cantidad_planificada=cantidad_planificada+$cantProducto 
							where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$global_linea'
							and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and grupo_salida=1";
							$respActProductos=mysql_query($sqlActProductos);
						}	
						//PARRILLA MATERIAL APOYO
						$sqlParrilla="select pd.`codigo_material`,  SUM(pd.`cantidad_material`) from `parrilla_especial` p, `parrilla_detalle_especial` pd
						where p.`cod_ciclo` = $cicloDistribucion and p.`codigo_gestion` = $gestionDistribucion and p.`codigo_grupo_especial` = $codGrupo and 
						p.`codigo_linea` = $global_linea_distribucion and p.`codigo_parrilla_especial`=pd.`codigo_parrilla_especial` 
						group by pd.`codigo_material`";
						$respParrilla=mysql_query($sqlParrilla);
						while($datParrilla=mysql_fetch_array($respParrilla)){
							$codProducto=$datParrilla[0];
							$cantProducto=$datParrilla[1];
						
							$sqlVeriDist="select * from distribucion_productos_visitadores where codigo_gestion='$gestionDistribucion' and
							cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and codigo_linea='$global_linea'
							and grupo_salida=2";
							$respVeriDist=mysql_query($sqlVeriDist);
							$filasVeriDist=mysql_num_rows($respVeriDist);
							if($filasVeriDist==0){
								$sqlActProductos="insert into distribucion_productos_visitadores values('$gestionDistribucion',
								'$cicloDistribucion','$codTerritorio','$global_linea','$codVisitador','$codProducto','$cantProducto',
								0,'2',0)";
								$respActProductos=mysql_query($sqlActProductos);
							}else{
								$sqlActProductos="update distribucion_productos_visitadores set cantidad_planificada=cantidad_planificada+$cantProducto 
								where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$global_linea'
								and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and grupo_salida=2";
								$respActProductos=mysql_query($sqlActProductos);
							}	
						}
					}			
				}
			}

/*echo "<script language='JavaScript'>
		location.href='registro_distribucion_lineasterritorios1.php?global_linea=$global_linea';
	</script>";*/
?>
