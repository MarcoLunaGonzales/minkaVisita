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
require("estilos_regional_pri.inc");
echo "<form action='guarda_cambio_contrasena_regional.php' method='post'>";
echo "<h1>Cambio de Clave de Acceso</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Ingresar Clave</th>";
echo "<td align='center'><input type='password' class='texto' name='clave'></td>";
echo "<tr><th>Reescribir Clave</th>";
echo "<td align='center'><input type='password' class='texto' name='clave_reescrita'></td>";
echo "</tr></table><br>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>