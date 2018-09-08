<?php
echo "<script language='Javascript'>
function validar(f){
	if(f.materno.value==''){	
		alert('El campo Apellido Materno esta vacio');
		f.materno.focus();
		return(false);
	}
	if(f.nombres.value=='')
		{	alert('El campo Nombres esta vacio');
	f.nombres.focus();
	return(false);
	}
	f.submit();
}
</script>";
require("conexion.inc");
require("estilos_gerencia.inc");

$ciudad_volver=$cod_ciudad;
echo "<h1>Registro de Funcionario</h1>";
echo "<form action='guardar_funcionario.php' method='get'>";
echo "<center><table class='texto'>";
echo "<tr><th>Paterno</th><th>Materno (*)</th><th>Nombres (*)</th><th>Fecha de Nacimiento</th></tr>";
echo "<tr>";
echo "<td align='center'><input type='text' name='paterno' style='text-transform:uppercase;' class='texto'></td>";
echo "<td align='center'><input type='text' name='materno' style='text-transform:uppercase;' class='texto'></td>";
echo "<td align='center'><input type='text' name='nombres' style='text-transform:uppercase;' class='texto'></td>";
echo "<td align='center'><INPUT  type='text' class='texto' id='exafinicial' size='10' name='exafinicial'>";
echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
echo" input_element_id='exafinicial' ";
echo" click_element_id='imagenFecha'></DLCALENDAR></td>";
echo "</tr>";
echo "<tr><th>Direccion</th><th>Telefono</th><th>Celular</th><th>Cargo</th></tr>";
echo "<tr>";
echo "<td align='center'><input type='text' name='direccion' style='text-transform:uppercase;' class='texto'></td>";
echo "<td align='center'><input type='text' name='telefono' class='texto'></td>";
echo "<td align='center'><input type='text' name='celular' class='texto'></td>";
echo "<td align='center'><select name='cargo' class='texto'>";
$sql_cargo=mysql_query("select cod_cargo,cargo from cargos order by cargo asc");
while($dat_cargo=mysql_fetch_array($sql_cargo))
	{	$cod_cargo=$dat_cargo[0];
		$cargo=$dat_cargo[1];
		echo "<option value='$cod_cargo'>$cargo</option>";
	}
	echo "</select></td>";
	echo "</tr>";
	echo "<tr><th>Email</th><th>Agencia (*)</th><th>Codigo Externo</th><th></th></tr>";
	echo "<tr>";
	echo "<td align='center'><input type='text' name='email' class='texto'></td>";
	echo "<td align='center'><select name='agencia' class='texto'>";
	$sql_agencia=mysql_query("select cod_ciudad,nombre_ciudad from ciudades order by 2");
	while($dat_agencia=mysql_fetch_array($sql_agencia))
		{	$codciudad=$dat_agencia[0];
			$descripcion=$dat_agencia[1];
			if($codciudad==$cod_ciudad)
				{	echo "<option value='$codciudad' selected>$descripcion</option>";
		}	
		else
			{	echo "<option value='$codciudad'>$descripcion</option>";
	}
}
echo "</select></td>";
echo "<td align='center'><input type='text' name='codigoexterno' class='texto'></td>";
echo "<td></td>";
echo "</tr>";
echo "</table><br></center>";

echo "<div class='divBotones'>
		<input type='hidden' name'cod_ciudad' value='$codciudad'>
		<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'>
		<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_funcionarios.php?cod_ciudad=$ciudad_volver\"'>
	<div>";

echo "</form>";
echo "</center>";
echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";
?>