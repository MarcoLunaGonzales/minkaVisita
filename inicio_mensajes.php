<style>
.info, .success, .warning, .error, .validation {
	border: 1px solid;
	margin: 10px 0px;
	padding:35px 10px 35px 150px;
	background-repeat: no-repeat;
	background-position: 10px center;
	width: 30%;
	margin: 0 auto;
}
.info {
	color: #00529B;
	background-color: #BDE5F8;
	background-image: url('images/info.png');
}
.success {
	color: #4F8A10;
	background-color: #DFF2BF;
	background-image:url('images/success.png');
}
.warning {
	color: #9F6000;
	background-color: #FEEFB3;
	background-image: url('images/warning.png');
}
.error {
	color: #D8000C;
	background-color: #FFBABA;
	background-image: url('images/error.png');
}
a {
	font-size: 12px !important;
}
</style>
<?php
require("estilos_gerencia.inc");
require("conexion.inc");
echo "<h1>Mensajes</h1>";
echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Mensaje</th><th>Fecha</th></tr>";
$sql="SELECT cod_mensaje, mensaje, fecha_mensaje from mensajes order by fecha_mensaje desc";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp))
{
	$codigo=$dat[0];
	$mensaje=$dat[1];
	$fecha=$dat[2];
	echo "<tr>
	<td><img src='imagenes/note.png'></td>
	<td>$mensaje</td>
	<td>$fecha</td></tr>";
}
echo "</table></center>";

?>