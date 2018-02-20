<script language='JavaScript'>
    function totales(){
        var main=document.getElementById('main');
        var numFilas=main.rows.length;
        var numCols=main.rows[2].cells.length;
        for(var j=1; j<=numCols-1; j++) {
            var subtotal=0;
            for(var i=2; i<=numFilas-2; i++) {
                var dato=parseInt(main.rows[i].cells[j].innerHTML);
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

$rpt_gestion = $_GET["rpt_gestion"];
$rpt_ciclo = $_GET["rpt_ciclo"];
$rpt_territorio = $_GET["rpt_territorio"];
$rpt_linea = $_GET['rpt_linea'];
$rptNombreLinea = $_GET['rptNombreLinea'];
$rptVer = $_GET['rpt_ver'];
$tipo_distribucion = $_GET['rpt_ver_dis'];


$sql_nombreGestion = mysql_query("select nombre_gestion from gestiones where codigo_gestion=$rpt_gestion");
$dat_nombreGestion = mysql_fetch_array($sql_nombreGestion);
$nombreGestion = $dat_nombreGestion[0];
echo "<html><body onload='totales();'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Reporte Distribuci�n x Ciclo x Territorio<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo<br>
Linea: $rptNombreLinea
</th></tr></table></center><br>";

echo "<table border=1 class='texto' cellspacing=0 cellpading=0 id='main' align='center'>";
$sql_territorio = "select cod_ciudad, descripcion from ciudades where cod_ciudad in ($rpt_territorio) order by descripcion";

$resp_territorios = mysql_query($sql_territorio);
echo "<tr>";
echo "<th>&nbsp;C&oacute;digo</th>";
echo "<th>&nbsp;Producto</th>";
while ($dat_territorios = mysql_fetch_array($resp_territorios)) {
    $cod_territorio = $dat_territorios[0];
    $nombre_territorio = $dat_territorios[1];
    if ($tipo_distribucion == 0 or $tipo_distribucion == 1) {
        echo "<th colspan='3'>$nombre_territorio</th>";
    } else {
        echo "<th colspan='3'>$nombre_territorio</th>";
    }
}
if ($tipo_distribucion == 0 or $tipo_distribucion == 1) {
    echo "<th colspan='3'>TOTALES</th>";
} else {
    echo "<th colspan='3'>TOTALES</th>";
}

echo "</tr>";
echo "<tr>";
echo "<th>&nbsp;</th>";
$resp_territorios = mysql_query($sql_territorio);
while ($dat_territorios = mysql_fetch_array($resp_territorios)) {
    if ($tipo_distribucion == 0) {
        echo "<th>CP</th><th>CD</th><th>Dif.</th>";
    }
    if ($tipo_distribucion == 1) {
        echo "<th>CP(GE)</th><th>CD(GE)</th><th>Dif.(GE)</th>";
    }
    if ($tipo_distribucion == 2) {
        echo "<th>CP(B)</th><th>CD(B)</th><th>Dif.(B)</th>";
    }
}
if ($tipo_distribucion == 0) {
    echo "<th>CP</th><th>CD</th><th>Dif.</th>";
}
if ($tipo_distribucion == 1) {
    echo "<th>CP(GE)</th><th>CD(GE)</th><th>Dif.(GE)</th>";
}
if ($tipo_distribucion == 2) {
    echo "<th>CP(B)</th><th>CD(B)</th><th>Dif.(B)</th>";
}
//echo "<th>CP(GE)</th><th>CD(GE)</th><th>Dif.(GE)</th><th>CBancoMM</th>";
echo "</tr>";

if ($rptVer == 0) {
    $sql_productos = "
        select * from (select distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
        d.grupo_salida from distribucion_productos_visitadores d, `muestras_medicas` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        d.codigo_linea in ($rpt_linea) and m.`codigo`=d.`codigo_producto` 
			union 
        select distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
        d.grupo_salida from distribucion_grupos_especiales d, `muestras_medicas` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and d.codigo_linea in ($rpt_linea) and
        m.`codigo`=d.`codigo_producto`  
                                                        union
        select distinct (d.codigo_producto), concat(m.`descripcion`,' ',m.presentacion),
        d.grupo_salida from distribucion_banco_muestras d, `muestras_medicas` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and d.codigo_linea in ($rpt_linea) and
        m.`codigo`=d.`codigo_producto`) as tabla order by 2";
} else {
    $sql_productos = "select * from (select distinct (d.codigo_producto), m.`descripcion_material`,
        d.grupo_salida from distribucion_productos_visitadores d, `material_apoyo` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        d.codigo_linea in ($rpt_linea) and m.`codigo_material`=d.`codigo_producto`
        and m.codigo_material<>0 
			union 
		select distinct (d.codigo_producto), m.`descripcion_material`,
        d.grupo_salida from `distribucion_grupos_especiales` d, `material_apoyo` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        m.`codigo_material`=d.`codigo_producto` and d.codigo_linea in ($rpt_linea)
        and m.codigo_material<>0
			union 
		select distinct (d.codigo_producto), m.`descripcion_material`,
        d.grupo_salida from `distribucion_banco_muestras` d, `material_apoyo` m
        where d.cod_ciclo in ($rpt_ciclo) and d.codigo_gestion = $rpt_gestion and
        m.`codigo_material`=d.`codigo_producto` and d.codigo_linea in ($rpt_linea)
        and m.codigo_material<>0) as tabla order by 2";
}

//echo $sql_productos;

$resp_productos = mysql_query($sql_productos);
while ($dat_productos = mysql_fetch_array($resp_productos)) {
    $cod_prod = $dat_productos[0];
    $nombre_producto = $dat_productos[1];
    $grupo_salida = $dat_productos[2];
    //sacamos el nombre del producto
    $cad_mostrar = "<tr>";
    $cad_mostrar2 = "<tr>";
    $cad_mostrar3 = "<tr>";
    $cad_mostrar.="<td>$cod_prod</td>";
    $cad_mostrar.="<td>$nombre_producto</td>";
    $cad_mostrar2 .="<td>$nombre_producto</td>";
    $cad_mostrar3 .="<td>$nombre_producto</td>";
    $sql_territorio = "select cod_ciudad, descripcion from ciudades where cod_ciudad in ($rpt_territorio) order by descripcion";
    $resp_territorios = mysql_query($sql_territorio);
    //
    $suma_cantidadfaltante = 0;
    $suma_cantidadfaltante2 = 0;
    $totalPlanificado = 0;
    $totalPlanificado2 = 0;
    $totalPlanificado2_b = 0;
    $totalDistribuido = 0;
    $totalDistribuido2 = 0;
    $totalDistribuido2_b = 0;
    $totalFaltante = 0;
    $totalFaltante2 = 0;
    $totalFaltante2_b = 0;
    $totalBMM = 0;
    //
    while ($dat_territorios = mysql_fetch_array($resp_territorios)) {
        $cod_territorio = $dat_territorios[0];
        $nombre_territorio = $dat_territorios[1];
        //
        $sql_dist = "
            select sum(cantidad_planificada), sum(cantidad_distribuida)
            from distribucion_productos_visitadores
            where codigo_gestion='$rpt_gestion' and cod_ciclo in ('$rpt_ciclo') and codigo_producto='$cod_prod'
            and territorio='$cod_territorio' and codigo_linea in ($rpt_linea)
            group by territorio";
        $resp_dist = mysql_query($sql_dist);
        $dat_dist = mysql_fetch_array($resp_dist);
        $cantidad_planificada = $dat_dist[0];
        $cantidad_distribuida = $dat_dist[1];
        $cantidad_faltante = $cantidad_planificada - $cantidad_distribuida;
        $suma_cantidadfaltante = $suma_cantidadfaltante + $cantidad_faltante;
        $totalPlanificado = $totalPlanificado + $cantidad_planificada;
        $totalDistribuido = $totalDistribuido + $cantidad_distribuida;
        $totalFaltante = $totalFaltante + $cantidad_faltante;
        if ($cantidad_planificada == "") {
            $cantidad_planificada = 0;
        }
        if ($cantidad_distribuida == "") {
            $cantidad_distribuida = 0;
        }
        $cad_mostrar.="<td>$cantidad_planificada</td><td>$cantidad_distribuida</td><td>$cantidad_faltante</td>";
        //COLUMNAS DISTRIBUCION DE GRUPOS ESPECIALES
        $sql_dist = "
            select sum(cantidad_planificada), sum(cantidad_distribuida), sum(cantidad_bancomm)
            from distribucion_grupos_especiales
            where codigo_gestion='$rpt_gestion' and cod_ciclo in ($rpt_ciclo) and codigo_producto='$cod_prod'
            and territorio='$cod_territorio' and codigo_linea in ($rpt_linea)
            group by territorio";
        //echo $sql_dist;
        $resp_dist = mysql_query($sql_dist);
        $dat_dist = mysql_fetch_array($resp_dist);
        $cantidad_planificada = $dat_dist[0];
        $cantidad_distribuida = $dat_dist[1];
        $cantidad_banco = $dat_dist[2];
        $cantidad_faltante = $cantidad_planificada - $cantidad_distribuida;
        $suma_cantidadfaltante2 = $suma_cantidadfaltante2 + $cantidad_faltante;
        $totalPlanificado2 = $totalPlanificado2 + $cantidad_planificada;
        $totalDistribuido2 = $totalDistribuido2 + $cantidad_distribuida;
        $totalFaltante2 = $totalFaltante2 + $cantidad_faltante;
        $totalBMM = $totalBMM + $cantidad_banco;
        if($cantidad_banco == ''){
            $cantidad_banco = 0;
        }
        if ($cantidad_planificada == "") {
            $cantidad_planificada = 0;
        }
        if ($cantidad_distribuida == "") {
            $cantidad_distribuida = 0;
        }
        $cad_mostrar2 .="<td bgcolor='#CED8F6'>$cantidad_planificada</td>
		<td bgcolor='#CED8F6'>$cantidad_distribuida</td>
		<td bgcolor='#CED8F6'>$cantidad_faltante</td>";
//		<td bgcolor='#CED8F6'>$cantidad_banco</td>";
        //
        //COLUMNAS DISTRIBUCION DE banco de muestras
        $sql_dist_b = "
            select sum(cantidad_planificada), sum(cantidad_distribuida)
            from distribucion_banco_muestras
            where codigo_gestion='$rpt_gestion' and cod_ciclo in ($rpt_ciclo) and codigo_producto='$cod_prod'
            and territorio='$cod_territorio' and codigo_linea in ($rpt_linea)
            group by territorio";
//        echo $sql_dist;
        $resp_dist_b = mysql_query($sql_dist_b);
        $dat_dist_b = mysql_fetch_array($resp_dist_b);
        $cantidad_planificada_b = $dat_dist_b[0];
        $cantidad_distribuida_b = $dat_dist_b[1];
        $cantidad_faltante_b = $cantidad_planificada_b - $cantidad_distribuida_b;
        $suma_cantidadfaltante2_b = $suma_cantidadfaltante2_b + $cantidad_faltante_b;
        $totalPlanificado2_b = $totalPlanificado2_b + $cantidad_planificada_b;
        $totalDistribuido2_b = $totalDistribuido2_b + $cantidad_distribuida_b;
        $totalFaltante2_b = $totalFaltante2_b + $cantidad_faltante_b;
        if ($cantidad_planificada_b == "") {
            $cantidad_planificada_b = 0;
        }
        if ($cantidad_distribuida_b == "") {
            $cantidad_distribuida_b = 0;
        }
        
        $cad_mostrar3 .="<td bgcolor='#CED8F6'>$cantidad_planificada_b</td>
		<td bgcolor='#CED8F6'>$cantidad_distribuida_b</td>
		<td bgcolor='#CED8F6'>$cantidad_faltante_b</td>";
    }
    $cad_mostrar .="<th>$totalPlanificado</th><th>$totalDistribuido</th><th>$totalFaltante</th>";
//    $cad_mostrar2 .="<th>$totalPlanificado2</th><th>$totalDistribuido2</th><th>$totalFaltante2</th><th>$totalBMM</th>";
    $cad_mostrar2 .="<th>$totalPlanificado2</th><th>$totalDistribuido2</th><th>$totalFaltante2</th>";
    $cad_mostrar3 .="<th>$totalPlanificado2_b</th><th>$totalDistribuido2_b</th><th>$totalFaltante2_b</th>";
    //
    $cad_mostrar.="</tr>";
    $cad_mostrar2 .="</tr>";
    $cad_mostrar3 .="</tr>";
    if ($tipo_distribucion == 0) {
        echo $cad_mostrar;
    }
    if ($tipo_distribucion == 1) {
        echo $cad_mostrar2;
    }
    if ($tipo_distribucion == 2) {
        echo $cad_mostrar3;
    }
}
echo "<tr><th>TOTALES</th></tr>";
echo "</table><br>";
echo "<center>";
echo "<table border='0'>";
echo "<tr><td><a href='javascript:window.print();'><IMG border='no' src='imagenes/print.gif'>Imprimir</a></td></tr>";
echo "</table>";
echo "<center>";
?>
