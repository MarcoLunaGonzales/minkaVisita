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
require("estilos.inc");
echo "<form action='guarda_cambio_contrasena.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><th>Cambio de Contraseña</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Ingresar Contraseña</th>";
echo "<td align='center'><input type='password' class='texto' name='clave'></td>";
echo "<tr><th>Reescribir Contraseña</th>";
echo "<td align='center'><input type='password' class='texto' name='clave_reescrita'></td>";
echo "</tr></table><br>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>