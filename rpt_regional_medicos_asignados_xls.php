<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 
$global_visitador=$visitador;
require("conexion.inc");
require('estilos_reportes_regional_xls.inc');	
//fin sacar dia contacto actual
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$global_agencia'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
	$sql_visitador="select paterno, materno, nombres
	from funcionarios f	where codigo_funcionario='$global_visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	echo "<center><table border='0' class='textotit'><tr><th>Reporte Medicos Asignados x Especialidades<br>Visitador: <strong>$nombre_visitador</strong></th></tr></table></center><br>";
	$sql_especialidades="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
	$resp_especialidades=mysql_query($sql_especialidades);
	echo "<table class='texto' border=1 cellspacing='0' align='center'><tr><th>Especialidad</th><th>Medicos Cat. A</th><th>Medicos Cat. B</th><th>Sub-Total</th></tr>";
	$suma_medicos_a=0;
	$suma_medicos_b=0;
	while($dat_especialidades=mysql_fetch_array($resp_especialidades))
	{	$cod_especialidad=$dat_especialidades[0];
		$nombre_especialidad=$dat_especialidades[1];
		$sql_medicos_a="select * from categorias_lineas c, medico_asignado_visitador m
						where c.codigo_linea=m.codigo_linea and c.cod_med=m.cod_med and m.codigo_visitador='$visitador' and c.codigo_linea='$global_linea' and c.cod_especialidad='$cod_especialidad' 
						and c.categoria_med='A'";
		$resp_medicos_a=mysql_query($sql_medicos_a);
		$cantidad_medicos_a=mysql_num_rows($resp_medicos_a);
		$suma_medicos_a=$suma_medicos_a+$cantidad_medicos_a;
		$sql_medicos_b="select * from categorias_lineas c, medico_asignado_visitador m
						where c.codigo_linea=m.codigo_linea and c.cod_med=m.cod_med and m.codigo_visitador='$visitador' and c.codigo_linea='$global_linea' and c.cod_especialidad='$cod_especialidad' 
						and c.categoria_med='B'";
		$resp_medicos_b=mysql_query($sql_medicos_b);
		$cantidad_medicos_b=mysql_num_rows($resp_medicos_b);
		$suma_medicos_b=$suma_medicos_b+$cantidad_medicos_b;
		$suma_medicos_espe=$cantidad_medicos_a+$cantidad_medicos_b;
		if($suma_medicos_espe!=0)
		{	echo "<tr><td>$nombre_especialidad</td><td align='right'>$cantidad_medicos_a</td><td align='right'>$cantidad_medicos_b</td><td align='right'>$suma_medicos_espe</td></tr>";
		}		
	}
	$suma_total_medicos=$suma_medicos_a+$suma_medicos_b;
	echo "<tr><th colspan='3'>Total Medicos:</td><th align='right'>$suma_total_medicos</th></tr>";
	echo "</table>";

?>