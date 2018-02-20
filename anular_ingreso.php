<?php
echo "<script language='JavaScript'>
	function validar(f)
	{	if(f.observaciones.value=='')
		{	alert('El campo observaciones no puede estar vacio.');
			return(false);
		}
		f.submit();			
	}
	</script>";
require("conexion.inc");
if($global_tipoalmacen==1)
{	require('estilos_almacenes_central_sincab.php');
}
else
{	require('estilos_almacenes_sincab.inc');
}
echo "<form action='guarda_anular_ingreso.php'>";
echo "<table class='texto' align='center'><tr><th>Escriba el motivo de la anulación del ingreso.</th></tr></table>";
echo "<center><textarea rows='3' cols='25' name='observaciones' class='texto'></textarea><br><br>";
echo "<input type='hidden' name='codigo_registro' value='$codigo_registro'>";
echo "<input type='hidden' name='grupo_ingreso' value='$grupo_ingreso'>";
echo "<input class='boton' type='button' value='Registrar anulación' OnClick='validar(this.form)'></center>";
echo "</form>";
?>