<?php
require("conexion.inc");
require("estilos_administracion.inc");

echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.linea.value=='')
		{	alert('El campo Nombre de Línea esta vacio.');
			f.linea.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
$sql=mysql_query("select nombre_linea from lineas where codigo_linea=$cod_linea");
$dat=mysql_fetch_array($sql);
$linea=$dat[0];
echo "<form action='guarda_modi_linea.php' method='post'>";

echo "<h1>Editar Linea</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Linea</th></tr>";
echo "<input type='hidden' name='codigo' value='$cod_linea'>";
echo "<tr><td align='center'><input type='text' class='texto' name='linea' value='$linea' size='40'></td></tr>";
echo "</table><br></center>";

echo "<div class='divBotones'>
	<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
	<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_lineas.php\"'>
</div>";
echo "</form>";
?>