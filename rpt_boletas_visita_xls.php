<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
 require("conexion.inc");
 require('estilos_reportes_administracion.inc');
 $sql_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
 $resp_territorio=mysql_query($sql_territorio);
 $dat_territorio=mysql_fetch_array($resp_territorio);
 $nombre_territorio=$dat_territorio[0];
 echo "<table align='center' class='textotit'><tr><th>Reporte Boletas de Visita</th></tr></table><br>";
 $sql_visitadores="select fl.codigo_funcionario, f.paterno, f.materno, f.nombres, fl.codigo_linea 
 from funcionarios_lineas fl, funcionarios f 
 where f.codigo_funcionario=fl.codigo_funcionario and f.cod_cargo=1011 and f.estado='1' and f.cod_ciudad='$rpt_territorio'";
 $resp_visitadores=mysql_query($sql_visitadores);
 while($dat_visitadores=mysql_fetch_array($resp_visitadores))
 {	$codigo_funcionario=$dat_visitadores[0];
 	$nombre_funcionario="$dat_visitadores[1] $dat_visitadores[2] $dat_visitadores[3]";
	$linea_funcionario=$dat_visitadores[4];
	$sql_linea="select nombre_linea from lineas where codigo_linea='$linea_funcionario'";
	$resp_linea=mysql_query($sql_linea);
	$dat_linea=mysql_fetch_array($resp_linea);
	$nombre_linea=$dat_linea[0];
	//desde aqui va el rutero maestro aprobado
	$sql="select r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from rutero_maestro r, rutero_maestro_cab rmc, orden_dias o where rmc.cod_rutero=r.cod_rutero and rmc.estado_aprobado=1 and r.cod_visitador='$codigo_funcionario' and r.dia_contacto=o.dia_contacto and rmc.codigo_linea='$linea_funcionario' order by o.id";
	$resp=mysql_query($sql);
	$filas_rutero=mysql_num_rows($resp);
	//echo "<h2>$nombre_funcionario $linea_funcionario</h2>";
		//saca el nombre del rutero maestro
		$sql_nom_rutero=mysql_query("select nombre_rutero from rutero_maestro_cab where cod_rutero='$rutero' and cod_visitador='$global_visitador'");
		$dat_nom_rutero=mysql_fetch_array($sql_nom_rutero);
		$nombre_rutero=$dat_nom_rutero[0];
		//fin sacar nombre
	$sql_cantidad_total_boletas="select rd.cod_contacto from rutero_maestro r, rutero_maestro_cab rmc, rutero_maestro_detalle rd where rmc.cod_rutero=r.cod_rutero and rmc.estado_aprobado=1 and r.cod_visitador='$codigo_funcionario' and rmc.codigo_linea='$linea_funcionario' and r.cod_contacto=rd.cod_contacto";
	$resp_cantidad_total_boletas=mysql_query($sql_cantidad_total_boletas);
	$cantidad_total_boletas=mysql_num_rows($resp_cantidad_total_boletas);
	$indice_boleta=1;
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$cod_ciclo=$dat[1];
		$dia_contacto=$dat[3];
		$turno=$dat[4];
		$zona_de_viaje=$dat[5];
		if($zona_de_viaje==1)
		{	$fondo_fila="#FFD8BF";
		}
		else
		{	$fondo_fila="";
		}
		$sql1="select c.orden_visita, m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, c.estado, m.telf_med
				from rutero_maestro_detalle c, medicos m, direcciones_medicos d
					where (c.cod_contacto=$cod_contacto) and (c.cod_visitador=$codigo_funcionario) and (c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and (c.cod_zona=d.numero_direccion) order by c.orden_visita";
		$resp1=mysql_query($sql1);
		$contacto="<table class='textomini' width='100%'>";
		$contacto=$contacto."<tr><th width='5%'>Orden</th><th width='35%'>Medico</th><th width='10%'>Especialidad</th><th width='10%'>Categoria</th><th width='30%'>Direccion</th></tr>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$orden_visita=$dat1[0];
			$codigo_medico=$dat1[1];
			$pat=$dat1[2];
			$mat=$dat1[3];
			$nombre=$dat1[4];
			$sql_dir="select direccion, descripcion from direcciones_medicos where cod_med=$codigo_medico order by descripcion asc";
			$resp_dir=mysql_query($sql_dir);
			$direccion_medico="<table border=0 class='textomini'>";
			while($dat_dir=mysql_fetch_array($resp_dir))
			{
				$dir=$dat_dir[0];
				$desc_dir=$dat_dir[1];
				$direccion_medico="$direccion_medico<tr><td align='left'>$dir</td></tr>";
			}
			$direccion_medico="$direccion_medico</table>";
			$direccion=$dat1[5];
			$nombre_medico="$pat $mat $nombre";
			$espe=$dat1[6];
			$cat=$dat1[7];
			$telefono_medico=$dat1[9];
			//saca el numero de visita
			$sql_numero_visita="select distinct(o.id), rmd.cod_contacto, rmd.orden_visita from orden_dias o, rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd
			where rm.cod_contacto=rmd.cod_contacto and rmc.cod_rutero=rm.cod_rutero and rmc.estado_aprobado=1 and rmc.codigo_linea='$linea_funcionario' and rm.cod_visitador='$codigo_funcionario' and rmd.cod_med='$codigo_medico' 
			and o.dia_contacto=rm.dia_contacto order by o.id";
			$resp_numero_visita=mysql_query($sql_numero_visita);
			$indice_visita=1;
			while($dat_numero_visita=mysql_fetch_array($resp_numero_visita))
			{	$contacto_sistema=$dat_numero_visita[1];
				$orden_visita_sistema=$dat_numero_visita[2];
				if($contacto_sistema==$cod_contacto and $orden_visita_sistema==$orden_visita)
				{	$numero_visita=$indice_visita;
				}
				$indice_visita++;
			}
			//SACAMOS LA PARRILLA PARA EL NUMERO DE VISITA
			$sql_parrilla="select * from parrilla where codigo_linea='$linea_funcionario' and cod_ciclo='$ciclo_rpt' and cod_especialidad='$espe' and categoria_med='$cat' and numero_visita='$numero_visita' order by numero_visita";
			$resp_parrilla=mysql_query($sql_parrilla);
			while($dat_parrilla=mysql_fetch_array($resp_parrilla))
			{
				$cod_parrilla=$dat_parrilla[0];
				$cod_ciclo=$dat_parrilla[1];
				$cod_espe=$dat_parrilla[2];
				$cod_cat=$dat_parrilla[3];
				$numero_de_visita=$dat_parrilla[7];
				$agencia=$dat_parrilla[8];
				$sql_parrilla_detalle="select m.descripcion, m.presentacion, p.cantidad_muestra, mm.descripcion_material, p.cantidad_material, p.observaciones
						from muestras_medicas m, parrilla_detalle p, material_apoyo mm
      						where p.codigo_parrilla=$cod_parrilla and m.codigo=p.codigo_muestra and mm.codigo_material=p.codigo_material order by p.prioridad";
				$resp_parrilla_detalle=mysql_query($sql_parrilla_detalle);
				$filas_parrilla=mysql_num_rows($resp_parrilla_detalle);
				$parrilla_medica="<table class='textomini' width='100%' border='1'>";
				$parrilla_medica=$parrilla_medica."<tr><th colspan='2'>Productos</th><th colspan='2'>Material de Apoyo</th><th>Observaciones de PMA(*)</th></tr>";
				while($dat_parrilla_detalle=mysql_fetch_array($resp_parrilla_detalle))
				{
					$muestra="$dat_parrilla_detalle[0] $dat_parrilla_detalle[1]";
					$cant_muestra=$dat_parrilla_detalle[2];
					$material=$dat_parrilla_detalle[3];
					$cant_material=$dat_parrilla_detalle[4];
					$parrilla_medica=$parrilla_medica."<tr><td width='30%'>$muestra</td><td width='2%'>$cant_muestra</td><td width='30%'>$material</td><td width='2%'>$cant_material</td><td align='center' width='36%'>&nbsp;</td></tr>";
				}
				for($ii=$filas_parrilla;$ii<8;$ii++)
				{	$parrilla_medica=$parrilla_medica."<tr><td width='30%'>&nbsp;</td><td width='2%'>&nbsp;</td><td width='30%'>&nbsp;</td><td width='2%'>&nbsp;</td><td align='center' width='36%'>&nbsp;</td></tr>";
				}
				$parrilla_medica=$parrilla_medica."</table>";
			}
			//FIN SACAR PARRILLA
			//fin sacar numero visita
			$cabecera_izq="<table border='0' class='textomini' width='100%'><tr><td width='50%'>Gestión: $gestion_rpt</td><td width='50%'>Ciclo: $ciclo_rpt</td></tr>";
			$cabecera_izq="$cabecera_izq<tr><td width='50%'>Territorio: $nombre_territorio </td><td width='50%'>Línea: $nombre_linea</td></tr>";
			$cabecera_izq="$cabecera_izq<tr><td colspan=2>Visitador: $nombre_funcionario</td></tr>";
			$cabecera_izq="$cabecera_izq<tr><td colspan=2>Número de Boleta: $indice_boleta de $cantidad_total_boletas</td></tr></table>";
			$cabecera_der="<table border='0' class='textomini' width='100%'><tr><td>Medico:</td><td>$nombre_medico</td></tr>";
			$cabecera_der="$cabecera_der<tr><td>Direcciones:</td><td>$direccion_medico</td></tr>";
			$cabecera_der="$cabecera_der<tr><td>Teléfono: </td><td>$telefono_medico </td></tr>";
			$cabecera_der="$cabecera_der<tr><td>Especialidad: </td><td>$espe</td></tr></table>";
			echo "<table class='textomini' width='100%' border='1' align='center'>";
			echo "<tr><td width='40%'>$cabecera_izq</td><td width='40%'>$cabecera_der</td><td width='20%' align='center'><img src='imagenes/logo_cofar.png'></td></tr>";
			echo "<tr><td colspan='3'>$parrilla_medica</td></tr>";
			echo "<tr><td>Comentarios:<br><br></td><td>Fecha de Visita:<br><br></td><td>Sello y firma Medico<br><br></td></tr>";
			echo "<tr><td colspan='3'>(*)PMA: Producto y/o Material de Apoyo.</td></tr>";
			echo "</table><br><br>";	
			$indice_boleta++;
			
		}
						
	}	
	//fin rutero maestro aprobado
 }
?>