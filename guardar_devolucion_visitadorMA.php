<?php
require("conexion.inc");
require("estilos_visitador.inc");
/*$numero2=count($_POST);
$tags2=array_keys($_POST);
$valores2=array_values($_POST);*/

$ciclo_global   = $_GET['cod_ciclo'];
$global_gestion = $_GET['cod_gestion'];

$tam1MA       = $_GET["nrMA"];
$tam2MA       = $_GET["nregsMA"];
$cadLstMA     = $_GET["rMA"];
$cadLstMAval  = $_GET["rMAval"];
$cadLstMAteo  = $_GET["rMAteo"];
$cadLstMAe    = $_GET["maE"];
$cadLstMAeval = $_GET["maEval"];
/*$tamMA     = sizeof($cadLstMA);
$tamMAval  = sizeof($cadLstMAval);
$tamMAteo  = sizeof($cadLstMAteo);
$tamMAe    = sizeof($cadLstMAe);
$tamMAeval = sizeof($cadLstMAeval);
$lstMA     = split(",",$cadLstMA);
$lstMAval  = split(",",$cadLstMAval);
$lstMAteo  = split(",",$cadLstMAteo);
$lstMAe    = split(",",$cadLstMAe);
$lstMAeval = split(",",$cadLstMAeval);
*/

if($tamMA==$tamMAval && $tamMA==$tamMAteo && $tamMAe==$tamMAeval) {
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
    $sqlInsertCab="insert into devoluciones_ciclo values('$codigoDevolucion','$global_gestion','$ciclo_global','$global_visitador',1,2)";
    $respInsertCab=mysql_query($sqlInsertCab);
    //
    for($i=1;$i<=$tam1MA;$i++) {
        $codigoMaterial=$_GET["rMA".$i];
        $cantidadTeorica=$_GET["rMAteo".$i];
        $cantidadDevolucion=$_GET["rMAval".$i];
        $sqlInsertDet="insert into devoluciones_ciclodetalle values('$codigoDevolucion','$codigoMaterial','$cantidadTeorica','$cantidadDevolucion',0)";
        $respInsertDet=mysql_query($sqlInsertDet);
    }
    //
    $tam2MA=$tam2MA-1;// -1 para discriminar la opcion de adicionar nuevo registro extra
    for($i=1;$i<=$tam2MA;$i++) {
        $codigoMaterial=$_GET["maE".$i];
        $cantidadTeorica=0;
        $cantidadDevolucion=$_GET["maEval".$i];
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
