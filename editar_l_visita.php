<?php
echo "<script language='Javascript'>
	function validar(f)
	{
		if(f.nombre_linea.value=='')
		{	alert('El campo Nombre de Línea de Visita esta vacio.');
			f.categoria.focus();
			return(false);
		}
		if(f.espe.value=='')
		{	alert('El campo Especialidad no debe ser nulo.');
			return(false);
		}
		f.submit();
	}
	</script>";
require("conexion.inc");
require("estilos.inc");
echo "<form action='guarda_modi_linea_visita.php?cod_l_visita=$cod_linea_vis' method='post'>";
$sql="select * from lineas_visita where codigo_l_visita='$cod_linea_vis'";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$nombre_linea=$dat[1];
echo "<center><table border='0' class='textotit'><tr><td>Editar Línea de Visita</td></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0'>";
echo "<tr><th>Nombre de Línea de Visita</th></tr>";
echo "<tr><td align='center'><input type='text' class='texto' value='$nombre_linea' name='nombre_linea'></td>";
echo "</tr></table><br>";
echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<input type='button' class='boton' value='Guardar' onClick='validar(this.form)'></center>";
echo "</form>";
?>