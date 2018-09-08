<?php
session_start();

$codCUP=$_GET["codCUP"];
$codMed=$_GET["codMed"];
$parametro=$_GET["parametro"];
$idMercado=$_GET["idMercado"];

//verificamos si existe el medico en CUP
$_SESSION['codCUP']=$codCUP;
$_SESSION['idMercado']=$idMercado;


if($parametro==1){
	header("Location:navMedicoLabCup.php?codCUP=$codCUP&codMed=$codMed");
}
if($parametro==2){
	header("Location:navMedicoMercadoCup.php?codCUP=$codCUP&codMed=$codMed");
}
if($parametro==3){
	header("Location:navMedicoProductoCup.php?codCUP=$codCUP&codMed=$codMed&idMercado=$idMercado");
}


?>