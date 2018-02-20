<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls");
$global_agencia=$rpt_territorio;
require("conexion.inc");
require("estilos_reportes_central_xls.inc");
	if($global_agencia==0)
	{	$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
		$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
		$nombre_cab_gestion=$datos_cab_gestion[0];
		echo "<center><table border='0' class='textotit'><tr><th>Medicos x Especialidad<br>Todos los territorios</th></tr></table></center><br>";
		$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp=mysql_query($sql);
		echo "<center><table border='1' class='texto' cellspacing='0' width='100%'>";
		echo "<tr><th>Especialidad</th>";
		$sql_ciudades="select cod_ciudad, descripcion from ciudades where cod_ciudad!='115' order by descripcion";
		$resp_ciudades=mysql_query($sql_ciudades);
		while($dat_ciudades=mysql_fetch_array($resp_ciudades))
		{	$nombre_territorio=$dat_ciudades[1];
			echo "<th class='textomini'>$nombre_territorio";
		}
		echo "<th>TOTAL</th></tr>";
		while($dat=mysql_fetch_array($resp))
		{
			$cod_espe=$dat[0];
			$nom_espe=$dat[1];
			echo "<tr><td align='left'>$nom_espe</td>";
			$sql_ciudades="select cod_ciudad, descripcion from ciudades where cod_ciudad!='115' order by descripcion";
			$resp_ciudades=mysql_query($sql_ciudades);
			$total_med_espe=0;
			while($dat_ciudades=mysql_fetch_array($resp_ciudades))
			{	$rpt_territorio=$dat_ciudades[0];
				$sql_cantidad_medicos="select m.cod_med from medicos m, especialidades_medicos e where e.cod_med=m.cod_med and m.cod_ciudad='$rpt_territorio' and e.cod_especialidad='$cod_espe'";
				$resp_cantidad_medicos=mysql_query($sql_cantidad_medicos);
				$cant_medicos=mysql_num_rows($resp_cantidad_medicos);
				$total_med_espe=$total_med_espe+$cant_medicos;
				echo "<td align='right'>$cant_medicos</td>";
			}
			echo "<td align='right'>$total_med_espe</td></tr>";
		}
		$sql_ciudades="select cod_ciudad, descripcion from ciudades where cod_ciudad!='115' order by descripcion";
		$resp_ciudades=mysql_query($sql_ciudades);
		echo "<tr><td align='left'>Totales por ciudad</td>";
		$total_nacional=0;
		while($dat_ciudades=mysql_fetch_array($resp_ciudades))
		{	$rpt_territorio=$dat_ciudades[0];
			$sql_totales="select m.cod_med from medicos m, especialidades_medicos e where e.cod_med=m.cod_med and m.cod_ciudad='$rpt_territorio'";
			$resp_totales=mysql_query($sql_totales);
			$totales_ciudad=mysql_num_rows($resp_totales);
			$total_nacional=$total_nacional+$totales_ciudad;
			echo "<td align='right'>$totales_ciudad</td>";
		}
		echo "<td align='right'>$total_nacional</td></tr>";
		echo "</table></center>";
	}
	else
	{	$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
		$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
		$nombre_cab_gestion=$datos_cab_gestion[0];
		$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
		$dato=mysql_fetch_array($resp1);
		$nombre_territorio=$dato[1];	
		echo "<center><table border='0' class='textotit'><tr><th>Medicos x Especialidad<br>Territorio: $nombre_territorio</th></tr></table></center><br>";
		$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp=mysql_query($sql);
		echo "<center><table border='1' class='texto' cellspacing='0' width='35%'>";
		echo "<tr><th>Especialidad</th><th>Cantidad Medicos</th></tr>";
		$total_medicos=0;
		while($dat=mysql_fetch_array($resp))
		{
			$cod_espe=$dat[0];
			$nom_espe=$dat[1];
			$sql_cantidad_medicos="select m.cod_med from medicos m, especialidades_medicos e where e.cod_med=m.cod_med and m.cod_ciudad='$rpt_territorio' and e.cod_especialidad='$cod_espe'";
			$resp_cantidad_medicos=mysql_query($sql_cantidad_medicos);
			$cant_medicos=mysql_num_rows($resp_cantidad_medicos);
			if($cant_medicos!=0)
			{	echo "<tr><td>&nbsp;&nbsp;$nom_espe</td><td align='center'><table border=0 class='texto' width='30%'><tr><td align='right'>$cant_medicos</td></tr></table></td></tr>";
				$total_medicos=$total_medicos+$cant_medicos;
			}
		}
		echo "<tr><th align='left'>&nbsp;&nbsp;TOTAL</td><td align='center'><table border=0 class='texto' width='30%'><tr><td align='right'>$total_medicos</td></tr></table></td></tr>";
		echo "</table></center>";
	}	
?>