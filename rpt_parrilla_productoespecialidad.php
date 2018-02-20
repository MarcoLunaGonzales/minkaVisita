<script language='JavaScript'>
function totales(){
   var main=document.getElementById('main');   
   var numFilas=main.rows.length;
   var numCols=main.rows[2].cells.length;
	 
	 for(var j=1; j<=numCols-1; j++){
	 		var subtotal=0;
	 		for(var i=2; i<=numFilas-2; i++){
	 				var dato=parseInt(main.rows[i].cells[j].innerHTML);
	 				subtotal=subtotal+dato;
	 		}
	 		var fila=document.createElement('TD');
			main.rows[numFilas-1].appendChild(fila);
			main.rows[numFilas-1].cells[j].innerHTML=subtotal;
	 }	 
}
</script>
<?php
require("conexion.inc");
require("estilos_reportes.inc");
$global_linea = $linea_rpt;
$sql_cabecera_gestion = mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
$datos_cab_gestion = mysql_fetch_array($sql_cabecera_gestion);
$nombre_cab_gestion = $datos_cab_gestion[0];
$nombre_linea = "select nombre_linea from lineas where codigo_linea='$global_linea'";
$resp_linea = mysql_query($nombre_linea);
$dat_linea = mysql_fetch_array($resp_linea);
$nombre_linea = $dat_linea[0];

$sqlNombreTerr="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$respNombreTerr=mysql_query($sqlNombreTerr);
$datNombreTerr=mysql_fetch_array($respNombreTerr);
$nombreTerritorio=$datNombreTerr[0];

echo "<html><body onload='totales();'>";
echo "<form method='post' action='opciones_medico.php' >";
$sql_ciclo = mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
$dat = mysql_fetch_array($sql_ciclo);
$cod_ciclo = $dat[0];
echo "<center><table border='0' class='textotit'><tr><th>Productos de Parrilla por Especialidad <br>
Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt Línea: $nombre_linea<br>Territorio: $nombreTerritorio</th></tr></table></center><br>";
// //////////
$sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
$resp = mysql_query($sql);
$num_especialidades = mysql_num_rows($resp);
echo "<center><table border='1' class='textomini' cellspacing='0' width='100%' id='main'>";
echo "<tr><th>Especialidad</th>";
$num_lineasvisita = 1;
while ($dat = mysql_fetch_array($resp)) {
    $cod_espe = $dat[0];
    $nombre_espe = $dat[1];
    echo "<th colspan=4>$nombre_espe</th>";
    $sql_lineasvisita = "select l.codigo_l_visita, l.nombre_l_visita
						from lineas_visita l, lineas_visita_especialidad le
							where l.codigo_l_visita=le.codigo_l_visita and le.cod_especialidad='$cod_espe' and l.codigo_linea='$linea_rpt'";
    $resp_lineasvisita = mysql_query($sql_lineasvisita);
    while ($dat_lineasvisita = mysql_fetch_array($resp_lineasvisita)) {
        $cod_lineavisita = $dat_lineasvisita[0];
        $nombre_lineavisita = $dat_lineasvisita[1];
        echo "<th colspan=4>$nombre_lineavisita</th>";
        $num_lineasvisita++;
    } 
} 
$num_especialidades = $num_especialidades + $num_lineasvisita -1;
echo "</tr>";
echo "<tr><th>&nbsp;</th>";
for($i=1; $i<=$num_especialidades; $i++) {
    echo "<th>A</th><th>B</th><th>C</th><th>Total</th>";
} 
echo "</tr>";
$sql_prod = "select distinct(pd.codigo_muestra), m.descripcion, m.presentacion
				from parrilla p, parrilla_detalle pd, muestras_medicas m
				 where m.codigo=pd.codigo_muestra and p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$linea_rpt'
				 and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and p.agencia='$rpt_territorio' 
				 order by m.descripcion";
$resp_prod = mysql_query($sql_prod);
while ($dat_prod = mysql_fetch_array($resp_prod)) {
    $cod_producto = $dat_prod[0];
    $nombre_producto = $dat_prod[1];
    $presentacion = $dat_prod[2];
    echo "<tr><th>$nombre_producto $presentacion</th>";
    $sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
    $resp = mysql_query($sql);
    while ($dat = mysql_fetch_array($resp)) {
        $cod_espe = $dat[0];
        $desc_espe = $dat[1];
        $sql_cant_a = "select sum(pd.cantidad_muestra)
				from parrilla p, parrilla_detalle pd
				 where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$linea_rpt'
				 and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and
				 pd.codigo_muestra='$cod_producto' and p.cod_especialidad='$cod_espe' and p.categoria_med='A' and p.agencia='$rpt_territorio' and p.codigo_l_visita='0'";
        $resp_cant_a = mysql_query($sql_cant_a);
        $dat_cant_a = mysql_fetch_array($resp_cant_a);
        $cantidad_prod_a = $dat_cant_a[0];
        if($cantidad_prod_a==""){$cantidad_prod_a=0;}

        $sql_cant_b = "select sum(pd.cantidad_muestra)
				from parrilla p, parrilla_detalle pd
				 where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$linea_rpt'
				 and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and
				 pd.codigo_muestra='$cod_producto' and p.cod_especialidad='$cod_espe' and p.categoria_med='B' and p.agencia='$rpt_territorio' and p.codigo_l_visita='0'";
        $resp_cant_b = mysql_query($sql_cant_b);
        $dat_cant_b = mysql_fetch_array($resp_cant_b);
        $cantidad_prod_b = $dat_cant_b[0];
        if($cantidad_prod_b==""){$cantidad_prod_b=0;}

        $sql_cant_c = "select sum(pd.cantidad_muestra)
				from parrilla p, parrilla_detalle pd
				 where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$linea_rpt'
				 and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and
				 pd.codigo_muestra='$cod_producto' and p.cod_especialidad='$cod_espe' and p.categoria_med='C' and p.agencia='$rpt_territorio' and p.codigo_l_visita='0'";
        $resp_cant_c = mysql_query($sql_cant_c);
        $dat_cant_c = mysql_fetch_array($resp_cant_c);
        $cantidad_prod_c = $dat_cant_c[0];
        if($cantidad_prod_c==""){$cantidad_prod_c=0;}

				$subtotal=$cantidad_prod_a+$cantidad_prod_b+$cantidad_prod_c;
        echo "<td title='$cod_espe A $nombre_producto'>$cantidad_prod_a</td>
        <td title='$cod_espe B $nombre_producto'>$cantidad_prod_b</td>
        <td title='$cod_espe C $nombre_producto'>$cantidad_prod_c</td>
        <td title='$cod_espe $nombre_producto'>$subtotal</td>";

        $sql_lineasvisita = "select l.codigo_l_visita, l.nombre_l_visita
						from lineas_visita l, lineas_visita_especialidad le
							where l.codigo_l_visita=le.codigo_l_visita and le.cod_especialidad='$cod_espe' and l.codigo_linea='$linea_rpt'";
				
        $resp_lineasvisita = mysql_query($sql_lineasvisita);
        while ($dat_lineasvisita = mysql_fetch_array($resp_lineasvisita)) {
            $cod_lineavisita = $dat_lineasvisita[0];
            $sql_cant_a = "select sum(pd.cantidad_muestra)
						from parrilla p, parrilla_detalle pd
				 		where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$linea_rpt'
				 		and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and
				 		pd.codigo_muestra='$cod_producto' and p.cod_especialidad='$cod_espe' and p.categoria_med='A' and p.agencia='$rpt_territorio' and p.codigo_l_visita='$cod_lineavisita'";
            $resp_cant_a = mysql_query($sql_cant_a);
            $dat_cant_a = mysql_fetch_array($resp_cant_a);
            $cantidad_prod_a = $dat_cant_a[0];
		        if($cantidad_prod_a==""){$cantidad_prod_a=0;}

            $sql_cant_b = "select sum(pd.cantidad_muestra)
						from parrilla p, parrilla_detalle pd
				 		where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$linea_rpt'
				 		and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and
				 		pd.codigo_muestra='$cod_producto' and p.cod_especialidad='$cod_espe' and p.categoria_med='B' and p.agencia='$rpt_territorio' and p.codigo_l_visita='$cod_lineavisita'";
            $resp_cant_b = mysql_query($sql_cant_b);
            $dat_cant_b = mysql_fetch_array($resp_cant_b);
            $cantidad_prod_b = $dat_cant_b[0];
        		if($cantidad_prod_b==""){$cantidad_prod_b=0;}

            $sql_cant_c = "select sum(pd.cantidad_muestra)
						from parrilla p, parrilla_detalle pd
						 where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$linea_rpt'
						 and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and
				 		pd.codigo_muestra='$cod_producto' and p.cod_especialidad='$cod_espe' and p.categoria_med='C' and p.agencia='$rpt_territorio' and p.codigo_l_visita='$cod_lineavisita'";
            $resp_cant_c = mysql_query($sql_cant_c);
            $dat_cant_c = mysql_fetch_array($resp_cant_c);
            $cantidad_prod_c = $dat_cant_c[0];
        		if($cantidad_prod_c==""){$cantidad_prod_c=0;}
        		
            $subtotal=$cantidad_prod_a+$cantidad_prod_b+$cantidad_prod_c;
            
            echo "<td title='$cod_espe $cod_lineavisita A $nombre_producto'>$cantidad_prod_a</td>
            <td title='$cod_espe $cod_lineavisita B $nombre_producto'>$cantidad_prod_b</td>
            <td title='$cod_espe $cod_lineavisita C $nombre_producto'>$cantidad_prod_c</td>
            <td title='$cod_espe $cod_lineavisita $nombre_producto'>$subtotal</td>";
            
        } 
    } 
    echo "</tr>";
} 
echo "<tr><TH>TOTALES</TH></tr>";
echo "</table></center><br>";
echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
echo "</form></body></html>";
?>