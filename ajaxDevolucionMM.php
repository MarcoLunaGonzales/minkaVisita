<?php

require("conexion.inc");

$nroReg=$_GET["nregMM"];
$lstMMno=$_GET["lstMMno"];

if($lstMMno=="") {
    $lstMMno="-1";
}
$vecLstMMno=split(",", $lstMMno);
$tam=sizeof($vecLstMMno);
$cadNo="";
for($i=0;$i<$tam;$i++) {
    if($cadNo!="") {$cadNo.=",";}
    $cadNo.="'$vecLstMMno[$i]'";
}
//echo "<br>aaaa:$nroReg<br>";
$sqlProductos="
    SELECT m.codigo, CONCAT(m.descripcion, ' ', m.presentacion)
    FROM muestras_medicas m
    WHERE m.estado = 1 AND m.codigo NOT IN ($cadNo)
    ORDER BY 2";
$respProductos=mysql_query($sqlProductos);
//echo "<tr>";
echo "<td><select id='mmE$nroReg' name='mmE$nroReg' class='texto'>";
echo "<option value='0'></option>";
while($datProductos=mysql_fetch_array($respProductos)){
    $codProducto=$datProductos[0];
    $nombreProducto=$datProductos[1];
    echo "<option value='$codProducto'>$nombreProducto</option>";
}
echo "</select></td>";
echo "<td><input type='text' name='mmEval$nroReg' class='texto' size=5 value='0'></td>";
echo "<td><a href='javascript:eliFilaMM($nroReg);'>Eliminar</a></td>";
//echo "</tr>";

?>
