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
$sql=mysql_query("select descripcion from ciudades where cod_ciudad=$cod_territorio");
$dat=mysql_fetch_array($sql);
$descripcion=$dat[0];
echo "<form action='guarda_modi_territorio.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Editar Territorio</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Territorio</th></tr>";
echo "<input type='hidden' name='cod_territorio' value='$cod_territorio' size='30'>";
echo "<tr><td align='center'><input type='text' class='texto' name='territorio' value='$descripcion' size='40'></td></tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_territorios.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>