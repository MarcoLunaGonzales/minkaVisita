<?PHP
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.categoria.value=='')
		{	alert('El campo Categoria esta vacio.');
			f.categoria.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
echo "<form action='guarda_categoria.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Adicionar Categoria de Medico</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Categoria</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='categoria' size='5'></td>";
echo "</tr></table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_categorias.php'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>