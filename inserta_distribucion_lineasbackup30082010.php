<?php
require("conexion.inc");
if($global_usuario==1052)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
	$global_linea=$global_linea_distribucion;
	$gestionDistribucion=$global_gestion_distribucion;
	$cicloDistribucion=$global_ciclo_distribucion;

$sql_verificacion="select * from distribucion_productos_visitadores where codigo_gestion='$global_gestion_distribucion'
and cod_ciclo='$global_ciclo_distribucion' and codigo_linea='$global_linea' and grupo_salida=1";
$resp_verificacion=mysql_query($sql_verificacion);
$num_filas_verificacion=mysql_num_rows($resp_verificacion);

if($num_filas_verificacion==0)
{	$sql_visitador="select fl.codigo_funcionario,f.cod_ciudad,f.codigo_lineaclave
	from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and
	f.cod_cargo='1011' and f.estado=1	order by fl.codigo_funcionario";
	
	echo $sql_visitador."<br>";
	
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	//cereamos la matriz para cada visitador
		
		$codVisitador=$dat_visitador[0];
		$codTerritorio=$dat_visitador[1];
		$codLineaClave=$dat_visitador[2];
		
		$sqlRutero="select count(rmd.cod_med), rmd.cod_med, rmd.cod_especialidad, rmd.categoria_med
		from rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m
		where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and 
		m.cod_med=rmd.cod_med and rmc.estado_aprobado='1' and rmc.codigo_linea='$global_linea' and rmc.codigo_ciclo='$cicloDistribucion' and 
		rmc.codigo_gestion='$gestionDistribucion'
		and rmc.cod_visitador='$codVisitador' group by rmd.cod_med";
		
		echo $sqlRutero;
		
		$respRutero=mysql_query($sqlRutero);
		$numFilasRutero=mysql_num_rows($respRutero);

		while($datRutero=mysql_fetch_array($respRutero)){	
			$cantContactos=$datRutero[0];
			$codMed=$datRutero[1];
			$codEspe=$datRutero[2];
			$codCat=$datRutero[3];
			//PREPARAMOS PARA SACAR LAS PARRILLAS
			$lineaVisita=0;
			$sqlverificaLineas="select l.codigo_l_visita from lineas_visita l, lineas_visita_especialidad le, 
				lineas_visita_visitadores lv where l.codigo_l_visita=le.codigo_l_visita 
				and l.codigo_l_visita=lv.codigo_l_visita and le.codigo_l_visita=lv.codigo_l_visita
				and l.codigo_linea='$global_linea' and lv.codigo_funcionario='$codVisitador' 
				and le.cod_especialidad='$codEspe'";
					
			$respVerificaLineas=mysql_query($sqlverificaLineas);
			$numVerificaLineas=mysql_num_rows($respVerificaLineas);
			if($numVerificaLineas>0){
				$datVerificaLineas=mysql_fetch_array($respVerificaLineas);
				$lineaVisita=$datVerificaLineas[0];	
			}
			//PARRILLA POR TERRITORIO PARA MUESTRAS MEDICAS
			$sqlParrilla="select pd.codigo_muestra, sum(pd.cantidad_muestra)
			from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and
			p.codigo_linea='$global_linea' and p.cod_ciclo='$cicloDistribucion'
			and p.codigo_gestion='$gestionDistribucion'
			and p.cod_especialidad='$codEspe' and p.categoria_med='$codCat' and p.codigo_l_visita='$lineaVisita' 
			and p.agencia='$codTerritorio' and p.numero_visita<='$cantContactos' group BY(pd.codigo_muestra)";
			$respParrilla=mysql_query($sqlParrilla);
			$numFilasParrilla=mysql_num_rows($respParrilla);
			
			if($numFilasParrilla==0){
				//PARRILLA NACIONAL
				$sqlParrilla="select pd.codigo_muestra, sum(pd.cantidad_muestra)
				from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and
				p.codigo_linea='$global_linea' and p.cod_ciclo='$cicloDistribucion'
				and p.codigo_gestion='$gestionDistribucion'
				and p.cod_especialidad='$codEspe' and p.categoria_med='$codCat' and p.codigo_l_visita='$lineaVisita' 
				and p.agencia='0' and p.numero_visita<='$cantContactos' group BY(pd.codigo_muestra)";
				$respParrilla=mysql_query($sqlParrilla);	
			}
			
			echo "<br> SQL PARRILLA  ".$sqlParrilla;
			
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
				
				
				//echo $sqlActProductos."<br>";
			
			}
			
			//PARRILLA POR TERRITORIO PARA MATERIAL DE APOYO
			$sqlParrilla="select pd.codigo_material, sum(pd.cantidad_material)
			from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and
			p.codigo_linea='$global_linea' and p.cod_ciclo='$cicloDistribucion'
			and p.codigo_gestion='$gestionDistribucion'
			and p.cod_especialidad='$codEspe' and p.categoria_med='$codCat' and p.codigo_l_visita='$lineaVisita' 
			and p.agencia='$codTerritorio' and p.numero_visita<='$cantContactos' group BY(pd.codigo_material)";
			$respParrilla=mysql_query($sqlParrilla);
			$numFilasParrilla=mysql_num_rows($respParrilla);
			
			if($numFilasParrilla==0){
				//PARRILLA NACIONAL
				$sqlParrilla="select pd.codigo_material, sum(pd.cantidad_material)
				from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and
				p.codigo_linea='$global_linea' and p.cod_ciclo='$cicloDistribucion'
				and p.codigo_gestion='$gestionDistribucion'
				and p.cod_especialidad='$codEspe' and p.categoria_med='$codCat' and p.codigo_l_visita='$lineaVisita' 
				and p.agencia='0' and p.numero_visita<='$cantContactos' group BY(pd.codigo_material)";
				$respParrilla=mysql_query($sqlParrilla);	
			}
			while($datParrilla=mysql_fetch_array($respParrilla)){
				$codProducto=$datParrilla[0];
				$cantProducto=$datParrilla[1];
				
				$sqlVeriDist="select * from distribucion_productos_visitadores where codigo_gestion='$gestionDistribucion' and
				cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and  codigo_linea='$global_linea'
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
				//fin PARRILLA PROMOCIONAL MATERIAL APOYO

		}
	}
}
echo "<script language='JavaScript'>
		location.href='registro_distribucion_lineasterritorios1.php?global_linea=$global_linea';
	</script>";
?>