<?php

require("conexion.inc");
require("estilos_visitador.inc");
echo "<form method='post' action='' name=''>";


$globalGestion=$global_gestion;

$sql ="SELECT distinct(c.`cod_ciclo`), g.`codigo_gestion`, g.`nombre_gestion` from `ciclos` c, gestiones g 
where c.`codigo_gestion`=g.`codigo_gestion` and g.codigo_gestion in ('$globalGestion')  order by g.codigo_gestion desc, c.cod_ciclo  desc limit 12"; 


$resp=mysql_query($sql);
echo "<h1>Devolucion de MM y MA</h1>";
echo "<center><table class='texto'>";
echo "<tr><th>Ciclo</th><th>Devolucion MM >></th><th>Devolucion MA >></th></tr>";
while($dat=mysql_fetch_array($resp))
{   $codigo=$dat[0];
    $codGestion=$dat[1];
    $nombreGestion =$dat[2];

    $sql1="SELECT * from devoluciones_ciclo d where d.codigo_ciclo = $codigo and d.codigo_gestion = $codGestion and d.codigo_visitador=$global_visitador and d.tipo_devolucion=1";
    $resp1=mysql_query($sql1);
    $numFilasMM=mysql_num_rows($resp1);

    $sql2="SELECT * from devoluciones_ciclo d where d.codigo_ciclo = $codigo and d.codigo_gestion = $codGestion and d.codigo_visitador=$global_visitador and d.tipo_devolucion=2";
    $resp2=mysql_query($sql2);
    $numFilasMA=mysql_num_rows($resp2);

    echo "<tr><td align='center'>$codigo  /  $nombreGestion</td>";
    if($numFilasMM==0){
        echo "<td align='center'><a href='navegador_devolucion_visitador.php?cod_ciclo=$codigo&cod_gestion=$codGestion'>
		<img src='imagenes/devolver.png' width='40'></a></td>";
    }else{
        echo "<td align='center'>-</td>";
    }

    if($numFilasMA==0){
        echo "<td align='center'><a href='navegador_devolucion_visitadorMA.php?cod_ciclo=$codigo&cod_gestion=$codGestion'>
		<img src='imagenes/devolver.png' width='40'></a></td>";
    }else{
        echo "<td align='center'>-</td>";
    }
    echo "</tr>";
}
echo "</table></center><br>";
echo "</form>";

?>
