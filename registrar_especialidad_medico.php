<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
		echo "<script language='JavaScript'>
			function enviar_atras(){
			location.href='registro_medico.php';
			}
			</script>";
//deben ingresar a este modulo el codigo ciudad y el codigo de medico
	require("estilos.inc");
	require("conexion.inc");
	echo "<title>Registrar Especialidades de Medicos</title>";
	echo "<center><table border='0' class='textotit'><tr><td><center>Registro de Especialidades de Medicos</center></td></tr></table><br>";
	echo "<table border='0' class='texto'>"; 
	echo "<form name='' action='guardar_especialidad_medico.php'>";
		$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp=mysql_query($sql);
		echo "<tr><td>Especialidad</td><td>";
		echo "<select name='h_esp' class='texto'>";
		while($dat=mysql_fetch_array($resp))
		{
			$p_cod_espe=$dat[0];
			$p_desc_espe=$dat[1];
			echo "<option value='$p_cod_espe'>$p_desc_espe</option>";
		}
		echo "</select></td></tr></table>";
	
		$sql1="select * from categorias_medicos order by categoria_med";
		$resp1=mysql_query($sql1);
		echo "<table border='0' class='texto'><tr><td>Categoria</td></tr></table>";
		echo "<table border='0' class='texto'><tr>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$p_cod_cat=$dat1[0];
			echo "<td><input type='radio' name='h_categoria' value=$p_cod_cat checked>$p_cod_cat</td>";
		}
		echo "</tr></table>";
		echo "<input type='hidden' name='h_cod_med' value='$h_cod_med'>";
		echo "<input type='hidden' name='h_nombre_med' value='$h_nombre_med'>";
		echo "<input type='hidden' name='h_cod_ciudad' value='$h_ciudad'>";
		echo "<input type='submit' class='texto' value='Enviar'>";
		echo "<input type='button' value='Cancelar' class='texto' onClick='enviar_atras()'></center></form>";		
?>