<?php  
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");

$funcionario = $_GET['funcionario'];
$ciclo       = $_GET['ciclo'];
$gestion     = $_GET['gestion'];

$sql_ciudad = mysql_query("SELECT cod_ciudad from funcionarios where codigo_funcionario = $funcionario");
$ciudad = mysql_result($sql_ciudad, 0, 0);

$sql_medicos = mysql_query("SELECT DISTINCT rmd.cod_med, rmd.cod_especialidad, rmd.categoria_med  from rutero_maestro_cab_aprobado rma, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd where rma.cod_rutero = rm.cod_rutero and rm.cod_contacto = rmd.cod_contacto and rma.codigo_ciclo =$ciclo and rma.codigo_gestion = $gestion and rma.cod_visitador = $funcionario ORDER BY 1");

while ($sql_medicos = mysql_fetch_array($sql_medicos)) {
	
}

?>	