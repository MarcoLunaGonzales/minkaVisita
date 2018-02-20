<?php

$cadena="asd";
echo $cadena."<br>";
$tipo_cadena=gettype($cadena);
echo $tipo_cadena."<br>";
echo settype($cadena,"integer");
$tipo_cadena=gettype($cadena);
echo $tipo_cadena."<br>";
echo $cadena;
?>