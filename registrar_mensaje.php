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

echo "<h1>Registrar Mensaje</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Mensaje</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='mensaje' size='100'></td>";
echo "</tr></table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_mensajes.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>