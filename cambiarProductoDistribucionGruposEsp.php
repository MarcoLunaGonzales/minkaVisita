<?php
require("conexion.inc");
echo "<script language='JavaScript'>
	function enviar(f)
	{	
		if(f.codProdOrigen.value==f.codProdDestino.value){
			alert('Debe elegir productos diferentes.');
			return(false);
		}
		f.submit();
	}
</script>";
require("estilos_gerencia.inc");
require("funcion_nombres.php");
echo "<form name='form1' action='guardaCambiarProductoDistribucionGruposEsp.php' method='post'>";

$global_gestion_distribucion=$_GET['codGestion'];
$global_ciclo_distribucion=$_GET['codCiclo'];

$sql_gestion="select nombre_gestion from gestiones where estado='Activo'";
$resp_gestion=mysql_query($sql_gestion);
$dat_gestion=mysql_fetch_array($resp_gestion);
$nombre_gestion=$dat_gestion[0];

echo "
<input type='hidden' name='codCicloDist' value='$global_ciclo_distribucion'>
<input type='hidden' name='codGestionDist' value='$global_gestion_distribucion'>
";

echo "<center><table border='0' class='textotit'><tr><td align='center'>Cambiar Productos en Distribucion Grupos Especiales<br>
Ciclo: <strong>$global_ciclo_distribucion</strong> Gestión: <strong>$nombre_gestion</strong></td></tr></table></center><br>";

echo "<center><table border='1' class='texto' cellspacing='0' width='80%'>";
echo "<tr><th>Producto a reemplazar</th><th>Producto reemplazo</th></tr>";

$sql="select codigo, concat(descripcion, ' ', presentacion) from muestras_medicas where estado=1 order by 2";
$resp=mysql_query($sql);
echo "<tr><td><select name='codProdOrigen' class='texto'>";
while($dat=mysql_fetch_array($resp)){
	echo "<option value='$dat[0]'>$dat[1]</option>";
}
echo "</select></td>";

$sql="select codigo, concat(descripcion, ' ', presentacion) from muestras_medicas where estado=1 order by 2";
$resp=mysql_query($sql);
echo "<td><select name='codProdDestino' class='texto'>";
while($dat=mysql_fetch_array($resp)){
	echo "<option value='$dat[0]'>$dat[1]</option>";
}
echo "</select></td>";
echo "</table></center><br>";

echo "<center><input type='button' class='boton' value='Intercambiar' onClick='enviar(this.form)'></center></form>";
?>