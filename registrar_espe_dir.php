<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
	echo "<script language='JavaScript'>
		function envia_select(menu,form){
			form.submit();
			return(true);
		}
		</script>";
	
	require("estilos.inc");
	require("conexion.inc");
	echo "<table border='0' class='texto'>";
	echo "<tr><td>Direccion</td><td>Zona</td><td>Distrito</td></tr>";  
	echo "<form name='' action=''>";
		echo "<tr><td><input type='text' class='texto' name='direccion'></td>";
		$sql="select * from distritos where cod_ciudad='$h_ciudad'";
		$resp=mysql_query($sql);
		echo "<td>";
		echo "<select name='h_distrito' class='texto' onChange=envia_select(this,this.form)>";
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
		echo "</select></td>";
	
		$sql1="select * from zonas where cod_dist='$h_distrito'";
		$resp1=mysql_query($sql1);
		echo "<td>";
		echo "<select name='h_zona' class='texto'";
		echo "<option value=''>--Seleccionar--</option>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$p_cod_zona=$dat1[2];
			$p_zona=$dat1[3];
			echo "<option value='$p_cod_zona'>$p_zona</option>";
		}
		echo "</select></td>";
		echo "</tr><input type='hidden' name='h_ciudad' value='$h_ciudad'>";
		echo "<input type='button' class='texto' onClick='enviar_form(this.form)'></form>";
		
?>