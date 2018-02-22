<?php
require("conexion.inc");
require("estilos_administracion.inc");

echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.zona.value=='')
		{	alert('El campo Zona esta vacio.');
			f.zona.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
	    $sql_cab="select descripcion from ciudades where cod_ciudad=$cod_territorio";
		$resp_cab=mysql_query($sql_cab);
		$dat_cab=mysql_fetch_array($resp_cab);
		$nombre_ciudad=$dat_cab[0];
		$sql_cab1="select descripcion from distritos where cod_dist=$cod_distrito";
		$resp_cab1=mysql_query($sql_cab1);
		$dat_cab1=mysql_fetch_array($resp_cab1);
		$nombre_distrito=$dat_cab1[0];

echo "<form action='guarda_zona.php' method='get'>";
echo "<center><table border='0' class='textotit'><tr><th>Adicionar Distrito<br>Territorio $nombre_ciudad<br>Distrito $nombre_distrito</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Zona</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='zona' size='30'></td></tr>";
echo "<input type='hidden' name='cod_territorio' value=$cod_territorio>";
echo "<input type='hidden' name='cod_distrito' value=$cod_distrito>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_zonas.php?cod_territorio=$cod_territorio&cod_distrito=$cod_distrito'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>