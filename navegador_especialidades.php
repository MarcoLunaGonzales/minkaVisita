<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 

	echo" \n<html>";
	
	echo"\n<head><link href='stilos.css' rel='stylesheet' type='text/css'>";
	echo"\n<script language='Javascript'>";
	echo"\n	function eliminacion_especialidades(f){ ";
		
		echo"\n var e=f.length ;";	
		echo"\n	  var codigos_especialidades='';";
		echo"\n var k;";
		echo"\n	  var editar=f.editar.value;";
		echo"\n for ( k = 0; k< f.length; k++){";
		echo"\n	          var e = f.elements[k];";
		
		echo"\n	          	if(e.checked){ ;";
		echo"\n	                codigos_especialidades=codigos_especialidades+e.value+'|';";
		echo"\n             }";
		echo"\n  } ";
		echo"\n  if(codigos_especialidades==''){alert('Debe seleccionar un registro '); return(0);} ";
		echo"\n	location='opciones_especialidades.php?editar='+editar+'&codigos_especialidades='+codigos_especialidades+'';";
	
		echo"\n	location='navegador_eliminacion.php?codigos_especialidades='+codigos_especialidades;";
	
	echo"\n	}";
	echo"\n	function validacion_editar(f){ ";
		echo"\n var e=f.length ;";	
		echo"\n	  var codigos_especialidades='';";
		echo"\n	  var contador=0;";
		echo"\n	  var editar=f.editar.value;";
		echo"\n var k;";
		echo"\n for ( k = 0; k< f.length; k++){";
		echo"\n	          var e = f.elements[k];";
		
		echo"\n	          	if(e.checked){ ;";
		echo"\n	          	     if(contador<1){ ;";
		echo"\n	                 codigos_especialidades=codigos_especialidades+e.value+'|';";
		echo"\n	                	contador++;";
		echo"\n                 }";
		echo"\n                 else {alert('Para editar solo debe selecionar un registro'); return(0);";
		echo"\n              	}";
		echo"\n             }";
		echo"\n  } ";
		echo"\n  if(codigos_especialidades==''){alert('Debe seleccionar un registro '); return(0);} ";
		echo"\n	location='opciones_especialidades.php?editar='+editar+'&codigos_especialidades='+codigos_especialidades+'';";
	
	echo"\n	}";
		echo"\nfunction error_guardar()";
		echo"\n{	alert('La especialidad no fue guardada ya existe una especialidad con el mismo codigo'); ";
		echo"\n	return(0);";
		echo"\n}";
		echo"\n</script>";
	
	echo"\n</head>";  
	
	
	require("conexion.inc");
	require("estilos_administracion.inc");
	
	if($mensaje==001)
	{
		echo "<body background='imagenes/fondo.png' onload='error_guardar();'>";
	}
	else
	{
		
		echo "<body background='imagenes/fondo.png' >"; 
	}
	echo"\n<form   method='post' name='formulario'  action='opciones_especialidades.php'>";
	$sql="select cod_especialidad,desc_especialidad from especialidades order by cod_especialidad";
	$resp=mysql_query($sql);
	echo"\n<center><table border='0' class='textotit'><tr><td>Registro de Especialidad</td></tr></table></center><br>";
	echo"\n<center><table border='1' class='texto' cellspacing='0' width='40%'>";
	echo"\n<tr><td>&nbsp;</td><td>&nbsp;</td><th>Abreviatura</th><th>Especialidad</th></tr>";
	$cont=1;
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codespecialidad=$dat[0];
		$descespecialidad=$dat[1];
		echo "<tr><td align='center'>$indice_tabla</td><td><input type='checkbox' name='codigosespecialidad$cont'value='$codespecialidad'></td><td>$codespecialidad</td><td>$descespecialidad</td></tr>";
	 $cont++;
		$indice_tabla++;	
	}
	echo"\n</table></center><br>";
	echo"\n<center><table border='0' class='texto'>";
	echo"\n<tr><td><input type='submit' value='Adicionar' name='adicionar' class='boton'></td>";
	echo"\n\n<td><input class='boton' type='button' name='eliminar' value='Eliminar' onclick=' return eliminacion_especialidades(formulario);'></td>";
	echo"\n<td><input type='button'  name='editar' value='Editar' onclick='return validacion_editar(formulario);' class='boton'></td></tr>";
	echo"\n</table></center>";
	echo"\n</form>";
	echo"\n</body>";
	echo"\n</html>";
?>
