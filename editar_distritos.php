<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.distrito.value=='')
		{	alert('El campo distrito esta vacio.');
			f.distrito.focus();
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
$sql=mysql_query("select descripcion from distritos where cod_ciudad=$cod_territorio and cod_dist=$cod_distrito");
$dat=mysql_fetch_array($sql);
$descripcion=$dat[0];
echo "<form action='guarda_modi_distrito.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><th>Editar distritos<br>Territorio $nombre_ciudad</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Territorio</th></tr>";
echo "<input type='hidden' name='cod_distrito' value='$cod_distrito'>";
echo "<input type='hidden' name='cod_territorio' value='$cod_territorio'>";
echo "<tr><td align='center'><input type='text' class='texto' name='distrito' value='$descripcion' size='40'></td></tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_distritos.php?cod_territorio=$cod_territorio'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>