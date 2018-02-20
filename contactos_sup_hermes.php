<?php
echo "<script language='JavaScript'>
function validar(f)
	{	if(f.asunto.value=='')
		{	alert('El campo Asunto no puede estar vacio.');
			f.asunto.focus();
			return(false);
		}
		if(f.mensaje.value=='')
		{	alert('El campo Mensaje no puede estar vacio.');
			f.mensaje.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("estilos_visitador.inc");
require("conexion.inc");
echo "<table class='textotit' align='center'><tr><th>Contactarme con mi Supervisor - Jefe Regional</th></tr></table>";
echo "<form method='post' action='envia_mail.php'>";
echo "<table border='1' class='texto' align='center'>";
echo "<tr><th>Asunto<th><td><input type='text' name='asunto' size='40'></td>";
echo "<tr><th>Mensaje<th><td><textarea name='mensaje' cols='30' rows='10'></textarea></td>";
echo "<tr><th>Adjuntar archivo<th><td><input type='file' name='archivo' cols='30' rows='10'></td></tr>";
echo "</table>";
echo"\n<table align='center'><tr><td><a href='principal_visitador.php'><img  border='0'src='imagenes/home.gif'>Principal</a></td></tr></table>";
echo"<center><input type='button' class='boton' value='Enviar' onClick='validar(this.form)'></center>";
echo "</form>";
?>