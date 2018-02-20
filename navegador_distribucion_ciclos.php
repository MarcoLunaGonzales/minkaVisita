<?php
require("conexion.inc");
if($global_usuario==1052)
{	require("estilos_gerencia.inc");	
}
else
{	require("estilos_inicio_adm.inc");
}	
//$sql_gestion="select codigo_gestion from gestiones where estado='Activo'";
$sql_gestion="select codigo_gestion from gestiones where codigo_gestion=1014";
$resp_gestion=mysql_query($sql_gestion);
$dat_gestion=mysql_fetch_array($resp_gestion);
$gestion_activa=$dat_gestion[0];
/*$sql="select distinct(cod_ciclo), fecha_ini, fecha_fin, estado from ciclos where 
codigo_gestion='$gestion_activa' order by cod_ciclo desc";*/
$sql="select distinct(cod_ciclo), fecha_ini, fecha_fin, estado, codigo_gestion from ciclos where 
codigo_gestion in (1014) order by  codigo_gestion desc, cod_ciclo desc";
$resp=mysql_query($sql);
$indice_tabla=1;
echo "<center><table border='0' class='textotit'><tr><td>Distribuci&oacute;n de MM y MA</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0' width='60%'>";
echo "<tr><th>Ciclo</th><th>Fecha de Inicio</th><th>Fecha de Fin</th><th>Estado</th><th>&nbsp;</th></tr>";
while($dat=mysql_fetch_array($resp))
{
	$codigo=$dat[0];
	$fecha_inicio=$dat[1];
	$fecha_inicio="$fecha_inicio[8]$fecha_inicio[9]-$fecha_inicio[5]$fecha_inicio[6]-$fecha_inicio[0]$fecha_inicio[1]$fecha_inicio[2]$fecha_inicio[3]";
	$fecha_fin=$dat[2];
	$fecha_fin="$fecha_fin[8]$fecha_fin[9]-$fecha_fin[5]$fecha_fin[6]-$fecha_fin[0]$fecha_fin[1]$fecha_fin[2]$fecha_fin[3]";
	$estado=$dat[3];
	$codigoGestion=$dat[4];
	
	if($estado=="Activo")
	{	$desc_estado="En Curso"; 
		echo "<tr><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td><td align='center'><a href='cookie_distribucion.php?codigo_ciclo=$codigo&codigo_gestion=$codigoGestion'>Realizar Distribuci&oacute;n >></a></td></tr>"; 
	}
	if($estado=="Inactivo")
	{	$desc_estado="Programado";
		echo "<tr><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td><td align='center'><a href='cookie_distribucion.php?codigo_ciclo=$codigo&codigo_gestion=$codigoGestion'>Realizar Distribuci&oacute;n >></a></td></tr>"; 
	}
	if($estado=="Cerrado")
	{	$desc_estado="Cerrado"; 
		echo "<tr><td align='center'>$codigo</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td><td align='center'>$desc_estado</td><td align='center'>Realizar Distribuci&oacute;n >></td></tr>"; 
	}
	$indice_tabla++;
	
}
echo "</table></center><br>";
?>