<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	echo "<script language='JavaScript'>
			function envia_select(menu,form){
			form.submit();
			return(true);}
		
			function envia_form(f){
				var j_ciclo;
				j_ciclo=f.elements[0].value;
				location.href='ver_fechas_ciclo.php?j_ciclo='+j_ciclo+'';
				return(true);
			}
		</script>
	";
	
	require("estilos.inc");
	require("conexion.inc");
	$sql="select * from ciclos order by cod_ciclo desc";
	$resp=mysql_query($sql);
		$sql1="select * from ciclos where cod_ciclo='$h_ciclo'";
		$resp1=mysql_query($sql1);
		$dat1=mysql_fetch_array($resp1);
		$p_inicio=$dat1[1];
		$p_fin=$dat1[2];
	echo "<form action=''>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td>Selecccione Ciclo</td>";
	echo "<td><select name='h_ciclo' class='texto' onChange='envia_select(this,this.form)'>";
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
	{	$p_ciclo=$dat[0];
		if($h_ciclo==$p_ciclo)
		{	echo "<option value=$p_ciclo selected>$p_ciclo</option>";
		}
		else
		{	echo "<option value=$p_ciclo>$p_ciclo</option>";
		}
		
	}
	echo "</select>&nbsp;";
	echo "<input type='text' name='h_inicio' value='$p_inicio' disabled='true' size='10' class='texto'>&nbsp;<input type='text' name='h_fin' value='$p_fin' disabled='true' size='10' class='texto'></td></tr></table>";
	echo "<input type='button' value='Enviar' class='texto' onClick=envia_form(this.form)>";
	echo "</form></center>";
?>