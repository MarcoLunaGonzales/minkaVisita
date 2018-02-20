<?php
require("conexion.inc");
require("estilos_reportes.inc");
$bandera=0;
if($semana=="" && $dia_contacto=="")
{	$sql="select r.cod_contacto, r.dia_contacto, r.turno from rutero r, orden_dias o where r.dia_contacto=o.dia_contacto and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador' order by o.id";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Contactos Realizados</th></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Dia Contacto</th><th>Medicos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$dia_contacto=$dat[1];
		$turno=$dat[2];
		$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' and estado='1' order by orden_visita";
		$resp_detalle=mysql_query($sql_detalle);
		$filas_detalle=mysql_num_rows($resp_detalle);
		if($filas_detalle!=0)
		{	$medicos_visitados="";
			$medicos_visitados.="<table border=1 width='100%' class='texto'><tr><th width='10%'>Orden Visita</th><th width='50%'>Medico</th><th width='20%'>Especialidad</th><th width='20%'>Categoria</th></tr>";
			while($dat_detalle=mysql_fetch_array($resp_detalle))
			{	$orden_visita=$dat_detalle[0];
				$cod_med=$dat_detalle[1];
				$cod_especialidad=$dat_detalle[2];
				$categoria=$dat_detalle[3];
				$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
				$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
				$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
				$medicos_visitados.="<tr><td align='center'>$orden_visita</td><td>$nombre_medico</td><td align='center'>$cod_especialidad</td><td align='center'>$categoria</td></tr>";
			}
			$medicos_visitados.="</table>";
			echo "<tr><td align='left'>$dia_contacto $turno</td><td align='center' class='texto'>$medicos_visitados</td></tr>";		
		}
	}
	echo "</table></center><br>";
	$bandera=1;
}
if($semana!="" and $bandera==0)
{	$consulta_semana="(r.dia_contacto='Lunes $semana' or r.dia_contacto='Martes $semana' or r.dia_contacto='Miercoles $semana' or r.dia_contacto='Jueves $semana' or r.dia_contacto='Viernes $semana')";
	$sql="select r.cod_contacto, r.dia_contacto, r.turno from rutero r, orden_dias o where $consulta_semana and r.dia_contacto=o.dia_contacto and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador' order by o.id";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Contactos Realizados<br>Semana $semana</th></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Dia Contacto</th><th>Medicos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$dia_contacto=$dat[1];
		$turno=$dat[2];
		$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' and estado='1' order by orden_visita";
		$resp_detalle=mysql_query($sql_detalle);
		$filas_detalle=mysql_num_rows($resp_detalle);
		if($filas_detalle!=0)
		{	$medicos_visitados="";
			$medicos_visitados.="<table border=1 width='100%' class='texto'><tr><th width='10%'>Orden Visita</th><th width='50%'>Medico</th><th width='20%'>Especialidad</th><th width='20%'>Categoria</th></tr>";
			while($dat_detalle=mysql_fetch_array($resp_detalle))
			{	$orden_visita=$dat_detalle[0];
				$cod_med=$dat_detalle[1];
				$cod_especialidad=$dat_detalle[2];
				$categoria=$dat_detalle[3];
				$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
				$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
				$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
				$medicos_visitados.="<tr><td align='center'>$orden_visita</td><td>$nombre_medico</td><td align='center'>$cod_especialidad</td><td align='center'>$categoria</td></tr>";
			}
			$medicos_visitados.="</table>";
			echo "<tr><td align='left'>$dia_contacto $turno</td><td align='center' class='texto'>$medicos_visitados</td></tr>";		
		}
	}
	echo "</table></center><br>";
	$bandera=1;
}
if($dia_contacto!="" and $bandera==0)
{	$sql="select r.cod_contacto, r.dia_contacto from rutero r, orden_dias o where r.dia_contacto='$dia_contacto' and r.dia_contacto=o.dia_contacto and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador' order by o.id";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Contactos Realizados<br>Dia de Contacto $dia_contacto</th></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Medicos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$dia_contacto=$dat[1];
		$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' and estado='1' order by orden_visita";
		$resp_detalle=mysql_query($sql_detalle);
		$filas_detalle=mysql_num_rows($resp_detalle);
		if($filas_detalle!=0)
		{	$medicos_visitados="";
			$medicos_visitados.="<table border=1 width='100%' class='texto'><tr><th width='10%'>Orden Visita</th><th width='50%'>Medico</th><th width='20%'>Especialidad</th><th width='20%'>Categoria</th></tr>";
			while($dat_detalle=mysql_fetch_array($resp_detalle))
			{	$orden_visita=$dat_detalle[0];
				$cod_med=$dat_detalle[1];
				$cod_especialidad=$dat_detalle[2];
				$categoria=$dat_detalle[3];
				$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
				$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
				$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
				$medicos_visitados.="<tr><td align='center'>$orden_visita</td><td>$nombre_medico</td><td align='center'>$cod_especialidad</td><td align='center'>$categoria</td></tr>";
			}
			$medicos_visitados.="</table>";
			echo "<tr><td align='center' class='texto'>$medicos_visitados</td></tr>";		
		}
	}
	echo "</table></center><br>";
	$bandera=1;
}
echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>