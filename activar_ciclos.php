<?php
require("conexion.inc");
require("estilos.inc");
$sql_upd=mysql_query("update ciclos set estado='Inactivo' where estado='Activo' and codigo_linea=$global_linea");
$sql_update=mysql_query("update ciclos set estado='Activo' where cod_ciclo='$cod_ciclo' and codigo_linea=$global_linea");
$sql=mysql_query("select fecha_ini,fecha_fin from ciclos where estado='Activo'");
$dat=mysql_fetch_array($sql);
$fecha_ini=$dat[0];
$fecha_fin=$dat[1];
$fecha_ini_real="$fecha_ini[0]$fecha_ini[1]$fecha_ini[2]$fecha_ini[3],$fecha_ini[5]$fecha_ini[6],$fecha_ini[8]$fecha_ini[9]";
$fecha_fin_real="$fecha_fin[0]$fecha_fin[1]$fecha_fin[2]$fecha_fin[3],$fecha_fin[5]$fecha_fin[6],$fecha_fin[8]$fecha_fin[9]";
//prueba
$cadena="";
$cad1="start_date: '$fecha_ini_real',   end_date: '$fecha_fin_real', \n";
$archivo = fopen("dlcalendar1.js" , "r");
$i=1;
if ($archivo) 
{	
	while (!feof($archivo)) 
	{	$linea = fgets($archivo,255);
		if($i==9)
		{	$cadena="$cadena $cad1";	
		}
		else
		{	$cadena="$cadena $linea";
		}
		$i++;
	}
}
fclose ($archivo);
$archivo = fopen("dlcalendar1.js" , "w");
if ($archivo) {
fputs ($archivo, "$cadena");
}
fclose ($archivo);
//prueba

echo "<script language='Javascript'>
				alert('El ciclo se activo satisfactoriamente.');
				location.href='navegador_ciclos.php';
			</script>
		";

?>