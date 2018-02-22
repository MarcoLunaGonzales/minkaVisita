<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
echo "<script language='JavaScript'>
	function validar(f)
	{	if(f.exafinicial.value=='')
		{	alert('El campo Fecha Inicial de Ciclo no puede estar vacio.');
			return(false);
		}
		if(f.exafvalidez.value=='')
		{	alert('El campo Fecha Final de Ciclo no puede estar vacio.');
			return(false);
		}
		f.submit();
	}
	</script>";	
			require("estilos_regional.inc");
			require("conexion.inc");
			echo "<center><table border='0' class='textotit'><tr><td>Editar baja de Medicos</td></tr></table></center><br>";
			echo "<form method='post' action='guarda_edicion_baja_medicos.php'>";
			$sql_baja="select inicio, fin, codigo_motivo from baja_medicos where cod_med='$cod_medico'";
			$resp_baja=mysql_query($sql_baja);
			$dat_baja=mysql_fetch_array($resp_baja);
			$fecha_ini=$dat_baja[0];
			$fecha_fin=$dat_baja[1];
			$fecha_ini_real="$fecha_ini[8]$fecha_ini[9]/$fecha_ini[5]$fecha_ini[6]/$fecha_ini[0]$fecha_ini[1]$fecha_ini[2]$fecha_ini[3]";
			$fecha_fin_real="$fecha_fin[8]$fecha_fin[9]/$fecha_fin[5]$fecha_fin[6]/$fecha_fin[0]$fecha_fin[1]$fecha_fin[2]$fecha_fin[3]";
			$motivo=$dat_baja[2];
			echo"<center><table class='texto' border='1' cellspacing='0'>";
			echo "<tr><th align='left'>Medico</th>";
			//aqui formamos los ciclos de manera consecutiva para mandarlos a guardar
				$sql_medico="select ap_pat_med, ap_mat_med, nom_med from medicos
				where cod_med='$cod_medico'";
				$resp_medico=mysql_query($sql_medico);
				echo "<td>";
				while($dat_medico=mysql_fetch_array($resp_medico))
				{	$nombre_medico="$dat_medico[0] $dat_medico[1] $dat_medico[2]";
					echo "$nombre_medico";
				}
				echo "</td>";
			echo "<input type='hidden' name='medico' value='$cod_medico'>";
			echo "</tr>";
			echo "<tr><th align='left'>Fecha Inicio</th>";
			//<!-- INI fecha con javascript -->
    		echo" <TD bgcolor='#ffffff'><INPUT  type='text' class='texto' id='exafinicial' size='10' name='exafinicial' value='$fecha_ini_real'>";
    		echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo" input_element_id='exafinicial' ";
    		echo" click_element_id='imagenFecha'></DLCALENDAR>";
    		echo"  </TD>";
  			//<!-- FIN fecha con javascript -->
			echo"</tr>";
			echo"<tr><th align='left'>Fecha Final</th>";
			//<!-- INI fecha con javascript -->
   			 echo" <TD bgcolor='#ffffff'><INPUT type='text'class='texto' id='exafvalidez' size='10' name='exafvalidez' value='$fecha_fin_real'>";
   			 echo" <IMG id='imagenFecha1' src='imagenes/fecha.bmp'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo"  input_element_id='exafvalidez' ";
    		echo" click_element_id='imagenFecha1'></DLCALENDAR>";
    		echo"  </TD>";
  			//<!-- FIN fecha con javascript -->

///NO OLVIDES DE ESTO DEBE IR DEBAJO DEL FORM MUCHA SUERTE
echo"</tr>";
echo "<tr><th align='left'>Motivo</th>";
	$sql_motivo="select codigo_motivo, descripcion_motivo from motivos_baja where tipo_motivo='2' order by descripcion_motivo";
	$resp_motivo=mysql_query($sql_motivo);
	echo "<td>";
	echo "<select name='motivo' class='texto'>";
	while($dat_motivo=mysql_fetch_array($resp_motivo))
	{	$codigo_motivo=$dat_motivo[0];
		$descripcion_motivo=$dat_motivo[1];
		if($codigo_motivo==$motivo)
		{	echo "<option value='$codigo_motivo' selected>$descripcion_motivo</option>";
		}
		else
		{	echo "<option value='$codigo_motivo'>$descripcion_motivo</option>";
		}
	}
	echo "</select>";
	echo "</td></tr>";
echo "</table></center><br>";
echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
echo"<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo"</form>\n";
echo "</div>";
echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>