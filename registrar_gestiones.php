<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.nombre.value=='')
		{	alert('El campo Gestión esta vacio.');
			f.nombre.focus();
			return(false);
		}
		f.submit();
		return(true);
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
echo "<form action='guarda_gestiones.php' method='get'>";
echo "<center><table border='0' class='textotit'><tr><td>Adicionar Gestiones</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Gestión</th></tr>";
echo "<tr>";
echo"<td align='center'><input type='text' class='texto' name='nombre' size='40'></td>";
echo"</tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_gestiones.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>