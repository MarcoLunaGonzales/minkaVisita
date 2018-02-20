<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.clave.value=='')
		{	alert('El campo Contraseña no puede estar vacio.');
			f.clave.focus();
			return(false);
		}
		if(f.clave_reescrita.value=='')
		{	alert('El campo Reescribir Contraseña no puede estar vacio.');
			f.clave_reescrita.focus();
			return(false);
		}
		if(f.clave_reescrita.value!=f.clave.value)
		{	alert('Las contraseñas son diferentes.');
			return(false);	
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_visitador.inc");

echo "<form action='guarda_cambio_contrasena_visitador.php' method='post'>";
echo "<h1>Cambio de Clave de Ingreso</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Ingresar Clave</th>";
echo "<td align='center'><input type='password' class='texto' name='clave'></td>";
echo "<tr><th>Reescribir Clave</th>";
echo "<td align='center'><input type='password' class='texto' name='clave_reescrita'><input type='hidden' name='codigo_funcionario' value='$global_visitador'></td>";
echo "</tr></table><br>";
echo"\n<table align='center'><tr><td><a href='principal_visitador.php'><img  border='0' src='imagenes/home.gif' width='15' height='8'>Principal</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>