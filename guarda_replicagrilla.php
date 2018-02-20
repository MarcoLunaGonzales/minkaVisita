<?php

require("conexion.inc");
require("estilos_gerencia.inc");
$codigo_grilla=$_POST['codigo_grilla'];
$codigo_territorio=$_POST['codigo_territorio'];
$sql="select codigo_grilla from grilla order by codigo_grilla desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo=1000;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
$fecha=date("Y-m-d");
$sql_cab="select agencia, total_medicos, total_contactos, total_visitadores, contactos_visitador, codigo_linea from grilla where codigo_grilla=$codigo_grilla";
$resp_cab=mysql_query($sql_cab);
while($dat_cab=mysql_fetch_array($resp_cab)){
	$agencia=$dat_cab[0];
	$total_medicos=$dat_cab[1];
	$total_contactos=$dat_cab[2];
	$total_visitadores=$dat_cab[3];
	$contactos_visitador=$dat_cab[4];
	$codigo_linea=$dat_cab[5];
	$sql_insertacab="insert into grilla values('$codigo','GRILLA REPLICA','$codigo_territorio','$total_medicos','$total_contactos','$total_visitadores','$contactos_visitador',
				'$fecha','$fecha','$codigo_linea','2',0)";
	echo  $sql_insertacab;
	
	$resp_insertacab=mysql_query($sql_insertacab);
	$sql_detalle="select cod_especialidad, cod_categoria, frecuencia from grilla_detalle where codigo_grilla=$codigo_grilla";
	$resp_detalle=mysql_query($sql_detalle);
	while($dat_detalle=mysql_fetch_array($resp_detalle)){
		$codEsp=$dat_detalle[0];
		$codCat=$dat_detalle[1];
		$frecuencia=$dat_detalle[2];
		$sql_insertadetalle="insert into grilla_detalle values('$codigo','$codEsp','$codCat','$frecuencia','0')";
		$resp_insertadetalle=mysql_query($sql_insertadetalle);
	}	
}

echo "<script language='Javascript'>
			alert('Se replico la grilla correctamente.');
			window.close();
			</script>";

?>