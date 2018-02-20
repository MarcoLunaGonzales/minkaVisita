<?php
require('conexion.inc');
require('funcion_nombres.php');

function insertaDistribucion($codFuncionario, $global_linea, $cicloDistribucion, $gestionDistribucion){
	
$sql_gestion = mysql_query("select codigo_gestion from gestiones where estado = 'Activo' ");
$gestion = mysql_result($sql_gestion, 0,0);	
	$sqlDelete=mysql_query("delete from distribucion_productos_visitadores2 where cod_visitador in ($codFuncionario) and codigo_linea in ($global_linea)");
	
	$sql_visitador="select fl.codigo_funcionario,f.cod_ciudad,f.codigo_lineaclave
	from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and
	f.cod_cargo='1011' and f.estado=1  and f.codigo_funcionario=$codFuncionario
	order by fl.codigo_funcionario";
	
	//echo $sql_visitador."<br>";
	
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
		
		//echo $sqlRutero;
		
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
				and le.cod_especialidad='$codEspe and lv.codigo_ciclo = $global_ciclo_distribucion and lv.codigo_gestion = $gestion'";
					
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
			and p.agencia='$codTerritorio' and p.numero_visita<='$cantContactos' 
			group BY(pd.codigo_muestra)";
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
			
			//echo "<br> SQL PARRILLA  ".$sqlParrilla;
			
			while($datParrilla=mysql_fetch_array($respParrilla)){
				$codProducto=$datParrilla[0];
				$cantProducto=$datParrilla[1];
				
				$sqlVeriDist="select * from distribucion_productos_visitadores2 where codigo_gestion='$gestionDistribucion' and
				cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and codigo_linea='$global_linea'
				and grupo_salida=1";
				$respVeriDist=mysql_query($sqlVeriDist);
				$filasVeriDist=mysql_num_rows($respVeriDist);
				if($filasVeriDist==0){
					$sqlActProductos="insert into distribucion_productos_visitadores2 values('$gestionDistribucion',
					'$cicloDistribucion','$codTerritorio','$global_linea','$codVisitador','$codProducto','$cantProducto',
					0,'1',0,0)";
					$respActProductos=mysql_query($sqlActProductos);
				}else{
					$sqlActProductos="update distribucion_productos_visitadores2 set cantidad_planificada=cantidad_planificada+$cantProducto 
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
				
				$sqlVeriDist="select * from distribucion_productos_visitadores2 where codigo_gestion='$gestionDistribucion' and
				cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and  codigo_linea='$global_linea'
				and grupo_salida=2";
				$respVeriDist=mysql_query($sqlVeriDist);
				$filasVeriDist=mysql_num_rows($respVeriDist);
				if($filasVeriDist==0){
					$sqlActProductos="insert into distribucion_productos_visitadores2 values('$gestionDistribucion',
					'$cicloDistribucion','$codTerritorio','$global_linea','$codVisitador','$codProducto','$cantProducto',
					0,'2',0,0)";
					$respActProductos=mysql_query($sqlActProductos);
				}else{
					$sqlActProductos="update distribucion_productos_visitadores2 set cantidad_planificada=cantidad_planificada+$cantProducto 
					where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$global_linea'
					and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and grupo_salida=2";
					$respActProductos=mysql_query($sqlActProductos);
				}
			}
			//fin PARRILLA PROMOCIONAL MATERIAL APOYO
			
			
			//GRUPOS ESPECIALES
$sqlGrupos="select g.`codigo_grupo_especial` from `grupo_especial` g, 
`grupo_especial_detalle` gd where g.`codigo_grupo_especial` = gd.`codigo_grupo_especial` and 
gd.`cod_med`='$codMed'";


$respGrupos=mysql_query($sqlGrupos);
while($datGrupos=mysql_fetch_array($respGrupos)){
	$codGrupo=$datGrupos[0];
	
	//PARRILLA MUESTRAS
	$sqlParrilla="select pd.`codigo_muestra`,  SUM(pd.`cantidad_muestra`) from `parrilla_especial` p, `parrilla_detalle_especial` pd
	where p.`cod_ciclo` = $cicloDistribucion and p.`codigo_gestion` = $gestionDistribucion and p.`codigo_grupo_especial` = $codGrupo and 
	p.`codigo_linea` = $global_linea and p.`codigo_parrilla_especial`=pd.`codigo_parrilla_especial` 
	group by pd.`codigo_muestra`";

	
	$respParrilla=mysql_query($sqlParrilla);
	while($datParrilla=mysql_fetch_array($respParrilla)){
		$codProducto=$datParrilla[0];
		$cantProducto=$datParrilla[1];
		
		$sqlVeriDist="select * from distribucion_productos_visitadores2 where codigo_gestion='$gestionDistribucion' and
		cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and codigo_linea='$global_linea'
		and grupo_salida=1";
		$respVeriDist=mysql_query($sqlVeriDist);
		$filasVeriDist=mysql_num_rows($respVeriDist);
		if($filasVeriDist==0){
			$sqlActProductos="insert into distribucion_productos_visitadores2 values('$gestionDistribucion',
			'$cicloDistribucion','$codTerritorio','$global_linea','$codVisitador','$codProducto',0,
			0,'1',0,$cantProducto)";
			$respActProductos=mysql_query($sqlActProductos);
		}else{
			$sqlActProductos="update distribucion_productos_visitadores2 set cantidad_grupoespecial=cantidad_grupoespecial+$cantProducto 
			where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$global_linea'
			and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and grupo_salida=1";
			$respActProductos=mysql_query($sqlActProductos);
		}
	}	
		
		
		//PARRILLA MATERIAL APOYO
		$sqlParrilla="select pd.`codigo_material`,  SUM(pd.`cantidad_material`) from `parrilla_especial` p, `parrilla_detalle_especial` pd
		where p.`cod_ciclo` = $cicloDistribucion and p.`codigo_gestion` = $gestionDistribucion and p.`codigo_grupo_especial` = $codGrupo and 
		p.`codigo_linea` = $global_linea and p.`codigo_parrilla_especial`=pd.`codigo_parrilla_especial` 
		group by pd.`codigo_muestra`";
		$respParrilla=mysql_query($sqlParrilla);
		while($datParrilla=mysql_fetch_array($respParrilla)){
			$codProducto=$datParrilla[0];
			$cantProducto=$datParrilla[1];
			
			$sqlVeriDist="select * from distribucion_productos_visitadores2 where codigo_gestion='$gestionDistribucion' and
			cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and codigo_linea='$global_linea'
			and grupo_salida=2";
			$respVeriDist=mysql_query($sqlVeriDist);
			$filasVeriDist=mysql_num_rows($respVeriDist);
			if($filasVeriDist==0){
				$sqlActProductos="insert into distribucion_productos_visitadores2 values('$gestionDistribucion',
				'$cicloDistribucion','$codTerritorio','$global_linea','$codVisitador','$codProducto',0,
				0,'2',0, $cantProducto)";
				$respActProductos=mysql_query($sqlActProductos);
			}else{
				$sqlActProductos="update distribucion_productos_visitadores2 set cantidad_grupoespecial=cantidad_grupoespecial+$cantProducto 
				where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$global_linea'
				and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and grupo_salida=2";
				$respActProductos=mysql_query($sqlActProductos);
			}	
		}
}
		//FIN GRUPOS ESPECIALES
		}
	}
	
	return(1);
}


function mostrarDistribucion($codVisitador, $cicloDistribucion, $gestionDistribucion){
	
	$nombreVisitador=nombreVisitador($codVisitador);
	echo "<center>Visitador: $nombreVisitador</center>";
	echo "<table border=1 class='texto' width='70%' align='center'>";
	$sqlLineas="select distinct(d.`codigo_linea`) from `distribucion_productos_visitadores2` d where d.`cod_visitador`=$codVisitador order by 1";
	$respLineas=mysql_query($sqlLineas);
	echo "<tr><th>Producto</th>";
	while($datLineas=mysql_fetch_array($respLineas)){
		$codLinea=$datLineas[0];
		$nombreLinea=nombreLinea($codLinea);
		echo "<th colspan=2>$nombreLinea</th>";
	}
	echo "<th>Total</th></tr>";
	
	$respLineas=mysql_query($sqlLineas);
	echo "<tr><th>&nbsp;</th>";
	while($datLineas=mysql_fetch_array($respLineas)){
		echo "<th>Cant. Linea</th><th>Cant. Grupo Especial</th>";
	}
	echo "</tr>";
	
	
	$sqlProd="select distinct(d.`codigo_producto`) ,concat(m.`descripcion`, ' ',m.`presentacion`),
       d.`grupo_salida` from `distribucion_productos_visitadores2` d, `muestras_medicas` m 
       where d.`codigo_producto`=m.`codigo` and d.`cod_visitador` = $codVisitador and d.`cod_ciclo` = $cicloDistribucion and 
       d.`codigo_gestion` = $gestionDistribucion and d.`grupo_salida` = 1 order by 2";
 	$respProd=mysql_query($sqlProd);
	while($datProd=mysql_fetch_array($respProd)){
		$codProd=$datProd[0];
		$nombreProd=$datProd[1];
		$sumaProducto=0;
		echo "<tr><td>$nombreProd</td>";
		$sqlLineas="select distinct(d.`codigo_linea`) from `distribucion_productos_visitadores2` d where d.`cod_visitador`=$codVisitador order by 1";
		$respLineas=mysql_query($sqlLineas);
		while($datLineas=mysql_fetch_array($respLineas)){
			$codLinea=$datLineas[0];
			
			$sqlCant="select d.`cantidad_planificada`, d.`cantidad_grupoespecial` from `distribucion_productos_visitadores2` d 
			where d.`cod_visitador`=$codVisitador and d.`cod_ciclo`=$cicloDistribucion and d.`codigo_gestion`=$gestionDistribucion and 
			d.`codigo_linea`=$codLinea and d.`codigo_producto`='$codProd'";
			$respCant=mysql_query($sqlCant);
			$numFilas=mysql_num_rows($respCant);
			if($numFilas>0){
				$cantLinea=mysql_result($respCant,0,0);
				$cantGrupoEsp=mysql_result($respCant,0,1);
			}else{
				$cantLinea=0;
				$cantGrupoEsp=0;
			}
			$sumaProducto=$sumaProducto+$cantLinea+$cantGrupoEsp;
			echo "<td>$cantLinea</td><td>$cantGrupoEsp</td>";
		}
		echo "<td>$sumaProducto</td></tr>";		
	}
	echo "</table>";
	
}

function mostrarDistribucionMA($codVisitador, $cicloDistribucion, $gestionDistribucion){
	
	echo "<table border=1 class='texto' width='70%' align='center'>";
	$sqlLineas="select distinct(d.`codigo_linea`) from `distribucion_productos_visitadores2` d where d.`cod_visitador`=$codVisitador order by 1";
	$respLineas=mysql_query($sqlLineas);
	echo "<tr><th>Producto</th>";
	while($datLineas=mysql_fetch_array($respLineas)){
		$codLinea=$datLineas[0];
		$nombreLinea=nombreLinea($codLinea);
		echo "<th colspan=2>$nombreLinea</th>";
	}
	echo "<th>Total</th></tr>";
	
	$respLineas=mysql_query($sqlLineas);
	echo "<tr><th>&nbsp;</th>";
	while($datLineas=mysql_fetch_array($respLineas)){
		echo "<th>Cant. Linea</th><th>Cant. Grupo Especial</th>";
	}
	echo "</tr>";
	
	
	$sqlProd="select distinct(d.`codigo_producto`) ,m.`descripcion_material`,
       d.`grupo_salida` from `distribucion_productos_visitadores2` d, `material_apoyo` m 
       where d.`codigo_producto`=m.`codigo_material` and d.`cod_visitador` = $codVisitador and d.`cod_ciclo` = $cicloDistribucion and 
       d.`codigo_gestion` = $gestionDistribucion and d.`grupo_salida` = 2 and m.codigo_material<>0 order by 2";
 	$respProd=mysql_query($sqlProd);
 	
	while($datProd=mysql_fetch_array($respProd)){
		$codProd=$datProd[0];
		$nombreProd=$datProd[1];
		$sumaProducto=0;

		echo "<tr><td>$nombreProd</td>";
		$sqlLineas="select distinct(d.`codigo_linea`) from `distribucion_productos_visitadores2` d where d.`cod_visitador`=$codVisitador order by 1";
		$respLineas=mysql_query($sqlLineas);
		while($datLineas=mysql_fetch_array($respLineas)){
			$codLinea=$datLineas[0];
			
			$sqlCant="select d.`cantidad_planificada`, d.`cantidad_grupoespecial` from `distribucion_productos_visitadores2` d 
			where d.`cod_visitador`=$codVisitador and d.`cod_ciclo`=$cicloDistribucion and d.`codigo_gestion`=$gestionDistribucion and 
			d.`codigo_linea`=$codLinea and d.`codigo_producto`='$codProd'";
			$respCant=mysql_query($sqlCant);
			$numFilas=mysql_num_rows($respCant);
			if($numFilas>0){
				$cantLinea=mysql_result($respCant,0,0);
				$cantGrupoEsp=mysql_result($respCant,0,1);
			}else{
				$cantLinea=0;
				$cantGrupoEsp=0;
			}
			$sumaProducto=$sumaProducto+$cantLinea+$cantGrupoEsp;
			echo "<td>$cantLinea</td><td>$cantGrupoEsp</td>";
		}
		echo "<td>$sumaProducto</td></tr>";		
	}
	echo "</table>";
	
}


?>