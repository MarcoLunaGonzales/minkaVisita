<?php  
require_once ('conexion.inc');
error_reporting(0);

$cod_med = $_GET['cod_med'];
$linea   = $_GET['linea'];

$cod_linea = $linea;

$sql_nom_med = mysql_query("SELECT CONCAT(m.ap_pat_med,' ',m.ap_mat_med, ' ', m.nom_med) as nombre, m.cod_ciudad from medicos m where m.cod_med = $cod_med ");
$nom_med     = mysql_result($sql_nom_med, 0, 0); 
$ciu_med     = mysql_result($sql_nom_med, 0, 1);

$sql_nom_lin = mysql_query("SELECT nombre_linea from lineas where codigo_linea = $linea");
$nom_linea   = mysql_result($sql_nom_lin, 0, 0);

$sql_espe_med = mysql_query("SELECT e.desc_especialidad, cl.categoria_med, e.cod_especialidad from especialidades e, categorias_lineas cl where cl.cod_especialidad = e.cod_especialidad and cl.cod_med = $cod_med and cl.codigo_linea = $linea");
$especialidad = mysql_result($sql_espe_med, 0, 0);
$categoria    = mysql_result($sql_espe_med, 0, 1);
$cod_espec    = mysql_result($sql_espe_med, 0, 2);
?>
<!DOCTYPE HTML>
<html lang="es-US">
<head>
    <meta charset="iso-8859-1">
    <title>Adicionar Producto M&eacute;dicos</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <link type="text/css" href="js/fancybox/jquery.fancybox.css" rel="stylesheet" />
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="js/fancybox/jquery.fancybox.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("body").on({
                ajaxStart: function() { 
                    $(this).addClass("loading"); 
                },
                ajaxStop: function() { 
                    $(this).removeClass("loading"); 
                }    
            });
            $("#ver_parrilla").fancybox({
                fitToView   : true,
                modal       : false
            });
            $('#table-principal tr td.deleteSlide').bind("mousedown", ( deleteSlide ));
            $('#add-row').bind("mousedown", (function(event){
                var highestID = 0;
                $('#table-principal tr').each(function() {
                    var curID = parseInt($(this).attr('id'));
                    if (highestID < curID){
                        highestID = curID;
                    }
                });
                $('#table-clone tr').clone().appendTo($('#table-principal'));
                $('#table-principal tr:last').attr("id",++highestID);
                $('#table-principal tr').each(function(index) {
                    $("#table-principal tr td.position").eq(index).html(index+1);
                    $('#table-principal tr:last').attr("id",index+1);
                    $(this).find("input.frecuencia").attr('name', 'frecuencia_'+(index+1));
                });
                $('#table-principal tr:last td.deleteSlide').bind("mousedown", ( deleteSlide ));
            }));
            function deleteSlide() {
                if (confirm("Borrar este producto?")) {
                    $(this).parent().remove();
                }
                $('#table-principal tr').each(function(index) {
                    $("#table-principal tr td.position").eq(index).html(index+1);
                    $('#table-principal tr:last').attr("id",index+1);
                    $(this).find("input.frecuencia").attr('name', 'frecuencia_'+(index+1));
                });
                return false;
            }
            $(".va_ultima_pos").live('click', function(event) {
                if($(this).is(':checked') == true){
                    $(this).parent().find('.enter_pos').hide('slow')
                    $(this).val("1");
                }else{
                    $(this).parent().find('.enter_pos').show('slow')
                    $(this).val("0");
                }
            });
            $(".que_cantidad").live('click', function(event) {
                if($(this).is(':checked') == true){
                    $(this).parent().find('.enter_cant').hide('slow')
                    $(this).val("1");
                }else{
                    $(this).parent().find('.enter_cant').show('slow')
                    $(this).val("0");
                }
            });
            var cadena_frecuencia = '';
            $(".frecuencia").live('change', function(event) {

                $(this).parent().find('.frecuencia:checked').each(function() {
                    cadena_frecuencia += $(this).val()+"#" ;
                });
                $(this).parent().parent().find('.cadena_frecuencia').val(cadena_frecuencia);
            });
            $("#adicionar").click(function(event) {
                var cadena='';
                $("#table-principal tr.lineaaa td .cod_med, #table-principal tr.lineaaa td .cod_linea, #table-principal tr.lineaaa td #nuevo_prod, #table-principal tr.lineaaa td .cadena_frecuencia, #table-principal tr.lineaaa td .va_ultima_pos,#table-principal tr.lineaaa td .posicionn, #table-principal tr.lineaaa td .que_cantidad, #table-principal tr.lineaaa td .cantidad_prod").each(function(){
                    cadena += $(this).val()+"@";
                })

                $.ajax({
                    type: "POST",
                    url: "ajax/adicionar_producto/agregar_producto.php",
                    dataType : 'json',
                    data: { 
                        cadena : cadena
                    }
                }).done(function(data) {
                    alert(data.mensaje)
                    window.location.href = "adicionar_producto_medico.php";
                });
            });
        });
function validate(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.which;
    key = String.fromCharCode( key );
    var regex = /[0-9]|\./;
    if( /[ -~]/ && !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
    }
}
</script>
<style>
    .modal {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 0, 0, 0,.8 ) 
        url('http://i.stack.imgur.com/FhHRx.gif') 
        50% 50% 
        no-repeat;
    }
    body.loading {
        overflow: hidden;   
    }
    body.loading .modal {
        display: block;
    }
    a:hover {
        text-decoration: underline;
    }
    table tbody tr td {
        padding: 10px !important;
    }
    input[type="text"], textarea {
        box-shadow: none !important;
        border-radius: 0px !important;
        height: 38px !important;
        margin: 0 !important;
    }
</style>
</head>
<body>
    <div id="container">
        <header id="titulo">
            <?php require_once('estilos3.inc'); ?>
            <br />
            <h3>Adicionar Productos</h3>
            <h4 style="font-size: 1.1em">M&eacute;dico: <?php echo $nom_med; ?> | L&iacute;nea: <?php echo $nom_linea; ?></h4>
            <h4 style="font-size: 1em">Especialidad: <?php echo $especialidad; ?> | Categor&iacute;a: <?php echo $categoria; ?></h4>
        </header>
        <div id="contenido">
            <div class="row" style="margin-bottom: 15px">
                <div class="left">
                    <a href="#mostrar_parrilla" class="button" id="ver_parrilla">Ver parrilla Actual</a>
                </div>
                <div class="right">
                    <div id="add-row"></div>
                </div>
            </div>
            <div class="row">
                <table width="99%" border="1" cellpadding="0" id="" style="margin:0px">
                    <tr>
                        <th width="4%" scope="col">&nbsp;</th>
                        <th width="4%" scope="col">&nbsp;</th>
                        <th width="34%" scope="col">Producto</th>
                        <th width="25%" scope="col">Contacto</th>
                        <th width="17%" scope="col">Posici&oacute;n</th>
                        <th width="16%" scope="col">Cantidad</th>
                    </tr>
                </table>
                <table width="99%" border="1" cellpadding="0" id="table-principal" style="margin:0px">
                    <tr id="1" class="row-style lineaaa">
                        <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
                        <td class="position" style="text-align: center; font-weight: bold" width="4%">
                            1
                        </td>
                        <td class="cargar_producto" width="34%">
                            <input type="hidden" value="<?php echo $cod_med; ?>"  class="cod_med">
                            <input type="hidden" value="<?php echo $cod_linea; ?>" class="cod_linea">
                            <?php  
                            $sql_nuevo_prod = mysql_query("SELECT DISTINCT m.codigo, CONCAT(m.descripcion,' ',m.presentacion) from muestras_medicas m where m.estado = 1 and m.codigo_linea = 1021 order by 2");
                            ?>
                            <select name="nuevo_prod" id="nuevo_prod" class="para_guardar">
                                <?php while($row_nuevo_prod =  mysql_fetch_array($sql_nuevo_prod)){ ?>
                                <option value="<?php echo $row_nuevo_prod[0] ?>"><?php echo $row_nuevo_prod[1] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td width="25%">
                            <?php 
                            $sql_frecuencia = mysql_query("SELECT max(gd.frecuencia) from grilla g, grilla_detalle gd where g.codigo_grilla = gd.codigo_grilla and g.estado = 1 and g.codigo_linea = $cod_linea and gd.cod_especialidad = '$cod_espec' and gd.cod_categoria = '$categoria' and g.agencia = $ciu_med and gd.frecuencia < 8");
                            $max_frecuencia = mysql_result($sql_frecuencia, 0, 0);
                            for ($i=1; $i <= $max_frecuencia; $i++) { 
                                ?>
                                <div style="margin-right: 15px; float: left">
                                    <input type="checkbox" name="frecuencia_1" class="frecuencia para_guardar" value="<?php echo $i; ?>"> <?php echo $i ?>
                                </div>
                                <?php  
                            }
                            ?>
                            <input type="hidden" class="cadena_frecuencia" value="" />
                        </td>
                        <td width="17%">
                            <div class="enter_pos" style="margin-bottom:10px;display:none">
                                <input type="number" placeholder="Posicion" style="width:70%" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" class="para_guardar posicionn" value="0">
                            </div>
                            <input type="checkbox" value="1" checked class="va_ultima_pos para_guardar"> &Uacute;ltima posici&oacute;n  
                        </td>
                        <td width="16%">
                            <div class="enter_cant" style="margin-bottom:10px">
                                <input type="number" class="cantidad_prod para_guardar" placeholder="Cantidad" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" value="0" style="max-width:115px">
                            </div>
                            <input type="checkbox" value="0" class="que_cantidad para_guardar"> Va cantidad establecida 
                        </td>
                    </tr>
                </table>


                <table width="99%" border="1" cellpadding="0" id="table-clone" style="display:none;">
                    <tr id="999" class="row-style lineaaa">
                        <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
                        <td class="position" style="text-align: center; font-weight: bold" width="4%">
                            999
                        </td>
                        <td class="cargar_producto" width="34%">
                            <input type="hidden" value="<?php echo $cod_med; ?>" class="cod_med">
                            <input type="hidden" value="<?php echo $cod_linea; ?>" class="cod_linea">
                            <?php  
                            $sql_nuevo_prod = mysql_query("SELECT DISTINCT m.codigo, CONCAT(m.descripcion,' ',m.presentacion) from muestras_medicas m where m.estado = 1 order by 2");
                            ?>
                            <select name="nuevo_prod" id="nuevo_prod" class="para_guardar">
                                <?php while($row_nuevo_prod =  mysql_fetch_array($sql_nuevo_prod)){ ?>
                                <option value="<?php echo $row_nuevo_prod[0] ?>"><?php echo $row_nuevo_prod[1] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td width="25%">
                            <?php 
                            $sql_frecuencia = mysql_query("SELECT max(gd.frecuencia) from grilla g, grilla_detalle gd where g.codigo_grilla = gd.codigo_grilla and g.estado = 1 and g.codigo_linea = $cod_linea and gd.cod_especialidad = '$cod_espec' and gd.cod_categoria = '$categoria' and g.agencia = $ciu_med and gd.frecuencia < 8");
                            $max_frecuencia = mysql_result($sql_frecuencia, 0, 0);
                            for ($i=1; $i <= $max_frecuencia; $i++) { 
                                ?>
                                <div style="margin-right: 15px; float: left">
                                    <input type="checkbox" name="frecuencia_" class="frecuencia para_guardar" value="<?php echo $i; ?>"> <?php echo $i ?>
                                </div>
                                <?php  
                            }
                            ?>
                            <input type="hidden" class="cadena_frecuencia" value="" />
                        </td>
                        <td width="17%">
                            <div class="enter_pos" style="margin-bottom:10px;display:none">
                                <input type="number" placeholder="Posicion" style="width:70%" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" class="para_guardar posicionn" value="0">
                            </div>
                            <input type="checkbox" value="1" checked class="va_ultima_pos para_guardar"> &Uacute;ltima posici&oacute;n  
                        </td>
                        <td width="16%">
                            <div class="enter_cant" style="margin-bottom:10px">
                                <input type="number" class="cantidad_prod para_guardar" placeholder="Cantidad" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" value="0" style="max-width:115px">
                            </div>
                            <input type="checkbox" value="0" class="que_cantidad para_guardar"> Va cantidad establecida 
                        </td>
                    </tr>
                </table>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="two columns centered">
                    <a href="javascript:void(0)" class="button" id="adicionar">Adicionar</a>
                </div>
            </div>
        </div>
    </div>
    <div id="mostrar_parrilla" style="width:100%;display: none;">
        <h3>Parrilla para el M&eacute;dico</h3>
        <div class="row">
            <div class="twleve columns">
                <?php  
                $ciclo_global = $ciclo_global + 1;
                $sql_parrillas = mysql_query("SELECT DISTINCT lv.nom_orden, p.numero_visita, CONCAT(m.descripcion,' ',m.presentacion), pd.cantidad_muestra, ma.descripcion_material, pd.cantidad_material from parrilla p, parrilla_detalle pd, lineas_visita lv, muestras_medicas m, material_apoyo ma where ma.codigo_material = pd.codigo_material and p.codigo_parrilla = pd.codigo_parrilla and lv.codigo_l_visita = p.codigo_l_visita and pd.codigo_muestra = m.codigo and p.cod_ciclo = $ciclo_global and p.codigo_gestion = $codigo_gestion and p.codigo_linea = $cod_linea and p.agencia = $ciu_med and p.cod_especialidad = '$cod_espec' and p.categoria_med = '$categoria' order by 1, 2,3");
                // echo("SELECT DISTINCT lv.nom_orden, p.numero_visita, CONCAT(m.descripcion,' ',m.presentacion), pd.cantidad_muestra, ma.descripcion_material, pd.cantidad_material from parrilla p, parrilla_detalle pd, lineas_visita lv, muestras_medicas m, material_apoyo ma where ma.codigo_material = pd.codigo_material and p.codigo_parrilla = pd.codigo_parrilla and lv.codigo_l_visita = p.codigo_l_visita and pd.codigo_muestra = m.codigo and p.cod_ciclo = $ciclo_global and p.codigo_gestion = $codigo_gestion and p.codigo_linea = $cod_linea and p.agencia = $ciu_med and p.cod_especialidad = '$cod_espec' and p.categoria_med = '$categoria' order by 1, 2,3");
                $aux  = '';
                $aux2 = '';
                ?>
                <table>
                    <?php 
                    while ($row_parrillas = mysql_fetch_array($sql_parrillas)) {
                        if($aux != $row_parrillas[0] or $aux2 != $row_parrillas[1]){
                            $count = 1;
                            ?>
                            <tr>
                                <th>&nbsp;</th>
                                <th>L&iacute;nea</th>
                                <th>N&uacute;mero Visita</th>
                                <th>Muestra M&eacute;dica</th>
                                <th>Cantidad</th>
                                <th>Material Apoyo</th>
                                <th>Cantidad</th>
                            </tr>
                            <?php  
                        }
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $row_parrillas[0]; ?></td>
                            <td><?php echo $row_parrillas[1]; ?></td>
                            <td><?php echo $row_parrillas[2]; ?></td>
                            <td><?php echo $row_parrillas[3]; ?></td>
                            <td><?php echo $row_parrillas[4]; ?></td>
                            <td><?php echo $row_parrillas[5]; ?></td>
                        </tr>
                        <?php
                        $count++;
                        $aux  = $row_parrillas[0];
                        $aux2 = $row_parrillas[1];
                    }
                    ?>
                </table>
            </div>
        </div>
        <div class="row centered">
            <div class="three columns centered">
                <a href="javascript:$.fancybox.close();" class="button">Cerrar</a>
            </div>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>