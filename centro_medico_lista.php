<?php
error_reporting(0);
require("conexion.inc");
?>
<?php
$sql_centro_medico = mysql_query("SELECT cm.cod_centro_medico,cm.nombre,cm.direccion,c.descripcion from centros_medicos cm, ciudades c where cm.cod_ciudad = c.cod_ciudad order by cod_centro_medico DESC");
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <link href='stilos.css' rel='stylesheet' type='text/css'>
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="lib/jquery.tablesorter.min.js"></script>
        <script type="text/javascript" src="ajax/centro_medico/send.data.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $("body").on({
                    ajaxStart: function() { 
                        $(this).addClass("loading"); 
                    },
                    ajaxStop: function() { 
                        $(this).removeClass("loading"); 
                    }    
                });
                $("#centros_medicos").tablesorter();
                $(".eliminar").click(function(){
                    var codd = $(this).attr('value');
                    eliminar(codd)
                })
                $(".editar").click(function(){
                    var codd = $(this).attr('value');
                    window.location = "editar_centro_medico.php?cod="+codd;
                })
                var ciduades_seleccionas = '';
                $(".row_ciudades input").change(function(){
                    $("#body_center").html('');
                    $(".row_ciudades input.ciudades").each(function(index){
                        //                ciduades_seleccionas += " "+$(this).val()+", "
                        if (jQuery(this).is(":checked")){
                            ciduades_seleccionas += $(this).val()+","
                        }
                        //                alert(ciduades_seleccionas)
                    })
                    $.ajax({
                        type: "POST",
                        url: "ajax/centro_medico/generar.php",
                        dataType : 'json',
                        data: { 
                            codigos: ciduades_seleccionas
                        },
                        success : function(data){
                            $("#body_center").html(data.msg);
                            //                            $("#centros_medicos").tablesorter();
                            $(".eliminar").click(function(){
                                var codd = $(this).attr('value');
                                eliminar(codd)
                            })
                            $(".editar").click(function(){
                                var codd = $(this).attr('value');
                                window.location = "editar_centro_medico.php?cod="+codd;
                            })
                        }
                    })
                    ciduades_seleccionas = ''
                })
//                $("#centros_medicos").trigger("update"); 
//                $("#centros_medicos").trigger("appendCache");; 
            })
        </script>
        <style type="text/css">
            #centros_medicos tr th { font-weight: bold; font-size: 14px; padding: 5px } 
            #centros_medicos tr td { font-weight: normal !important; font-size: 12px !important; padding: 3px; text-align: center } 
            th.headerSortUp {
                background-image: url(imagenes/descd.gif) !important;
                background-position: center right;
                background-color: #3399FF;
            }
            th.headerSortDown {
                background-image: url(imagenes/desc.gif)  !important;
                background-position: center right;
                background-color: #3399FF;
            }
            th.header {
                background-image: url(imagenes/bg.gif);
                cursor: pointer;
                font-weight: bold;
                background-repeat: no-repeat;
                background-position: center right;
                padding-right: 15px;
                border-right: 1px solid #dad9c7;
                margin-left: 1px;
            }
            .row_ciudades {
                margin-bottom: 10px
            }
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
            /* When the body has the loading class, we turn
               the scrollbar off with overflow:hidden */
            body.loading {
                overflow: hidden;   
            }

            /* Anytime the body has the loading class, our
               modal element will be visible */
            body.loading .modal {
                display: block;
            }
        </style>
    </head>
    <body>
    <center>
        <table border='0' class='textotit'>
            <tr>
                <td><h2>Centros M&eacute;dicos</h2></td>
            </tr>
        </table>
    </center>
    <center>
        <div class="row_ciudades">
            <?php
            $sql_ciudades = mysql_query("select cod_ciudad, descripcion from ciudades where cod_ciudad <> 115");
            while ($row_ciudades = mysql_fetch_array($sql_ciudades)) {
                ?>
                <input type="checkbox" value="<?php echo $row_ciudades[0]; ?>" class="ciudades" name="ciudades" /><?php echo $row_ciudades[1]; ?>
            <?php } ?>
        </div>
    </center>
    <center>
        <table border='0' class='texto'>
            <tr>
                <td><a href="adicionar_centro_medico.php" class="boton"  style="padding: 3px 34px; color: #fff !important; text-decoration: none; margin-bottom: 10px">Adicionar</a></td>
            </tr>
        </table>
    </center>
    <center style="margin: 20px 0">
        <table border="1" class="texto" id="centros_medicos" width="50%">
            <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>Nombre</th>
                    <th>Direccion</th>
                    <th>Ciudad</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody id="body_center">
                <?php $count = 1; ?>
                <?php while ($row_centro_medico = mysql_fetch_array($sql_centro_medico)) { ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><input type="hidden" value="<?php echo $row_centro_medico[0]; ?>" /><?php echo $row_centro_medico[1]; ?></td>
                        <td><?php echo $row_centro_medico[2]; ?></td>
                        <td><?php echo $row_centro_medico[3]; ?></td>
                        <td><a href="#" class="editar" value="<?php echo $row_centro_medico[0]; ?>"><img src="imagenes/btn_modificar.png" alt="Editar" /></a></td>
                        <td><a href="#" class="eliminar" value="<?php echo $row_centro_medico[0]; ?>"><img src="imagenes/no.png" alt="Eliminar" /></a></td>
                    </tr>
                    <?php $count++; ?>
                <?php } ?>
            </tbody>
        </table>
    </center>
    <center>
        <table border='0' class='texto'>
            <tr>
                <td><a href="adicionar_centro_medico.php" class="boton"  style="padding: 3px 34px; color: #fff !important; text-decoration: none;">Adicionar</a></td>
            </tr>
        </table>
    </center>
    <div class="modal"></div>
</body>
</html>