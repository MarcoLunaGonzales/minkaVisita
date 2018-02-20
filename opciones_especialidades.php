<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/
 

if($adicionar)
	{  	echo" \n<html>";
		echo "<body background='imagenes/fondo.png' onload='error_guardar();'>";
		require("conexion.inc");
		require("estilos_administracion.inc"); 
		echo" \n<SCRIPT LANGUAJE='JavaScript'>";
		
		echo" \nfunction validar(f){ ";
		echo" \n	if(f.codespecialidad.value==''){\n";
		echo" \n		alert('El campo Codigo esta vacio');f.codespecialidad.focus(); return(0);}\n";
		echo" \n	if(f.descespecialidad.value==''){\n";
		echo" \n		alert('El campo Descripción esta vacio');f.descespecialidad.focus(); return(0);}\n";
		echo" \nf.submit();\n";
		echo" \n}\n";
		
		echo" \n </SCRIPT>";
		echo" \n <form   method='post' name='formulario'  action='guardar_especialidades.php'>";
		echo" \n <center><table border='0' class='textotit'><tr><td>ADICIÓN DE ESPECIALIDAD</td></tr></table></center><br>";
		echo" \n <center><table  border='1' class='texto' cellspacing='0'>";
		echo" \n <tr><td>";
		echo" \n		<center><table class='texto'>";
		echo" \n		<tr><th>Datos de Registro</th></tr>";
		echo" \n		</table></center>";
		echo" \n </td></tr>";
		echo" \n <tr><td>";
		echo" \n		<table class='texto'>";
		echo" \n		<tr><th>Abreviatura</th><th>Descripcion de Especialidad</th></tr>";
		echo" \n		<tr><td><INPUT  class='texto' type='text' name='codespecialidad' size ='5' maxlength='5'></td>
		            <td align='center'><INPUT class='texto' type='text' name='descespecialidad' size ='40' maxlength='100'></td></tr>";
		echo" \n		</table>";
		echo" \n </td></tr>";
		echo" \n </table></center><br>";
		echo"\n<table align='center'><tr><td><a href='navegador_especialidades.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
		echo" \n <center><table border='0' class='texto'>";
		echo" \n <tr>";
		echo" \n <td><input class='boton' type='button' name='aceptar' value='Aceptar' onClick='return validar(formulario)'></td>";
		echo" \n <td><input class='boton' type='button' name='cancelar' value='Cancelar' onClick='javascript: history.go(-1)'></td>";
		echo" \n </tr>";
		echo" \n </table></center>";
		echo" \n	";
		echo" \n	";
		echo" \n	";
		echo" \n	";
		echo" \n </form>";
		echo" \n </body>";
		echo" \n </html>";
	}

if($editar)
	{
	$codigos=explode("|",$codigos_especialidades);
	$codespecialidad=$codigos[0];
	echo" \n<html>";
		echo "<body background='imagenes/fondo.png' onload='error_guardar();'>";
		require("conexion.inc");
		require("estilos_administracion.inc");
		echo" \n<SCRIPT LANGUAJE='JavaScript'>";
		
		echo" \nfunction validar(f){ ";
		echo" \n	if(f.codespecialidad.value==''){\n";
		echo" \n		alert('El campo Codigo esta vacio');f.codespecialidad.focus(); return(0);}\n";
		echo" \n	if(f.descespecialidad.value==''){\n";
		echo" \n		alert('El campo Descripción esta vacio');f.descespecialidad.focus(); return(0);}\n";
		echo" \nf.submit();\n";
		echo" \n}\n";
		
		echo" \n </SCRIPT>";
		echo" \n <form   method='post' name='formulario'  action='modificar_especialidades.php'>";
		echo" \n <center><table border='0' class='textotit'><tr><td>ADICIÓN DE ESPECIALIDAD</td></tr></table></center><br>";
		$sql="select cod_especialidad,desc_especialidad from especialidades where cod_especialidad='$codespecialidad'";
		$resp=mysql_query($sql);
		while($dat=mysql_fetch_array($resp))
		{
			$codespecialidad=$dat[0];
			$descespecialidad=$dat[1];
			
		}
		echo" \n <center><table  border='1' class='texto' cellspacing='0'>";
		echo" \n <tr><td>";
		echo" \n		<center><table class='textotit'>";
		echo" \n		<tr><td>Datos de Registro</td></tr>";
		echo" \n		</table></center>";
		echo" \n </td></tr>";
		echo" \n <tr><td>";
		echo" \n		<table class='texto'>";
		echo" \n		<tr><th>Abreviatura</th><th>Descripcion de Especialidad</th></tr>";
		echo" \n		<tr><td><INPUT  class='texto' type='text' name='codespecialidad'  value='$codespecialidad'size ='5' maxlength='5' readonly></td>
		            		<td><INPUT class='texto' type='text' name='descespecialidad' value='$descespecialidad' size ='40' maxlength='100'></td>
						</tr>";
		echo" \n		</table>";
		echo" \n </td></tr>";
		echo" \n </table></center><br>";
		echo"\n<table align='center'><tr><td><a href='navegador_especialidades.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
		echo" \n <center><table border='0' class='texto'>";
		echo" \n <tr>";
		echo" \n <td><input class='boton' type='button' name='aceptar' value='Aceptar' onClick='return validar(formulario)'></td>";
		echo" \n <td><input class='boton' type='button' name='cancelar' value='Cancelar' onClick='javascript: history.go(-1)'></td>";
		echo" \n </tr>";
		echo" \n </table></center>";
		echo" \n	";
		echo" \n	";
		echo" \n	";
		echo" \n	";
		echo" \n </form>";
		echo" \n </body>";
		echo" \n </html>";
	
	}

?>