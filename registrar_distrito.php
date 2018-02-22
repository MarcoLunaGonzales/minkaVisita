<?php
require("conexion.inc");
require("estilos_administracion.inc");

echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.distrito.value=='')
		{	alert('El campo Distrito esta vacio.');
			f.distrito.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
		$sql_cab="select descripcion from ciudades where cod_ciudad=$cod_territorio";
		$resp_cab=mysql_query($sql_cab);
		$dat_cab=mysql_fetch_array($resp_cab);
		$nombre_ciudad=$dat_cab[0];
echo "<form action='guarda_distrito.php' method='get'>";
echo "<center><table border='0' class='textotit'><tr><th>Adicionar Distrito<br>Territorio $nombre_ciudad</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Distrito</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='distrito' size='30'></td></tr>";
echo "<input type='hidden' name='cod_territorio' value=$cod_territorio>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_distritos.php?cod_territorio=$cod_territorio'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>