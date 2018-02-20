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

	$nombre_cab_gestion=nombreGestion($rpt_gestion);
	$nombreTerritorio=nombreTerritorio($rpt_territorio);
	$nombreVisitador=nombreVisitador($rpt_visitador);
	
	echo "<html><body onload='totales();'>";

	echo "<center><table border='0' class='textotit'><tr><th>Parrillas para Verificacion de la Distribucion 
	<br>Gestión: $nombre_cab_gestion Ciclo: $rpt_ciclo<br>Territorio: $nombreTerritorio  Visitador: $nombreVisitador
	</th></tr></table></center><br>";

	//sacamos las especialidades que hace el visitador
	
	$sqlEspe="select distinct(rd.`cod_especialidad`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm, 
	`rutero_maestro_detalle_aprobado` rd where rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and  
	rc.`codigo_gestion`=$rpt_gestion and rc.`codigo_ciclo`=$rpt_ciclo and rc.`codigo_linea`=$rpt_linea and rc.`cod_visitador`=$rpt_visitador";

	$respEspe=mysql_query($sqlEspe);
	$txtCodEspe="";
	while($datEspe=mysql_fetch_array($respEspe)){
		$codEspe=$datEspe[0];
		$txtCodEspe.="'".$codEspe."'".",";
	}
	
	$tamCad=strlen($txtCodEspe);
	$txtCodEspe=substr($txtCodEspe,0,$tamCad-1);

	$sql="select codigo_parrilla, cod_especialidad, categoria_med, agencia, codigo_l_visita from parrilla 
	where cod_ciclo='$rpt_ciclo' and codigo_gestion='$rpt_gestion' and codigo_linea='$rpt_linea'
	and agencia='$rpt_territorio' and cod_especialidad in ($txtCodEspe) group BY cod_especialidad, categoria_med, codigo_l_visita";	
 
	$resp=mysql_query($sql);
	$filas=mysql_num_rows($resp);
	
	echo "<center><table border='1' class='textomini' cellspacing='0' width='80%' id='main'>";
	echo "<tr><th>Especialidad</th><th>Categoria</th><th>Nro. Medicos</th><th>Parrilla Promocional</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_parrilla=$dat[0];
		$cod_espe=$dat[1];
		$cod_cat=$dat[2];
		$agencia=$dat[3];
		$cod_lineavisita=$dat[4];
		
		//sacamos el numero de medicos para la espe y cat
		$sqlNroMed="select count(distinct(rd.`cod_med`)) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm, 
		`rutero_maestro_detalle_aprobado` rd where rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and  
		rc.`codigo_gestion`=$rpt_gestion and rc.`codigo_ciclo`=$rpt_ciclo and rc.`codigo_linea`=$rpt_linea and 
		rc.`cod_visitador`=$rpt_visitador and rd.cod_especialidad='$cod_espe' and rd.categoria_med='$cod_cat'";
		$respNroMed=mysql_query($sqlNroMed);
		$filasNroMed=mysql_num_rows($respNroMed);
		if($filasNroMed>0){
			$nroMedicos=mysql_result($respNroMed,0,0);
		}
		
		$sql_nombre_lineavisita="select nombre_l_visita from lineas_visita where codigo_l_visita='$cod_lineavisita'";
		$resp_lineavisita=mysql_query($sql_nombre_lineavisita);
		$dat_lineavisita=mysql_fetch_array($resp_lineavisita);
		$nombre_lineavisita=$dat_lineavisita[0];

		$sql1="SELECT pd.codigo_muestra, SUM(pd.cantidad_muestra), concat(m.descripcion,' ',m.presentacion) from parrilla p, parrilla_detalle pd, muestras_medicas m where 
		p.codigo_parrilla=pd.codigo_parrilla and pd.codigo_muestra=m.codigo 
		and p.cod_especialidad='$cod_espe' and p.categoria_med='$cod_cat' and p.agencia='$agencia' 
		and p.codigo_l_visita='$cod_lineavisita' and p.cod_ciclo='$rpt_ciclo' and p.codigo_gestion='$rpt_gestion' 
		and p.codigo_linea='$rpt_linea' group by codigo_muestra order by 3";
		
		$resp1=mysql_query($sql1);
		$parrilla_medica="<table class='textomini' width='100%' border='0'>";
		$parrilla_medica=$parrilla_medica."<tr><th>Producto</th><th>Cantidad</th><th>Cantidad Total</th></tr>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$codigo_muestra=$dat1[0];
			$cantidad_muestra=$dat1[1];
			$nombre_muestra=$dat1[2];

			$cantidadTotalMedico=$cantidad_muestra*$nroMedicos;
			$parrilla_medica=$parrilla_medica."<tr><td width='50%'>$nombre_muestra</td><td width='25%' align='center'>$cantidad_muestra</td><td width='25%' align='center'>$cantidadTotalMedico</td></tr>";
		}
		$parrilla_medica=$parrilla_medica."</table>";
		echo "<tr><th>$cod_espe</th><th>$cod_cat</th><th>$nroMedicos</th>
		<td>$parrilla_medica</td></tr>";
	}
	echo "</table></center><br>";
	
	$sqlMedEspecial="select distinct(rd.`cod_med`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm, 
	`rutero_maestro_detalle_aprobado` rd where rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and  
	rc.`codigo_gestion`=$rpt_gestion and rc.`codigo_ciclo`=$rpt_ciclo and rc.`codigo_linea`=$rpt_linea and rc.`cod_visitador`=$rpt_visitador";
	$respMedEspecial=mysql_query($sqlMedEspecial);
	$txtCodMedEsp="";
	while($datMedEspecial=mysql_fetch_array($respMedEspecial)){
		$codMedEsp=$datMedEspecial[0];
		$txtCodMedEsp.=$codMedEsp.",";
	}
	$txtCodMedEsp=substr($txtCodMedEsp,0,strlen($txtCodMedEsp)-1);

	echo "<table border=1 width='80%' align='center' class='texto' cellspacing=0 cellpaddin=0>";
	echo "<tr><th>Grupo Especial</th><th>Nro Medicos</th><th>Parrilla Especial</th></tr>";
	$sqlGrupoEsp="select gd.`codigo_grupo_especial`, count(gd.`cod_med`), g.`nombre_grupo_especial` 
	from `grupo_especial_detalle` gd, `grupo_especial` g 
	where gd.`codigo_grupo_especial`=g.`codigo_grupo_especial` and 
	cod_med in ($txtCodMedEsp) group by g.`codigo_grupo_especial`;";
	$respGrupoEsp=mysql_query($sqlGrupoEsp);
	while($datGrupoEsp=mysql_fetch_array($respGrupoEsp)){
		$codGrupoEsp=$datGrupoEsp[0];
		$cantMedEsp=$datGrupoEsp[1];
		$nombreGrupoEsp=$datGrupoEsp[2];
		
		
		$sql1="SELECT pd.codigo_muestra, SUM(pd.cantidad_muestra), concat(m.descripcion, ' ', m.presentacion) 
		from `parrilla_especial` p, `parrilla_detalle_especial` pd, muestras_medicas m 
		where p.`codigo_parrilla_especial` = pd.`codigo_parrilla_especial` and 
		pd.codigo_muestra = m.codigo and p.cod_ciclo = '$rpt_ciclo' and p.codigo_gestion = '$rpt_gestion' and 
		p.`codigo_grupo_especial`=$codGrupoEsp group by codigo_muestra order by 3";
		
		$resp1=mysql_query($sql1);
		$parrilla_medica="<table class='textomini' width='100%' border='0'>";
		$parrilla_medica=$parrilla_medica."<tr><th width='50%'>Producto</th><th width='25%'>Cantidad</th><th width='25%'>Cantidad Total</th></tr>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$codigo_muestra=$dat1[0];
			$cantidad_muestra=$dat1[1];
			$nombre_muestra=$dat1[2];

			$cantidadTotalMedico=$cantidad_muestra*$cantMedEsp;
			$parrilla_medica=$parrilla_medica."<tr><td>$nombre_muestra</td><td align='center'>$cantidad_muestra</td><td align='center'>$cantidadTotalMedico</td></tr>";
		}
		$parrilla_medica=$parrilla_medica."</table>";
		echo "<tr><th>$nombreGrupoEsp</th><th>$cantMedEsp</th>
		<td>$parrilla_medica</td></tr>";		
	}
echo "<tr><TH>TOTALES</TH></tr>";
echo "</table>";

	
?>