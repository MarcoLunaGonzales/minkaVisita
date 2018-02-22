<link rel="stylesheet" href="lib/jqueryui/themes/base/jquery.ui.all.css" />
<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript" src="lib/jqueryui/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="lib/jqueryui/ui/jquery.ui.widget.js"></script>
<script type="text/javascript" src="lib/jqueryui/ui/jquery.ui.button.js"></script>
<script type="text/javascript" src="lib/jqueryui/ui/jquery.ui.position.js"></script>
<script type="text/javascript" src="lib/jqueryui/ui/jquery.ui.autocomplete.js"></script>
<script type="text/javascript" src="ajax/categorizacion_medicos/send.data.js"></script>
<link rel="stylesheet" href="lib/jqueryui/demos.css" />
<script type="text/javascript">
    $(document).ready(function(){
        // Delete a slide
        $('#table-principal tr td.deleteSlide').bind("mousedown", ( deleteSlide ));
        $('#add-row').bind("mousedown", (function(event){
            var highestID = 0;
            $('#table-principal tr').each(function() {
                var curID = parseInt($(this).attr('id'));
                if (highestID < curID){
                    highestID = curID;
                }
            });
            // Clone table row
            $('#table-clone tr').clone().appendTo($('#table-principal'));
            $('#table-principal tr:last').attr("id",++highestID);
            // sort displayed row numbers
            $('#table-principal tr').each(function(index) {
                $("#table-principal tr td.position").eq(index).html(index+1);
                $(this).find("input.name_farm").attr('name', 'name_farm_'+(index+1));
                $(this).find("input.name_farm").attr('id', 'name_farm_'+(index+1));
                $(this).find("input.dir_farm").attr('name','dir_farm_'+(index+1));
                $(this).find("input.dir_farm").attr('id','dir_farm_'+(index+1));
                $("#cuantos").val(index+1);
            });

            // Add click event to the remove button on the newly added row
            $('#table-principal tr:last td.deleteSlide').bind("mousedown", ( deleteSlide ));
        }));
        function deleteSlide() {
            // remove delete slide button if only one slide is left
            if ($('#table-principal tr').size() == 1) {
                alert("La eliminacion no esta permitida! Al menos una referencia de farmacia debe estar presente.");
                return false;
            } else {
                if (confirm("¿Borrar esta farmacia?")) {
                    $(this).parent().remove();
                }
                // sort displayed row numbers
                $('#table-principal tr').each(function(index) {
                    $("#table-principal tr td.position").eq(index).html(index+1);
                    $(this).find("input.name_farm").attr('name', 'name_farm_'+(index+1));
                    $(this).find("input.name_farm").attr('id', 'name_farm_'+(index+1));
                    $(this).find("input.dir_farm").attr('name','dir_farm_'+(index+1));
                    $(this).find("input.dir_farm").attr('id','dir_farm_'+(index+1));
                    $("#cuantos").val(index+1);
                });

                event.stopPropagation;
                return false;
            }
        }
        $(".boton").click(function(){
            $("#loader").css('display', 'block')
            $("#manto").css('display', 'block')
            $(".para_bloquear").attr('disabled', 'disabled')
            sendData(<?php echo $cod_medico ?>);
        })
        
    });
</script>
<style>
    #add-row {
        background: transparent url(imagenes/add-slide.png) no-repeat scroll 50% 50%;
        cursor:pointer;
        margin:20px 0 10px 10px;
        width:102px;
        height:32px;
        float:left;
    }
    #add-row:hover {
        background: transparent url(imagenes/add-slide-hover.png) no-repeat scroll 50% 50%;
        cursor:pointer;
        margin:20px 0 10px 10px;
        width:102px;
        height:32px;
    }
    td.deleteSlide	{
        background: transparent url(imagenes/delete-slide.png) no-repeat scroll 50% 50%;
        cursor:pointer;
    }
    #loader { display: none;  position: absolute; z-index: 110; top: 50%; left: 50% }
    #manto { width: 100%; height: 100%; background: #000; opacity:0.60; position: absolute; top: 0; left: 0; display: none; z-index: 100 }
    .ui-button { margin-left: -1px; }
    .ui-button-icon-only .ui-button-text { padding: 0.35em; } 
    .ui-autocomplete-input { margin: 0; padding: 0.48em 0 0.47em 0.45em; }
</style>
<?php
require("conexion.inc");
require("estilos_cuerpo.inc");
$cod_medico = $_GET['cod_medico'];

$sql2 = "select DISTINCT(a.desc_especialidad), b.direccion, d.nom_med, d.ap_pat_med, d.ap_mat_med from especialidades a , direcciones_medicos b, rutero_maestro_detalle c, medicos d where  c.cod_med = $cod_medico and c.cod_especialidad = a.cod_especialidad and b.cod_med = c.cod_med and c.cod_med = d.cod_med";
$resp_sql2 = mysql_query($sql2);
while ($row = mysql_fetch_assoc($resp_sql2)) {
    $nombre_medico = $row['nom_med'] . " " . $row['ap_pat_med'] . " " . $row['ap_mat_med'];
    $especialidad = $row['desc_especialidad'];
    $direccion = $row['direccion'];
}
?>
<div id="manto"></div>
<center style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Registro de Categorizaci&oacute;n del M&eacute;dico</center>
<center style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada"><?php echo $nombre_medico ?></center>
<table align='center'><tr><td><a href='categorizacion_medica_lista.php'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>

<table align="left" border="0" style="margin-top:25px; margin-bottom: 10px" width="100%">
    <tr>
        <td style="width: 120px; font-weight: bold" >Especialidad:</td>
        <td><?php echo $especialidad ?></td>
    </tr>
    <tr>
        <td style="width: 120px; font-weight: bold">Direccion:</td>
        <td><?php echo $direccion ?></td>
    </tr>
</table>

<center><strong>Datos complementarios</strong></center>
<div id="add-row"></div>
<table width="99%" border="1" cellpadding="0" id="">
    <tr>
        <th width="4%" scope="col">&nbsp;</th>
        <th width="4%" scope="col">&nbsp;</th>
        <th width="46%" scope="col">Farmacias de Referencia</th>
        <th width="46%" scope="col">Direccion</th>
    </tr>
</table>
<?php
$query_farm = "select nombre_farmacia, direccion_farmacia from farmacias_referencia_medico where cod_med=$cod_medico";
$resp_query_farm = mysql_query($query_farm);
$num_query_farm = mysql_num_rows($resp_query_farm);
if ($num_query_farm == 0):
    ?>
    <table width="99%" border="1" cellpadding="0" id="table-principal">
        <tr id="1" class="row-style">
            <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
            <td class="position" style="text-align: center; font-weight: bold" width="4%">1</td>
    <!--            <td width="46%"><input type="text" class="name_farm" name="nom_farm_1" id="name_farm_1" style="width: 100%; border: none; background: #F8E8DB"  /></td>
            <td width="46%"><input type="text" class="dir_farm" name="dir_farm_1" id="dir_farm_1" style="width: 100%; border: none; background: #F8E8DB"  /></td>-->
            <td width="46%">
                <div class="demo">
                    <div class="ui-widget">
                        <select class="name_farm" name="nom_farm_1" id="name_farm_1" >
                            <option value="">Select one...</option>
                            <option value="ActionScript">ActionScript</option>
                            <option value="AppleScript">AppleScript</option>
                            <option value="Asp">Asp</option>
                            <option value="BASIC">BASIC</option>
                            <option value="C">C</option>
                            <option value="C++">C++</option>
                            <option value="Clojure">Clojure</option>
                            <option value="COBOL">COBOL</option>
                            <option value="ColdFusion">ColdFusion</option>
                            <option value="Erlang">Erlang</option>
                            <option value="Fortran">Fortran</option>
                            <option value="Groovy">Groovy</option>
                            <option value="Haskell">Haskell</option>
                            <option value="Java">Java</option>
                            <option value="JavaScript">JavaScript</option>
                            <option value="Lisp">Lisp</option>
                            <option value="Perl">Perl</option>
                            <option value="PHP">PHP</option>
                            <option value="Python">Python</option>
                            <option value="Ruby">Ruby</option>
                            <option value="Scala">Scala</option>
                            <option value="Scheme">Scheme</option>
                        </select>
                    </div>
                </div>
            </td>
            <td width="46%"><input type="text" class="dir_farm" name="dir_farm_1" id="dir_farm_1" style="width: 100%; border: none; background: #F8E8DB"  /></td>
        </tr>
    </table>
<?php else: $count = 1; ?>
    <table width="99%" border="1" cellpadding="0" id="table-principal">
        <?php while ($row_farm = mysql_fetch_assoc($resp_query_farm)) { ?>
            <tr id="<?php echo $count; ?>" class="row-style">
                <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
                <td class="position" style="text-align: center; font-weight: bold" width="4%"><?php echo $count; ?></td>
                <td width="46%"><input type="text" class="name_farm" name="nom_farm_<?php echo $count; ?>" id="name_farm_<?php echo $count; ?>" style="width: 100%; border: none; background: #F8E8DB" value="<?php echo $row_farm['nombre_farmacia']; ?>"  /></td>
                <td width="46%"><input type="text" class="dir_farm" name="dir_farm_<?php echo $count; ?>" id="dir_farm_<?php echo $count; ?>" style="width: 100%; border: none; background: #F8E8DB" value="<?php echo $row_farm['direccion_farmacia']; ?>"   /></td>
            </tr>
            <?php
            $count++;
            ;
        }
        ?>
    </table>
<?php endif; ?>
<table width="99%" border="1" cellpadding="0" id="table-clone" style="display:none;">
    <tr id="999" class="row-style">
        <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
        <td class="position"  style="text-align: center; font-weight: bold" width="4%">999</td>
<!--        <td width="46%"><input type="text" class="name_farm" name="nom_farm_999" style="width: 100%; border: none; background: #F8E8DB"  /></td>
        <td width="46%"><input type="text" class="dir_farm" name="dir_farm_999" style="width: 100%; border: none; background: #F8E8DB"  /></td>-->
        <td width="46%">
            <div class="demo">
                <div class="ui-widget">
                    <select class="name_farm" name="nom_farm_999" id="name_farm_999" >
                        <option value="">Select one...</option>
                        <option value="ActionScript">ActionScript</option>
                        <option value="AppleScript">AppleScript</option>
                        <option value="Asp">Asp</option>
                        <option value="BASIC">BASIC</option>
                        <option value="C">C</option>
                        <option value="C++">C++</option>
                        <option value="Clojure">Clojure</option>
                        <option value="COBOL">COBOL</option>
                        <option value="ColdFusion">ColdFusion</option>
                        <option value="Erlang">Erlang</option>
                        <option value="Fortran">Fortran</option>
                        <option value="Groovy">Groovy</option>
                        <option value="Haskell">Haskell</option>
                        <option value="Java">Java</option>
                        <option value="JavaScript">JavaScript</option>
                        <option value="Lisp">Lisp</option>
                        <option value="Perl">Perl</option>
                        <option value="PHP">PHP</option>
                        <option value="Python">Python</option>
                        <option value="Ruby">Ruby</option>
                        <option value="Scala">Scala</option>
                        <option value="Scheme">Scheme</option>
                    </select>
                </div>
            </div>
        </td>
        <td width="46%"><input type="text" class="dir_farm" name="dir_farm_999" style="width: 100%; border: none; background: #F8E8DB"  /></td>
    </tr>
</table>
<?php if ($num_query_farm == 0): ?>
    <input type="hidden" name="cuantos" id="cuantos" value="1" />
<?php else: ?>
    <input type="hidden" name="cuantos" id="cuantos" value="<?php echo $count - 1; ?>" />
<?php endif; ?>
<?php
$query = "select * from categorizacion_medico where cod_med=$cod_medico";
$resp_query = mysql_query($query);
$num_query = mysql_num_rows($resp_query);
if ($num_query == 0):
    ?>
    <table width="99%" border="0" cellpadding="5" style="margin-top: 10px">
        <tr>
            <th scope="row" style="width: 52%; text-align: left">Sexo</th>
            <td>
                <select name="sexo" id="sexo" style="width: 20%; background: #f8e8db; border: none">
                    <option value="Femenino">Femenino</option>
                    <option value="Masculino">Masculino</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row" style="text-align: left">Nro. de pacientes Dia en el lugar de visita</th>
            <td><input type="text" name="paci" id="paci" style="width: 20%; border: none; background: #F8E8DB"  /></td>
        </tr>
        <tr>
            <th scope="row"style="text-align: left">Tiene preferencia prescriptiva por productos de marca</th>
            <td>
                <select name="prescriptiva" id="prescriptiva" style="width: 20%; background: #f8e8db; border: none">
                    <option value="Alta">Alta</option>
                    <option value="Media">Media</option>
                    <option value="Baja">Baja</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"style="text-align: left">Nivel Socieconómico de los Pacientes</th>
            <td>
                <select name="nivel" id="nivel" style="width: 20%;background: #f8e8db; border: none">
                    <option value="Alta">Alta</option>
                    <option value="Media">Media</option>
                    <option value="Baja">Baja</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row" style="text-align: left">Costo consulta (Bs.)</th>
            <td><input type="text" name="consulta" id="consulta" style="width: 20%; border: none; background: #F8E8DB"  /></td>
        </tr>
    </table>
    <?php
else:
    while ($row = mysql_fetch_assoc($resp_query)) {
        $sexo = $row['sexo'];
        $paci = $row['n_pacientes'];
        $preferencia = $row['tiene_preferencia'];
        $nivel = $row['nivel'];
        $costo = $row['costo'];
    }
    ?>
    <table width="99%" border="0" cellpadding="5" style="margin-top: 10px">
        <tr>
            <th scope="row" style="width: 52%; text-align: left">Sexo</th>
            <td>
                <select name="sexo" id="sexo" style="width: 20%; background: #f8e8db; border: none">
                    <option value="Femenino" <?php if ($sexo == 'Femenino'): echo 'selected="selected" ';
    endif; ?>>Femenino</option>
                    <option value="Masculino" <?php if ($sexo == 'Masculino'): echo 'selected="selected" ';
                        endif; ?>>Masculino</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row" style="text-align: left">Nro. de pacientes Dia en el lugar de visita</th>
            <td><input type="text" name="paci" id="paci" style="width: 20%; border: none; background: #F8E8DB" value="<?php echo $paci ?>"  /></td>
        </tr>
        <tr>
            <th scope="row"style="text-align: left">Tiene preferencia prescriptiva por productos de marca</th>
            <td>
                <select name="prescriptiva" id="prescriptiva" style="width: 20%; background: #f8e8db; border: none">
                    <option value="Alta" <?php if ($preferencia == 'Alta'): echo 'selected="selected" ';
                        endif; ?>>Alta</option>
                    <option value="Media" <?php if ($preferencia == 'Media'): echo 'selected="selected" ';
                        endif; ?>>Media</option>
                    <option value="Baja" <?php if ($preferencia == 'Baja'): echo 'selected="selected" ';
                        endif; ?>>Baja</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"style="text-align: left">Nivel Socieconómico de los Pacientes</th>
            <td>
                <select name="nivel" id="nivel" style="width: 20%;background: #f8e8db; border: none">
                    <option value="Alta" <?php if ($nivel == 'Alta'): echo 'selected="selected" ';
                        endif; ?>>Alta</option>
                    <option value="Media" <?php if ($nivel == 'Media'): echo 'selected="selected" ';
                        endif; ?>>Media</option>
                    <option value="Baja" <?php if ($nivel == 'Baja'): echo 'selected="selected" ';
                        endif; ?>>Baja</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row" style="text-align: left">Costo consulta (Bs.)</th>
            <td><input type="text" name="consulta" id="consulta" style="width: 20%; border: none; background: #F8E8DB" value="<?php echo $costo ?>"  /></td>
        </tr>
    </table>
<?php endif; ?>
<center><input type="button"  value="Guardar" class="boton para_bloquear"> <img src="imagenes/ajax-loader.gif" alt="" id="loader" /></center>
<script type="text/javascript" src="lib/jqueryui/functions.js"></script>