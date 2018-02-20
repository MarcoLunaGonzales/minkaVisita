<?php
error_reporting(0);
require("conexion.inc");


$ciclo          = $_GET['ciclo'];
$fecha          = $_GET["fecha"];
$cuantos        = $_GET["cuantos"];
$desde          = $_GET["desde"];
$territorios    = $_GET["territorios"];
$tipo           = $_GET["tipo"];
$explode_ciclo  = explode("|", $ciclo);
$codigo_ciclo   = $explode_ciclo[0];
$codigo_gestion = $explode_ciclo[1];
$tags           = $_GET['tags'];

$hasra_js  = $cuantos;
$desde_js  = $desde;
$cauntos_f = $cuantos;
$desde_f   = $desde;

if (isset($tags)) {
    $tags_sub = substr($tags, 0, -1);
    $tags_exp = explode(",", $tags_sub);
    foreach ($tags_exp as $key => $valor) {
        $valor_exp = explode("@", $valor);
        $valores_finales .="'" . $valor_exp[1] . "',";
    }
    $valores_finales_sub = substr($valores_finales, 0, -1);
    $cadena = "AND a.codigo_producto not in ($valores_finales_sub)";
} else {
    $cadena = '';
}
if($tipo == 0){
    if ($desde == 1) {
        $tabla = "nth-child(-n+" . $cuantos . ")";
    }
    if ($desde > 1) {
        $cuantos = ($cuantos + $desde) - 1;
        $tabla = "nth-child(n+" . $desde . "):nth-child(-n+" . $cuantos . ")";
    }
}

$territorios_finales = explode(",", $territorios);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Productos Objetivo</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="js/tableDnD/tablednd.css" rel="stylesheet" />
    <link type="text/css" href="js/tags/jquery.tagsinput.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="lib/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="js/tableDnD/js/jquery.tablednd.0.7.min.js"></script>
    <script type="text/javascript" src="js/tags/jquery.tagsinput.js"></script>
    <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/jquery-ui.min.js'></script>
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.13/themes/start/jquery-ui.css" />
    <style type="text/css">
    <?php  
    if($tipo == 0){
        ?>
        .primerostres tbody tr:<?php echo $tabla ?> {
            background-color: #fa5968;
        } 
        <?php
    }
    if($tipo == 1){
        $cuantos_explode = explode(",",$desde);
        foreach($cuantos_explode as $valores){
            ?>
            .primerostres tbody tr:nth-child(<?php echo $valores ?>) {
                background-color: #fa5968;
            }
            <?php
        }
    }
    ?>
    input[type="button"]{
        cursor: pointer;
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
    body.loading {
        overflow: hidden;   
    }
    body.loading .modal {
        display: block;
    }
    </style>
    <script type="text/javascript">
    $(document).ready(function() {
        $("body").on({
            ajaxStart: function() { 
                $(this).addClass("loading"); 
            },
            ajaxStop: function() { 
                $(this).removeClass("loading"); 
            }    
        });
        var desde, hasta,hasta1;
        desde = <?php echo $desde_js; ?>;
        hasta = <?php echo $hasra_js; ?>;
        if(desde == 1){
            $(".primerostres tbody tr:nth-child(-n+"+desde+")").addClass("empieza")
        }
        if(desde > 1){
            hasta1 = (hasta + desde) - 1;
            $(".primerostres tbody tr:nth-child(n+"+desde+"):nth-child(-n+"+hasta1+")").addClass("empieza")
        }
        var ciclo_de,fecha_hasta,territorios,textos='',cuantos,desde;
        cuantos = <?php echo $cauntos_f; ?>;
        desde = <?php echo $desde_f; ?>;
        tipo  = <?php echo $tipo; ?>;
        ciclo_de    = $("#ciclo_de").val();
        fecha_hasta = $("#fecha1").val();
        territorios = $("#territorio").val();
        $('#tags').tagsInput({    
            autocomplete_url:'js/tags/fake_json_endpoint.php',
            defaultText:'quitar muestra',
            removeWithBackspace: true,
            onAddTag: function(){
                $("span.tag span").each(function(){
                    textos += $(this).text()+",";
                })
                window.location.href = "productos_objetivo_detalle.php?ciclo="+ciclo_de+"&fecha="+fecha_hasta+"&territorios="+territorios+"&cuantos="+cuantos+"&desde="+desde+"&tipo="+tipo+"&tags="+textos;
                textos = '';
            },
            onRemoveTag:function(){
                $("span.tag span").each(function(){
                    textos += $(this).text()+",";
                })
                window.location.href = "productos_objetivo_detalle.php?ciclo="+ciclo_de+"&fecha="+fecha_hasta+"&territorios="+territorios+"&cuantos="+cuantos+"&desde="+desde+"&tipo="+tipo+"&tags="+textos;
                textos = '';
            }
        });
        $(".primerostres tbody").tableDnD();  
        $(".primerostres").tablesorter({
            headers: { 
                0: { 
                    sorter: false 
                },
                2: { 
                    sorter: false 
                }
            },
            sortList: [[1,1]] 
        });  
        $("#guardar").click(function(){
            var datos = '', codigos_funcioanrioss = "", relegados ="";
            $(".foreach .primerostres tr").each(function(index){
                var codigo_f = $(this).parent().parent().parent().find('.codigo_funcionario').val()+","
                if($(this).css('background-color') == 'rgb(250, 89, 104)'){
                    $("input.codigo_funcionario, .primerostres tbody input",this).each(function(index){
                        datos += codigo_f + $(this).val() + ",";
                    })
                }
            })

            $("#tags_tagsinput span.tag").each(function(index){
                relegados += $(this).find("span").text()+",";
            })
            $.ajax({
                type: "POST",
                url: "ajax/productos_objetivo/guardar2.php",
                dataType : 'json',
                data: { 
                    datos: datos,
                    relegados: relegados,
                    ciclo: <?php echo $codigo_ciclo; ?>,
                    gestion: <?php echo $codigo_gestion; ?>,
                    fecha: '<?php echo $fecha ?>',
                    cuantos: cuantos,
                    ciudad : '<?php echo $territorios;  ?>',
                    desde:desde
                }
            }).done(function() { 
                alert("Datos Guardados Satisfactoriamente.")
                window.location.href = "productos_objetivo.php";
            });
        })
})
</script>
</head>
<body>
    <div id="container">
        <?php 
        $codigo_gestion2 = $codigo_gestion; 
        require("estilos2.inc"); 
        ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Productos Objetivo Detallado</h3>
            <input type="hidden" id="ciclo_de" value="<?php echo $ciclo ?>" />
            <input type="hidden" id="fecha1" value="<?php echo $fecha ?>" />
            <input type="hidden" id="territorio" value="<?php echo $territorios ?>" />
        </header>
        <section role="main">
            <div class="row">
                <div class="twelve columns">
                    <p>Escriba los nombres de los productos que desea que no figuren en los productos objetivo:</p>
                    <input id='tags' type='text' class='tags' value="<?php echo $tags ?>"></p>
                </div>
            </div>
            <div class="row">
                <?php 
                foreach ($territorios_finales as $valores) { 
                    $productos_guardados_array       = array();
                    $productos_guardados_array_final = '';
                    $cantidad_prodcutos_total_ciudad = 1;
                    $sql_ciudad = mysql_query("SELECT c.descripcion FROM ciudades c WHERE c.cod_ciudad = $valores"); 
                    $nom_ciudad = mysql_result($sql_ciudad, 0, 0); 
                    $sql_canti  = mysql_query("SELECT p.cantidad FROM productos_objetivo_cantidad p WHERE p.cod_ciudad = $valores");
                    $canti      = mysql_result($sql_canti, 0, 0);
                    ?>
                    <div class="twelve columns">
                        <h3 style="text-align: left; color: #5F7BA9"><?php echo $nom_ciudad." <span style='font-size: 12px'>maximo de muestras(".$canti.")</span>"; ?></h3>
                    </div>
                    <?php
                    $funcionarios = mysql_query("SELECT DISTINCT d.cod_visitador FROM distribucion_productos_visitadores d WHERE d.cod_ciclo = $codigo_ciclo AND d.codigo_gestion = $codigo_gestion2 AND d.territorio = $valores");
                    while ($row_funcionarios = mysql_fetch_array($funcionarios)) {
                        $codigo_funcionarios .= $row_funcionarios[0] . ",";
                    }
                    $codigos_funcioarios = substr($codigo_funcionarios, 0, -1);
                    $codigo_funcionario_final = explode(",", $codigos_funcioarios);
                    $veces = 0; 
                    foreach ($codigo_funcionario_final as $val_fun) {
                        ?>
                        <div class="four columns end foreach">
                            <?php
                            $sql_nom_funcionario = mysql_query("SELECT CONCAT(f.nombres,' ',f.paterno,' ',f.materno) FROM funcionarios f WHERE f.codigo_funcionario = $val_fun");
                            $nombre_funcionario = mysql_result($sql_nom_funcionario, 0, 0);
                            ?>
                            <h4 style="font-size: 1em"><?php echo $nombre_funcionario; ?></h4>
                            <input type="hidden" value="<?php echo $val_fun; ?>" class="codigo_funcionario" />
                            <table border="1" style="height: 500px" class="primerostres">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <!-- <th>Cantidad</th> -->
                                        <th>Contactos</th>
                                        <th>*</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql_productos = mysql_query("SELECT a.codigo_producto, CONCAT(b.descripcion,' ',b.presentacion), a.cantidad_planificada, a.cantidad_distribuida FROM distribucion_productos_visitadores a, muestras_medicas b WHERE a.codigo_producto = b.codigo AND a.cod_ciclo = $codigo_ciclo AND a.codigo_gestion = $codigo_gestion2 AND a.territorio = $valores AND a.cod_visitador = $val_fun AND a.codigo_linea = 1021 $cadena ORDER BY 3 DESC LIMIT 0, 20");
                                    $num = mysql_num_rows($sql_productos);
                                    if($num > 0){
                                        while ($row_producto = mysql_fetch_array($sql_productos)) {
                                            if (array_key_exists($row_producto[0], $productos_guardados_array)) {
                                                $productos_guardados_array[$row_producto[0]] = $productos_guardados_array[$row_producto[0]] + 1;
                                            }else{
                                                $productos_guardados_array[$row_producto[0]] = $cantidad_prodcutos_total_ciudad;
                                            }

                                            $sqlMedicos = "SELECT rd.cod_especialidad, rd.categoria_med, count(distinct(rd.cod_med)) from  rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, rutero_maestro_detalle_aprobado rd where rc.cod_rutero=r.cod_rutero and r.cod_contacto=rd.cod_contacto and rc.codigo_ciclo = '$codigo_ciclo' and rc.codigo_gestion = '$codigo_gestion2' and rc.cod_visitador = '$val_fun' group by rd.cod_especialidad, rd.categoria_med";
                                            $respMedicos = mysql_query($sqlMedicos);
                                            $num_contactos=0;
                                            while ($datMedicos = mysql_fetch_array($respMedicos)) {
                                                $codEspe = $datMedicos[0];
                                                $catMed = $datMedicos[1];
                                                $cantMed=$datMedicos[2];
                                                $sqlLineasVisita="SELECT l.codigo_l_visita from lineas_visita l, lineas_visita_especialidad le, lineas_visita_visitadores lv where l.codigo_l_visita=le.codigo_l_visita and l.codigo_l_visita=lv.codigo_l_visita and lv.codigo_funcionario='$val_fun' and le.cod_especialidad='$codEspe'";
                                                $respLineasVisita=mysql_query($sqlLineasVisita);
                                                $numFilasLineasVisita=mysql_num_rows($respLineasVisita);
                                                if($numFilasLineasVisita==0){
                                                    $codLineaVisita=0;
                                                }else{
                                                    $datLineaVisita=mysql_fetch_array($respLineasVisita);
                                                    $codLineaVisita=$datLineaVisita[0];
                                                }   
                                                $sqlParrilla="SELECT count(p.codigo_parrilla) as nro_filas from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.cod_ciclo='$codigo_ciclo' and p.codigo_gestion='$codigo_gestion2' and p.cod_especialidad='$codEspe' and p.categoria_med='$catMed' and pd.codigo_muestra='$row_producto[0]' and p.codigo_l_visita='$codLineaVisita' and p.agencia='$valores'";
                                                echo $sqlParrilla."<br />";
                                                $respParrilla=mysql_query($sqlParrilla);
                                                $datParrilla=mysql_fetch_array($respParrilla);

                                                $nroContactos=$datParrilla[0]*$cantMed;
                                                $num_contactos=$num_contactos+$nroContactos;                      
                                            } 
                                            // if($productos_guardados_array[$row_producto[0]] < $canti){
                                            ?>
                                            <tr>
                                                <td><?php echo $row_producto[1] ?> <input type="hidden" value="<?php echo $row_producto[0] ?>" /> </td>
                                                <!-- <td align="center" style="text-align: center; font-weight: bold"><?php echo $row_producto[2] ?> <input type="hidden" value="<?php echo $row_producto[2] ?>" /></td> -->
                                                <td align="center" style="text-align: center; font-weight: bold"><?php echo $num_contactos; ?> <input type="hidden" value="<?php echo $num_contactos; ?>" /></td>
                                                <td><?php echo $productos_guardados_array[$row_producto[0]]; ?></td>
                                            </tr>
                                            <?php
                                            // }
                                        } 
                                    }else{
                                        ?>
                                        <tr>
                                            <td>0</td>
                                            <td>0</td>
                                            <td>0</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    } 
                    $codigo_funcionarios = ''; 
                } 
                ?>
            </div>
            <div class="row">
                <div class="two columns centered">
                    <input type="button" value="Guardar" id="guardar" />
                </div>
            </div>
            <div class="row" style="margin-top:20px; margin-bottom: 20px;">
                <div class="two columns centered">
                    <a href="productos_objetivo.php">Volver Atr&aacute;s</a>
                </div>
            </div>
        </section>
    </div>
    <div class="modal"></div>
</body>
</html>