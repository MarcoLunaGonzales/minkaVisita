<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
	
//deben ingresar a este modulo el codigo ciudad y el codigo de medico
	echo "<script language='JavaScript'>
		function envia_select(menu,form){
			form.submit();
			return(true);
		}
		function enviar_form(f){
			var j_dir;
			var j_dist;
			var j_zona;
			j_dir=f.h_direccion.value;
			j_dist=f.h_distrito.value;
			j_zona=f.h_zona.value;
			j_cod_med=f.h_cod_med.value;
			if(j_dir=='' || j_dist=='' || j_zona=='')
			{
				alert('No puede enviar datos Incompletos.');
				return(false);
			}
			else
			{
				location.href='guardar_direccion_medico.php?h_direccion='+j_dir+'&h_distrito='+j_dist+'&h_zona='+j_zona+'&h_cod_med='+j_cod_med+'';
				return(true);
			}
		}
		function enviar_atras(){
			location.href='registro_medico.php';
		}
		</script>";
	
	require("estilos.inc");
	require("conexion.inc");
	echo "<title>Registrar Direcciones de Medicos</title>";
	echo "<center><table border='0' class='textotit'><tr><td><center>Registro de Direccion de Medicos</center></td></tr></table><br>";
	echo "<table border='0' class='texto'>"; 
	echo "<form name='' action=''>";
		echo "<tr><td>Direccion</td><td><input size=30 type='text' class='texto' name='h_direccion'></td></tr>";
		$sql="select * from distritos where cod_ciudad='$h_ciudad'";
		$resp=mysql_query($sql);
		echo "<tr><td>Distrito</td><td>";
		echo "<select width=30 name='h_distrito' class='texto' onChange=envia_select(this,this.form)>";
		echo "<option value=''></option>";
		while($dat=mysql_fetch_array($resp))
		{
			$p_ciudad=$dat[0];
			$p_dist=$dat[1];
			$p_desc=$dat[2];
			if($p_dist==$h_distrito)
			{
				echo "<option value='$p_dist' selected>$p_desc</option>";
			}
			else
			{
				echo "<option value='$p_dist'>$p_desc</option>";
			}
		}
		echo "</select></td></tr>";
	
		$sql1="select * from zonas where cod_dist='$h_distrito'";
		$resp1=mysql_query($sql1);
		echo "<tr><td>Zona</td><td>";
		echo "<select name='h_zona' class='texto'";
		echo "<option value=''>--Seleccionar--</option>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$p_cod_zona=$dat1[2];
			$p_zona=$dat1[3];
			echo "<option value='$p_cod_zona'>$p_zona</option>";
		}
		echo "</select></td></tr>";
		echo "</tr></table><br><input type='hidden' name='h_ciudad' value='$h_ciudad'>";
		echo "<input type='hidden' name=h_cod_med value=$h_cod_med>";
		echo "<input type='button' value='Enviar' class='texto' onClick='enviar_form(this.form)'>";
		echo "<input type='button' value='Cancelar' class='texto' onClick='enviar_atras()'></center></form>";
?>