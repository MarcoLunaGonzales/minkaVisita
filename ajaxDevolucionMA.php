<?php

require("conexion.inc");

$nroReg=$_GET["nregMA"];
$lstMAno=$_GET["lstMAno"];

if($lstMAno=="") {
    $lstMAno="-1";
}
$vecLstMAno=split(",", $lstMAno);
$tam=sizeof($vecLstMAno);
$cadNo="";
for($i=0;$i<$tam;$i++) {
    if($cadNo!="") {$cadNo.=",";}
    $cadNo.="'$vecLstMAno[$i]'";
}
//echo "<br>bbbb:$nroReg<br>";
$sqlProductos="
    SELECT m.codigo_material, m.descripcion_material
    FROM material_apoyo m
    WHERE m.estado = 'Activo' AND m.codigo_material<>0 AND m.codigo_material NOT IN ($cadNo)
    ORDER BY 2";
$respProductos=mysql_query($sqlProductos);
//echo "<tr>";
echo "<td><select id='maE$nroReg' name='maE$nroReg' class='texto'>";
echo "<option value='0'></option>";
while($datProductos=mysql_fetch_array($respProductos)){
    $codProducto=$datProductos[0];
    $nombreProducto=$datProductos[1];
    echo "<option value='$codProducto'>$nombreProducto</option>";
}
echo "</select></td>";
echo "<td><input type='text' name='maEval$nroReg' class='texto' size=5 value='0'></td>";
echo "<td><a href='javascript:eliFilaMA($nroReg);'>Eliminar</a></td>";
//echo "</tr>";

?>
