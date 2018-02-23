<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.zona.value=='')
		{	alert('El campo zona esta vacio.');
			f.zona.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
		$sql_cab="select descripcion from ciudades where cod_ciudad=$cod_territorio";
		$resp_cab=mysql_query($sql_cab);
		$dat_cab=mysql_fetch_array($resp_cab);
		$nombre_ciudad=$dat_cab[0];
		$sql_cab1="select descripcion from distritos where cod_dist=$cod_distrito";
		$resp_cab1=mysql_query($sql_cab1);
		$dat_cab1=mysql_fetch_array($resp_cab1);
		$nombre_distrito=$dat_cab1[0];
$sql=mysql_query("select zona from zonas where cod_ciudad=$cod_territorio and cod_dist=$cod_distrito and cod_zona=$cod_zona");
$dat=mysql_fetch_array($sql);
$descripcion=$dat[0];
echo "<form action='guarda_modi_zona.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><th>Editar distritos<br>Territorio $nombre_ciudad<br>Distrito $nombre_distrito</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Zona</th></tr>";
echo "<input type='hidden' name='cod_distrito' value='$cod_distrito'>";
echo "<input type='hidden' name='cod_territorio' value='$cod_territorio'>";
echo "<input type='hidden' name='cod_zona' value='$cod_zona'>";
echo "<tr><td align='center'><input type='text' class='texto' name='zona' value='$descripcion' size='40'></td></tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_zonas.php?cod_territorio=$cod_territorio&cod_distrito=$cod_distrito'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>