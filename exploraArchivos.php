<?php

$ruta = dirname(__FILE__)."/";
$array_codif = Array(
"UTF-8",
"ISO-8859-1",
"ISO-8859-15"
);

$codificacion = "ISO-8859-1";

if ($ruta == "") $ruta = dirname(__FILE__)."/";

//Esta variable contendrá la lista de nodos (carpetas y archivos)
$presenta_nodos = "";

if (is_dir($ruta)){//ES UNA CARPETA
    $ruta = realpath($ruta)."/";
    $presenta_nodos = explora_ruta($ruta);
} else {//ES UN ARCHIVO
    $ruta = realpath($ruta);
    $presenta_nodos = explora_ruta(dirname($ruta)."/");
    $contentArchivo=explora_archivo(dirname($ruta),$codificacion);
}

function explora_ruta($ruta){
    $cadena = "";
    $barra = "";
    $manejador = @dir($ruta);
    while ($recurso = $manejador->read()){
        $nombre = "$ruta$recurso";
        if (@is_dir($nombre)) {//ES UNA CARPETA
            //Agregamos la barra al final
            $barra = "/";
            $cadena .= "CARPETA: ";
        } else {//ES UN ARCHIVO
            //No agregamos barra
            $barra = "";
            $cadena .= "ARCHIVO: ";
        }
        if (@is_readable($nombre)){
            $cadena .= "<a href=\"".$_SERVER["PHP_SELF"].
            "?una-ruta=$nombre$barra\">$recurso$barra</a>";
        } else {
            $cadena .= "$recurso$barra";
        }
        $cadena .= "<br />";
    }
    $manejador->close();
    return $cadena;
}

function explora_archivo($ruta, $codif){
    
}

?>
    <?php
        echo "<br/>$presenta_nodos";
    ?>