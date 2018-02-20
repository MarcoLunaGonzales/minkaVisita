<?php
function comprimir ($nom_arxiu)
{
$fptr = fopen("archivos_mail/$nom_arxiu", "rb");
$dump = fread($fptr, filesize($nom_arxiu));
fclose($fptr);

//Comprime al máximo nivel, 9
$gzbackupData = gzencode($dump,9);

$fptr = fopen($nom_arxiu . ".gz", "wb");
fwrite($fptr, $gzbackupData);
fclose($fptr);
//Devuelve el nombre del archivo comprimido
return $nom_arxiu.".zip";
} 
//if ($ok)
//echo "Archivo comprimido correctamente con el nombre ".$ok;
?>
