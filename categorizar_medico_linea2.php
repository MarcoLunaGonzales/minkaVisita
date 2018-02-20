<?php
/*
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/
require("conexion.inc");
$vector=explode(",",$datos);
$n=sizeof($vector);

echo "<form method='post' action='guarda_cat_medico_linea.php' name='formu'>";
echo "<center><table border='0' class='textotit'><tr><td>Añadir Categoria</td></tr></table></center><br>";
echo "<center><table class='texto' border=1 cellspacing='0'>";
for($k=0;$k<=$n;$k++)
{	$valor_vector=$vector[$k];
	$cadena=$cadena."|".$valor_vector;
}
echo "<input type='hidden' name='cadena' value='$cadena'>";

for($i=0;$i<$n;$i++)
{	$cod_med=$vector[$i];
	$sql="select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'";
	$resp=mysql_query($sql);
	$dat_med=mysql_fetch_array($resp);
	$pat=$dat_med[0];$mat=$dat_med[1];$nombre=$dat_med[2];
	$nombre_completo="$pat $mat $nombre";
	$indice=$i+1;
	echo "<tr><td>$indice</td><td>$nombre_completo</td>";
	$sql2="select em.cod_especialidad from especialidades_medicos em, especialidades e where 
	em.cod_med='$cod_med' and em.cod_especialidad=e.cod_especialidad order by em.cod_especialidad";
	$resp2=mysql_query($sql2);
	echo "<td align='left'><select name='especialidad_med$i' class='texto'>";
	while($dat2=mysql_fetch_array($resp2))
	{	$espe=$dat2[0];
		echo "<option value='$espe'>$espe</option>";
	}
	echo "<td align='center'>";
	$sql_cat="select categoria_med from categorias_medicos order by categoria_med desc";
	$resp_cat=mysql_query($sql_cat);
	echo"<select name='cat$i' class='texto'>";
	while($dat_cat=mysql_fetch_array($resp_cat))
	{	$cat=$dat_cat[0];
		echo "<option value='$cat'>$cat</option>";	
	}
	echo"</select>";
	echo "</td>";
	$sql_perfil='select codigo_perfilprescriptivo, nombre_perfilprescriptivo, abrev_perfilprescriptivo from perfil_prescriptivo order by codigo_perfilprescriptivo';
	$resp_perfil=mysql_query($sql_perfil);
	echo "<td><select name='perfil$i' class='texto'>";
	while($dat_perfil=mysql_fetch_array($resp_perfil))
	{	$cod_perfil=$dat_perfil[0];
		$nombre_perfil=$dat_perfil[1];
		$abrev_perfil=$dat_perfil[2];
		echo "<option value='$cod_perfil'>$nombre_perfil ($abrev_perfil)</option>";
	}
	echo "</select></td>";
//	echo "&nbsp;</td>";
	if($chk_visitador==1)
	{	echo "<td align='center'>";
		$sql="select f.codigo_funcionario,c.cargo,f.paterno,f.materno,f.nombres
		from funcionarios f, cargos c, ciudades ci, funcionarios_lineas cl
		where f.cod_cargo=c.cod_cargo and f.cod_cargo='1011' and f.cod_ciudad='$global_agencia' 
		and f.estado='1' and cl.codigo_funcionario=f.codigo_funcionario and cl.codigo_linea='$global_linea' 
		and f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo";
	
		
		$resp=mysql_query($sql);
		echo"<select name='visitador$i' class='texto'>";
		while($dat=mysql_fetch_array($resp))
		{	$codigo=$dat[0];
			$cargo=$dat[1];
			$paterno=$dat[2];
			$materno=$dat[3];
			$nombre=$dat[4];
			$nombre_f="$paterno $materno $nombre";
			echo "<option value='$codigo'>$nombre_f</option>";	
		}
		echo"</select>";
		echo "$sql</td>";
	}
	echo "</tr>";
	echo "<input type='hidden' name='med$i' value='$cod_med'>";
}
echo "<input type='hidden' name='cantidad' value='$n'>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><input type='submit' value='Guardar' class='boton'></center>";

?>