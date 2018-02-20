<?PHP
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.mensaje.value=='')
		{	alert('El campo Mensaje esta vacio.');
			f.mensaje.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_gerencia.inc");
echo "<form action='guarda_mensaje.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Registrar Mensaje</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Mensaje</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='mensaje' size='100'></td>";
echo "</tr></table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_mensajes.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>