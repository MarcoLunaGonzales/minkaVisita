<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.nombre.value=='')
		{	alert('El campo Gestión esta vacio.');
			f.nombre.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
$sql=mysql_query("select nombre_gestion from gestiones where codigo_gestion=$cod_gestion");
$dat=mysql_fetch_array($sql);
$nombre=$dat[0];
echo "<form action='guarda_modi_gestion.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Editar Gestiones</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Gestión</th></tr>";
echo "<input type='hidden' name='codigo' value='$cod_gestion'>";
echo "<tr><td align='center'><input type='text' class='texto' name='nombre' value='$nombre' size='40'></td></tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_gestiones.php'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";	
?>