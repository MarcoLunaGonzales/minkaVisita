<?php
$cod_territorio=$global_territorio;
require("conexion.inc");
if($global_usuario==1032)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
$vector=explode(",",$vector_campos);
$n=sizeof($vector);
for($i=1;$i<=$n;$i++)
{	list($cod_visitador,$cod_producto,$cant_a_distribuir)=explode("|",$vector[$i]);
	//echo "$cod_visitador $cod_producto $cant_a_distribuir<br>";
	$sql_cantidades="select cantidad_planificada, cantidad_distribuida from distribucion_productos_visitadores
	where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$cod_territorio' and
	codigo_linea='$global_linea' and codigo_producto='$cod_producto' and cod_visitador='$cod_visitador'";
	$resp_cantidades=mysql_query($sql_cantidades);
	$dat_cantidades=mysql_fetch_array($resp_cantidades);
	$cant_plan=$dat_cantidades[0];
	$cant_dist=$dat_cantidades[1];
	$maximo_a_insertar=$cant_plan-$cant_dist;
	//$cant_a_insertar=$maximo_a_insertar+$cant_dist;
	$cant_a_insertar=$cant_a_distribuir+$cant_dist;
	$sql_cantidades="update distribucion_productos_visitadores set cantidad_distribuida='$cant_a_insertar'
	where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$cod_territorio' and
	codigo_linea='$global_linea' and codigo_producto='$cod_producto' and cod_visitador='$cod_visitador'";
	$resp_cantidades=mysql_query($sql_cantidades);
}
/*echo "<script language='JavaScript'>
		alert('Los datos se guardaron correctamente.');
		location.href='registro_distribucion_lineasterritorios1.php?global_linea=$global_linea';
</script>";*/
?>