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
$rpt_gestion=$_GET["rpt_gestion"];
$rpt_ciclo=$_GET["rpt_ciclo"];
$rpt_territorio=$_GET["rpt_territorio"];
$rpt_linea=$_GET['rpt_linea'];
$rptNombreLinea=$_GET['rptNombreLinea'];
$rptVer=$_GET['rpt_ver'];

$sqlFechas="SELECT distinct(s.`fecha`) from `salida_almacenes` s, `salida_detalle_visitador` sv, `funcionarios` f 
					where s.`cod_salida_almacenes` = sv.`cod_salida_almacen` and sv.`codigo_ciclo`=$rpt_ciclo
					and sv.`codigo_gestion`=$rpt_gestion and f.`codigo_funcionario`=sv.`codigo_funcionario` and f.`cod_ciudad` in ($rpt_territorio)";

$respFechas=mysql_query($sqlFechas);
$cadFechas="";
while($datFechas=mysql_fetch_array($respFechas)){
	$fecha=$datFechas[0];
	$cadFechas.=$fecha." ";
}

$sql_nombreGestion=mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion=$rpt_gestion");
$dat_nombreGestion=mysql_fetch_array($sql_nombreGestion);
$nombreGestion=$dat_nombreGestion[0];
echo "<html><body onload='totales();'>";
echo "<table border='0' class='textotit' align='center'><tr><th>MM-MA No Distribuido x Ciclo y Territorio<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo<br>
Linea: $rptNombreLinea <br>
Fechas de Salida: $cadFechas
</th></tr></table></center><br>";

	echo "<table border=1 class='texto' cellspacing=0 cellpading=0 id='main' align='center'>";
	$sql_territorio="SELECT cod_ciudad, descripcion from ciudades where cod_ciudad in ($rpt_territorio) order by descripcion";

	$resp_territorios=mysql_query($sql_territorio);
	echo "<tr><th>&nbsp;Producto</th>";
	while($dat_territorios=mysql_fetch_array($resp_territorios))
	{	$cod_territorio=$dat_territorios[0];
		$nombre_territorio=$dat_territorios[1];
		echo "<th>$nombre_territorio</th>";
	}	
	echo "<th>TOTALES</th>";
	if($rptVer==0){
		// $sql_productos="SELECT distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
					       // d.grupo_salida from distribucion_productos_visitadores d, `muestras_medicas` m 
					       // where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
					       // d.codigo_linea in ($rpt_linea) and m.`codigo`=d.`codigo_producto` order by m.`descripcion`";	
		$sql_productos = "SELECT * from (SELECT distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
        d.grupo_salida from distribucion_productos_visitadores d, `muestras_medicas` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        d.codigo_linea in ($rpt_linea) and m.`codigo`=d.`codigo_producto` 
			union 
        SELECT distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
        d.grupo_salida from distribucion_grupos_especiales d, `muestras_medicas` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        m.`codigo`=d.`codigo_producto`
                                                        union
        SELECT distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
        d.grupo_salida from distribucion_banco_muestras d, `muestras_medicas` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        m.`codigo`=d.`codigo_producto`) as tabla order by 2";
	}else{
		// $sql_productos="SELECT distinct (d.codigo_producto), m.`descripcion_material`,
					       // d.grupo_salida from distribucion_productos_visitadores d, `material_apoyo` m 
					       // where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
					       // d.codigo_linea in ($rpt_linea) and m.`codigo_material`=d.`codigo_producto` 
					       // and m.codigo_material<>0 order by m.`descripcion_material`";	
		$sql_productos="SELECT * from (SELECT distinct (d.codigo_producto), m.`descripcion_material`,
        d.grupo_salida from distribucion_productos_visitadores d, `material_apoyo` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        d.codigo_linea in ($rpt_linea) and m.`codigo_material`=d.`codigo_producto`
        and m.codigo_material<>0 
			union 
		SELECT distinct (d.codigo_producto), m.`descripcion_material`,
        d.grupo_salida from `distribucion_grupos_especiales` d, `material_apoyo` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        m.`codigo_material`=d.`codigo_producto`
        and m.codigo_material<>0
                                                    union
		SELECT distinct (d.codigo_producto), m.`descripcion_material`,
        d.grupo_salida from `distribucion_banco_muestras` d, `material_apoyo` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        m.`codigo_material`=d.`codigo_producto`
        and m.codigo_material<>0) as tabla order by 2";
	}
	// echo $sql_productos;
	$resp_productos=mysql_query($sql_productos);
	while($dat_productos=mysql_fetch_array($resp_productos))
	{	$cod_prod=$dat_productos[0];
		$nombre_producto=$dat_productos[1];
		$grupo_salida=$dat_productos[2];
		//sacamos el nombre del producto
		$cad_mostrar="<tr><td>$nombre_producto</td>";
		$sql_territorio="SELECT cod_ciudad, descripcion from ciudades where cod_ciudad in ($rpt_territorio) order by descripcion";
		$resp_territorios=mysql_query($sql_territorio);
		$suma_cantidadfaltante=0;
		$suma_cantidadfaltante_ge=0;
		$suma_cantidadfaltante_bm=0;
		$totalPlanificado=0;
		$totalPlanificado_ge=0;
		$totalPlanificado_bm=0;
		$totalDistribuido=0;
		$totalDistribuido_ge=0;
		$totalDistribuido_bm=0;
		$totalFaltante=0;
		$totalFaltante_ge=0;
		$totalFaltante_bm=0;
		while($dat_territorios=mysql_fetch_array($resp_territorios))
		{	$cod_territorio=$dat_territorios[0];
			$nombre_territorio=$dat_territorios[1];
			$sql_dist="SELECT sum(cantidad_planificada), sum(cantidad_distribuida), SUM(cantidad_sacadaalmacen) 
				from distribucion_productos_visitadores
				where codigo_gestion='$rpt_gestion' and cod_ciclo in ('$rpt_ciclo') and codigo_producto='$cod_prod' 
				and territorio='$cod_territorio' and codigo_linea in ($rpt_linea)
				group by territorio";
				// echo $sql_dist."<br />";
			$resp_dist=mysql_query($sql_dist);
			$dat_dist=mysql_fetch_array($resp_dist);
			$cantidad_planificada=$dat_dist[0];
			$cantidad_distribuida=$dat_dist[1];
			$cantidad_sacadaalmacen = $dat_dist[2];
			$cantidad_faltante=$cantidad_planificada-$cantidad_distribuida;
			// $cantidad_faltante=$cantidad_distribuida - $cantidad_sacadaalmacen;
			$suma_cantidadfaltante=$suma_cantidadfaltante+$cantidad_faltante;
			$totalPlanificado=$totalPlanificado+$cantidad_planificada;
			$totalDistribuido=$totalDistribuido+$cantidad_distribuida;
			$totalFaltante=$totalFaltante+$cantidad_faltante;

			/*Grupos Especiales*/
			$sql_dist_ge="SELECT sum(cantidad_planificada), sum(cantidad_distribuida) , SUM(cantidad_sacadaalmacen) 
				from distribucion_grupos_especiales
				where codigo_gestion='$rpt_gestion' and cod_ciclo in ('$rpt_ciclo') and codigo_producto='$cod_prod' 
				and territorio='$cod_territorio' and codigo_linea in ($rpt_linea)
				group by territorio";
				// echo $sql_dist_ge."<br />";
			$resp_dist_ge=mysql_query($sql_dist_ge);
			$dat_dist_ge=mysql_fetch_array($resp_dist_ge);
			$cantidad_planificada_ge=$dat_dist_ge[0];
			$cantidad_distribuida_ge=$dat_dist_ge[1];
			$cantidad_sacadaalmacen_ge = $dat_dist_ge[2];
			$cantidad_faltante_ge=$cantidad_planificada_ge-$cantidad_distribuida_ge;
			// $cantidad_faltante_ge=$cantidad_distribuida_ge-$cantidad_sacadaalmacen_ge;
			$suma_cantidadfaltante_ge=$suma_cantidadfaltante_ge+$cantidad_faltante_ge;
			$totalPlanificado_ge=$totalPlanificado_ge+$cantidad_planificada_ge;
			$totalDistribuido_ge=$totalDistribuido_ge+$cantidad_distribuida_ge;
			$totalFaltante_ge=$totalFaltante_ge+$cantidad_faltante_ge;

			/*FIN Grupos Especiales*/

			/*Banco De Muestras*/
			$sql_dist_bm="SELECT sum(cantidad_planificada), sum(cantidad_distribuida), SUM(cantidad_sacadaalmacen)  
				from distribucion_banco_muestras
				where codigo_gestion='$rpt_gestion' and cod_ciclo in ('$rpt_ciclo') and codigo_producto='$cod_prod' 
				and territorio='$cod_territorio' and codigo_linea in ($rpt_linea)
				group by territorio";
				// echo $sql_dist_bm."<br />";
			$resp_dist_bm=mysql_query($sql_dist_bm);
			$dat_dist_bm=mysql_fetch_array($resp_dist_bm);
			$cantidad_planificada_bm=$dat_dist_bm[0];
			$cantidad_distribuida_bm=$dat_dist_bm[1];
			$cantidad_sacadaalmacen_bm=$dat_dist_bm[2];
			$cantidad_faltante_bm=$cantidad_planificada_bm-$cantidad_distribuida_bm;
			// $cantidad_faltante_bm=$cantidad_distribuida_bm-$cantidad_sacadaalmacen_bm;
			$suma_cantidadfaltante_bm=$suma_cantidadfaltante_bm+$cantidad_faltante_bm;
			$totalPlanificado_bm=$totalPlanificado_bm+$cantidad_planificada_bm;
			$totalDistribuido_bm=$totalDistribuido_bm+$cantidad_distribuida_bm;
			$totalFaltante_bm=$totalFaltante_bm+$cantidad_faltante_bm;

			/*FIN Banco De Muestras*/

			$cantidad_faltante_final = $cantidad_faltante + $cantidad_faltante_ge + $cantidad_faltante_bm;
			// echo $cantidad_faltante_final."<br />";
			$totalFaltante_final = $totalFaltante + $totalFaltante_ge + $totalFaltante_bm;
			$cad_mostrar.="<td>$cantidad_faltante_final</td>";			
		}
			$cad_mostrar.="<th>$totalFaltante_final</th>";
			if($totalFaltante_final>0){
					echo $cad_mostrar;						
			}

		}
echo "<tr><TH>TOTALES</TH></tr>";
echo "</table>";
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";	
?>