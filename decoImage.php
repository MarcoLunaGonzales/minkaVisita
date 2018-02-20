<?php
require("conexion.inc");
$id=$_GET['id'];
$tabla=$_GET['tabla'];
header('Content-type: image/png');

$sql="select firma from $tabla where id_boleta='$id'";
$resp=mysql_query($sql);
$data=mysql_result($resp,0,0);

$data=base64_decode($data);
$img = imagecreatefromstring($data);
imagepng($img);
imagedestroy($img);
?>