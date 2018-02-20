<?php
function convierteFecha($string){
	list($mes, $dia, $año, $hora) = explode(" ", $string);
	if($mes=="Jan"){	$mesx="01";}
	if($mes=="Feb"){	$mesx="02";}
	if($mes=="Mar"){	$mesx="03";}
	if($mes=="Apr"){	$mesx="04";}
	if($mes=="May"){	$mesx="05";}
	if($mes=="Jun"){	$mesx="06";}
	if($mes=="Jul"){	$mesx="07";}
	if($mes=="Aug"){	$mesx="08";}
	if($mes=="Sep"){	$mesx="09";}
	if($mes=="Oct"){	$mesx="10";}
	if($mes=="Nov"){	$mesx="11";}
	if($mes=="Dec"){	$mesx="12";}
	$dia=substr($dia,0,-1);
	
	$nuevaFecha="$año-$mesx-$dia $hora";
	return($nuevaFecha);
}
function sanear_string($string)
{
 
    $string = trim($string);
 
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );
 
    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );
 
    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );
 
    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );
 
    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );
 
    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );
 
    $string = str_replace(
        array('.', ',', ';', '/','-'),
        array('', '', '', '',''),
        $string
    );
    return $string;
}
?>