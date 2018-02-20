<?php
require("conexion.inc");
$id=$_GET['id'];
header('Content-type: image/png');

$sql="select firma from boletas_visita_cab where id_boleta='$id'";
$resp=mysql_query($sql);
$data=mysql_result($resp,0,0);

$data=base64_decode($data);
$img = imagecreatefromstring($data);
imagepng($img);
imagedestroy($img);
?>