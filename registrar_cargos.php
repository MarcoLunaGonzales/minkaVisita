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
echo "<form action='guarda_tiposbaja.php' method='post'>";
echo "<center><table border='0' class='textotit'><tr><th>Adicionar Tipos de Baja</th></tr></table></center><br>";
echo "<center><table class='texto' cellspacing='0'>";
echo "<tr><th>Descripcion</th><th>Tipo</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' name='cargo' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>
<td><select name='tipoBaja' class='texto'>
		<option value='1'>Baja de Dias</option>
		<option value='2'>Baja de Medicos</option>
</select></td>
</tr>";
echo "</table></center>";
echo"<div class='divBotones'>
<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_cargos.php\"'>
</div>";

echo "</form>";

?>
