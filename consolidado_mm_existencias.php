<?php
error_reporting(0);
require("conexion.inc");
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion de Material De Apoyo</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {

        var ciclo;
        $("#ciclo").change(function(){
            ciclo = $("#ciclo").val();

            $t = $(this).parent().parent().parent().find('#a td.td-overlay');
            $tt =$(this).parent().parent().parent().find('#b');
            $("#overlay").css({
                opacity: 0.5,
                top: (($tt.offset().top) / 3)-5,
                width: $t.outerWidth()-8,
                height: $t.outerHeight()
            });

            $("#img-load").css({
              top:  ($t.height() / 2),
              left: ($t.width() / 2)
          });

            $("#overlay").fadeIn();
            $.getJSON("ajax/select/productos_ama.php",{
                "ciclo" : ciclo
            },responsse);
            return false;
        })
        function responsse(datos){
            $("#productos").html(datos)
            $("#overlay").fadeOut();
        }

        $("#continuar").click(function(){
            var productos = $("#productos").val()
            var ciclo_gestion = $("#ciclo").val();
            var str = "";
            $("#productos option:selected").each(function () {
                str += $(this).text() + ", ";
            });
            var windowSizeArray = [ "scrollbars=yes" ];
            var url = "consolidado_mm_detalle.php?ciclo_gestion="+ciclo_gestion+"&productos="+productos+"&codigos_productos="+str;
            var windowName = "Consolidado_MM";//$(this).attr("name");
            var windowSize = windowSizeArray[0];
            window.open(url, windowName, windowSize);
        })

    });
</script>
<style type="text/css">
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
form, select {
    margin: 10px 0;
}
th {
    padding: 0 10px 
}
#overlay { 
    display:none; 
    position:absolute; 
    background:#fff; 
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
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Consolidado de Muestras Medicas (Existencias)</h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="twelve columns">
                    <center>
                        <table border="1" style="width:70%;position:relative">
                            <tr id="b">
                                <th>Ciclo - Gestion Destino</th>
                                <td>
                                    <?php $sql_ciclo = mysql_query("SELECT distinct(c.ciclo), c.gestion, g.nombre_gestion from asignacion_productos_excel c, gestiones g where c.gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.ciclo desc"); ?>
                                    <select name="ciclo" id="ciclo" style="width:83%">
                                        <option value="0">Seleccione el ciclo-gestion</option>
                                        <?php while($row_ciclo = mysql_fetch_array($sql_ciclo)){ ?>
                                        <option value="<?php echo $row_ciclo[0]."-".$row_ciclo[1] ?>"><?php echo $row_ciclo[0]." ".$row_ciclo[2] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="a">
                                <th>Productos</th>
                                <td class="td-overlay">
                                    <select name="productos" id="productos" multiple size="15" style="float:left;margin-right:10px;width:83%">
                                        <option value="0">Seleccione un Ciclo-Gestion</option>
                                    </select>
                                    <div id="overlay">
                                        <img src="imagenes/ajax-loader-b.gif" alt="" id="img-load" />    
                                    </div>
                                </td>
                            </tr>
                        </table> 
                        <a href="javascript:void(0)" class="button" id="continuar">Continuar</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>