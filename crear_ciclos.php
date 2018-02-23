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
		var dia_ini,mes_ini,anio_ini,dia_fin,mes_fin,anio_fin;
		vec_ini=new Array(5);
		vec_fin=new Array(5);
		var fecha_ini,fecha_fin;
		fecha_ini=f.exafinicial.value;
		fecha_fin=f.exafvalidez.value;
		vec_ini=fecha_ini.split('/');
		vec_fin=fecha_fin.split('/');
		dia_ini=vec_ini[0]*1; mes_ini=(vec_ini[1]*1)-1; anio_ini=vec_ini[2]*1;
		dia_fin=vec_fin[0]*1; mes_fin=(vec_fin[1]*1)-1; anio_fin=vec_fin[2]*1;
		fecha_ini=new Date(anio_ini,mes_ini,dia_ini);
		fecha_fin=new Date(anio_fin,mes_fin,dia_fin);
		if(fecha_fin<=fecha_ini)
		{	alert('La Fecha de Fin de Ciclo no puede ser Anterior o Igual a la de Inicio de Ciclo.');
			return(false);
		}
		f.submit();
	}
	</script>";	
			require("estilos.inc");
			echo "<center><table border='0' class='textotit'><tr><td>Creación de Ciclos</td></tr></table></center><br>";
			echo "<form method='post' action='guarda_ciclo.php'>";
			echo"<center><table class='texto' border='1' cellspacing='0'>";
			echo "<tr><th>Ciclo a Crear</th>";
			//aqui formamos los ciclos de manera consecutiva para mandarlos a guardar
				$sql_codigo="select cod_ciclo from ciclos where codigo_linea='$global_linea' and codigo_gestion='$codigo_gestion' order by fecha_ini desc";
				$resp_codigo=mysql_query($sql_codigo);
				$dat_codigo=mysql_fetch_array($resp_codigo);
				$codigo_ciclo=$dat_codigo[0];
				/*if($codigo_ciclo>=12)
				{	$codigo_ciclo=0;
				}*/
				$codigo_ciclo=$codigo_ciclo+1;
				$codigo_ciclo="$codigo_ciclo/$gestion";
			echo "<input type='hidden' name='cod_ciclo' value='$codigo_ciclo'>";
			echo "<th>$codigo_ciclo</th>";			
			echo "</tr>";
			echo "<tr><td>Fecha Inicio Ciclo</td>";
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
			echo"<tr><td>Fecha Final de Ciclo</td>";
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
echo"</tr></table></center><br>";
echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo"<center><input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo"</form>\n";
echo "</div>";
echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>