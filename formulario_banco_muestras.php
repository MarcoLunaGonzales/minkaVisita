<?php
error_reporting(0);
require("conexion.inc");
$cod_medico = $_GET['cod_medico'];
$especialidadd = $_GET['espe'];
$categoriaa = $_GET['categoria'];
$agencia = $_GET['agencia'];

$sql2 = "SELECT DISTINCT(a.desc_especialidad), b.direccion, d.nom_med, d.ap_pat_med, d.ap_mat_med from especialidades a , direcciones_medicos b, rutero_maestro_detalle_aprobado c, medicos d where  c.cod_med = $cod_medico and c.cod_especialidad = a.cod_especialidad and b.cod_med = c.cod_med and c.cod_med = d.cod_med";

$sql_gestion = mysql_query("SELECT codigo_gestion from gestiones where estado = 'Activo'");
$gestion = mysql_result($sql_gestion, 0, 0);
$sql_ciclo = mysql_query("SELECT DISTINCT cod_ciclo from ciclos where estado = 'Activo'");
$ciclo = mysql_result($sql_ciclo, 0, 0);
$resp_sql2 = mysql_query($sql2);
while ($row = mysql_fetch_assoc($resp_sql2)) {
    $nombre_medico = $row['nom_med'] . " " . $row['ap_pat_med'] . " " . $row['ap_mat_med'];
    $especialidad = $row['desc_especialidad'];
    $direccion = $row['direccion'];
}
$sql_ciudad = "SELECT cod_ciudad from funcionarios where codigo_funcionario = $global_visitador";
$resp_sql_ciudad = mysql_query($sql_ciudad);
while ($row_ciudad = mysql_fetch_assoc($resp_sql_ciudad)) {
    $cod_ciudad = $row_ciudad['cod_ciudad'];
}
?>

<?php
$sql_grilla = mysql_query("SELECT max(DISTINCT b.frecuencia) from grilla a, grilla_detalle b where b.cod_especialidad = '$especialidadd' and b.cod_categoria = '$categoriaa' and a.codigo_linea = $global_linea and a.codigo_grilla = b.codigo_grilla and a.agencia = $agencia and a.estado = 1 ");
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

        var valor1c = $("#ciclo1").val();
        var valor2c = $("#ciclo2").val();

        $(".dir_farm").blur(function(){
            var value = $(this).val();
            if(value<=30 && value > 0){

            }else{
                alert("El valor no puede pasar de 30 unidades o tener 0 unidades.")
                $(this).val('1');
            }
        })
        $("#calcular-row").bind("click",(calcular));
        $('#table-principal tr td.deleteSlide').bind("mousedown", ( deleteSlide ));
        var cuantos = $("#cuantos").val()
        if(cuantos < 5 ){
            $('#add-rows').attr('id','add-row')
        }else{
            $('#add-row').attr('id','add-rows')
        }
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
            if(cuantos < 5 ){
                $('#add-rows').attr('id','add-row')
            }else{
                $('#add-row').attr('id','add-rows')
            }
            $('#table-principal tr:last td.deleteSlide').bind("mousedown", ( deleteSlide ));
            $(".dir_farm").blur(function(){
                var value = $(this).val();
                if(value<=30 && value > 0){

                }else{
                    alert("El valor no puede pasar de 30 unidades o tener 0 unidades.")
                    $(this).val('1');
                }
            })
        }));

function deleteSlide() {
    if ($('#table-principal tr').size() == 1) {
        alert("La eliminacion no esta permitida! Al menos una referencia de farmacia debe estar presente.");
        return false;
    } else {
        if (confirm("Borrar esta Muestra?")) {
            $(this).parent().remove();
            $("#head_visitadores th:last-child").remove();
            $("#body_visitadores td:last-child").remove();
        }
        $('#table-principal tr').each(function(index) {
            $("#table-principal tr td.position").eq(index).html(index+1);
            $(this).find("select.name_farm").attr('name', 'name_farm_'+(index+1));
            $(this).find("select.name_farm").attr('id', 'name_farm_'+(index+1));
            $(this).find("select.dir_farm").attr('name','dir_farm_'+(index+1));
            $(this).find("select.dir_farm").attr('id','dir_farm_'+(index+1));
            $("#cuantos").val(index+1);
            if($("#cuantos").val() < 5 ){$('#add-rows').attr('id','add-row')}else{$('#add-row').attr('id','add-rows')}
        });
        $(".dir_farm").blur(function(){
            var value = $(this).val();
            if(value<=30 && value > 0){

            }else{
                alert("El valor no puede pasar de 30 unidades o tener 0 unidades.")
                $(this).val('1');
            }
        })

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
    if(a % cuantos == 0 ){
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
            if(cantidad == 1){
                cantidad = cantidad;
            }else{
                var nmenosuno = (cantidad-1)
            }
            var fnal = valor1/cantidad;
            fnal = fnal.toFixed();
            var sumfinal = 0 ;
            for(var a =1; a<=nmenosuno;a++){
                $("#cant_asig_"+(index+1)+'_'+a).attr('value',fnal);
                sumfinal = parseFloat(fnal) + parseFloat(sumfinal);
            }
            var nmasuno = parseFloat(nmenosuno)+1;
            var ul_fnal = valor1 - sumfinal;
            $("#cant_asig_"+(index+1)+'_'+nmasuno).attr('value',ul_fnal);
        }
    })
}
$(".boton").click(function(){
    var valor1c = $("#ciclo1").val();
	//alert(valor1c);
    var valor2c = $("#ciclo2").val();
	//alert(valor2c);
    var totoal = valor2c - valor1c;
    if(totoal <3 && totoal >=0){
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
    }else{
        alert("El lapso de ciclos no puede ser mayor a 3 ciclos")
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
                <div class="columns">
                    <p class="bold">Categor&iacute;a:</p>
                </div>
                <div class="one columns end">
                    <p><?php echo $categoriaa; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="two columns">
                    <p class="bold">Direcci&oacute;n:</p>
                </div>
                <div class="two columns end">
                    <p><?php echo $direccion ?></p>
                </div>
            </div>
            <div class="row">
                <div class="four columns end">
                    <table align="left" border="1" style="margin-top:25px; margin-bottom: 10px" width="100%">
                        <thead>
                            <tr>
                                <th>Contacto N&uacute;mero:</th>
                                <td>
                                    <select name="numero_visita" id="numero_visita">
                                        <?php
                                        while ($frecuencia >= 1) {
                                            ?>
                                            <option value="<?php echo $frecuencia; ?>"><?php echo $frecuencia; ?></option>
                                            <?php
                                            $frecuencia = $frecuencia - 1;
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </thead>

                    </table>    
                </div>
                <div class="one columns end">
                    <div id="add-row"></div>
                </div>

            </div>
            <div class="row">
                <?php
                $sql_ciclo = mysql_query("SELECT distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,11");
                ?>
                <div class="three columns end">
                    <table align="left" border="1" style="margin-top:25px; margin-bottom: 10px" width="100%">
                        <thead>
                            <tr>
                                <th>De:</th>
                                <td>
                                    <select name="ciclo1" id="ciclo1">
                                        <?php
                                        while ($row1 = mysql_fetch_array($sql_ciclo)) {
                                            $codCiclo = $row1[0];
                                            $codGestion = $row1[1];
                                            $nomGestion = $row1[2];
                                            ?>
                                            <option value="<?php echo $codCiclo; ?>"><?php echo $codCiclo . " " . $nomGestion; ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                </tr>
                            </thead>
                        </table>    
                    </div>
                    <div class="three columns end">
                        <?php
                        $sql_ciclo2 = mysql_query("SELECT distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,11");
                        ?>
                        <table align="left" border="1" style="margin-top:25px; margin-bottom: 10px" width="100%">
                            <thead>
                                <tr>
                                    <th>Hasta:</th>
                                    <td>
                                        <select name="ciclo2" id="ciclo2">
                                            <?php
                                            while ($row2 = mysql_fetch_array($sql_ciclo2)) {
                                                $codCiclo1 = $row2[0];
                                                $codGestion1 = $row2[1];
                                                $nomGestion1 = $row2[2];
                                                ?>
                                                <option value="<?php echo $codCiclo1; ?>"><?php echo $codCiclo1 . " " . $nomGestion1; ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                    </tr>
                                </thead>
                            </table>    
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
                            $query_farm = "SELECT cod_muestra, cantidad from banco_muestras_detalle where cod_med=$cod_medico";
                            $resp_query_farm = mysql_query($query_farm);
                            $num_query_farm = mysql_num_rows($resp_query_farm);
                            if ($num_query_farm == 0){
                                ?>
                                <table width="99%" border="1" cellpadding="0" id="table-principal">
                                    <tr id="1" class="row-style">
                                        <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
                                        <td class="position" style="text-align: center; font-weight: bold; font-size: 2em; line-height: 51px" width="4%">1</td>
                                        <td class="cargar_categoria" width="66%">
                                            <select class="name_farm" name="name_farm_1" id="name_farm_1" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                                                <option value="vacio">Selecionar una opcion</option>
                                                <?php $query_farmacias = "SELECT codigo, CONCAT(descripcion,' ',presentacion) as nombre from muestras_medicas where estado = '1' order by  descripcion ASC"; ?>
                                                <?php $resp_query_farmacias = mysql_query($query_farmacias); ?>
                                                <?php while ($row = mysql_fetch_assoc($resp_query_farmacias)) { ?>
                                                <option value="<?php echo $row['codigo']; ?>"><?php echo $row['nombre'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td width="26%">
                                            <input type="text" class="dir_farm" name="dir_farm_1" id="dir_farm_1" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold "  value="" />
                                        </td>
                                    </tr>
                                </table>
                                <?php }else{ $count = 1; ?>
                                <table width="99%" border="1" cellpadding="0" id="table-principal">
                                    <?php while ($row_farm = mysql_fetch_assoc($resp_query_farm)) { ?>
                                    <tr id="<?php echo $count; ?>" class="row-style">
                                        <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
                                        <td class="position" style="text-align: center; font-weight: bold;font-size: 2em; line-height: 51px" width="4%"><?php echo $count; ?></td>
                                        <td class="cargar_categoria" width="66%">
                                            <select class="name_farm" name="name_farm_<?php echo $count; ?>" id="name_farm_<?php echo $count; ?>" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                                                <option value="vacio">Selecionar una opcion</option>
                                                <?php $query_farmacias = "SELECT codigo, CONCAT(descripcion,' ',presentacion) as nombre from muestras_medicas where estado = '1' order by  descripcion ASC"; ?>
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
                                            <input type="text" class="dir_farm" name="dir_farm_<?php echo $count; ?>" id="dir_farm_<?php echo $count; ?>" style="width: 100%; border: none; margin: 5px 0;padding-left: 10px; font-weight: bold " value="<?php echo $row_farm['cantidad'] ?>" />
                                        </td>
                                    </tr>
                                    <?php
                                    $count++;
                                }
                                ?>
                            </table>
                            <?php } ?>
                            <table width="99%" border="1" cellpadding="0" id="table-clone" style="display:none;">
                                <tr id="999" class="row-style">
                                    <td class="deleteSlide" style="text-align: center; font-weight: bold" width="4%"></td>
                                    <td class="position"  style="text-align: center; font-weight: bold;font-size: 2em; line-height: 51px" width="4%">999</td>
                                    <td class="cargar_categoria" width="66%">
                                        <select class="name_farm" name="name_farm_999" id="name_farm_999" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                                            <option value="vacio">Selecionar una opcion</option>
                                            <?php $query_farmacias = "SELECT codigo, CONCAT(descripcion,' ',presentacion) as nombre from muestras_medicas where estado = '1' order by  descripcion ASC"; ?>
                                            <?php $resp_query_farmacias = mysql_query($query_farmacias); ?>
                                            <?php while ($row = mysql_fetch_assoc($resp_query_farmacias)) { ?>
                                            <option value="<?php echo $row['codigo']; ?>"><?php echo $row['nombre'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td width="26%">
                                        <input type="text" class="dir_farm" name="dir_farm_999" id="dir_farm_999" style="width: 100%; border: none; margin: 5px 0;padding-left: 10px; font-weight: bold " value=""  />
                                    </td>
                                </tr>
                            </table>
                            <?php if ($num_query_farm == 0){ ?>
                            <input type="hidden" name="cuantos" id="cuantos" value="1" />
                            <?php }else{ ?>
                            <input type="hidden" name="cuantos" id="cuantos" value="<?php echo $count - 1; ?>" />
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="seven columns">
                            <?php $sql_linea = mysql_query("SELECT codigo_linea from categorias_lineas where cod_med = $cod_medico"); ?>
                            <?php
                            $linea_final = mysql_result($sql_linea, 0, 0);
                            ?>
                            <input type="hidden" name="linea" id="linea" value="<?php echo $linea_final; ?>" />
                            <center><input type="button"  value="Guardar" class="boton para_bloquear"> <img src="imagenes/ajax-loader3.gif" alt="" id="loader" /></center>
                        </div>
                    </div>
                </section>
            </div>
        </body>
        </html>