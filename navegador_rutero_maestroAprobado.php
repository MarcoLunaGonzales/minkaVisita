<?php
require("conexion.inc");
require("estilos_visitador.inc");
echo "<form method='post' action=''>";

echo "<h1>Ruteros Maestro Aprobados x Ciclo</h1>";

echo "<center><table class='texto'>
<tr><th>&nbsp;</th><th>Nombre de Rutero</th>
<th>Ciclo Asociado</th><th>Estado</th><th>&nbsp;</th></tr>";
$sql="select r.cod_rutero, r.nombre_rutero, r.estado_aprobado, r.codigo_ciclo, r.codigo_gestion from 
rutero_maestro_cab_aprobado r 
where r.cod_visitador='$global_visitador' and r.codigo_linea='$global_linea' 
order by r.codigo_gestion desc, r.codigo_ciclo desc";
$resp=mysql_query($sql);
$filas_ruteros=mysql_num_rows($resp);
$nombreGestion="";
while($dat=mysql_fetch_array($resp))
{
	$cod_rutero=$dat[0];
	$nombre_rutero=$dat[1];
	$estado=$dat[2];
	$codCiclo=$dat[3];
	$codigoGestion=$dat[4];
	
	$sqlGestion="select nombre_gestion from gestiones where codigo_gestion=$codigoGestion";
	$respGestion=mysql_query($sqlGestion);
	$datGestion=mysql_fetch_array($respGestion);
	$nombreGestion=$datGestion[0];
	
	$estado_desc="Aprobado";
	echo"<tr><td>&nbsp;</td><td>&nbsp;$nombre_rutero</td><td>$codCiclo/$nombreGestion</td>
	<td align='center'>$estado_desc</td>
	<td align='center'><a href='rutero_maestro_todo.php?rutero=$cod_rutero&aprobado=1&vista=2'><img src='imagenes/detalle.png' width='30'></a></td>
	</tr>";	
}
echo "</table><br>";
echo "</form>";
?>