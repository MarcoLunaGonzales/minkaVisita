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
			echo "<center><table border='0' class='textotit'><tr><td>Adicionar baja de Medicos</td></tr></table></center><br>";
			echo "<form method='post' action='guarda_baja_medicos.php'>";
			echo"<center><table class='texto' border='1' cellspacing='0'>";
			$sql_linea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 order by nombre_linea";
			$resp_linea=mysql_query($sql_linea);
			echo "<tr><th align='left'>Línea</th><td><select name='linea_baja' class='texto'>";
			while($datos_linea=mysql_fetch_array($resp_linea))
			{	$cod_linea_rpt=$datos_linea[0];$nom_linea_rpt=$datos_linea[1];
				echo "<option value='$cod_linea_rpt'>$nom_linea_rpt</option>";
			}
			echo "</select></td>";

			echo "<tr><th align='left'>Medico</th>";
			//aqui formamos los ciclos de manera consecutiva para mandarlos a guardar
				$sql_medico="select distinct(c.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c
				where m.cod_med=c.cod_med and m.cod_ciudad='$global_agencia' order by m.ap_pat_med, m.ap_mat_med, m.nom_med";
				$resp_medico=mysql_query($sql_medico);
				echo "<td><select name='medico' class='texto'>";
				while($dat_medico=mysql_fetch_array($resp_medico))
				{	$cod_med=$dat_medico[0];
					$nombre_medico="$dat_medico[1] $dat_medico[2] $dat_medico[3]";
					echo "<option value='$cod_med'>$nombre_medico</option>";
				}
				echo "</select></td>";
			echo "</tr>";
			echo "<tr><th align='left'>Fecha Inicio</th>";
			//<!-- INI fecha con javascript -->
    		echo" <TD bgcolor='#ffffff'><INPUT  type='text' class='texto' id='exafinicial' size='10' name='exafinicial'>";
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
   			 echo" <TD bgcolor='#ffffff'><INPUT type='text'class='texto' id='exafvalidez' size='10' name='exafvalidez'>";
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
		echo "<option value='$codigo_motivo'>$descripcion_motivo</option>";
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