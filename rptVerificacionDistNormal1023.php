<script language='JavaScript'>
/*function totales(){
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
}*/
</script>
<?php
set_time_limit(0);
error_reporting(0);
require("conexion.inc");
require("estilos_reportes.inc");
require("funcion_nombres.php");

$rpt_visitador=$_GET["rpt_visitador"];
$rpt_gestion=$_GET["rpt_gestion"];
$rpt_ciclo=$_GET["rpt_ciclo"];
$rpt_territorio=$_GET["rpt_territorio"];
$codLineaMkt=1023;

$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp1=mysql_query($sql_cab);
$dato=mysql_fetch_array($resp1);
$nombre_territorio=$dato[1];	
$cad_territorio="<br>Territorio: $nombre_territorio";

$sql_nombreGestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion=$rpt_gestion");
$dat_nombreGestion=mysql_fetch_array($sql_nombreGestion);
$nombreGestion=$dat_nombreGestion[0];

echo "<html><body onload='totales();'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Reporte Verificacion de Distribución<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo $cad_territorio</th></tr></table></center><br>";

$vector=explode(",",$rpt_visitador);
$n=sizeof($vector);

for($i=0; $i<=$n-1; $i++){
	$codVisitador=$vector[$i];
	$nombreVisitador=nombreVisitador($codVisitador);
	//CREAMOS LA TABLA
	//CABECERA
	echo "<table border=1 class='textosupermini' cellspacing=0 cellpading=0 id='main' align='center'>";
	echo "<tr>";
	//echo "<th>Visitador</th>";
	echo "<th>$nombreVisitador</th>";

	$sqlCat="select distinct(rd.`cod_especialidad`), rd.`categoria_med`,
			(select lvv.`codigo_l_visita` from `lineas_visita_visitadores` lvv, `lineas_visita_especialidad` lve 
			where lvv.`codigo_l_visita`=lve.`codigo_l_visita` and 
			lve.`cod_especialidad`=rd.cod_especialidad and lvv.`codigo_ciclo`=rc.codigo_ciclo and 
			lvv.`codigo_gestion`=rc.codigo_gestion 
			and lvv.`codigo_funcionario`=rc.cod_visitador) as lineaVisita
			 from `rutero_maestro_cab_aprobado` rc, 
			`rutero_maestro_aprobado` r, `rutero_maestro_detalle_aprobado` rd
			where rc.`cod_rutero`=r.`cod_rutero` and r.`cod_contacto`=rd.`cod_contacto` and 
			rc.`cod_visitador`='$codVisitador' and rc.`codigo_gestion`='$rpt_gestion' and rc.`codigo_ciclo`='$rpt_ciclo' and 
			rc.`codigo_linea`='$codLineaMkt' order by 1,2";
//        echo $sqlCat."<br />";

	$respCat=mysql_query($sqlCat);
	while($datCat=mysql_fetch_array($respCat)){
		$codEspe=$datCat[0];
		$codCat=$datCat[1];
		$codLineaVis=$datCat[2];
		$nombreLineaVis=nombreLineaVisita($codLineaVis);
		
		echo "<th colspan='3'>$codEspe $codCat($nombreLineaVis)</th>";
	}
	
	echo "<th>Total Producto</th>";
	echo "<th>Cantidad Distribuida</th></tr>";
	echo "<tr>";
	echo "<th>Producto</th>";
	$respCat=mysql_query($sqlCat);
	while($datCat=mysql_fetch_array($respCat)){
		$codEspe=$datCat[0];
		$codCat=$datCat[1];
		$codLineaVis=$datCat[2];
		echo "<th># Med.</th><th>Parrilla</th><th>Cant.</th>";
	}
	echo "</tr>";




	
	$sqlCat="select distinct(rd.`cod_especialidad`), rd.`categoria_med`,
		(select lvv.`codigo_l_visita` from `lineas_visita_visitadores` lvv, `lineas_visita_especialidad` lve 
		where lvv.`codigo_l_visita`=lve.`codigo_l_visita` and 
		lve.`cod_especialidad`=rd.cod_especialidad and lvv.`codigo_ciclo`=rc.codigo_ciclo and 
		lvv.`codigo_gestion`=rc.codigo_gestion 
		and lvv.`codigo_funcionario`=rc.cod_visitador) as lineaVisita
		 from `rutero_maestro_cab_aprobado` rc, 
		`rutero_maestro_aprobado` r, `rutero_maestro_detalle_aprobado` rd
		where rc.`cod_rutero`=r.`cod_rutero` and r.`cod_contacto`=rd.`cod_contacto` and 
		rc.`cod_visitador`='$codVisitador' and rc.`codigo_gestion`='$rpt_gestion' and rc.`codigo_ciclo`='$rpt_ciclo' and 
		rc.`codigo_linea`='$codLineaMkt' order by 1,2";
//echo $sqlCat."<br />";
		$respCat=mysql_query($sqlCat);

	$cadEspe="(";

	while($datCat=mysql_fetch_array($respCat)){
		$codEspe=$datCat[0];
		$catMed=$datCat[1];
		$codLV=$datCat[2];
//                echo $codLV."<br />";
                            
		if($codLV=="null" or $codLV == ''){
			$codLV=0;
		}
		$cadEspe=$cadEspe."(p.`codigo_l_visita`='$codLV' and p.`cod_especialidad`='$codEspe' and p.`categoria_med`='$catMed') or";
	}
	$tamCadEspe=strlen($cadEspe);
	$cadEspe=substr($cadEspe,0,$tamCadEspe-2);
	$cadEspe=$cadEspe.")";
	
	$sqlProductos="select distinct(pd.`codigo_muestra`), concat(m.`descripcion`,' ', m.`presentacion`) 
	from parrilla p, `parrilla_detalle` pd, `muestras_medicas` m 
	where  
	 p.`codigo_parrilla`=pd.`codigo_parrilla` and p.`cod_ciclo`='$rpt_ciclo' and p.`codigo_gestion`='$rpt_gestion'
	and p.`codigo_linea`='$codLineaMkt' and p.`agencia`='$rpt_territorio' and pd.`codigo_muestra`=m.`codigo` and  $cadEspe 
	order by 2";
	
//	echo $sqlProductos."<br />";
	
	$respProductos=mysql_query($sqlProductos);
	while($datProductos=mysql_fetch_array($respProductos)){
		$codProd=$datProductos[0];
		$nombreProd=$datProductos[1];
		$sumaTotalProd=0;
		echo "<tr><td>$nombreProd</td>";
		
		
		$respCat=mysql_query($sqlCat);
		while($datCat=mysql_fetch_array($respCat)){
			$codEspe=$datCat[0];
			$codCat=$datCat[1];
			$codLineaVis=$datCat[2];
			if($codLineaVis=="null" or $codLineaVis == ''){
				$codLineaVis=0;
			}

			//nro de medicos
			$sqlNroMed="select count(distinct(rd.`cod_med`))as nromedicos from `rutero_maestro_cab_aprobado` rc, 
				`rutero_maestro_aprobado` r, `rutero_maestro_detalle_aprobado` rd
				where rc.`cod_rutero`=r.`cod_rutero` and r.`cod_contacto`=rd.`cod_contacto` and 
				rc.`cod_visitador`='$rpt_visitador' and rc.`codigo_gestion`='$rpt_gestion' and rc.`codigo_ciclo`='$rpt_ciclo' and 
				rc.`codigo_linea`='$codLineaMkt' and rd.`cod_especialidad`='$codEspe' and rd.`categoria_med`='$codCat'";
				$respNroMed=mysql_query($sqlNroMed);
			$nroMed=mysql_result($respNroMed,0,0);
			
			$sqlNroProd="select sum(pd.`cantidad_muestra`) from parrilla p, `parrilla_detalle` pd 
				where p.`codigo_parrilla`=pd.`codigo_parrilla` and  
				p.`agencia`='$rpt_territorio' and p.`codigo_gestion`='$rpt_gestion' and p.`cod_ciclo`='$rpt_ciclo' and  
				p.`cod_especialidad`='$codEspe' and p.`categoria_med`='$codCat' and p.`codigo_l_visita`='$codLineaVis'  
				and pd.`codigo_muestra`='$codProd' and p.codigo_linea='$codLineaMkt'";
			
//			echo $sqlNroProd."<br>";
			
			$respNroProd=mysql_query($sqlNroProd);
			$nroProd=0;
			$nroProd=mysql_result($respNroProd,0,0);
			$cantxEspeCat=$nroMed*$nroProd;
			
			$sumaTotalProd=$sumaTotalProd+$cantxEspeCat;
			
			if($nroProd>0){
				echo "<td bgcolor='#2EFEC8' align='right'>$nroMed</td>";
				echo "<td bgcolor='#2EFEC8' align='right'>$nroProd</td>";
				echo "<td bgcolor='#2EFEC8' align='right'>$cantxEspeCat</td>";
			}else{
				echo "<td>-</td>";
				echo "<td>-</td>";
				echo "<td>-</td>";
			}
			
			
		}
		echo "<td align='right'>$sumaTotalProd</td>";
                                    $sql_cantidad_distribuida = mysql_query("select cantidad_planificada from distribucion_productos_visitadores where codigo_linea = $codLineaMkt 
                                        and cod_ciclo =$rpt_ciclo and codigo_gestion = $rpt_gestion and territorio = $rpt_territorio and cod_visitador = $codVisitador and codigo_producto = '$codProd'");
                                    $cantidad_distribuida = mysql_result($sql_cantidad_distribuida, 0,0);
                                    if(mysql_num_rows($sql_cantidad_distribuida) == 0){
                                        $cantidad_distribuida_final = "<td align='right' bgcolor='red' style='background:red'>
                                            <span style='color:white; font-weight:bold'>No se distribuyo el producto</span>
                                        </td>";
                                    }
                                    if($cantidad_distribuida == $sumaTotalProd){
                                        $cantidad_distribuida_final = "<td align='right' bgcolor='green' style='background:green'>
                                            <span style='color:white; font-weight:bold'>$cantidad_distribuida</span>
                                        </td>";
                                    }else{
                                        $cantidad_distribuida_final = "<td align='right' bgcolor='red' style='background:red'>
                                            <span style='color:white; font-weight:bold'>$cantidad_distribuida</span>
                                        </td>";
                                    }
		echo $cantidad_distribuida_final;	
	}
	echo "</table><br><br>";
}

echo "</form></body></html>";
?>