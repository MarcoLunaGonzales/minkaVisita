<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.linea.value=='')
		{	alert('El campo Nombre de L�nea esta vacio.');
			f.linea.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
echo "<form action='guarda_lineas.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Adicionar L�neas</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Nombre L�nea</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='linea' size='40'></td></tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_lineas.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>