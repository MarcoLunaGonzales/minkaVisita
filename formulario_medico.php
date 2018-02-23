<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript" src="ajax/categorizacion_medicos/send.data.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        // Delete a slide
        $('#table-principal tr td.deleteSlide').bind("mousedown", ( deleteSlide ));
        $('#table-principal tr td.cargar_categoria .name_farm').bind("change", ( change ));
        var cuantos = $("#cuantos").val()
        if(cuantos < 3 ){$('#add-rows').attr('id','add-row')}else{$('#add-row').attr('id','add-rows')}
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
                $(this).find("select.name_farm").attr('name', 'name_farm_'+(index+1));
                $(this).find("select.name_farm").attr('id', 'name_farm_'+(index+1));
                $(this).find("input.dir_farm").attr('name','dir_farm_'+(index+1));
                $(this).find("input.dir_farm").attr('id','dir_farm_'+(index+1));
                $("#cuantos").val(index+1);
            });
            cuantos =  $("#cuantos").val()
            if(cuantos < 3 ){$('#add-rows').attr('id','add-row')}else{$('#add-row').attr('id','add-rows')}
            // Add click event to the remove button and Drogstore categorie on the newly added row
            $('#table-principal tr:last td.deleteSlide').bind("mousedown", ( deleteSlide ));
            $('#table-principal tr td.cargar_categoria .name_farm').bind("change", ( change ));
        }));
        
        function deleteSlide() {
            // remove delete slide button if only one slide is left
            if ($('#table-principal tr').size() == 1) {
                alert("La eliminacion no esta permitida! Al menos una referencia de farmacia debe estar presente.");
                return false;
            } else {
                if (confirm("�Borrar esta farmacia?")) {
                    $(this).parent().remove();
                }
                // sort displayed row numbers
                $('#table-principal tr').each(function(index) {
                    $("#table-principal tr td.position").eq(index).html(index+1);
                    $(this).find("select.name_farm").attr('name', 'name_farm_'+(index+1));
                    $(this).find("select.name_farm").attr('id', 'name_farm_'+(index+1));
                    $(this).find("select.dir_farm").attr('name','dir_farm_'+(index+1));
                    $(this).find("select.dir_farm").attr('id','dir_farm_'+(index+1));
                    $("#cuantos").val(index+1);
                    if($("#cuantos").val() < 3 ){$('#add-rows').attr('id','add-row')}else{$('#add-row').attr('id','add-rows')}
                });

                event.stopPropagation;
                return false;
            }
        }
        function change(){
            cambiarCategoria($(this).val(),$(this).attr('name'))
        }
        $(".boton").click(function(){
            var vacio
            if($("#name_farm_1").val() == 'vacio' || $("#name_farm_2").val() == 'vacio' || $("#name_farm_3").val() == 'vacio' ){
                vacio = 0;
            }else{
                vacio = 1;
            }
            if($("#paci").val().length <= 0 || $("#consulta").val().length <= 0 || vacio ==0 ){
                alert("campos vacios")
            }else{
                $("#loader").css('display', 'block')
                $("#manto").css('display', 'block')
                $(".para_bloquear").attr('disabled', 'disabled');
                sendData(<?php echo $cod_medico ?>);
            }
        })
    });
</script>
<style>
    #add-row {
        background: transparent url('imagenes/add-slide.png') no-repeat scroll 50% 50%;
        cursor: pointer;
        margin: 20px 0 10px 10px;
        width: 102px;
        height: 32px;
        float: left;
    }
    #add-row:hover { 
        background: transparent url('imagenes/add-slide-hover.png') no-repeat scroll 50% 50%;
        cursor: pointer;
        margin: 20px 0 10px 10px;
        width: 102px;
        height: 32px;
    }
    td.deleteSlide	{
        background: transparent url(imagenes/delete-slide.png) no-repeat scroll 50% 50%;
        cursor:pointer;
    }
    #loader { display: none;  position: absolute; z-index: 110; top: 50%; left: 50% }
    #manto { width: 100%; height: 100%; background: #000; opacity:0.60; filter:Alpha(Opacity=60);  position: absolute; top: 0; left: 0; display: none; z-index: 100 }
</style>
<?php
error_reporting(0);
require("conexion.inc");
require("estilos_cuerpo.inc");
$cod_medico = $_GET['cod_medico'];

$sql2 = "select DISTINCT(a.desc_especialidad), b.direccion, d.nom_med, d.ap_pat_med, d.ap_mat_med from especialidades a , 
direcciones_medicos b, rutero_maestro_detalle_aprobado c, medicos d where  c.cod_med = $cod_medico and c.cod_especialidad = a.cod_especialidad and b.cod_med = c.cod_med and c.cod_med = d.cod_med";
//echo $sql2;
$resp_sql2 = mysql_query($sql2);
while ($row = mysql_fetch_assoc($resp_sql2)) {
    $nombre_medico = $row['nom_med'] . " " . $row['ap_pat_med'] . " " . $row['ap_mat_med'];
    $especialidad = $row['desc_especialidad'];
    $direccion = $row['direccion'];
}
$sql_ciudad = "select cod_ciudad from funcionarios where codigo_funcionario = $global_visitador";
$resp_sql_ciudad = mysql_query($sql_ciudad);
while ($row_ciudad = mysql_fetch_assoc($resp_sql_ciudad)) {
    $cod_ciudad = $row_ciudad['cod_ciudad'];
}
?>
<div id="manto"></div>
<center style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Registro de Categorizaci&oacute;n del M&eacute;dico</center>
<center style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada"><?php echo $nombre_medico ?></center>
<table align='center'><tr><td><a href='categorizacion_medica_lista.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>

<table align="left" border="0" style="margin-top:25px; margin-bottom: 10px" width="100%">
    <tr>
        <td style="width: 120px; font-weight: bold" >Especialidad:</td>
        <td><?php echo $especialidad ?></td>
    </tr>
    <tr>
        <td style="width: 120px; font-weight: bold">Direcci&oacute;n:</td>
        <td><?php echo $direccion ?></td>
    </tr>
</table>

<div style="clear: both; width: 99%;">
    <center><strong>Datos complementarios</strong></center>
    <div id="add-row"></div>
</div>
<table width="99%" border="1" cellpadding="0" id="">
    <tr>
        <th width="4%" scope="col">&nbsp;</th>
        <th width="4%" scope="col">&nbsp;</th>
        <th width="46%" scope="col">Farmacias de Referencia</th>
        <th width="46%" scope="col">Categoria l&iacute;nea &eacute;tica</th>
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
            <td class="cargar_categoria" width="46%">
                <select class="name_farm" name="name_farm_1" id="name_farm_1" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                    <option value="vacio">Selecionar una opcion</option>
                    <?php $query_farmacias = "SELECT nombre, cat_ventaetica from fclientes where cod_ciudad = $global_agencia order by nombre ASC"; ?>
                    <?php $resp_query_farmacias = mysql_query($query_farmacias); ?>
                    <?php while ($row = mysql_fetch_assoc($resp_query_farmacias)) { ?>
                        <option value="<?php echo str_replace('"', "", $row['nombre']) ?>"><?php echo $row['nombre'] ?></option>
                    <?php } ?>
                </select>
            </td>
            <td width="46%">
                <input type="text" class="dir_farm" name="dir_farm_1" id="dir_farm_1" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold " disabled="disabled" value="" />
            </td>
        </tr>
    </table>
<?php else: $count = 1; ?>
    <table width="99%" border="1" cellpadding="0" id="table-principal">
        <?php while ($row_farm = mysql_fetch_assoc($resp_query_farm)) { ?>
            <tr id="<?php echo $count; ?>" class="row-style">
                <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
                <td class="position" style="text-align: center; font-weight: bold" width="4%"><?php echo $count; ?></td>
                <td class="cargar_categoria" width="46%">
                    <select class="name_farm" name="name_farm_<?php echo $count; ?>" id="name_farm_<?php echo $count; ?>" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                        <option value="vacio">Selecionar una opcion</option>
                        <?php $query_farmacias = "SELECT nombre, cat_ventaetica from fclientes where cod_ciudad = $global_agencia order by nombre ASC"; ?>
                        <?php $resp_query_farmacias = mysql_query($query_farmacias); ?>
                        <?php while ($row = mysql_fetch_assoc($resp_query_farmacias)) { ?>
                            <option  value="<?php echo str_replace('"', "", $row['nombre']) ?>" <?php
                if (str_replace('"', "", $row_farm['nombre_farmacia']) == str_replace('"', "", $row['nombre'])): echo 'selected="selected"';
                endif;
                            ?>><?php echo $row['nombre'] ?></option>
                                 <?php } ?>
                    </select>
                </td>
                <td width="46%">
                    <input type="text" class="dir_farm" name="dir_farm_<?php echo $count; ?>" id="dir_farm_<?php echo $count; ?>" style="width: 100%; border: none; margin: 5px 0;padding-left: 10px; font-weight: bold " value="<?php echo $row_farm['direccion_farmacia'] ?>" disabled="disabled" />
                </td>
            </tr>
            <?php
            $count++;
        }
        ?>
    </table>
<?php endif; ?>
<table width="99%" border="1" cellpadding="0" id="table-clone" style="display:none;">
    <tr id="999" class="row-style">
        <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
        <td class="position"  style="text-align: center; font-weight: bold" width="4%">999</td>
        <td class="cargar_categoria" width="46%">
            <select class="name_farm" name="name_farm_999" id="name_farm_999" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                <option value="vacio">Selecionar una opcion</option>
                <?php $query_farmacias = "SELECT nombre, cat_ventaetica from fclientes where cod_ciudad = $global_agencia order by nombre ASC"; ?>
                <?php $resp_query_farmacias = mysql_query($query_farmacias); ?>
                <?php while ($row = mysql_fetch_assoc($resp_query_farmacias)) { ?>
                    <option value="<?php echo str_replace('"', "", $row['nombre']) ?>"><?php echo $row['nombre'] ?></option>
                <?php } ?>
            </select>
        </td>
        <td width="46%">
            <input type="text" class="dir_farm" name="dir_farm_999" id="dir_farm_999" style="width: 100%; border: none; margin: 5px 0;padding-left: 10px; font-weight: bold " value="" disabled="disabled" />
        </td>
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
            <th scope="row" style="width: 52%; text-align: left">Edad</th>
            <td>
                <?php $sql_edad = mysql_query(" select * from edad_categorizacion_medico ") ?>
                <select name="edad" id="edad" style="width: 40%; background: #f8e8db; border: none">
                    <option value="0">Elegir...</option>
                    <?php while ($row_edad = mysql_fetch_array($sql_edad)) { ?>
                        <?php
                        $codigo_edad = $row_edad[0];
                        $nombre_edad = $row_edad[1];
                        ?>
                        <option value="<?php echo $codigo_edad ?>"><?php echo $nombre_edad ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row" style="text-align: left">Nro. de pacientes d&iacute;a en el lugar de visita</th>
            <td><input type="text" name="paci" id="paci" style="width: 20%; border: none; background: #F8E8DB" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');"  /></td>
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
            <th scope="row"style="text-align: left">Nivel Sociecon&oacute;mico de los Pacientes</th>
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
            <td><input type="text" name="consulta" id="consulta" style="width: 20%; border: none; background: #F8E8DB" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" /></td>
        </tr>
    </table>
    <?php
else:
    while ($row = mysql_fetch_assoc($resp_query)) {
        $sexo = $row['sexo'];
        $edad = $row['edad'];
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
                    <option value="Femenino" <?php
    if ($sexo == 'Femenino'): echo 'selected="selected" ';
    endif;
    ?>>Femenino</option>
                    <option value="Masculino" <?php
                        if ($sexo == 'Masculino'): echo 'selected="selected" ';
                        endif;
    ?>>Masculino</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row" style="width: 52%; text-align: left">Edad</th>
            <td>
                <select name="edad" id="edad" style="width: 40%; background: #f8e8db; border: none">
                    <option value="1" <?php if ($edad == '1'): echo "selected=selected"; endif ?>>Menor igual a 30 a&ntilde;os</option>
                    <option value="2" <?php if ($edad == '2'): echo "selected=selected"; endif ?>>Mayor de 31 y menor igual a 50 a&ntilde;os</option>
                    <option value="3" <?php if ($edad == '3'): echo "selected=selected"; endif ?>>Mayor de 51 y menor igual a 60 a&ntilde;os</option>
                    <option value="4" <?php if ($edad == '4'): echo "selected=selected"; endif ?>>Mayor de 60 a&ntilde;os </option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row" style="text-align: left">Nro. de pacientes d&iacute;a en el lugar de visita</th>
            <td><input type="text" name="paci" id="paci" style="width: 20%; border: none; background: #F8E8DB" value="<?php echo $paci ?>" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');"  /></td>
        </tr>
        <tr>
            <th scope="row"style="text-align: left">Tiene preferencia prescriptiva por productos de marca</th>
            <td>
                <select name="prescriptiva" id="prescriptiva" style="width: 20%; background: #f8e8db; border: none">
                    <option value="Alta" <?php
                        if ($preferencia == 'Alta'): echo 'selected="selected" ';
                        endif;
    ?>>Alta</option>
                    <option value="Media" <?php
                        if ($preferencia == 'Media'): echo 'selected="selected" ';
                        endif;
    ?>>Media</option>
                    <option value="Baja" <?php
                        if ($preferencia == 'Baja'): echo 'selected="selected" ';
                        endif;
                        ?>>Baja</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row"style="text-align: left">Nivel Sociecon&oacute;mico de los Pacientes</th>
            <td>
                <select name="nivel" id="nivel" style="width: 20%;background: #f8e8db; border: none">
                    <option value="Alta" <?php
                        if ($nivel == 'Alta'): echo 'selected="selected" ';
                        endif;
    ?>>Alta</option>
                    <option value="Media" <?php
                        if ($nivel == 'Media'): echo 'selected="selected" ';
                        endif;
    ?>>Media</option>
                    <option value="Baja" <?php
                        if ($nivel == 'Baja'): echo 'selected="selected" ';
                        endif;
                        ?>>Baja</option>
                </select>
            </td>
        </tr>
        <tr>
            <th scope="row" style="text-align: left">Costo consulta (Bs.)</th>
            <td><input type="text" name="consulta" id="consulta" style="width: 20%; border: none; background: #F8E8DB" value="<?php echo $costo ?>" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" /></td>
        </tr>
    </table>
<?php endif; ?>
<center><input type="button"  value="Guardar" class="boton para_bloquear"> <img src="imagenes/ajax-loader3.gif" alt="" id="loader" /></center>