<?php  
require_once ('conexion.inc');
error_reporting(0);

$cod_med = $_GET['cod_med'];
$linea   = $_GET['linea'];

$cod_linea = $linea;

$sql_nom_med = mysql_query("SELECT CONCAT(m.ap_pat_med,' ',m.ap_mat_med, ' ', m.nom_med) as nombre from medicos m where m.cod_med = $cod_med ");
$nom_med     = mysql_result($sql_nom_med, 0, 0); 

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
    <title>Quitar Producto M&eacute;dicos</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
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
        $("#eliminar").click(function(event) {
            var cadena='';
            $(".aa .checkbox:checked").each(function(){
                cadena += $(this).val()+",";
            })
            if(cadena == '' || cadena == 'undefined'){
                alert("Seleccione por lo menos un producto")
            }else{
                $.ajax({
                    type: "POST",
                    url: "ajax/adicionar_producto/quitar_producto_quitado.php",
                    dataType : 'json',
                    data: { 
                        cadena : cadena,
                        medico : '<?php echo $cod_med; ?>',
                        linea  : '<?php echo $cod_linea; ?>'
                    }
                }).done(function(data) {
                    alert(data.mensaje)
                    window.location.href = "quitar_producto_medico.php";
                });
            }
        });
    });
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
table.aa tr td {
    padding: 10px !important;
}
table.aa tr th {
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
            <h3>Quitar Productos</h3>
            <h4 style="font-size: 1.1em">M&eacute;dico: <?php echo $nom_med; ?> | L&iacute;nea: <?php echo $nom_linea; ?></h4>
            <h4 style="font-size: 1em">Especialidad: <?php echo $especialidad; ?> | Categor&iacute;a: <?php echo $categoria; ?></h4>
        </header>
        <div id="contenido">
            <div class="row centered">
                <center>
                    <table border="1" class="aa">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Producto</th>
                            <th>Presentaci&oacute;n</th>
                        </tr>
                        <?php  
                        $sql_prod = mysql_query("SELECT m.codigo, m.descripcion, m.presentacion from muestras_negadas n, muestras_medicas m where m.codigo = n.codigo_muestra and n.codigo_linea = $cod_linea and n.cod_med = '$cod_med' order by m.descripcion");
                        $num_prod = mysql_num_rows($sql_prod);
                        if($num_prod == 0){
                            ?>
                            <tr>
                                <td colspan="3">No existen productos quitados</td>
                            </tr>
                            <?php
                        }else{
                            while ( $row_prod = mysql_fetch_array($sql_prod)) {
                                ?>
                                <tr>
                                    <td>
                                        <input type='checkbox' name='codigo' value='<?php echo $row_prod[0]; ?>' class="checkbox">
                                    </td>
                                    <td><?php echo $row_prod[1]; ?></td>
                                    <td><?php echo $row_prod[2]; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </center>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="six columns centered">
                    <a href="quitar_porducto_medico_detalle_registro.php?cod_med=<?php echo $cod_med; ?>&linea=<?php echo $cod_linea; ?>" class="button">Quitar Producto</a>
                    <a href="javascript:void(0)" class="button" id="eliminar">Eliminar Producto de la lista</a>
                </div>
                <div class="six columns centered">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>