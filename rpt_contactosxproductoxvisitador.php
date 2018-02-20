<?php
require("conexion.inc");
require("estilos_reportes_central.inc"); 
set_time_limit(0);
// echo $rpt_territorio;
$rpt_linea = $linea_rpt;
$sql_cab = "SELECT cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp1 = mysql_query($sql_cab);
$dato = mysql_fetch_array($resp1);
$nombre_territorio = $dato[1];
$sql_cabecera_gestion = mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
$datos_cab_gestion = mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion = $datos_cab_gestion[0];
echo "<form method='post' action=''>";
$sql_ciclo = mysql_query("SELECT cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
$dat = mysql_fetch_array($sql_ciclo);
$cod_ciclo = $dat[0];
if ($rpt_territorio == 0) {
	echo "<center><table border='0' class='textotit'><tr><th>Contactos x Producto x Visitador<br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
} else {
	echo "<center><table border='0' class='textotit'><tr><th>Contactos x Producto x Visitador<br>Territorio: $nombre_territorio<br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
} 
// //////////
echo "<center><table border='1' class='textomini' cellspacing='0' width='100%'>";
echo "<tr><th>&nbsp;</th><th>Producto</th>";
$sqlVisitadores = "SELECT codigo_funcionario, concat(paterno, ' ',nombres) as nombreCompleto from funcionarios where cod_cargo=1011 and cod_ciudad=$rpt_territorio and estado=1 and codigo_funcionario in (SELECT codigo_funcionario from funcionarios_lineas where codigo_linea=$rpt_linea)order by paterno";
$respVisitadores = mysql_query($sqlVisitadores);
while ($datVisitadores = mysql_fetch_array($respVisitadores)) {
	$nombreVisitador = $datVisitadores[1];
	echo "<th>$nombreVisitador</th>";
} 
echo "<th>Total</th></tr>";
$sqlProductos = "SELECT distinct(m.codigo), concat(m.descripcion,' ',m.presentacion)  from muestras_medicas m, parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and pd.codigo_muestra=m.codigo and p.cod_ciclo=$ciclo_rpt and p.codigo_gestion=$gestion_rpt order by 2";
$respProductos = mysql_query($sqlProductos);
$indice=1;
while ($datProductos = mysql_fetch_array($respProductos)) {
	$codProducto = $datProductos[0];
	$nombreProducto = $datProductos[1];
	echo "<tr><td>$indice</td><td>$nombreProducto</td>";
	$indice++;
	$sqlVisitadores = "SELECT codigo_funcionario, concat(paterno, ' ',nombres) as nombreCompleto from funcionarios where cod_cargo=1011 and cod_ciudad=$rpt_territorio and estado=1 and codigo_funcionario in  (SELECT codigo_funcionario from funcionarios_lineas where codigo_linea=$rpt_linea) order by paterno";
	$respVisitadores = mysql_query($sqlVisitadores);
	
	$sumProducto=0;
	while ($datVisitadores = mysql_fetch_array($respVisitadores)) {
		$codVisitador = $datVisitadores[0];

		$sqlMedicos="SELECT rd.cod_especialidad, rd.categoria_med, count(distinct(rd.cod_med)) from  rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto and rc.codigo_ciclo = '$ciclo_rpt' and rc.codigo_gestion = '$gestion_rpt' and rc.cod_visitador = '$codVisitador' and rc.codigo_linea = '$linea_rpt' group by rd.cod_especialidad, rd.categoria_med";
		// echo $sqlMedicos."<br />";

		$respMedicos = mysql_query($sqlMedicos);
		$sumProdVisitador=0;
		while ($datMedicos = mysql_fetch_array($respMedicos)) {
			$codEspe = $datMedicos[0];
			$catMed = $datMedicos[1];
			$cantMed=$datMedicos[2];

			//revisa lineas de visita por especialidad
			$sqlLineasVisita="SELECT l.codigo_l_visita from lineas_visita l, lineas_visita_especialidad le, lineas_visita_visitadores lv where l.codigo_l_visita=le.codigo_l_visita and l.codigo_l_visita=lv.codigo_l_visita and lv.codigo_funcionario='$codVisitador' and l.codigo_linea='$linea_rpt' and le.cod_especialidad='$codEspe'";
			$respLineasVisita=mysql_query($sqlLineasVisita);
			$numFilasLineasVisita=mysql_num_rows($respLineasVisita);
			if($numFilasLineasVisita==0){
				$codLineaVisita=0;
			}else{
				$datLineaVisita=mysql_fetch_array($respLineasVisita);
				$codLineaVisita=$datLineaVisita[0];
			}	

			//fin revisa lineas de visita por especialidad
			
			$sqlParrilla="SELECT count(p.codigo_parrilla) as nro_filas from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.cod_ciclo='$ciclo_rpt' and p.codigo_gestion='$gestion_rpt' and p.codigo_linea='$linea_rpt' and p.cod_especialidad='$codEspe' and p.categoria_med='$catMed' and pd.codigo_muestra='$codProducto' and p.codigo_l_visita='$codLineaVisita' and p.agencia='$rpt_territorio'";
			// echo $sqlParrilla."<br />";
			$respParrilla=mysql_query($sqlParrilla);
			$datParrilla=mysql_fetch_array($respParrilla);
			
			$nroContactos=$datParrilla[0]*$cantMed;

			$sumProdVisitador=$sumProdVisitador+$nroContactos;						
		} 
		echo "<td align='center'>$sumProdVisitador</td>";
		$sumProducto=$sumProducto+$sumProdVisitador;
	}
	echo "<td align='center'>$sumProducto</td></tr>"; 
} 
echo "</table></center>";
echo "</form>";
?>
