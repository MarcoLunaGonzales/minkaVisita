<?php
error_reporting(0);
require("../../conexion.inc");
$idd = $_GET['codigo'];

$sql_medico = mysql_query("SELECT cod_med from banco_muestras where id = $idd");
$cod_medico = mysql_result($sql_medico, 0, 0);
$sql2 = "select DISTINCT(a.desc_especialidad), b.direccion, d.nom_med, d.ap_pat_med, d.ap_mat_med, c.categoria_med from especialidades a , direcciones_medicos b, rutero_maestro_detalle_aprobado c, medicos d where  c.cod_med = $cod_medico and c.cod_especialidad = a.cod_especialidad and b.cod_med = c.cod_med and c.cod_med = d.cod_med";
$resp_sql2 = mysql_query($sql2);
while ($row = mysql_fetch_assoc($resp_sql2)) {
    $nombre_medico = $row['nom_med'] . " " . $row['ap_pat_med'] . " " . $row['ap_mat_med'];
    $especialidad = $row['desc_especialidad'];
    $direccion = $row['direccion'];
    $categoriaa = $row['categoria_med'];
}

$sql_nu_visita = mysql_query("select * from banco_muestras where cod_med = $cod_medico");
while ($row2 = mysql_fetch_array($sql_nu_visita)) {
    $nro_visita = $row2[3];
    $ciclo_inicio = $row2[4];
    $ciclo_final = $row2[5];
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
        <link rel="stylesheet" href="responsive/stylesheets/style.css">
        <link type="text/css" href="css/tables.css" rel="stylesheet" />
    </head>
    <body>
        <div id="container">
            <header id="titulo" style="min-height: 50px; height: 50px">
                <h4 style="color: #5F7BA9; font-size: 1.2em; font-family: Vernada"><?php echo $nombre_medico ?></h4>
            </header>
            <section role="main">
                <div class="row">
                    <div class="two columns">
                        <p class="bold">Especialidad:</p>
                    </div>
                    <div class="two columns end">
                        <p><?php echo $especialidad ?></p>
                    </div>
                    <div class="columns">
                        <p class="bold">Categor&iacute;a:</p>
                    </div>
                    <div class="one columns end">
                        <p class="bold"><?php echo $categoriaa; ?></p>
                    </div>
                    <div class="two columns">
                        <p class="bold">Direcci&oacute;n:</p>
                    </div>
                    <div class="three columns end">
                        <p><?php echo $direccion ?></p>
                    </div>
                </div><!-- end row -->
                <div class="row">
                    <div class="columns end">
                        <p class="bold">N&uacute;mero contacto:</p>
                    </div>
                    <div class="columns end">
                        <p><?php echo $nro_visita; ?></p>
                    </div>
                    <div class="two columns">
                        <p class="bold">Ciclo Inicio:</p>
                    </div>
                    <div class="two columns end">
                        <p><?php echo $ciclo_inicio ?></p>
                    </div>
                    <div class="two columns">
                        <p class="bold">Ciclo Final:</p>
                    </div>
                    <div class="two columns end">
                        <p><?php echo $ciclo_final ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="seven columns">
                        <?php
                        $query_farm = "select cod_muestra, cantidad from banco_muestras_detalle where cod_med=$cod_medico";
                        $resp_query_farm = mysql_query($query_farm);
                        $count = 1;
                        ?>
                        <table width="99%" border="1" cellpadding="0" id="" style="margin: 0">
                            <tr>
                                <!--<th width="4%" scope="col">&nbsp;</th>-->
                                <th width="4%" scope="col">&nbsp;</th>
                                <th width="66%" scope="col">Muestras M&eacute;dicas</th>
                                <th width="26%" scope="col">Cantidad</th>
                            </tr>
                        </table>
                        <table width="99%" border="1" cellpadding="0" id="table-principal">
                            <?php while ($row_farm = mysql_fetch_assoc($resp_query_farm)) { ?>
                                <tr id="<?php echo $count; ?>" class="row-style">
                                    <!--<td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>-->
                                    <td class="position" style="text-align: center; font-weight: bold;font-size: 2em; line-height: 51px" width="4%"><?php echo $count; ?></td>
                                    <td class="cargar_categoria" width="66%">
                                        <select class="name_farm" name="name_farm_<?php echo $count; ?>" id="name_farm_<?php echo $count; ?>" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; " disabled>
                                            <option value="vacio">Selecionar una opcion</option>
                                            <?php $query_farmacias = "SELECT codigo, CONCAT(descripcion,' ',presentacion) as nombre from muestras_medicas order by  descripcion ASC"; ?>
                                            <?php $resp_query_farmacias = mysql_query($query_farmacias); ?>
                                            <?php while ($row = mysql_fetch_assoc($resp_query_farmacias)) { ?>
                                                <option  value="<?php echo $row['codigo']; ?>" <?php
                                        if ($row_farm['cod_muestra'] == $row['codigo']): echo 'selected="selected"';
                                        endif;
                                                ?>><?php echo $row['nombre'] ?></option>
                                                     <?php } ?>
                                        </select>
                                    </td>
                                    <td width="26%">
                                        <input type="text" class="dir_farm" name="dir_farm_<?php echo $count; ?>" id="dir_farm_<?php echo $count; ?>" style="width: 100%; border: none; margin: 5px 0;padding-left: 10px; font-weight: bold " value="<?php echo $row_farm['cantidad'] ?>" disabled/>
                                    </td>
                                </tr>
                                <?php
                                $count++;
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </body>
</html>