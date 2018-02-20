<?php
require("conexion.inc");
require("estilos_visitador.inc");
/*$numero2=count($_POST);
$tags2=array_keys($_POST);
$valores2=array_values($_POST);*/

$ciclo_global   = $_GET['cod_ciclo'];
$global_gestion = $_GET['cod_gestion'];

$tam1MM       = $_GET["nrMM"];
$tam2MM       = $_GET["nregsMM"];
$cadLstMM     = $_GET["rMM"];
$cadLstMMval  = $_GET["rMMval"];
$cadLstMMteo  = $_GET["rMMteo"];
$cadLstMMe    = $_GET["mmE"];
$cadLstMMeval = $_GET["mmEval"];
/*$tamMM     = sizeof($cadLstMM);
$tamMMval  = sizeof($cadLstMMval);
$tamMMteo  = sizeof($cadLstMMteo);
$tamMMe    = sizeof($cadLstMMe);
$tamMMeval = sizeof($cadLstMMeval);
$lstMM     = split(",",$cadLstMM);
$lstMMval  = split(",",$cadLstMMval);
$lstMMteo  = split(",",$cadLstMMteo);
$lstMMe    = split(",",$cadLstMMe);
$lstMMeval = split(",",$cadLstMMeval);
*/

if($tamMM==$tamMMval && $tamMM==$tamMMteo && $tamMMe==$tamMMeval) {
    //
    //sacamos el codigo maximo
    $respMax=mysql_query("select codigo_devolucion from devoluciones_ciclo order by codigo_devolucion desc");
    $datMax=mysql_fetch_array($respMax);
    $filasMax=mysql_num_rows($respMax);
    if($filasMax==0) {
        $codigoDevolucion=1;
    }else{
        $codigoDevolucion=$datMax[0]+1;
    }
    //
    $sqlInsertCab="insert into devoluciones_ciclo values('$codigoDevolucion','$global_gestion','$ciclo_global','$global_visitador',1,1)";
    $respInsertCab=mysql_query($sqlInsertCab);
    //
    for($i=1;$i<=$tam1MM;$i++) {
        $codigoMaterial=$_GET["rMM".$i];
        $cantidadTeorica=$_GET["rMMteo".$i];
        $cantidadDevolucion=$_GET["rMMval".$i];
        $sqlInsertDet="insert into devoluciones_ciclodetalle values('$codigoDevolucion','$codigoMaterial','$cantidadTeorica','$cantidadDevolucion',0)";
        $respInsertDet=mysql_query($sqlInsertDet);
    }
    //
    $tam2MM=$tam2MM-1;// -1 para discriminar la opcion de adicionar nuevo registro extra
    for($i=1;$i<=$tam2MM;$i++) {
        $codigoMaterial=$_GET["mmE".$i];
        $cantidadTeorica=0;
        $cantidadDevolucion=$_GET["mmEval".$i];
        if(trim($codigoMaterial)!="" && trim($cantidadDevolucion)!="") {
            $sqlInsertDet="insert into devoluciones_ciclodetalle values('$codigoDevolucion','$codigoMaterial','$cantidadTeorica','$cantidadDevolucion',0)";
            $respInsertDet=mysql_query($sqlInsertDet);
        } else {
            //echo "$respInsertDet [$codigoMaterial, $cantidadDevolucion]&nbsp;";
        }
    }
    //
    echo "
<script language='JavaScript'>
    alert('Los datos se registraron satisfactoriamente');
    location.href='navegador_devolucion_visitadorCiclo.php';
</script>";
    //
} else {
    echo "
<script language='JavaScript'>
    alert('Error en el conteo de registros.');
    //location.href='navegador_devolucion_visitadorCiclo.php';
</script>";
}

?>
