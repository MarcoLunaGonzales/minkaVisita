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
	require("conexion.inc");
	echo "<center><table border='0' class='textotit'><tr><td>Editar Ciclo $cod_ciclo</td></tr></table></center>";
	$sql=mysql_query("select * from ciclos where cod_ciclo='$cod_ciclo' and codigo_linea=$global_linea");
	$dat=mysql_fetch_array($sql);
	$fecha_ini=$dat[1];
	$fecha_fin=$dat[2];
	$fecha_ini_real="$fecha_ini[8]$fecha_ini[9]/$fecha_ini[5]$fecha_ini[6]/$fecha_ini[0]$fecha_ini[1]$fecha_ini[2]$fecha_ini[3]";
	$fecha_fin_real="$fecha_fin[8]$fecha_fin[9]/$fecha_fin[5]$fecha_fin[6]/$fecha_fin[0]$fecha_fin[1]$fecha_fin[2]$fecha_fin[3]";
	echo "<form method='post' action='guarda_modificacion.php'>";
	echo "<input type='hidden' name='cod_ciclo' value='$cod_ciclo'>";
	echo "<center><table border=1 cellspacing='0' class='texto'>";
	echo "<tr><th>Fecha de Inicio de Ciclo</th><th>Fecha de Fin de Ciclo</th></tr>";
	echo "<tr>";
	echo "<TD bgcolor='#ffffff'><center><INPUT  type='text' class='texto' id='exafinicial' value='$fecha_ini_real' size='10' name='exafinicial'>";
    		echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo" input_element_id='exafinicial' ";
    		echo" click_element_id='imagenFecha'></DLCALENDAR>";
    		echo" </center></TD>";
	echo" <TD bgcolor='#ffffff'><center><INPUT type='text'class='texto' value='$fecha_fin_real' id='exafvalidez' size='10' name='exafvalidez'>";
   			 echo" <IMG id='imagenFecha1' src='imagenes/fecha.bmp'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo"  input_element_id='exafvalidez' ";
    		echo" click_element_id='imagenFecha1'></DLCALENDAR>";
    		echo"  </center></TD></TR>";
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	echo"<center><input type='button' class='boton' value='Modificar' onClick='validar(this.form)'></center>";
	echo "</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>