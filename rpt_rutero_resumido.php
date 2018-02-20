<?php
require("conexion.inc");
require("estilos_reportes.inc");
$bandera=0;
if($semana=="")
{	echo "<center><table border='0' class='textotit'><tr><th>Rutero Resumido</th></tr></table></center><br>";
	for($numero_semana=1;$numero_semana<=4;$numero_semana++)
	{	$sql="select r.dia_contacto, r.cod_contacto, r.turno 
		from rutero r, orden_dias o where r.dia_contacto=o.dia_contacto and r.dia_contacto like '%$numero_semana'and r.cod_ciclo='$ciclo_global' and r.codigo_linea='$global_linea' and r.cod_visitador='$global_visitador' order by o.id, r.turno";
		$resp=mysql_query($sql);
		$filas=mysql_num_rows($resp);
		echo "<center><table border='1' class='textosupermini' width='180%' cellspacing='0'>";
		echo "<tr><th colspan=5 bgcolor='#AAAAAA'>Semana $numero_semana</th></tr><tr>";
		while($dat=mysql_fetch_array($resp))
		{
			$cod_contacto=$dat[1];
			$dia_contacto=$dat[0];
			$turno=$dat[2];
			$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' order by orden_visita";
			$resp_detalle=mysql_query($sql_detalle);
			$filas_detalle=mysql_num_rows($resp_detalle);
			$sql_verifica="select turno from rutero where dia_contacto='$dia_contacto' and cod_ciclo='$ciclo_global' and cod_visitador='$global_visitador'";
			$resp_verifica=mysql_query($sql_verifica);
			$filas_verifica=mysql_num_rows($resp_verifica);
			if($filas_verifica!=2)
			{	$datos_verifica=mysql_fetch_array($resp_verifica);
				$turno_excepcion=$datos_verifica[0];
			}
			if($filas_verifica!=2 and $turno_excepcion=='Pm')
			{	$dia_rutero="";
				$dia_rutero.="<table border=1 width='100%' class='textosupermini'>";
				$dia_rutero.="<tr><th colspan='4' bgcolor='#DDDDDD'>$dia_contacto Am</th></tr><tr class='textosupermini'><th>O.V.</th><th>Medico</th><th>Esp.</th><th>Cat</th></tr>";
				for($jj=0;$jj<=12;$jj++)
				{	$dia_rutero.="<tr><td>&nbsp;</td><td></td><td></td><td></td></tr>";
				}
			}
			if($turno=="Am")
			{	$dia_rutero="";
				$dia_rutero.="<table border=1 width='100%' class='textosupermini'>";
			}
			$dia_rutero.="<tr><th colspan='4' bgcolor='#DDDDDD'>$dia_contacto $turno</th></tr><tr class='textosupermini'><th>O.V.</th><th>Medico</th><th>Esp.</th><th>Cat</th></tr>";
			while($dat_detalle=mysql_fetch_array($resp_detalle))
			{	$orden_visita=$dat_detalle[0];
				$cod_med=$dat_detalle[1];
				$cod_especialidad=$dat_detalle[2];
				$categoria=$dat_detalle[3];
				$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
				$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
				$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
				$dia_rutero.="<tr><td align='center' width='15%'>$orden_visita</td><td width='55%'>$nombre_medico</td><td align='center' width='15%'>$cod_especialidad</td><td align='center' width='15%'>$categoria</td></tr>";
			}
			for($ii=$orden_visita;$ii<=12;$ii++)
			{	$dia_rutero.="<tr><td>&nbsp;</td><td></td><td></td><td></td></tr>";
			}
			if($filas_verifica!=2 and $turno_excepcion=='Am')
			{	$dia_rutero.="<tr><th colspan='4' bgcolor='#DDDDDD'>$dia_contacto Pm</th></tr><tr class='textosupermini'><th>O.V.</th><th>Medico</th><th>Esp.</th><th>Cat</th></tr>";
				for($jj=0;$jj<=12;$jj++)
				{	$dia_rutero.="<tr><td>&nbsp;</td><td></td><td></td><td></td></tr>";
				}
				$dia_rutero.="</table>";
				echo "<td align='center'>$dia_rutero</td>";
			}
			if($turno=="Pm")
			{	$dia_rutero.="</table>";
				echo "<td align='center'>$dia_rutero</td>";
			}		
		}
		echo "</tr></table></center>";
		$bandera=1;
	}
}
if($semana!="")
{	echo "<center><table border='0' class='textotit'><tr><th>Rutero Resumido</th></tr></table></center><br>";
	$numero_semana=$semana;
	$sql="select r.dia_contacto, r.cod_contacto, r.turno from rutero r, orden_dias o where r.dia_contacto=o.dia_contacto and r.dia_contacto like '%$numero_semana'and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador' order by o.id, r.turno";
	$resp=mysql_query($sql);
	echo "<center><table border='1' class='textosupermini' width='160%' cellspacing='0'>";
	echo "<tr><th colspan=5 bgcolor='#AAAAAA'>Semana $numero_semana</th></tr><tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[1];
		$dia_contacto=$dat[0];
		$turno=$dat[2];
		$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' order by orden_visita";
		$resp_detalle=mysql_query($sql_detalle);
		$filas_detalle=mysql_num_rows($resp_detalle);
		if($turno=="Am")
		{	$dia_rutero="";
			$dia_rutero.="<table border=1 width='100%' class='textosupermini'>";
		}
		$dia_rutero.="<tr><th colspan='4' bgcolor='#DDDDDD'>$dia_contacto $turno</th></tr><tr class='textosupermini'><th>O. V.</th><th>Medico</th><th>Esp.</th><th>Cat.</th></tr>";
		while($dat_detalle=mysql_fetch_array($resp_detalle))
		{	$orden_visita=$dat_detalle[0];
			$cod_med=$dat_detalle[1];
			$cod_especialidad=$dat_detalle[2];
			$categoria=$dat_detalle[3];
			$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
			$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
			$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
			$dia_rutero.="<tr><td align='center' width='20%'>$orden_visita</td><td width='60%'>$nombre_medico</td><td align='center' width='20%'>$cod_especialidad</td><td align='center'>$categoria</td></tr>";
		}
		for($ii=$orden_visita;$ii<=8;$ii++)
		{	$dia_rutero.="<tr><td>&nbsp;</td><td></td><td></td><td></td></tr>";
		}
		if($turno=="Pm")
		{	$dia_rutero.="</table>";
			echo "<td align='center'>$dia_rutero</td>";
		}		
	}
	echo "</tr></table></center>";
	$bandera=1;
}
echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>