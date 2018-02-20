<?php
error_reporting(0);
require("conexion.inc");
require("funcion_nombres.php");
$ciclo_gestion = $_GET['ciclo_gestion'];
$lineaF=$_GET['linea'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final = $ciclo_gestion_explode[0];
$gestion_final = $ciclo_gestion_explode[1];

$nombreLinea=nombreLinea($lineaF);

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion De Cantidades MM</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <link type="text/css" href="css/jquery-ui-1.8.9.custom.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/slidepage/jquery.pageslide.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <!--<script type="text/javascript" src="lib/jquery.fixedtable.js"></script>-->
    <script type="text/javascript" src="lib/autocomplete/jquery-ui-1.8.9.custom.min.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var especialidad_ocultar,especialidad_ocultarr;
        var categoria_ocultar,categoria_ocultarr;
        var producto_ocultar,producto_ocultarr;
        // $(".second").pageslide({ direction: "left", modal: true });
        $('#filtro_b').toggle(function(){
            $("#pageslide").removeClass("cerrado");
            $("#pageslide").addClass("abierto");
        },
        function () {
            $("#pageslide").removeClass("abierto");
            $("#pageslide").addClass("cerrado");
        });
        $("#cerrarr").click(function(){
            $("#pageslide").removeClass("abierto");
            $("#pageslide").addClass("cerrado"); 
        })
        $("#bus_espe").autocomplete( { source: "parrilla/filtro/especialidad.php?ciclo_gestion=<?php echo $ciclo_gestion ?>" });
        $("#bus_espe").keypress(function(e){
            if(e.which == 13) {
                var variable = $(this).val();
                $("#inserta_espe").append('<span class="espe">'+variable+'<img class="eliminar_espe" alt="Cerrar" src="imagenes/no.png"> </span>')
                $(this).val("");
            }    
        })
        $(".eliminar_espe").live("click", function(){ 
            $(this).parent().remove();
        });
        var availableTags = ["A","B","C"]; 
        $("#bus_cat").autocomplete( { source: availableTags });
        $("#bus_cat").keypress(function(e){
            if(e.which == 13) {
                var variable = $(this).val();
                $("#inserta_cat").append('<span class="espe">'+variable+'<img class="eliminar_cat" alt="Cerrar" src="imagenes/no.png"> </span>')
                $(this).val("");
            }    
        })
        $(".eliminar_cat").live("click", function(){ 
            $(this).parent().remove();
        });
        $("#bus_prod").autocomplete( { source: "parrilla/filtro/producto.php?ciclo_gestion=<?php echo $ciclo_gestion ?>" });
        $("#bus_prod").keypress(function(e){
            if(e.which == 13) {
                var variable = $(this).val();
                $("#inserta_prod").append('<span class="espe">'+variable+'<img class="eliminar_prod" alt="Cerrar" src="imagenes/no.png"> </span>')
                $(this).val("");
            }    
        })
        $(".eliminar_prod").live("click", function(){ 
            $(this).parent().remove();
        });
        $("#filtrarr").click(function(){

            var cadena_especialidad = '';
            
            $("#table-principal tr th,#table-principal tr td").each(function(){
                $(this).show();
            })

            $("#table-principal tr").each(function(){
                $(this).show();
            })

            if($(".s_q_espe:checked").val() == 1){
                $("#inserta_espe .espe").each(function(){
                    especialidad_ocultar = $(this).text();
                    especialidad_ocultarr = especialidad_ocultar.slice(0,-1)
                        $("#table-principal tr th,#table-principal tr td").each(function(){
                        
						//alert("'"+$(this).attr('especialidad')+"'"+"=="+"'"+especialidad_ocultarr+"'")
                        
						if("'"+$(this).attr('especialidad')+"'" == "'"+especialidad_ocultarr+"'"){
                            $(this).hide();
                        }
                    })
                })
            }else{
                $("#inserta_espe .espe").each(function(){
                    especialidad_solo = $(this).text();
                    especialidad_soloo = especialidad_solo.slice(0,-1)
                    cadena_especialidad += especialidad_soloo+" ";
                    $("#table-principal tr th,#table-principal tr td").each(function(){
                        // alert($(this).attr('especialidad')+"=="+'undefined')
                        if(("'"+$(this).attr('especialidad')+"'" == "'"+especialidad_soloo+"'") || ("'"+$(this).attr('class')+"'" == "'siempre_mostrar'")){
                            $(this).show();
                        }else{
                            $(this).hide();
                        }
                    })
                })
            }

            if($(".s_q_cat:checked").val() == 1){
                $("#inserta_cat .espe").each(function(){
                    categoria_ocultar = $(this).text();
                    categoria_ocultarr = categoria_ocultar.slice(0,-1)
                    $("#table-principal tr th,#table-principal tr td").each(function(){
                        // alert("'"+$(this).attr('especialidad')+"'"+"=="+"'"+especialidad_ocultarr+"'")
                        if("'"+$(this).attr('categoria')+"'" == "'"+categoria_ocultarr+"'"){
                            $(this).hide();
                        }
                    })
                })
            }else{
                $("#inserta_cat .espe").each(function(){
                    categoria_solo = $(this).text();
                    categoria_soloo = categoria_solo.slice(0,-1)
                    cadena_especialidad = cadena_especialidad.slice(0,-1);
                    // alert(cadena_especialidad)
                    $("#table-principal tr th,#table-principal tr td").each(function(){
                        // alert("'"+$(this).attr('especialidad')+"'"+"=="+"'"+especialidad_ocultarr+"'")
                        if( ("'"+$(this).attr('categoria')+"'" == "'"+categoria_soloo+"'" && (cadena_especialidad.indexOf($(this).attr('especialidad')) >= 0 )) || ("'"+$(this).attr('class')+"'" == "'siempre_mostrar'") ){
                            $(this).show();
                        }else{
                            $(this).hide();
                        }
                    })
                })
            }   

            if($(".s_q_prod:checked").val() == 1){ 
                $("#inserta_prod .espe").each(function(){
                    producto_ocultar = $(this).text();
                    producto_ocultarr = producto_ocultar.slice(0,-1)
                    $("#table-principal tr").each(function(){
                        // alert("'"+$(this).attr('especialidad')+"'"+"=="+"'"+especialidad_ocultarr+"'")
                        if("'"+$(this).attr('id')+"'" == "'"+producto_ocultarr+"'"){
                            $(this).hide();
                        }
                    })
                })
            }else{
                $("#inserta_prod .espe").each(function(){
                    producto_ocultar = $(this).text();
                    producto_ocultarr = producto_ocultar.slice(0,-1);

                    $("#table-principal tr").each(function(){
                        // alert("'"+$(this).attr('especialidad')+"'"+"=="+"'"+especialidad_ocultarr+"'")
                        if("'"+$(this).attr('id')+"'" == "'"+producto_ocultarr+"'" || ("'"+$(this).attr('class')+"'" == "'siempre_mostrarr'")){
                            $(this).show();
                        }else{
                            $(this).hide();
                        }
                    })
                })
            }   

        })

        $("#restablecer").click(function(){
            $("#table-principal tr th,#table-principal tr td").each(function(){
                $(this).show();
            }) 

            $("#table-principal tr").each(function(){
                $(this).show();
            })

            $("#inserta_espe").html("");
            $("#inserta_cat").html("");
            $("#inserta_prod").html("");
        })

        $("#cerear").click(function(){
            $("input[type='text']").each(function(){
                $(this).val('0');
            })
        })
        $("#poner1").click(function(){
            $("input[type='text']").each(function(){
                $(this).val('1');
            })  
        })
        $("#poner2").click(function(){
            $("input[type='text']").each(function(){
                $(this).val('2');
            })  
        })

        $("input").live("change",function () {
            $(this).parent().css('background-color','rgba(41,103,126,0.7)');
            $(this).parent().find('img').css('display','inline-block');
            var thiss = $(this);
            var ciclo,gestion,linea,especialidad,categoria,cantidad,producto, lineaMkt;
            linea = $(this).attr('linea');
            especialidad = $(this).attr('especialidad');
            categoria = $(this).attr('categoria');
            producto = $(this).parent().parent().attr('id');
            cantidad = $(this).val();
			lineaMkt=$(this).attr('linea_mkt');
			
            $.ajax({
                type: "POST",
                url: "ajax/parrilla_excel/guardar_asignacion.php",
                dataType : 'json',
                data: { 
                    ciclo: '<?php echo $ciclo_final ?>',
                    gestion: '<?php echo $gestion_final ?>',
                    linea : linea,
                    especialidad : especialidad,
                    categoria : categoria,
                    producto : producto,
                    cantidad: cantidad,
					lineaMkt: lineaMkt
                }
            }).done(function() { 
                thiss.parent().css('background-color','transparent');
                thiss.parent().find('img').css('display','none');
            });
        });

        $(".eliminar_producto").live("click",function(){
            $(this).parent().parent().remove()
            var thiss = $(this);
            var prodcuto = $(this).parent().parent().attr('id')
            $.ajax({
                type: "POST",
                url: "ajax/parrilla_excel/elimina_producto.php",
                dataType : 'json',
                data: { 
                    ciclo: '<?php echo $ciclo_final ?>',
                    gestion: '<?php echo $gestion_final ?>',
                    producto : prodcuto
                }
            }).done(function() { 
                thiss.parent().css('background-color','transparent');
                thiss.parent().find('img').css('display','none');
            });
        })

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
        }));

        $(".guardar_nuevo_producto").live('click',function(){
            var nuevo_producto_codigo = $(this).parent().find('select').val()
            var nuevo_producto_nombre = $(this).parent().find('select option:selected').text();

            $t = $(this).parent().parent();
            $tt = $(this).parent();

            $("#overlay").css({
              opacity: 0.5,
              top: $t.offset().top,
              width: $t.outerWidth()+10,
              height: $t.outerHeight()+20
          });

            $("#img-load").css({
              top:  ($t.height() / 2),
              left: 140
          });

            $("#overlay").fadeIn();

            $(".para_guardar input",$t).each(function(){
                var especialidad,categoria,linea,cantidad;
                especialidad = $(this).attr('especialidad')
                categoria = $(this).attr('categoria')
                linea = $(this).attr('linea')
                cantidad = $(this).val()
                $.ajax({
                    type: "POST",
                    url: "ajax/parrilla_excel/nuevo_producto.php",
                    dataType : 'json',
                    data: { 
                        ciclo: '<?php echo $ciclo_final ?>',
                        gestion: '<?php echo $gestion_final ?>',
                        producto : nuevo_producto_codigo,
                        especialidad :  especialidad,
                        categoria : categoria,
                        linea : '<?php echo $linea ?>',
                        cantidad : cantidad
                    }
                }).done(function() {
                    $t.attr('id',nuevo_producto_codigo)
                    $tt.html(nuevo_producto_nombre+'<img src="imagenes/no.png" alt="Cerrar" class="eliminar_producto">');
                    $("#overlay").fadeOut();
                });
            })
            
        })
        // this "tableDiv" must be the table's class
//         $(".tableDiv").each(function() {      
//             var Id = $(this).get(0).id;
//             var maintbheight = 500;
//             var maintbwidth = 1320;

//             $("#" + Id + " .FixedTables").fixedTable({
//                 width : maintbwidth,
//                 height: maintbheight,
//                 fixedColumns: 1,
//                         // header style
//                         classHeader: "fixedHead",
//                         // footer style        
//                         classFooter: "fixedFoot",
//                         // fixed column on the left        
//                         classColumn: "fixedColumn",
//                         // the width of fixed column on the left      
//                         fixedColumnWidth: 200,
//                         // table's parent div's id           
//                         outerId: Id,
//                         // tds' in content area default background color                     
//                         Contentbackcolor: "#ffffff",
//                         // tds' in content area background color while hover.     
//                         Contenthovercolor: "#99CCFF", 
//                         // tds' in fixed column default background color   
//                         fixedColumnbackcolor:"#187BAF", 
//                         // tds' in fixed column background color while hover. 
//                         fixedColumnhovercolor:"#99CCFF"  
//                     });        
// });
});
</script>
<style type="text/css">
#container {
    position: relative;
}
.row {
    width: 100%
}
table tbody tr td {
    font-weight: bold !important;
    padding: 5px 10px;
    font-size: 12px;
    position: relative;
}
table tbody tr td img {
    position: absolute;
    margin-left: 5px;
    display: none
}
table tbody tr:hover {
    background: #99CCFF;
}
.fixedHead table.columna_estatica,.fixedTable table.columna_estatica {
    margin-right: 10px
}
table thead tr th {
    text-align: center;
    padding: 0 5px;
    font-size: 12px
}
table thead tr th:nth-child(2n) {
    background: #D0D0D0;
}
table td input[type="text"],table td textarea {
    width: 55px !important;
    margin-left: 6px;
    box-shadow: none;
    margin-bottom: 1px;
}
#botones {
    position: absolute;
    top: 60px;
    left: 20px;
}
#botones a {
    float: left;
    margin-right: 5px
}
textarea {
    min-height: 80px !important;
    font-size: 12px
}
input[type='text'] {
    font-size: 12px
}
.box {
    border-bottom: 1px dashed #999;
    padding: 5px 0;
    margin-bottom: 10px
}
.box p {
    color: #fff;
    margin-bottom: 3px;
    font-size: 12px;
}
.abierto {
    display: block !important;
    position: absolute;
    top: 0px;
    right: 0px;
}
.cerrado {
    display: none
}
.espe {
    display:block; 
    float: left;
    margin:2px; 
    padding:0 5px;
    height:25px; 
    line-height:25px;  
    background:#eee;  
    border: 1px dashed #999;
    color:#000;   
    font-size: 12px;            
}
.espe img {
    cursor: pointer;
    margin-left: 10px
}
.caja_apoyo {
    border: 1px solid #999999;
    padding: 2px;
    background: #cfcfcf;
    min-height:125px;
    height:auto!important;
    height:125px;
    width: 100%;
    overflow: hidden;
}  
#pageslide {
    overflow: scroll;
}
.eliminar_producto, .guardar_nuevo_producto {
    display: block;
    position: relative !important;
    cursor: pointer;
    float: right
}
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
#overlay { 
    display:none; 
    position:absolute; 
    background:#000; 
}
#img-load { 
    position:absolute; 
}

</style>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Asignaci&oacute;n de MM Linea  (<?php echo $nombreLinea; ?>)</h3>
        </header>
        <div id="botones">
            <a href="javascript:void(0)" id="filtro_b" class="button">Abrir Filtro</a>
            <!-- <a href="javascript:void(0)" id="cerear" class="button">Cerear</a> -->
            <!-- <a href="javascript:void(0)" id="poner1" class="button">Poner 1</a> -->
        </div>
        <div class="row">
            <div class="twelve columns">
                <div id="tableDiv_Arrays" class="tableDiv">
                    <table id="table-principal" class="FixedTables">
                        <thead>
                            <tr class="siempre_mostrarr">
                                <th class="siempre_mostrar">
                                    Especialidades <br /> MM
                                </th>
                                <?php 
								$txtEspeSql="SELECT DISTINCT CONCAT(especialidad,' ',categoria), especialidad,categoria from 
								asignacion_mm_excel_detalle ad, asignacion_mm_excel a
								where a.id = ad.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final 
								and posicion <= 201 order by especialidad, categoria";
                                $sql_especialidades = mysql_query($txtEspeSql);
                                while ($row_especialidades = mysql_fetch_array($sql_especialidades)) {
                                    ?>
                                    <th especialidad="<?php echo $row_especialidades[1]; ?>" categoria="<?php echo $row_especialidades[2]; ?>"><?php echo $row_especialidades[0]; ?></th>
                                    <?php 
                                } 
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            //$sql_productos = mysql_query("SELECT DISTINCT ad.producto, CONCAT(m.descripcion,' ',m.presentacion) from asignacion_mm_excel_detalle ad, asignacion_mm_excel a, muestras_medicas m where a.id = ad.id and m.codigo = ad.producto and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.linea_mkt= $lineaF  order by 2 limit 0,10");
							$txtSqlProd="SELECT DISTINCT ad.producto, CONCAT(m.descripcion,' ',m.presentacion) 
							from asignacion_mm_excel_detalle ad, asignacion_mm_excel a, muestras_medicas m, asignacion_productos_excel_detalle ap 
							where a.id = ad.id and 
							m.codigo = ad.producto and a.ciclo = $ciclo_final and 
							a.gestion = $gestion_final and ad.linea_mkt= $lineaF and ad.producto=ap.producto and ap.producto=m.codigo 
							and ap.linea_mkt=ad.linea_mkt   order by 2";
							$sql_productos = mysql_query($txtSqlProd);
                            while ($row_productos = mysql_fetch_array($sql_productos)) {
                                ?>
                                <tr id="<?php echo $row_productos[0]; ?>" class="row-style">
                                    <td class="siempre_mostrar"><?php echo $row_productos[1]; ?><img class="eliminar_producto" alt="Cerrar" src="imagenes/no.png"></td>
                                    <?php //for ($contador=1; $contador <= $count_especialidades; $contador++) { ?>
                                    <?php 
										$txt_sqlInp="SELECT DISTINCT especialidad,categoria, ad.linea, ad.cantidad, ad.linea_mkt
										from asignacion_mm_excel_detalle ad, asignacion_mm_excel a 
										where a.id = ad.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final 
										and posicion <= 201 and producto='$row_productos[0]' and ad.linea_mkt='$lineaF' order by especialidad, categoria";
										$sql_input=mysql_query($txt_sqlInp);
										/*$sql_input = mysql_query("SELECT especialidad,categoria,linea,cantidad, 
										linea_mkt from asignacion_mm_excel_detalle ad, asignacion_mm_excel a where a.id = ad.id 
										and a.ciclo = $ciclo_final and a.gestion = $gestion_final and 
										ad.producto = '$row_productos[0]' and ad.linea_mkt = $lineaF order by posicion");*/ 
										
										?>
                                    <?php while($row_input = mysql_fetch_array($sql_input)) { ?>
                                        <?php if($row_input[3] > 1){ ?>
										
                                        <td style="background:yellow !important" background="yellow" 
										especialidad="<?php echo $row_input[0]; ?>" 
										categoria="<?php echo $row_input[1];?>" 
										linea_mkt="<?php echo $row_input[4];?>" >
										<input type="text" value="<?php echo $row_input[3]; ?>" 
										especialidad="<?php echo $row_input[0]; ?>" categoria="<?php echo $row_input[1]; ?>" 
										linea="<?php echo $row_input[2]; ?>" producto="<?php echo $row_productos[0];?>" 
										linea_mkt="<?php echo $row_input[4];?>"/>
										
										<img src="parrilla/ajax-loader.gif" alt=""> </td>
										
                                        <?php }else{ ?>
										
                                        <td especialidad="<?php echo $row_input[0]; ?>" 
										categoria="<?php echo $row_input[1];?>" 
										linea_mkt="<?php echo $row_input[4];?>" >
										<input type="text" value="<?php echo $row_input[3]; ?>" 
										especialidad="<?php echo $row_input[0]; ?>" categoria="<?php echo $row_input[1]; ?>" 
										linea="<?php echo $row_input[2]; ?>" producto="<?php echo $row_productos[0];?>" 
										linea_mkt="<?php echo $row_input[4];?>"/>
										<img src="parrilla/ajax-loader.gif" alt=""></td>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                                <?php 
                                $producto_final = $row_productos[0];
                                ?>
                                <?php
                            }
                            ?>
                        </tbody>           
                    </table>
					
                    <table id="table-clone" class="FixedTables" style="display:none;">
                        <tr id="" class="row-style">
                            <td>
                                <?php  
                                $sql_nuevo_prod = mysql_query("SELECT DISTINCT m.codigo, CONCAT(m.descripcion,' ',m.presentacion) from muestras_medicas m where m.estado = 1 order by 2");
                                ?>
                                <select name="nuevo_prod" id="nuevo_prod">
                                    <?php while($row_nuevo_prod =  mysql_fetch_array($sql_nuevo_prod)){ ?>
                                    <option value="<?php echo $row_nuevo_prod[0] ?>"><?php echo $row_nuevo_prod[1] ?></option>
                                    <?php } ?>
                                </select>
                                <img class="eliminar_producto" alt="Cerrar" src="imagenes/no.png">
                                <img src="imagenes/si.png" alt="" class="guardar_nuevo_producto">
                            </td>
                            <?php //for ($contador=1; $contador <= 201; $contador++) { ?>
                            <?php $sql_input1 = mysql_query("SELECT especialidad,categoria,linea,cantidad from asignacion_mm_excel_detalle ad, asignacion_mm_excel a where a.id = ad.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.producto = '$producto_final' and ad.linea_mkt = '$lineaF' "); ?>
                            <?php while($row_input1 = mysql_fetch_array($sql_input1)) { ?>
                            <td especialidad="<?php echo $row_input1[0]; ?>" categoria="<?php echo $row_input1[1]; ?>" class="para_guardar"><input type="text" value="1" especialidad="<?php echo $row_input1[0]; ?>" categoria="<?php echo $row_input1[1]; ?>" linea="<?php echo $row_input1[2]; ?>" producto="" /> <img src="parrilla/ajax-loader.gif" alt=""> </td>
                            <?php } ?>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="columns right">
                <div id="add-row"></div>
            </div>
        </div>
        <div id="pageslide" class="cerrado">
            <div class="box">
                <p>Especialidad</p>
                <input type="text" id="bus_espe" />
                <div class="caja_apoyo" id="inserta_espe"></div>
                <input type="radio" name="s_q_espe" class="s_q_espe" value="0"> Filtrar
                <input type="radio" name="s_q_espe" class="s_q_espe" value="1"> Excluir
            </div>
            <div class="box">
                <p>Categoria</p>
                <input type="text" id="bus_cat" />
                <div class="caja_apoyo" id="inserta_cat"></div>
                <input type="radio" name="s_q_cat" class="s_q_cat" value="0"> Filtrar
                <input type="radio" name="s_q_cat" class="s_q_cat" value="1"> Excluir
            </div>
            <div class="box">
                <p>Producto</p>
                <input type="text" id="bus_prod" />
                <div class="caja_apoyo" id="inserta_prod"></div>
                <input type="radio" name="s_q_prod" class="s_q_prod" value="0"> Filtrar
                <input type="radio" name="s_q_prod" class="s_q_prod" value="1"> Excluir
            </div>
            <a href="javascript:void(0)" id="filtrarr">Filtrar</a> | <a href="javascript:void(0)" id="restablecer">Restablecer</a> | <a href="javascript:void(0)" id="cerear">Todo 0</a> | <a href="javascript:void(0)" id="poner1">Todo 1</a> | <a href="javascript:void(0)" id="poner2">Todo 2</a> | <a href="javascript:void(0)" id="cerrarr">Cerrar</a>
        </div>
    </div>
    <div id="overlay">
        <img src="imagenes/ajax-loader-b.gif" id="img-load" />
    </div>

    <!--<script src="js/slidepage/jquery.pageslide.js"></script>
    <script>
        $(".second").pageslide({ direction: "left"});
    </script>-->
</body>
</html>