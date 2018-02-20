<?php
require("conexion.inc");
require('estilos_almacenes_central.inc');
$sql_gestion="select codigo_gestion from gestiones where estado='Activo' and codigo_linea='1007'";
$resp_gestion=mysql_query($sql_gestion);
$dat_gestion=mysql_fetch_array($resp_gestion);
$codigo_gestion=$dat_gestion[0];
$sql="select * from ciclos where codigo_linea='1007' and codigo_gestion='$codigo_gestion' order by fecha_ini desc LIMIT 0,12";
$resp=mysql_query($sql);
$indice_tabla=1;
echo "<table align='center' border='0' class='textotit'><tr><th>Salida para ciclos enteros<br>Ciclos registrados</th></tr></table><br>";
echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Número de Ciclo</th><th>Fecha de Inicio</th><th>Fecha de Fin</th><th>Estado</th><th>&nbsp;</th></tr>";
while($dat=mysql_fetch_array($resp))
{
	$codigo=$dat[0];
	$fecha_inicio=$dat[1];
	$fecha_inicio="$fecha_inicio[8]$fecha_inicio[9]-$fecha_inicio[5]$fecha_inicio[6]-$fecha_inicio[0]$fecha_inicio[1]$fecha_inicio[2]$fecha_inicio[3]";
	$fecha_fin=$dat[2];
	$fecha_fin="$fecha_fin[8]$fecha_fin[9]-$fecha_fin[5]$fecha_fin[6]-$fecha_fin[0]$fecha_fin[1]$fecha_fin[2]$fecha_fin[3]";
	$estado=$dat[3];
	if($estado=="Activo")
	{	$desc_estado="En Curso"; 
		echo "<tr><td align='center'>$indice_tabla</td><td>&nbsp;</td><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td><td align='center'><a href='navegador_salidaciclosenterosterritorios.php?codigo_ciclo=$codigo'>Ingresar >></a></td></tr>";
	}
	if($estado=="Inactivo")
	{	$desc_estado="Programado"; 
		echo "<tr><td align='center'>$indice_tabla</td><td><input type='checkbox' name='codigos_ciclos' value='$codigo'></td><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td><td align='center'><a href='navegador_salidaciclosenterosterritorios.php?codigo_ciclo=$codigo'>Ingresar >></a></td></tr>";
	}
	if($estado=="Cerrado")
	{	$desc_estado="Cerrado"; 
		echo "<tr><td align='center'>$indice_tabla</td><td>&nbsp;</td><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td><td align='center'><a href='navegador_salidaciclosenterosterritorios.php?codigo_ciclo=$codigo'>Ingresar >></a></td></tr>";
	}
	$indice_tabla++;
	
}
echo "</table></center><br>";	
require('home_almacen.php');
?>