<script language='JavaScript'>
function totales(){
    var main=document.getElementById('main');   
    var numFilas=main.rows.length;
    var numCols=main.rows[2].cells.length;

    for(var j=1; j<=numCols-1; j++){
        var subtotal=0;
        for(var i=2; i<=numFilas-2; i++){
            var dato=parseFloat(main.rows[i].cells[j].innerHTML);
            subtotal=subtotal+dato;
        }
        var fila=document.createElement('TH');
        main.rows[numFilas-1].appendChild(fila);
        main.rows[numFilas-1].cells[j].innerHTML=subtotal;
    }    
}
</script>
<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_visitador = $_GET["rpt_visitador"];
$rpt_gestion = $_GET["rpt_gestion"];
$rpt_ciclo = $_GET["rpt_ciclo"];
$rpt_territorio = $_GET["rpt_territorio"];
$rptVer = $_GET['rpt_ver'];

$sql_cab = "SELECT cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp1 = mysql_query($sql_cab);
$dato = mysql_fetch_array($resp1);
$nombre_territorio = $dato[1];
$cad_territorio = "<br>Territorio: $nombre_territorio";

$sql_nombreGestion = mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion=$rpt_gestion");
$dat_nombreGestion = mysql_fetch_array($sql_nombreGestion);
$nombreGestion = $dat_nombreGestion[0];
echo "<html><body onload='totales();'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Devolucion de Muestras Medicas<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo $cad_territorio<br>
</th></tr></table></center><br>";

echo "<table border='0' class='textomini' align='center'>";
echo "<tr><td>Leyenda:</td><th>AR</th><th align='Left'>Asignacion Recibida</th></td></tr>";
echo "<tr><td></td><th>CR</th><th align='left'>Cantidad Reportada en HERMES</th></td></tr>";
echo "<tr><td></td><th>CED</th><th align='left'>Cantidad Efectiva Devuelta</th></td></tr>";
echo "<tr><td></td><th>Dif.</th><th align='left'>Diferencia</th></td></tr>";
echo "</table>";



echo "<table border=0 class='texto' align='center' cellspacing=0 id='main'>";
$sql_visitadores = "SELECT paterno, materno, nombres from funcionarios where cod_ciudad='$rpt_territorio'
and estado=1 and cod_cargo='1011' and codigo_funcionario in ($rpt_visitador) order by paterno, materno";
$resp_visitadores = mysql_query($sql_visitadores);
echo "<tr><th>&nbsp;Producto</th>";
while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {
    $cod_visitador = $dat_visitadores[0];
    $nombre_visitador = "$dat_visitadores[0] $dat_visitadores[2]";
    echo "<th colspan='7'>$nombre_visitador</th>";
}
echo "<th colspan='5'>TOTALES</th></tr>";

$resp_visitadores = mysql_query($sql_visitadores);
echo "<tr><th>&nbsp;</th>";
while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {
    echo "<th>AR</th><th>CR</th><th>Saldo</th><th>CED</th><th>Dif.</th>";
}
echo "<th>AR</th><th>CR</th><th>Saldo</th><th>CED</th><th>Dif.</th></tr>";

if ($rptVer == 0) {
    $sql_productos = "SELECT distinct(m.codigo), concat(m.descripcion,' ',m.presentacion) from reg_visita_detalle rvd, 
    reg_visita_cab rvc, muestras_medicas m where rvd.cod_reg_visita = rvc.cod_reg_visita and 
    m.codigo=rvd.cod_muestra and rvc.cod_ciclo = $rpt_ciclo and rvc.cod_gestion = $rpt_gestion 
    and  rvc.cod_visitador in ($rpt_visitador) order by 2";
} else {
    $sql_productos = "SELECT distinct (m.codigo_material), m.descripcion_material from salida_detalle_visitador sv, 
    salida_detalle_almacenes sd, material_apoyo m where sv.cod_salida_almacen = sd.cod_salida_almacen and 
    m.codigo_material = sd.cod_material and sv.codigo_ciclo = $rpt_ciclo and sv.codigo_gestion = $rpt_gestion and 
    sv.codigo_funcionario in ($rpt_visitador) and m.codigo_material<>0 order by 2;";
}
$resp_productos = mysql_query($sql_productos);
while ($dat_productos = mysql_fetch_array($resp_productos)) {
    $cod_prod = $dat_productos[0];
    $nombre_producto = $dat_productos[1];

    $cad_mostrar = "<tr><td>$nombre_producto</td>";
    $sql_visitadores = "SELECT codigo_funcionario, paterno, materno, nombres from funcionarios where cod_ciudad='$rpt_territorio'
    and estado=1 and cod_cargo='1011' and codigo_funcionario in ($rpt_visitador) order by paterno, materno";
    $resp_visitadores = mysql_query($sql_visitadores);

    $totalPlanificado = 0;
    $totalVisitado = 0;
    $totalSaldo = 0;
    $totalDevolucion = 0;
    $totalDiferencia = 0;
    while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {
        $cod_visitador = $dat_visitadores[0];
        if ($rptVer == 0) {
            $sql_plani = "SELECT m.codigo, sum(sd.cantidad_unitaria) 
            from salida_detalle_visitador sv, salida_detalle_almacenes sd, muestras_medicas m, salida_almacenes s
            where sv.cod_salida_almacen = sd.cod_salida_almacen and s.salida_anulada=0 and s.cod_salida_almacenes=sv.cod_salida_almacen
            and s.cod_salida_almacenes=sd.cod_salida_almacen and
            m.codigo=sd.cod_material and sv.codigo_ciclo = $rpt_ciclo and sv.codigo_gestion = $rpt_gestion and 
            sv.codigo_funcionario=$cod_visitador and sd.cod_material='$cod_prod' group by sd.cod_material";
        } else {
            $sql_plani = "SELECT m.codigo_material, sum(sd.cantidad_unitaria) 
            from salida_detalle_visitador sv, salida_detalle_almacenes sd, material_apoyo m 
            where sv.cod_salida_almacen = sd.cod_salida_almacen and 
            m.codigo_material=sd.cod_material and sv.codigo_ciclo = $rpt_ciclo and sv.codigo_gestion = $rpt_gestion and 
            sv.codigo_funcionario=$cod_visitador and sd.cod_material='$cod_prod' group by sd.cod_material";
        }
        // echo $sql_plani."<br />";
        $resp_plani = mysql_query($sql_plani);
        $numfilasPlani = mysql_num_rows($resp_plani);

        if ($numfilasPlani > 0) {
            $cantidad_planificada = mysql_result($resp_plani, 0, 1);
        } else {
            $cantidad_planificada = 0;
        }

        $totalPlanificado = $totalPlanificado + $cantidad_planificada;

        if ($rptVer == 0) {
            $sqlVisitado = "SELECT rd.COD_MUESTRA, (sum(rd.CANT_MM_ENT)+sum(rd.CANT_MM_EXTENT)) as cantidad 
            from reg_visita_cab r, reg_visita_detalle rd 
            where r.COD_REG_VISITA = rd.COD_REG_VISITA and r.COD_CICLO = '$rpt_ciclo' and 
            r.COD_GESTION = '$rpt_gestion' and r.COD_VISITADOR='$cod_visitador' and rd.COD_MUESTRA='$cod_prod' group by rd.COD_MUESTRA";
        } else {
            $sqlVisitado = "SELECT rd.COD_MATERIAL, (sum(rd.CANT_MAT_ENT)+sum(rd.CANT_MAT_EXTENT)) as cantidad 
            from reg_visita_cab r, reg_visita_detalle rd 
            where r.COD_REG_VISITA = rd.COD_REG_VISITA and r.COD_CICLO = '$rpt_ciclo' and 
            r.COD_GESTION = '$rpt_gestion' and r.COD_VISITADOR='$cod_visitador' and rd.COD_MATERIAL='$cod_prod' group by rd.COD_MATERIAL";
        }
//                        echo $sqlVisitado;
        $respVisitado = mysql_query($sqlVisitado);

        $numFilasVisitado = mysql_num_rows($respVisitado);
        if ($numFilasVisitado > 0) {
            $cantidadVisitada = mysql_result($respVisitado, 0, 1);
        } else {
            $cantidadVisitada = 0;
        }


        $totalVisitado = $totalVisitado + $cantidadVisitada;

        $saldo = $cantidad_planificada - $cantidadVisitada;

        $totalSaldo = $totalSaldo + $saldo;

        //SACAMOS LO EFECTIVO DEVUELTO
        $sqlDev = "SELECT dd.cantidad_devolucion from devoluciones_ciclo d, devoluciones_ciclodetalle dd 
        where d.codigo_devolucion = dd.codigo_devolucion and d.codigo_ciclo = '$rpt_ciclo' and d.codigo_gestion = '$rpt_gestion' and      
        d.codigo_visitador = $cod_visitador and dd.codigo_material='$cod_prod'";
        //echo $sqlDev."<br />";
        $respDev = mysql_query($sqlDev);
        $numFilasDev = mysql_num_rows($respDev);

        if ($numFilasDev > 0) {
            $cantEfecDev = mysql_result($respDev, 0, 0);
        } else {
            $cantEfecDev = 0;
        }

        $cantDiferencia = $cantEfecDev - $saldo;
        if ($saldo < 0) {
            $saldo = 0;
            $cantDiferencia = 0;
        }
        $totalDevolucion = $totalDevolucion + $cantEfecDev;
        $totalDiferencia = $totalDiferencia + $cantDiferencia;
        if ($cantidad_planificada == "") {
            $cantidad_planificada = 0;
        }
        if ($cantidadVisitada == "") {
            $cantidadVisitada = 0;
        }
        if ($cantEfecDev == "") {
            $cantEfecDev = 0;
        }
        //sacamos el costo de la muestra
        $sqlCosto = "SELECT costo from costo_muestras where codigo_muestra='$cod_prod'";
        $respCosto = mysql_query($sqlCosto);
        $numFilasCosto = mysql_num_rows($respCosto);
        if ($numFilasCosto == 1) {
            $costoProducto = mysql_result($respCosto, 0, 0);
        } else {
            $costoProducto = 0;
        }
        if ($saldo > 0) {
            $valorDev = $saldo * $costoProducto;
            ;
        } else {
            $valorDev = 0;
        }

        $cad_mostrar.="<td>$cantidad_planificada</td><td>$cantidadVisitada</td><td>$saldo</td>
		<td>$cantEfecDev</td><td>$cantDiferencia</td>";
    }
    $cad_mostrar.="<th>$totalPlanificado</th><th>$totalVisitado</th><th>$totalSaldo</th><th>$totalDevolucion</th><th>$totalDiferencia</th>";
    echo $cad_mostrar;
}
echo "<tr><TH>TOTALES</TH></tr>";
echo "</table>";
echo "</form></body></html>";
require("imprimirInc.php");

?>