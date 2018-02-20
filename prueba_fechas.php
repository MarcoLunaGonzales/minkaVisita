<?php
require('function_comparafechas.php');
$f1="2007-06-01"; 
$f2="2006-06-01";
if (compara_fechas($f1,$f2) <0)
    echo "$f1 es menor que $f2 <br>";
if (compara_fechas($f1,$f2) >0)
    echo "$f1 es mayor que $f2 <br>";
if (compara_fechas($f1,$f2) ==0)     
    echo "$f1 es igual  que $f2 <br>";
?>