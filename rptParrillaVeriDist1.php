<script language='JavaScript'>
function totales(){
   var main=document.getElementById('main');   
   var numFilas=main.rows.length;
   var numCols=main.rows[2].cells.length;
	 
	 for(var j=1; j<=numCols-1; j++){
	 		var subtotal=0;
	 		for(var i=2; i<=numFilas-2; i++){
	 				var dato=parseInt(main.rows[i].cells[j].innerHTML);
	 				subtotal=subtotal+dato;
	 		}
	 		var fila=document.createElement('TH');
			main.rows[numFilas-1].appendChild(fila);
			main.rows[numFilas-1].cells[j].innerHTML=subtotal;
	 }	 
}
</script>

<?php
	require("conexion.inc");
	require("estilos_reportes.inc");
	require("funcion_nombres.php");
	
	$rpt_gestion=$rpt_gestion;
	//$global_linea=$linea_rpt;
	$rpt_ciclo=$rpt_ciclo;
	$rpt_visitador=$rpt_visitador;
	$rpt_territorio=$rpt_territorio;
	$rpt_linea=$rpt_linea;
	$rpt_nombreVisitador=$rptNombreVisitador;

	$nombre_cab_gestion=nombreGestion($rpt_gestion);
	$nombreTerritorio=nombreTerritorio($rpt_territorio);
	$nombreVisitador=nombreVisitador($rpt_visitador);
	
	echo "<html><body onload='totales();'>";
	
	echo "<center><table border='0' class='textotit'><tr><th>Parrillas para Verificacion de la Distribucion 
	<br>Gestión: $nombre_cab_gestion Ciclo: $rpt_ciclo<br>Territorio: $nombreTerritorio  <br>Visitador: $rpt_nombreVisitador
	</th></tr></table></center><br>";

	//sacamos las especialidades que hace el visitador
	
	$sqlEspe="select distinct(rd.`cod_especialidad`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm, 
	`rutero_maestro_detalle_aprobado` rd where rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and  
	rc.`codigo_gestion`=$rpt_gestion and rc.`codigo_ciclo`=$rpt_ciclo and rc.`codigo_linea`=$rpt_linea and 
	rc.`cod_visitador` in ($rpt_visitador) order by 1";
	
	$respEspe=mysql_query($sqlEspe);
	$txtCodEspe="";
	while($datEspe=mysql_fetch_array($respEspe)){
		$codEspe=$datEspe[0];
		$txtCodEspe.="'".$codEspe."'".",";
	}
	$tamCad=strlen($txtCodEspe);
	$txtCodEspe=substr($txtCodEspe,0,$tamCad-1);

	echo "<center><table border='1' class='textomini' cellspacing='0' width='80%' id='main'>";
	echo "<tr><th>Producto</th>";
	
	$respEspe=mysql_query($sqlEspe);
	while($datEspe=mysql_fetch_array($respEspe)){
		$codEspe=$datEspe[0];
		echo "<th colspan='9'>$codEspe</th>";
	}
	echo "</tr><tr><th></th>";
	$respEspe=mysql_query($sqlEspe);
	while($datEspe=mysql_fetch_array($respEspe)){
		$sqlCat="select categoria_med from `categorias_medicos` where categoria_med<>'D' order by 1";
		$respCat=mysql_query($sqlCat);
		while($datCat=mysql_fetch_array($respCat)){
			$catMed=$datCat[0];
			echo "<th>$catMed</th><th># Med. $catMed</th><th>Total $catMed</th>";
		}
	}
	echo "<th>Total Producto</th></tr>";
	
	$sqlProd="select distinct(pd.`codigo_muestra`) from parrilla p, `parrilla_detalle` pd, muestras_medicas m where 
	pd.codigo_muestra=m.codigo and p.`codigo_parrilla`=pd.`codigo_parrilla` and p.cod_ciclo = '$rpt_ciclo' and p.codigo_gestion = '$rpt_gestion' and 
	p.codigo_linea = '$rpt_linea' and p.agencia = '$rpt_territorio' and p.cod_especialidad in ($txtCodEspe) order by m.descripcion";
	
	$respProd=mysql_query($sqlProd);
	while($datProd=mysql_fetch_array($respProd)){
		$codProd=$datProd[0];
		$nombreProd=nombreProducto($codProd);
		echo "<tr><td>$nombreProd</td>";
		$sumaTotalProd=0;
		$respEspe=mysql_query($sqlEspe);
		while($datEspe=mysql_fetch_array($respEspe)){
			$codEspe=$datEspe[0];
			$sqlCat="select categoria_med from `categorias_medicos` where categoria_med<>'D' order by 1";
			$respCat=mysql_query($sqlCat);
			while($datCat=mysql_fetch_array($respCat)){
				$catMed=$datCat[0];
				//cantidad de prod
				$sqlCantProd="select sum(pd.`cantidad_muestra`) from parrilla p, `parrilla_detalle` pd 
				where p.`codigo_parrilla` = pd.`codigo_parrilla` and p.cod_ciclo = '$rpt_ciclo' and p.codigo_gestion = '$rpt_gestion' and 
				p.codigo_linea = '$rpt_linea' and p.agencia = '$rpt_territorio' and p.cod_especialidad in ('$codEspe') and 
				p.`categoria_med` = '$catMed' and pd.codigo_muestra='$codProd'";
				$respCantProd=mysql_query($sqlCantProd);
				$filasProd=mysql_num_rows($respCantProd);
				if($filasProd>0){
					$cantProd=mysql_result($respCantProd,0,0);
				}else{
					$cantProd=0;
				}
				
				$sqlNroMed="select count(distinct(rd.`cod_med`)) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm, 
				`rutero_maestro_detalle_aprobado` rd where rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and  
				rc.`codigo_gestion`=$rpt_gestion and rc.`codigo_ciclo`=$rpt_ciclo and rc.`codigo_linea`=$rpt_linea and 
				rc.`cod_visitador` in ($rpt_visitador) and rd.cod_especialidad='$codEspe' and rd.categoria_med='$catMed'";
				$respNroMed=mysql_query($sqlNroMed);
				$filasNroMed=mysql_num_rows($respNroMed);
				if($filasNroMed>0){
					$nroMedicos=mysql_result($respNroMed,0,0);
				}else{
					$nroMedicos=0;
				}
				$cantTotalProd=$cantProd*$nroMedicos;
				$sumaTotalProd=$sumaTotalProd+$cantTotalProd;
				echo "<td>$cantProd</td><td>$nroMedicos</td><td>$cantTotalProd</td>";
			}
		}
		echo "<td>$sumaTotalProd</td></tr>";
		
	}
echo "<tr><TH>TOTALES</TH></tr>";
echo "</table>";


?>