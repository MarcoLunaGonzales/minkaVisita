<?php
require("conexion.inc");
?>
<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript" src="ajax/ejecutar_distribucion/send2.data.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $(".efectua_distribucion").click(function(){
        $('#loaderr').css('display','block')
        var cod_ciclo = $(this).attr('c_ci');
        var cod_gestion = $(this).attr('c_ge');
        sendBanco(cod_ciclo,cod_gestion)
    })
})
</script>
<script language='JavaScript'>
function distribuir(ciclo, gestion)
{	location.href='efectuarDistribucionBanco.php?codCiclo='+ciclo+'&codGestion='+gestion+'';
}
function eliminarDist(ciclo, gestion)
{   if(confirm('Esta seguro de eliminar la distribucion.'))
{   location.href='eliminarDistribucionLineasGruposEspe.php?codCiclo='+ciclo+'&codGestion='+gestion+'';
}
}
</script>

<?php

require("estilos_inicio_adm.inc");
require("funcion_nombres.php");

$sql_gestion = "SELECT codigo_gestion from gestiones where estado='Activo'";
$resp_gestion = mysql_query($sql_gestion);
$dat_gestion = mysql_fetch_array($resp_gestion);
$gestion_activa = $dat_gestion[0];
$sql = "SELECT distinct(cod_ciclo),codigo_gestion, fecha_ini, fecha_fin, estado from ciclos where codigo_gestion in (1014) order by codigo_gestion desc, cod_ciclo desc";
//echo $sql;
$resp = mysql_query($sql);
$indice_tabla = 1;
echo "<center><table border='0' class='textotit'><tr><td>Distribuci&oacute;n de Banco de Muestras</td></tr></table></center><br>";
?>
<div style=" margin: 0 auto; position: relative">
    <div id="loaderr" style="background: #000; width:90%; height:600px; position:absolute; top: 0; left: 5%; opacity: 0.8; z-index: 7; display: none; filter: alpha(opacity = 80);">
        <img src="imagenes/loader.gif" alt="" style="position: absolute; z-index: 9; width: 55px; margin: 0 auto; left: 47%; top: 32%" />
    </div>
    <?php
    echo "<center><table border='1' class='texto' cellspacing='0' width='90%'>";
    echo "<tr><th>Ciclo</th><th>Fecha de Inicio</th><th>Fecha de Fin</th><th>Estado</th>
    <th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th></tr>";
    while ($dat = mysql_fetch_array($resp)) {
        $codigo = $dat[0];
        $codGestion = $dat[1];
        $nombreGestion = nombreGestion($codGestion);
        $fecha_inicio = $dat[2];
        $fecha_inicio = "$fecha_inicio[8]$fecha_inicio[9]-$fecha_inicio[5]$fecha_inicio[6]-$fecha_inicio[0]$fecha_inicio[1]$fecha_inicio[2]$fecha_inicio[3]";
        $fecha_fin = $dat[3];
        $fecha_fin = "$fecha_fin[8]$fecha_fin[9]-$fecha_fin[5]$fecha_fin[6]-$fecha_fin[0]$fecha_fin[1]$fecha_fin[2]$fecha_fin[3]";
        $estado = $dat[4];
        if ($estado == "Activo") {
            $desc_estado = "En Curso";
            echo "<tr><td align='center'>$codigo - $nombreGestion</td><td align='center'>$fecha_inicio</td>
            <td align='center'>$fecha_fin</td>
            <td align='center'>$desc_estado</td>
            <td align='center'><a href='insertaDistribucionGruposEspecialesBanco.php?codigo_ciclo=$codigo&codigo_gestion=$codGestion'>Realizar Distribuci&oacute;n >></a></td>
            <td align='center'><a href='javascript:eliminarDist($codigo,$codGestion)'>Eliminar Distribuci&oacute;n >> </a></td>
            <td align='center'><a href='#' class='efectua_distribucion' c_ci='$codigo' c_ge='$codGestion'>Efectuar Distribuci&oacute;n >></a></td>
            </tr>";
        }
        if ($estado == "Inactivo") {
            $desc_estado = "Programado";
            echo "<tr><td align='center'>$codigo - $nombreGestion</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td>
            <td align='center'>$desc_estado</td>
            <td align='center'><a href='insertaDistribucionGruposEspecialesBanco.php?codigo_ciclo=$codigo&codigo_gestion=$codGestion'>Realizar Distribuci&oacute;n >></a></td>
            <td align='center'><a href='javascript:eliminarDist($codigo,$codGestion)'>Eliminar Distribuci&oacute;n >> </a></td>
            <td align='center'><a href='#' class='efectua_distribucion' c_ci='$codigo' c_ge='$codGestion'>Efectuar Distribuci&oacute;n >></a></td>
            </tr>";
        }
        if ($estado == "Cerrado") {
            $desc_estado = "Cerrado";
            echo "<tr><td align='center'>$codigo - $nombreGestion</td><td align='center'>$fecha_inicio</td><td align='center'>$fecha_fin</td>
            <td align='center'>$desc_estado</td>
            <td align='center'>Realizar Distribuci&oacute;n >></td>
            <td align='center'>Eliminar Distribuci&oacute;n >></td>
            <td align='center'>Efectuar Distribuci&oacute;n >></td>
            </tr>";
        }
        $indice_tabla++;
    }
    echo "</table></center><br>";
    ?>
</div>