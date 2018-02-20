<?php
require("conexion.inc");
require("estilos.inc");
//sacamos la agencia del grupoespecial
$grupo_especial=$_GET["grupo_especial"];
$datos=$_GET["datos"];
$ciclo_trabajo=$_GET["ciclo_trabajo"];

$sql="select agencia from grupo_especial where codigo_grupo_especial='$grupo_especial'";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$cod_ciudad=$dat[0];

$vector=explode(",",$datos);
$n=sizeof($vector);
for($i=0;$i<$n;$i++)
{	$codigo_parrilla=$vector[$i];
	$sql="select cod_ciclo, codigo_gestion, cod_especialidad, codigo_linea, numero_visita from parrilla where 
	codigo_parrilla='$codigo_parrilla'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$cod_ciclo=$dat[0];
	$codigo_gestion=$dat[1];
	$cod_especialidad=$dat[2];
	$codigo_linea=$dat[3];
	$numero_visita=$dat[4];
	$fecha_actual=date("Y-m-d");
	//insertamos la parrilla
	$sql_aux=mysql_query("select max(codigo_parrilla_especial) from parrilla_especial");
	$num_filas=mysql_num_rows($sql_aux);
	if($num_filas==0)
	{	$codigo_parrillaespecial=1000;	}
	else
	{	$dat_aux=mysql_fetch_array($sql_aux);
		$codigo_parrillaespecial=$dat_aux[0];
		$codigo_parrillaespecial++;
	}
	$sql_inserta="insert into parrilla_especial values('$codigo_parrillaespecial','$cod_ciclo','$cod_especialidad',
	'$global_linea','$fecha_actual','$fecha_actual','$numero_visita','$cod_ciudad','$grupo_especial','0','$codigo_gestion')";
	//echo $sql_inserta."<br>";
	$resp_inserta=mysql_query($sql_inserta);
	//sacamos el detalle	
	$sql_detalle="select codigo_muestra, cantidad_muestra, codigo_material, cantidad_material, prioridad, observaciones, extra
	from parrilla_detalle where codigo_parrilla=$codigo_parrilla";
	$resp_detalle=mysql_query($sql_detalle);
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$codigo_muestra=$dat_detalle[0];
		$cantidad_muestra=$dat_detalle[1];
		$codigo_material=$dat_detalle[2];
		$cantidad_material=$dat_detalle[3];
		$prioridad=$dat_detalle[4];
		$observaciones=$dat_detalle[5];
		$extra=$dat_detalle[6];
		$sql_inserta_detalle="insert into parrilla_detalle_especial values('$codigo_parrillaespecial','$codigo_muestra',
		'$cantidad_muestra','$codigo_material','$cantidad_material','$prioridad','$observaciones','$extra')";
		//echo $sql_inserta_detalle."<br>";
		$resp_sql_inserta_detalle=mysql_query($sql_inserta_detalle);
	}
}
echo "<script language='Javascript'>
		alert('Los datos fueron copiados satisfactoriamente.');
		location.href='navegador_parrilla_especial_ciclos.php?ciclo_trabajo=$ciclo_trabajo&grupo_especial=$grupo_especial';
		</script>";
?>