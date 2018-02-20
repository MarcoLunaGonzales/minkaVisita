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
require("estilos_inicio_adm.inc");
$sql=mysql_query("select * from categorias_medicos where categoria_med='$cod_categoria'");
$dat=mysql_fetch_array($sql);
$categoria=$dat[0];
echo "<form action='guarda_modi_categoria.php' method='get'>";
echo "<center><table border='0' class='textotit'><tr><td>Adicionar Categoria de Medico</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Categoria</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='categoria1' value='$categoria' disabled='true' size='5'></td>";
echo "<input type='hidden' name=categoria value='$categoria'>";
echo "</tr></table><br>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>