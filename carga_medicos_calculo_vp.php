<?php
error_reporting(0);
require("conexion.inc");
$idd = $_GET['id'];
$idd = substr($idd, 0, -1);
$sql_codigo = mysql_query("SELECT cod_med from banco_muestras where id in ($idd)");
while ($row_codigo = mysql_fetch_array($sql_codigo)) {
    $codigos .= $row_codigo[0] . ",";
}

if (!isset($global_linea)) {
    $global_linea = 1021;
}
$codigos_finales = substr($codigos, 0, -1);
$sql_gestion = mysql_query("SELECT codigo_gestion, nombre_gestion from gestiones where estado = 'Activo' ");
$codigo_gestion = mysql_result($sql_gestion, 0, 0);
$sql_ciclo = mysql_query("SELECT cod_ciclo from ciclos where estado = 'Activo' and codigo_linea = '$global_linea'");
$dat_ciclo = mysql_fetch_array($sql_ciclo);
$ciclo_global = $dat_ciclo[0];
$ciclo_global = $ciclo_global + 1;
$sql_medicos = mysql_query("SELECT DISTINCT m.cod_med, CONCAT(m.nom_med,' ',m.ap_pat_med,' ',m.ap_mat_med), rd.cod_especialidad from medicos m, rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd WHERE rc.cod_rutero = rm.cod_rutero and  rm.cod_contacto = rd.cod_contacto and rd.cod_med = m.cod_med and m.cod_med in ($codigos_finales) and rc.codigo_gestion = $codigo_gestion and rc.codigo_ciclo = $ciclo_global");
// echo("SELECT DISTINCT m.cod_med, CONCAT(m.nom_med,' ',m.ap_pat_med,' ',m.ap_mat_med), rd.cod_especialidad from medicos m, rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd WHERE rc.cod_rutero = rm.cod_rutero and  rm.cod_contacto = rd.cod_contacto and rd.cod_med = m.cod_med and m.cod_med in ($codigos_finales) and rc.codigo_gestion = $codigo_gestion and rc.codigo_ciclo = $ciclo_global");

$sql_cantidad = mysql_query("SELECT sum(cantidad) from banco_muestras_detalle where cod_med in ($codigos_finales)");
$cantidad = mysql_result($sql_cantidad, 0, 0);
$cantidad_final_total = $cantidad;
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Banco De Muestras</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <style type="text/css" href="lib/noty/buttons.css"></style>
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <link type="text/css" href="css/tables.css" rel="stylesheet" />
    <script type="text/javascript" language="javascript" src="lib/jquery.dataTables.js"></script>
    <style type="text/css">
    h3{
        color: #5F7BA9;
        font-size: 1.2em;
        text-align: left;
    }
    table tbody tr:nth-child(2n){
        /*background: #F9E5E0*/
        background: #e9e9e9
    }
    table thead, table tfoot{
        background: #cfcdcd
    }
    </style>
    <script type="text/javascript">
    $(document).ready(function(){
        $("#enviar").click(function(){
            var valores='', valores2='';
            $(".tablitas input").each(function(){
                valores += $(this).val()+","
            })
            $(".tablitas input").each(function(){
                valores2 += $(this).val()+","
            })
            var max_cantidades,cantidades_finales = 0;
            $(".tablitas input.cantidad_para_validar").each(function(){
                cantidades_finales = parseFloat(cantidades_finales) + parseFloat($(this).val());
            })
            max_cantidades = $("#cantidad_final_max").val();
            if(cantidades_finales < max_cantidades || cantidades_finales > max_cantidades ){
                var n = noty({
                    text: "Desea continuar?, Las cantidades son menores o mayores al monto total que se dispuso.",
                    type: "error",
                    dismissQueue: true,
                    layout: "center",
                    theme: 'defaultTheme',
                    modal:true,
                    buttons: [{
                        addClass: 'btn btn-primary', 
                        text: 'Ok', 
                        onClick: function($noty) {
                            $noty.close();
                            $.getJSON("ajax/cargaBanco/carga.php",{
                                "valores" : valores
                            },response);
                        }
                    },{
                        addClass: 'btn btn-danger', 
                        text: 'Cancel', 
                        onClick: function($noty) {
                            $noty.close();
                            alert("No se realizo el calculo respectivo.")
                        }
                    } ]
                });
}else{
    $.getJSON("ajax/cargaBanco/carga.php",{
        "valores" : valores
    },response);
}
})
function response(datos){
    if(datos.estado == 'bien'){
        alert(datos.mensaje)
        window.location.href = "calculo_banco_muestras.php";
    }
    if(datos.estado == 'mal'){
        alert(datos.mensaje)
    }
}
})
</script>
</head>
<body>
    <div id="container">
        <?php require("estilos2.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada; text-align: center">Realizar C&aacute;lculo Banco de Muestras Detallado</h3>
        </header>
        <section role="main">
            <div class="row">
                <?php
                $ciclo_global = $ciclo_global + 1;
                while ($row_medicos = mysql_fetch_array($sql_medicos)) {
                    $txt_visitadores="SELECT DISTINCT rmda.cod_visitador, CONCAT(f.nombres,' ',f.paterno,' ',f.materno) 
						from rutero_maestro_aprobado rma, rutero_maestro_cab_aprobado rmca, rutero_maestro_detalle_aprobado rmda, 
						funcionarios f where rmca.cod_rutero = rma.cod_rutero and rma.cod_contacto = rmda.cod_contacto and 
						f.codigo_funcionario = rmda.cod_visitador and rmda.cod_med = $row_medicos[0] and rmca.codigo_ciclo = $ciclo_global 
						and rmca.codigo_gestion = $codigo_gestion and f.estado = 1";
					//echo $txt_visitadores;
					$sql_visitadores = mysql_query($txt_visitadores);
                    
					$cantidad_visitadores = mysql_num_rows($sql_visitadores);
                    $num = 1;
                    $espe_med = $row_medicos[2];
                    ?>
                    <div class="six columns end tablitas">
                        <h3><?php echo $row_medicos[1] . " (" . $row_medicos[0] . " - " . $row_medicos[2] . ")"; ?></h3>
                        <?php $codigo_medico = $row_medicos[0]; ?>
                        <table border="1">
                            <thead>
                                <tr>
                                    <td>N&uacute;mero</td> 
                                    <td>Nombre Visitador</td> 
                                    <td></td> 
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $contador_linea = 0; 
                                while ($row_visitador = mysql_fetch_array($sql_visitadores)) { 
                                    ?>
                                    <tr>
                                        <td><?php echo $num; ?></td>
                                        <td><?php echo $row_visitador[1] ?>  </td>
                                        <?php
                                        $line_veri = mysql_query("SELECT codigo_l_visita from lineas_visita_visitadores where codigo_funcionario = $row_visitador[0] and codigo_ciclo = $ciclo_global and codigo_gestion = $codigo_gestion ");
                                        while ($row_line = mysql_fetch_array($line_veri)) {
                                            $lii .= $row_line[0] . ",";
                                        }
                                        $lii_sub = substr($lii, 0, -1);
                                        $lii_explode = explode(",", $lii_sub);
                                        ?>
                                        <td>
                                            <table border="1">
                                                <thead>
                                                    <tr>
                                                        <td>Producto</td>
                                                        <td align="center">Cantidad a Entregar</td>
                                                        <td align="center">Cantidad Total</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $sql_muestra = mysql_query("SELECT DISTINCT a.cod_muestra, CONCAT(b.descripcion,' ',b.presentacion),a.cantidad, a.id from banco_muestras_detalle a, muestras_medicas b WHERE a.cod_muestra = b.codigo and a.cod_med = $codigo_medico");
                                                    while ($row_muestras = mysql_fetch_array($sql_muestra)) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <?php echo $row_muestras[1] . " - " . $row_muestras[0]; ?> 
                                                                <input type="hidden" value="<?php echo $row_medicos[0] ?>" />
                                                                <input type="hidden" value="<?php echo $row_visitador[0]; ?>" />
                                                                <input type="hidden" value="<?php echo $row_muestras[0] ?>" />
                                                                <input type="hidden" value="<?php echo $row_muestras[3] ?>" />
                                                                <input type="hidden" value="<?php echo $row_muestras[2] ?>" />
                                                            </td>
                                                            <?php
                                                            $linea = mysql_query("SELECT DISTINCT codigo_l_visita from parrilla a, parrilla_detalle b where a.cod_ciclo = $ciclo_global and a.codigo_gestion = $codigo_gestion and a.cod_especialidad = '$row_medicos[2]' and a.codigo_parrilla = b.codigo_parrilla and b.codigo_muestra = '$row_muestras[0]'");
                                                            $linea_final = mysql_result($linea, 0, 0);
                                                            ?>
                                                            <td align="center">
                                                                <?php
                                                                $cantidad = $row_muestras[2];
                                                                $cantidad_visitadores = $cantidad_visitadores - $contador_linea;
                                                                if ($cantidad % $cantidad_visitadores == 0) {
                                                                    $cantidad_final = $cantidad / $cantidad_visitadores;
                                                                    echo "<input class='cantidad_para_validar' type='text' value='$cantidad_final' />";
                                                                } else {
                                                                    if ($cantidad_visitadores == 1) {
                                                                        $fnal_final = $cantidad;
                                                                    } else {
                                                                        $nmenosuno = $cantidad_visitadores - 1;
                                                                    }
                                                                    $fnal = $cantidad / $cantidad_visitadores;
                                                                    $fnal = round($fnal, 0);
                                                                    $fnal_aux = $fnal * $nmenosuno;
                                                                    if ($cantidad_visitadores == $num) {
                                                                        $fnal_final = $cantidad - $fnal_aux;
                                                                    } else {
                                                                        $fnal_final = $fnal;
                                                                    }
                                                                    echo "<input class='cantidad_para_validar' type='text' value='$fnal_final' />";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td><?php echo $cantidad; ?></td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <?php
                                    $num++;
                                }
                                $lii = '';
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                }
                ?>
                <input type="hidden" id="cantidad_final_max" value="<?php echo $cantidad_final_total; ?>">
            </div>
            <center>
                <div class="two columns centered">
                    <a href="javascript:void(0)" class="button" id="enviar">Enviar</a>
                </div>
            </center>
        </section>
    </div>
    <script type="text/javascript" src="lib/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="lib/noty/center.js"></script>
    <script type="text/javascript" src="lib/noty/default.js"></script>
</body>
</html>