<?php
require('conexion.inc');
$sql="select cod_ma,
(select descripcion_material from material_apoyo where codigo_material=cod_ma)nombre, 
sum(p.cantidad_ma) from parrilla_personalizada p where p.cod_gestion=1012 and p.cod_ciclo=5 and
p.cod_ma<>0 and p.cod_ma in (14830,14868,14904,14981,14986,15056,15063,15068,15069,15106,15107,15187,15188,15202,15237,15271,15281) group by p.cod_ma,nombre order by 1;";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	echo $dat[0]." ".$dat[1]." ".$dat[2]."<br>";
}


$sql="select distinct(a.codigo_ma) from asignacion_ma_excel_detalle a order by a.codigo_ma;";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	echo $dat[0]."<br>";
}

?>