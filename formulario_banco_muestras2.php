<?php
error_reporting(0);
require("conexion.inc");
$cod_medico = $_GET['cod_medico'];
$especialidadd = $_GET['espe'];
$categoriaa = $_GET['categoria'];
$agencia = $_GET['agencia'];

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

<?php
$sql_grilla = mysql_query("select max(DISTINCT b.frecuencia) from grilla a, grilla_detalle b where b.cod_especialidad = '$especialidadd' and b.cod_categoria = '$categoriaa' and a.codigo_linea = $global_linea and a.codigo_grilla = b.codigo_grilla and a.agencia = $agencia and a.estado = 1 ");
//echo("select max(DISTINCT b.frecuencia) from grilla a, grilla_detalle b where b.cod_especialidad = '$especialidadd' and b.cod_categoria = '$categoriaa' and a.codigo_linea = $global_linea and a.codigo_grilla = b.codigo_grilla and a.agencia = $agencia and a.estado = 1 ");
$frecuencia = mysql_result($sql_grilla, 0, 0);
?>

<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Banco De Muestras</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
        <link rel="stylesheet" href="responsive/stylesheets/style.css">
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <link type="text/css" href="css/tables.css" rel="stylesheet" />
        <script type="text/javascript" language="javascript" src="lib/jquery.dataTables.js"></script>
        <script type="text/javascript" src="ajax/banco_muestras/send.data.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                //calcular
                $("#calcular-row").bind("click",(calcular));
                // Delete a slide
                $('#table-principal tr td.deleteSlide').bind("mousedown", ( deleteSlide ));
                //        $('#table-principal tr td.cargar_categoria .name_farm').bind("change", ( change ));
                var cuantos = $("#cuantos").val()
                if(cuantos < 5 ){$('#add-rows').attr('id','add-row')}else{$('#add-row').attr('id','add-rows')}
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
                    var cuan= $("#cuantos").val();
                    var columna = '<th scope="col">Cantidad '+(highestID++)+'</th>';
                    $("#head_visitadores").append(columna);
                    
                    $("#body_visitadores tr").each(function(index){
                        inde = (index+1)
                        var columna_body = '<td class="'+cuan+'"><input type="text" class="dir_farm" name="cant_asig_'+cuan+'_'+inde+'" id="cant_asig_'+cuan+'_'+inde+'" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold "  value="" /></td>';
                        $(this).append(columna_body);
                    });
                    
                    cuantos =  $("#cuantos").val()
                    if(cuantos < 5 ){$('#add-rows').attr('id','add-row')}else{$('#add-row').attr('id','add-rows')}
                    // Add click event to the remove button and Drogstore categorie on the newly added row
                    $('#table-principal tr:last td.deleteSlide').bind("mousedown", ( deleteSlide ));
                    //            $('#table-principal tr td.cargar_categoria .name_farm').bind("change", ( change ));
                }));
        
                function deleteSlide() {
                    // remove delete slide button if only one slide is left
                    if ($('#table-principal tr').size() == 1) {
                        alert("La eliminacion no esta permitida! Al menos una referencia de farmacia debe estar presente.");
                        return false;
                    } else {
                        if (confirm("Borrar esta Muestra?")) {
                            $(this).parent().remove();
                            $("#head_visitadores th:last-child").remove();
                            $("#body_visitadores td:last-child").remove();
                        }
                        // sort displayed row numbers
                        $('#table-principal tr').each(function(index) {
                            $("#table-principal tr td.position").eq(index).html(index+1);
                            $(this).find("select.name_farm").attr('name', 'name_farm_'+(index+1));
                            $(this).find("select.name_farm").attr('id', 'name_farm_'+(index+1));
                            $(this).find("select.dir_farm").attr('name','dir_farm_'+(index+1));
                            $(this).find("select.dir_farm").attr('id','dir_farm_'+(index+1));
                            $("#cuantos").val(index+1);
                            if($("#cuantos").val() < 5 ){$('#add-rows').attr('id','add-row')}else{$('#add-row').attr('id','add-rows')}
                        });

                        event.stopPropagation;
                        return false;
                    }
                }
                function change(){
                    cambiarCategoria($(this).val(),$(this).attr('name'))
                }
                function is_impar(valor){
                    if(valor % 2 == 0){
                        return false;
                    }else{
                        return true;
                    }
                }
                function cal(valor,cuantos){
                    a = parseFloat(valor)+1;
                    if(a % cuantos == 0){
                        return true
                    }else{
                        return false
                    }
                }
                function calcular(){
                    $("#table-principal tr").each(function(index){
                        valor1 =$(this).find('#dir_farm_'+(index+1)).val();
                        cantidad = $("#vsitadores_totales").val()
                        if(valor1 % cantidad == 0){
                            var fnal = valor1/cantidad;
                            for(var i=1;i<=cantidad;i++){
                                $("#cant_asig_"+(index+1)+'_'+i).attr('value',fnal);
                            }
                        }else{
                            if(cal(valor1,cantidad)){
                                var valor_ext =parseFloat(valor1)+1;
                            }else{
                                var valor_ext =parseFloat(valor1)+2;
                            }
                            var fnal = valor_ext/cantidad;
                            for(var i=1;i<=cantidad;i++){
                                $("#cant_asig_"+(index+1)+'_'+i).attr('value',fnal);
                                fnal--;
                            }
                            if(cal(valor1,cantidad)){
                                fnall = $("#cant_asig_"+(index+1)+'_'+cantidad).attr('value');
                                $("#cant_asig_"+(index+1)+'_'+cantidad).attr('value',(parseFloat(fnall)+2));
                            }else{
                                fnall = $("#cant_asig_"+(index+1)+'_'+cantidad).attr('value');
                                $("#cant_asig_"+(index+1)+'_'+cantidad).attr('value',(parseFloat(fnall)+1));
                            }
                        }
                    })
                    
                    
                    //                    valor1 = $("#dir_farm_1").val();
                    //                    cantidad = $("#vsitadores_totales").val()
                    //                    if(valor1 % cantidad == 0){
                    //                        var fnal = valor1/cantidad;
                    //                        for(var i=1;i<=cantidad;i++){
                    //                            $("#cant_asig_"+i).attr('value',fnal);
                    //                        }
                    //                    }else{
                    //                        if(cal(valor1,cantidad)){
                    //                            var valor_ext =parseFloat(valor1)+1;
                    //                        }else{
                    //                            var valor_ext =parseFloat(valor1)+2;
                    //                        }
                    //                        //                        alert(valor_ext)
                    //                        var fnal = valor_ext/cantidad;
                    //                        for(var i=1;i<=cantidad;i++){
                    //                            $("#cant_asig_"+i).attr('value',fnal);
                    //                            fnal--;
                    //                        }
                    //                        if(cal(valor1,cantidad)){
                    //                            fnall = $("#cant_asig_"+cantidad).attr('value');
                    //                            $("#cant_asig_"+cantidad).attr('value',(parseFloat(fnall)+2));
                    //                        }else{
                    //                            fnall = $("#cant_asig_"+cantidad).attr('value');
                    //                            $("#cant_asig_"+cantidad).attr('value',(parseFloat(fnall)+1));
                    //                        }
                    //                    }
                    
                }
                $(".boton").click(function(){
                    var vacio
                    if($("#name_farm_1").val() == 'vacio' ){
                        vacio = 0;
                    }else{
                        vacio = 1;
                    }
                    if(vacio ==0 ){
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
    </head>
    <body>
        <div id="manto"></div>
        <div id="container">
            <header id="titulo">
                <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Registro Banco de muestras</h3>
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
                </div><!-- end row -->
                <div class="row">
                    <div class="two columns">
                        <p class="bold">Direcci&oacute;n:</p>
                    </div>
                    <div class="two columns end">
                        <p><?php echo $direccion ?></p>
                    </div>
                </div><!-- end row -->
                <?php
                $sql_numero = mysql_query("select nro_visita from banco_muestras where cod_med = $cod_medico");
                $nro_visita = mysql_result($sql_numero, 0, 0)
                ?>
                <div class="row">
                    <div class="four columns end">
                        <table align="left" border="1" style="margin-top:25px; margin-bottom: 10px" width="100%">
                            <thead>
                                <tr>
                                    <th>N&uacute;mero de Visita?</th>
                                    <td>
                                        <select name="numero_visita" id="numero_visita" disabled>
                                            <option value="<?php echo $nro_visita; ?>" ><?php echo $nro_visita; ?></option>
                                        </select>
                                    </td>
                                </tr>
                            </thead>

                        </table>    
                    </div>
                    <div class="three columns end">
                        <!--<div id="add-row"></div>-->
                    </div>
                    <div class="two columns end">
                        <!--<div id="calcular-row"></div>-->
                    </div>
                </div>
                <div class="row">
                    <div class="seven columns end">
                        <table width="99%" border="1" cellpadding="0" id="" style="margin: 0">
                            <tr>
                                <th width="4%" scope="col">&nbsp;</th>
                                <th width="4%" scope="col">&nbsp;</th>
                                <th width="66%" scope="col">Muestras M&eacute;dicas</th>
                                <th width="26%" scope="col">Cantidad</th>
                            </tr>
                        </table>
                        <?php
                        $query_farm = "select cod_muestra, cantidad from banco_muestras_detalle where cod_med=$cod_medico";
                        $resp_query_farm = mysql_query($query_farm);
                        $num_query_farm = mysql_num_rows($resp_query_farm);


                        if ($num_query_farm == 0):
                            ?>
                            <table width="99%" border="1" cellpadding="0" id="table-principal">
                                <tr id="1" class="row-style">
                                    <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
                                    <td class="position" style="text-align: center; font-weight: bold; font-size: 2em; line-height: 51px" width="4%">1</td>
                                    <td class="cargar_categoria" width="66%">
                                        <select class="name_farm" name="name_farm_1" id="name_farm_1" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; " disabled>
                                            <option value="vacio">Selecionar una opcion</option>
                                            <?php $query_farmacias = "SELECT codigo, CONCAT(descripcion,' ',presentacion) as nombre from muestras_medicas order by  descripcion ASC"; ?>
                                            <?php $resp_query_farmacias = mysql_query($query_farmacias); ?>
                                            <?php while ($row = mysql_fetch_assoc($resp_query_farmacias)) { ?>
                                                <option value="<?php echo $row['codigo']; ?>"><?php echo $row['nombre'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td width="26%">
                                        <input type="text" class="dir_farm" name="dir_farm_1" id="dir_farm_1" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold "  value=""  disabled/>
                                    </td>
                                </tr>
                            </table>
                        <?php else: $count = 1; ?>
                            <table width="99%" border="1" cellpadding="0" id="table-principal">
                                <?php while ($row_farm = mysql_fetch_assoc($resp_query_farm)) { ?>
                                    <tr id="<?php echo $count; ?>" class="row-style">
                                        <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
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
                        <?php endif; ?>
                        <table width="99%" border="1" cellpadding="0" id="table-clone" style="display:none;">
                            <tr id="999" class="row-style">
                                <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
                                <td class="position"  style="text-align: center; font-weight: bold;font-size: 2em; line-height: 51px" width="4%">999</td>
                                <td class="cargar_categoria" width="66%">
                                    <select class="name_farm" name="name_farm_999" id="name_farm_999" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                                        <option value="vacio">Selecionar una opcion</option>
                                        <?php $query_farmacias = "SELECT codigo, CONCAT(descripcion,' ',presentacion) as nombre from muestras_medicas order by  descripcion ASC"; ?>
                                        <?php $resp_query_farmacias = mysql_query($query_farmacias); ?>
                                        <?php while ($row = mysql_fetch_assoc($resp_query_farmacias)) { ?>
                                            <option value="<?php echo $row['codigo']; ?>"><?php echo $row['nombre'] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td width="26%">
                                    <input type="text" class="dir_farm" name="dir_farm_999" id="dir_farm_999" style="width: 100%; border: none; margin: 5px 0;padding-left: 10px; font-weight: bold " value="" disabled />
                                </td>
                            </tr>
                        </table>
                        <?php if ($num_query_farm == 0): ?>
                            <input type="hidden" name="cuantos" id="cuantos" value="1" />
                        <?php else: ?>
                            <input type="hidden" name="cuantos" id="cuantos" value="<?php echo $count - 1; ?>" />
                        <?php endif; ?>
                    </div>
                    <div class="five columns">
                        <!--<p>Visitadores que pertenecen al m&eacute;dico</p>-->
                        <?php
                        $sql_visitadores = mysql_query("select DISTINCT rmda.cod_visitador, CONCAT(f.nombres,' ',f.paterno,' ',f.materno) from rutero_maestro_aprobado rma, rutero_maestro_cab_aprobado rmca, 
                            rutero_maestro_detalle_aprobado rmda, funcionarios f where rmca.cod_rutero = rma.cod_rutero and rma.cod_contacto = rmda.cod_contacto
                            and f.codigo_funcionario = rmda.cod_visitador and rmda.cod_med = $cod_medico and rmca.codigo_ciclo = 8 and rmca.codigo_gestion = 1009");
//                        echo("select DISTINCT rmda.cod_visitador, CONCAT(f.nombres,' ',f.paterno,' ',f.materno) from rutero_maestro_aprobado rma, rutero_maestro_cab_aprobado rmca, 
//                            rutero_maestro_detalle_aprobado rmda, funcionarios f where rmca.cod_rutero = rma.cod_rutero and rma.cod_contacto = rmda.cod_contacto
//                            and f.codigo_funcionario = rmda.cod_visitador and rmda.cod_med = $cod_medico and rmca.codigo_ciclo = 8 and rmca.codigo_gestion = 1009");
                        $cont = 1;
                        $query_farm_can = "select cod_medico, cantidad from banco_muestra_cantidad_visitador where cod_medico=$cod_medico";
                        $resp_query_farm_can = mysql_query($query_farm_can);
                        $num_query_farm_can = mysql_num_rows($resp_query_farm_can);
                        if ($num_query_farm_can == 0):
                            ?>
                            <table width="99%" border="1" cellpadding="0" id="visitadoress">
                                <thead>
                                    <tr id="head_visitadores">
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col">Nombre Visitador</th>
                                        <th scope="col">Cantidad 1</th>
                                    </tr>
                                </thead>
                                <tbody id="body_visitadores">
                                    <?php while ($row_v = mysql_fetch_array($sql_visitadores)) { ?>
                                        <tr>
                                            <td class="position" style="text-align: center; font-weight: bold; font-size: 2em; line-height: 51px"><?php echo $cont; ?></td>
                                            <td class="cargar_categoria">
                                                <input type="hidden" id="visitador_<?php echo $cont; ?>" value="<?php echo $row_v[0]; ?>" />
                                                <?php echo $row_v[1]; ?>
                                            </td>
                                            <td>
                                                <input type="text" class="dir_farm" name="cant_asig_1_<?php echo $cont; ?>" id="cant_asig_1_<?php echo $cont; ?>" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold "  value=""  disabled/>
                                                <!--<input type="text" class="dir_farm" name="cant_asig_1" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold "  value="" />-->
                                            </td>
                                        </tr>
                                        <?php
                                        $cont++;
                                    }
                                    ?>
                                    </tbdoy>
                            </table>
                        <?php else: ?>
                            <table width="99%" border="1" cellpadding="0" id="visitadoress">
                                <thead>
                                    <tr id="head_visitadores">
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col">Nombre Visitador</th>
                                        <?php
                                        $cant = 1;
                                        while ($roww = mysql_fetch_array($resp_query_farm_can)) {
                                            ?>
                                            <th scope="col">Cantidad <?php echo $cant;
                                    $cant++;
                                    ?>
                                            </th>
    <?php } ?>
                                    </tr>
                                </thead>
                                <tbody id="body_visitadores">
                                    <?php
                                    $query_farm_can = "select cod_medico, cantidad from banco_muestra_cantidad_visitador where cod_medico=$cod_medico";
                                    $resp_query_farm_can = mysql_query($query_farm_can);
                                    ?>
    <?php while ($row_v = mysql_fetch_array($sql_visitadores)) { ?>
                                        <tr>
                                            <td class="position" style="text-align: center; font-weight: bold; font-size: 2em; line-height: 51px"><?php echo $cont; ?></td>
                                            <td class="cargar_categoria">
                                                <input type="hidden" id="visitador_<?php echo $cont; ?>" value="<?php echo $row_v[0]; ?>" />
                                            <?php echo $row_v[1]; ?>
                                            </td>
        <?php $cant = 1;
        while ($roww = mysql_fetch_array($resp_query_farm_can)) {
            ?>
                                                <td>
                                                    <input type="text" class="dir_farm" name="cant_asig_<?php echo $cant; ?>_<?php echo $cont; ?>" id="cant_asig_<?php echo $cant; ?>_<?php echo $cont; ?>" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold "  value="" disabled/>
                                                </td>
                                            <?php $cant++;
                                        }
                                        ?>
                                        </tr>
        <?php
        $cont++;
    }
    ?>
                                    </tbdoy>
                            </table>
<?php endif; ?>
                        <input type="hidden" value="<?php echo $cont = $cont - 1; ?>" id="vsitadores_totales" />
                    </div>
                </div>
                <div class="row">
                    <div class="seven columns">
<?php $sql_linea = mysql_query("select codigo_linea from categorias_lineas where cod_med = $cod_medico"); ?>
<?php
$linea_final = mysql_result($sql_linea, 0, 0);
?>
                        <input type="hidden" name="linea" id="linea" value="<?php echo $linea_final; ?>" />
                        <!--<center><input type="button"  value="Guardar" class="boton para_bloquear"> <img src="imagenes/ajax-loader3.gif" alt="" id="loader" /></center>-->
                    </div>
                </div>
            </section>
        </div>
    </body>
</html>