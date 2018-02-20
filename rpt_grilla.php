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
	$codigo_linea=$_GET['rpt_linea'];
	$global_linea=$codigo_linea;
	$rpt_territorio=$_GET['rpt_territorio'];
	$rpt_nombreTerritorio=$_GET['rpt_nombreTerritorio'];
	$nombreLinea=nombreLinea($rpt_linea);
	
	echo "<html><body onLoad='totales();'>";
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><th>Grilla de medicos en Rutero Maestro Ultimo Ciclo Vigente
	<br>Territorio: $rpt_nombreTerritorio<br>Linea: $nombreLinea</th></tr></table><br>";

echo "<table border=1 class='texto' cellspacing='0' id='main'><tr><th>&nbsp;</th><th>&nbsp;</th>";
$sqlTerritorio="select c.`cod_ciudad`, c.`descripcion` from `ciudades` c where c.`cod_ciudad` in ($rpt_territorio)";
$respTerritorio=mysql_query($sqlTerritorio);
while($datTerritorio=mysql_fetch_array($respTerritorio)){
	$codTerritorio=$datTerritorio[0];
	$nombreTerritorio=$datTerritorio[1];
	echo "<th colspan='3'>$nombreTerritorio</th>";
}
echo "<th colspan='3'>Totales</th></tr>
	<tr><th>Especialidad</th><th>Cat.</th>";
$respTerritorio=mysql_query($sqlTerritorio);
while($datTerritorio=mysql_fetch_array($respTerritorio)){
	$codTerritorio=$datTerritorio[0];
	$nombreTerritorio=$datTerritorio[1];
	echo "<th>Frec.</th><th>Cant.Med.</th><th>Cant. Cont.</th>";
}
echo "<th>Total Med.</th><th>Total Cont.</th></tr>";

$sqlEspe="select e.`cod_especialidad`, c.`categoria_med` from `especialidades` e, 
			`categorias_medicos` c order by e.`cod_especialidad`, c.`categoria_med`";
$respEspe=mysql_query($sqlEspe);
while($datEspe=mysql_fetch_array($respEspe)){
	$codEspe=$datEspe[0];
	$codCat=$datEspe[1];
	
	$sqlLineaVisita="select l.`codigo_l_visita`, l.`nombre_l_visita` from `especialidades` e, 
		     `lineas_visita_especialidad` le, `lineas_visita` l where e.`cod_especialidad`=le.`cod_especialidad` and  
		     l.`codigo_linea`=1021 and l.`codigo_l_visita`=le.`codigo_l_visita` and le.`cod_especialidad`='$codEspe'"; 
	$respLineaVisita=mysql_query($sqlLineaVisita);
		
	while($datLineaVisita=mysql_fetch_array($respLineaVisita)){
		$codLineaVisita=$datLineaVisita[0];
		$nombreLineaVisita=$datLineaVisita[1];
		echo "<tr><td>$codEspe ($nombreLineaVisita)</td><td>$codCat</td>";
	
		$respTerritorio=mysql_query($sqlTerritorio);
		$totalMed=0;
		$totalCont=0;
		while($datTerritorio=mysql_fetch_array($respTerritorio)){
			$codTerritorio=$datTerritorio[0];
			$sqlGrilla="select gd.`cod_categoria`, gd.`cod_especialidad`, gd.`frecuencia` from grilla g, `grilla_detalle` gd 
				where g.`codigo_grilla`=gd.`codigo_grilla` and g.`agencia`=$codTerritorio and gd.cod_linea_visita='$codLineaVisita' and
				g.`codigo_linea`=$rpt_linea and g.`estado`=1 and gd.cod_especialidad='$codEspe' and gd.cod_categoria='$codCat'";
			$respGrilla=mysql_query($sqlGrilla);
			$catGrilla=mysql_result($respGrilla,0,0);
			$espeGrilla=mysql_result($respGrilla,0,1);
			$frecGrilla=mysql_result($respGrilla,0,2);
			
			$sql_cantidadmedicos="select count(DISTINCT(cod_med))
				from `rutero_maestro_cab_aprobado` rc,
 	    		`rutero_maestro_aprobado` r,
 	    		`rutero_maestro_detalle_aprobado` rd
				where rc.`cod_rutero` = r.`cod_rutero` and
 	     	r.`cod_contacto` = rd.`cod_contacto` and
 	     	rc.`codigo_gestion` = 1007 and
 	     	rc.`codigo_ciclo` = 4 and
 	     	rd.`cod_especialidad` = '$espeGrilla' and 
 	     	rd.`categoria_med`='$catGrilla' and rc.`cod_visitador` in (select
 	     	 codigo_funcionario from funcionarios where cod_cargo = 1011 and
 	     	  cod_ciudad = $codTerritorio)";
			$resp_cantidadmedicos=mysql_query($sql_cantidadmedicos);
			$numMedicos=mysql_result($resp_cantidadmedicos,0,0);		
			$cantContactos=$frecGrilla*$numMedicos;
			$totalMed=$totalMed+$numMedicos;
			$totalCont=$totalCont+$cantContactos;
			
			echo "<td>$frecGrilla</td><td>$numMedicos</td><td>$cantContactos</td>";
		}
		echo "<td>$totalMed</td><td>$totalCont</td></tr>";
	}	
	
	echo "<tr><td>$codEspe</td><td>$codCat</td>";
	
	$respTerritorio=mysql_query($sqlTerritorio);
	$totalMed=0;
	$totalCont=0;
	while($datTerritorio=mysql_fetch_array($respTerritorio)){
		$codTerritorio=$datTerritorio[0];
		$sqlGrilla="select gd.`cod_categoria`, gd.`cod_especialidad`, gd.`frecuencia` from grilla g, `grilla_detalle` gd 
			where g.`codigo_grilla`=gd.`codigo_grilla` and g.`agencia`=$codTerritorio and 
			g.`codigo_linea`=$rpt_linea and g.`estado`=1 and gd.cod_especialidad='$codEspe' and gd.cod_categoria='$codCat'";
		$respGrilla=mysql_query($sqlGrilla);
		$catGrilla=mysql_result($respGrilla,0,0);
		$espeGrilla=mysql_result($respGrilla,0,1);
		$frecGrilla=mysql_result($respGrilla,0,2);
		
		$sql_cantidadmedicos="select count(DISTINCT(cod_med))
			from `rutero_maestro_cab_aprobado` rc,
     		`rutero_maestro_aprobado` r,
     		`rutero_maestro_detalle_aprobado` rd
			where rc.`cod_rutero` = r.`cod_rutero` and
      	r.`cod_contacto` = rd.`cod_contacto` and
      	rc.`codigo_gestion` = 1007 and
      	rc.`codigo_ciclo` = 4 and
      	rd.`cod_especialidad` = '$espeGrilla' and 
      	rd.`categoria_med`='$catGrilla' and rc.`cod_visitador` in (select
      	 codigo_funcionario from funcionarios where cod_cargo = 1011 and
      	  cod_ciudad = $codTerritorio)";
		$resp_cantidadmedicos=mysql_query($sql_cantidadmedicos);
		$numMedicos=mysql_result($resp_cantidadmedicos,0,0);		
		$cantContactos=$frecGrilla*$numMedicos;
		$totalMed=$totalMed+$numMedicos;
		$totalCont=$totalCont+$cantContactos;
		
		echo "<td>$frecGrilla</td><td>$numMedicos</td><td>$cantContactos</td>";
	}
	echo "<td>$totalMed</td><td>$totalCont</td></tr>";
}
echo "<tr><TH>TOTALES</TH></tr>";
echo "</table>";

$sqlNumVis="select count(*) from funcionarios f, funcionarios_lineas fl 
where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$rpt_linea' and 
f.cod_ciudad='$rpt_territorio' and f.estado=1 and f.cod_cargo=1011";
$respNumVis=mysql_query($sqlNumVis);
$numVis=mysql_result($respNumVis,0,0);
/*	$resp_detalle=mysql_query($sql_det);
	echo "<center><table border='1' cellspacing='0' class='texto' id='main'>";
	echo "<tr><th>Especialidad</th><th>Categoria</th><th>Frecuencia</th><th>Cantidad de Medicos</th><th>Cantidad de Contactos</th></tr>";
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{
		$espe=$dat_detalle[1];
		$cat=$dat_detalle[2];
		$frecuencia=$dat_detalle[3];
		$sql_cantidadmedicos="select c.cod_especialidad, c.categoria_med from categorias_lineas c,medicos m 
			where c.cod_med=m.cod_med and m.cod_ciudad='$rpt_territorio' and c.categoria_med='$cat' and c.cod_especialidad='$espe' 
			and c.codigo_linea=$global_linea";

		$resp_cantidadmedicos=mysql_query($sql_cantidadmedicos);
		$numero_medicos=mysql_num_rows($resp_cantidadmedicos);
		if($frecuencia!=0)
		{	$cantidad_contactos=$numero_medicos*$frecuencia;
			echo "<tr><td align='left'>$espe</td><td align='center'>$cat</td><td align='center'>$frecuencia</td><td align='center'>$numero_medicos</td><td align='center'>$cantidad_contactos</td></tr>";
		}		
	}
	echo "<tr><TH>TOTALES</TH></tr>";
	echo "</table><br>";
	$sqlNumVis="select count(*) from funcionarios f, funcionarios_lineas fl 
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$rpt_linea' and 
	f.cod_ciudad='$rpt_territorio' and f.estado=1 and f.cod_cargo=1011";
	$respNumVis=mysql_query($sqlNumVis);
	$numVis=mysql_result($respNumVis,0,0);*/
		
?>