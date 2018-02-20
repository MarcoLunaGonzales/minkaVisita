<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.territorio.value=='')
		{	alert('El campo Territorio esta vacio.');
			f.territorio.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
echo "<form action='guarda_territorio.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Adicionar Territorio</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Territorio</th><th>Tipo</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='territorio' size='30'></td>";
echo "<td><select name='tipo' class='texto'>";
echo "<option value='0'>No troncal</option>";
echo "<option value='1'>Troncal</option>";
echo "</select>";
echo "</td></tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_territorios.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>