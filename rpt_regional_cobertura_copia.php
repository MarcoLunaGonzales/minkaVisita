<?php
$global_visitador=$visitador;
require("conexion.inc");
require("estilos_reportes_regional.inc");
	$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
	$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
	$nombre_cab_gestion=$datos_cab_gestion[0];
if($visitador=="")
{	$sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres
	from funcionarios f, funcionarios_lineas fl where f.cod_ciudad='$global_agencia' and f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and f.cod_cargo='1011' order by paterno, materno";
	$resp_visitador=mysql_query($sql_visitador);
	echo "<center><table border='0' class='textotit'><tr><th>Cobertura Visitadores Medicos<br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' width='80%' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Visitador</th><th>Total Contactos</th><th>Total Ejecutado</th><th>Cobertura</th></tr>";
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
		$sql_contactos_maestro="select count(rd.cod_contacto) from rutero_detalle_utilizado rd, rutero_utilizado r 
		where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$codigo_visitador' and r.cod_contacto=rd.cod_contacto";
		$resp_contactos_maestro=mysql_query($sql_contactos_maestro);
		$dat_maestro=mysql_fetch_array($resp_contactos_maestro);
		$numero_contactos_maestro=$dat_maestro[0];
	
		$sql_contactos_ejecutado="select count(rd.cod_contacto) from rutero_detalle rd, rutero r 
			where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$codigo_visitador' and r.cod_contacto=rd.cod_contacto and rd.estado='1'";
		$resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
		$dat_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
		$numero_contactos_ejecutado=$dat_ejecutado[0];		
		$cobertura_visitador=($numero_contactos_ejecutado/$numero_contactos_maestro)*100;
		$cobertura_visitador=round($cobertura_visitador);
		echo "<tr><td align='center'>$indice_tabla</td><td>$nombre_visitador</td><td align='center'>$numero_contactos_maestro</td><td align='center'>$numero_contactos_ejecutado</td><td align='center'>$cobertura_visitador %</td></tr>";
		$indice_tabla++;
	}	
	echo "</table></center><br>";
}
else
{	$sql_visitador="select paterno, materno, nombres
	from funcionarios f	where codigo_funcionario='$global_visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	echo "<center><table border='0' class='textotit'><tr><th>Cobertura Visitadores Medicos<br>Visitador $nombre_visitador Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' width='80%' cellspacing='0'>";
	echo "<tr><th>Total Contactos</th><th>Total Ejecutado</th><th>Cobertura</th></tr>";
	$sql_contactos_maestro="select count(rd.cod_contacto) from rutero_detalle_utilizado rd, rutero_utilizado r 
		where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$global_visitador' and r.cod_contacto=rd.cod_contacto";
	$resp_contactos_maestro=mysql_query($sql_contactos_maestro);
	$dat_maestro=mysql_fetch_array($resp_contactos_maestro);
	$numero_contactos_maestro=$dat_maestro[0];
	$sql_contactos_ejecutado="select count(rd.cod_contacto) from rutero_detalle rd, rutero r 
		where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$global_visitador' and r.cod_contacto=rd.cod_contacto and rd.estado='1'";
	$resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
	$dat_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
	$numero_contactos_ejecutado=$dat_ejecutado[0];		
	$cobertura_visitador=($numero_contactos_ejecutado/$numero_contactos_maestro)*100;
	$cobertura_visitador=round($cobertura_visitador);
	echo "<tr><td align='center'>$numero_contactos_maestro</td><td align='center'>$numero_contactos_ejecutado</td><td align='center'>$cobertura_visitador %</td></tr>";

}
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>