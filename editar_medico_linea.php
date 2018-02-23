<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
require("conexion.inc");
require("estilos_regional_pri.inc");
$vector=explode(",",$datos);
$n=sizeof($vector);
echo "<form action='guardar_modi_categoria_linea.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><th>Editar Especialidades / Categorias <br>Medicos de la Línea</th></tr></table></center><br>";
echo "<table border=1 cellspacing=0 class='texto' width='50%' align='center'>";
echo "<tr><th>&nbsp;</th><th>Medico</th><th>Especialidad</th><th>Categoria</th><th>Frecuencia Linea</th><th>Frecuencia Especial</th></tr>";
for($i=0;$i<$n;$i++)
{
	$sql_cab=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$vector[$i]'");
	$dat_cab=mysql_fetch_array($sql_cab);
	$nombre_medico="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	$sql="select c.cod_especialidad, c.categoria_med, c.frecuencia_linea, c.frecuencia_permitida 
	from categorias_lineas c, especialidades_medicos e
	where c.cod_med='$vector[$i]' and c.cod_med=e.cod_med and c.cod_especialidad=e.cod_especialidad 
	and c.codigo_linea='$global_linea' order by c.cod_especialidad";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{
	  $espe=$dat[0];
	  $h_categoria=$dat[1];
	  $frecuenciaLinea=$dat[2];
	  $frecuenciaPermitida=$dat[3];
	  $indice=$i+1;
	  echo "<tr><td>$indice</td>";
	  echo "<td align='left'>$nombre_medico</td><td align='left'><select name='especialidad$i' class='texto'>";
	  $sql_especialidades="select em.cod_especialidad from especialidades_medicos em, especialidades e
	  where em.cod_especialidad=e.cod_especialidad and em.cod_med='$vector[$i]'";
	  $resp_especialidades=mysql_query($sql_especialidades);
	  while($dat_especialidades=mysql_fetch_array($resp_especialidades))
	  {		$cod_especialidad=$dat_especialidades[0];
	  		if($cod_especialidad==$espe)
			{	echo "<option value='$cod_especialidad' selected>$cod_especialidad</option>";
			}
			else
			{	echo "<option value='$cod_especialidad'>$cod_especialidad</option>";
			}
	  		
	  }
	  echo "</select></td><td align='center'>";
   	  echo "<select name='h_categoria$i' class='texto'>";
	  $sql_categoria=mysql_query("select categoria_med from categorias_medicos order by categoria_med");
	  while($dat_categoria=mysql_fetch_array($sql_categoria))
	  {		$cat=$dat_categoria[0];
			if($h_categoria==$cat)
			{	echo "<option value='$cat' selected>$cat</option>";
			}
			else
			{	echo "<option value='$cat'>$cat</option>";
			}
		}
		echo "</select>";
	}
	echo "</td>";
	echo "<td>$frecuenciaLinea</td>";
	echo "<td>
		<input type='text' class='texto' name='frecuencia_permitida$i' value='$frecuenciaPermitida' size='5'>
	</td>";
	echo "</tr>";
	echo "<input type='hidden' name='cod_med$i' value='$vector[$i]'>";
}
	echo "</table><br>";
	echo "<input type='hidden' name='numero_medicos' value='$i
	'>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><input type='submit' class='boton' value='Modificar'></center>";
	echo "</form>";

?>