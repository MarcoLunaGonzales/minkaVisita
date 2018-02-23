<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.cargo.value=='')
		{	alert('El campo Nombre de Cargo esta vacio.');
			f.cargo.focus();
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
$sql=mysql_query("select cargo from cargos where cod_cargo=$cod_cargo");
$dat=mysql_fetch_array($sql);
$cargo=$dat[0];
echo "<form action='guarda_modi_cargo.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><td>Editar Cargos</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Nombre Cargo</th></tr>";
echo "<input type='hidden' name='codigo' value='$cod_cargo'>";
echo "<tr><td align='center'><input type='text' class='texto' name='cargo' value='$cargo' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td></tr>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_cargos.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>