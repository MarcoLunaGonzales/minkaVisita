<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.nombre_linea.value=='')
		{	alert('El campo Nombre de Línea de Visita esta vacio.');
			f.categoria.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos.inc");
echo "<form action='guarda_linea_visita.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Adicionar Línea de Visita</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Nombre de Línea de Visita</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='nombre_linea'></td>";
echo "</tr></table><br>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>