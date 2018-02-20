<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	echo "<script language='JavaScript'>
		function ordenar_contactos(txt,j_cod_contacto,j_ciclo)
		{	
			var unicode=event.keyCode;
			var j_modificador;
			j_modificador=txt.value;
			if(unicode==13)
			{
				location.href='realizar_modi_orden_visita.php?j_modificador='+j_modificador+'&j_cod_contacto='+j_cod_contacto+'&j_ciclo='+j_ciclo+'';
			}	
		}
	</script>";
	require("estilos.inc");
	require("conexion.inc");
	//se requiere el cookie de usuario y el ciclo
	$visitador_global=14059;
	$sql="select * from contactos where cod_visitador='$visitador_global' and ciclo='$j_ciclo' order by fecha";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td><center>Agencia</center></td><tr>";
	echo "<tr><td><center>Visitador $visitador_global</center></td></tr></table></center><br>";
	echo "<center><table class='texto' border='1'><tr><td><center>Fecha</center></td><td><center>Turno</center></td><td><center>Orden Visita</center></td><td><center>Medico</center></td><td><center>Especialidad</center></td><td><center>Categoria</center></td><td><center>Direccion</center></td><td><center>Modificar</center></td></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_contacto=$dat[0];
		$p_fecha=$dat[2];
		$p_turno=$dat[3];
		$p_orden_visita=$dat[4];
		$p_cod_medico=$dat[6];
		$p_cod_especialidad=$dat[7];
		$p_categoria_med=$dat[8];
		$p_cod_zona=$dat[9];
		//formamos el nombre del medico
		$sql_aux="select * from medicos where cod_med='$p_cod_medico'";
		$resp_aux=mysql_query($sql_aux);
		$dat_aux=mysql_fetch_array($resp_aux);
		$p_nombre_medico=$dat_aux[3]." ".$dat_aux[1]." ".$dat_aux[2];
		//hasta esta parte formamos el nombre del medico	
		echo "<tr><td><center>$p_fecha</center></td><td><center>$p_turno</center></td><td><center>$p_orden_visita</center></td><td><center>$p_nombre_medico</center></td><td><center>$p_cod_especialidad</center></td><td><center>$p_categoria_med</center></td><td><center>$p_cod_zona</center></td>";
		echo "<td><center><input type='text' name='modi_orden_visita' class='texto' size='2' onkeyup='ordenar_contactos(this,$p_cod_contacto,$j_ciclo)'></center></td></tr>";
	}
	echo "</table></center>";
?>