<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.contrasena.value=='')
		{
		  	alert('El campo Contrase�a esta vacio.');
			f.contrasena.focus();
			return(false);
		}
		else
		{
			 if(f.contrasena.value.length < 8)
			 {
			 	alert('La contrase�a debe tener al menos 8 caracteres.');
				return(false);  
			 }
			  
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos_administracion.inc");
	$sql_cab=mysql_query("select paterno, materno, nombres from funcionarios where codigo_funcionario='$codigo_funcionario'");
	$dat_cab=mysql_fetch_array($sql_cab);
	$nombre_funcionario="$dat_cab[2] $dat_cab[0] $dat_cab[1]";
echo "<form action='guarda_alta_sistema.php' method='get'>";
echo "<center><table border='0' class='textotit'><tr><th>Alta en Sistema<br>Funcionario: $nombre_funcionario</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Usuario</th><th>Contrase�a</th></tr>";
echo "<tr><th>$codigo_funcionario</th><td align='center'><input type='text' class='texto' name='contrasena' size='40'></td></tr>";
echo "<input type='hidden' name='codigo_funcionario' value='$codigo_funcionario'>";
echo "<input type='hidden' name='cod_territorio' value='$cod_territorio'>";
echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='navegador_funcionarios.php?cod_ciudad=$cod_territorio'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
echo "<center><table border='0' width='40%'><tr><th>Nota: La contrase�a debe tener al menos 8 caracteres.</th></tr><tr><th>Debe contener 1 caracter especial, 1 numero y una mayuscula.</th></tr></table></center>";
?>