<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.linea.value=='')
		{	alert('El campo Nombre de Línea esta vacio.');
			f.linea.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
echo "<form action='guarda_lineas.php' method='post'>";

echo "<h1>Registrar Lineas</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Nombre Linea</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='linea' size='40'></td></tr>";
echo "</table><br></center>";

echo "<div class='divBotones'>
<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_lineas.php\"'>";
echo "</form>";
?>