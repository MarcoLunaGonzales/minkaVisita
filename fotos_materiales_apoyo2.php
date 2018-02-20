<?php  
require_once ('conexion.inc');
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
?>
<!DOCTYPE HTML>
<html lang="es-US">
<head>
    <meta charset="utf-8">
    <title>Cargado Fotos Materiales Apoyo</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="css/tables.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/fancybox/jquery.fancybox.css?v=2.1.3" type="text/css" media="screen" />
    <link rel="stylesheet" href="js/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="js/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" language="javascript" src="lib/listado_materiales_baco.js"></script>
    <script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
    <script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js?v=2.1.3"></script>
    <script type="text/javascript" src="js/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="js/fancybox/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

    <script type="text/javascript" src="js/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <script type="text/javascript" language="javascript" src="lib/jquery.dataTables.js"></script>
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
    </style>
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
        $(".fancybox").fancybox();
        $(".various").fancybox({
            fitToView   : true,
            width       : '90%',
            height      : '70%',
            autoSize    : false,
            closeClick  : true,
            openEffect  : 'none',
            closeEffect : 'none',
            modal       : false,
            'afterClose': function () {
             window.location.reload();
                // alert($(this).attr('class'))
                
            },
            afterLoad: load
        });
        function load(parameters) {
            $('.fancybox-outer').after('<a href="javascript:;" onclick="javascript:{parent.$.fancybox.close();}" class="fancybox-item fancybox-close" title="Close"></a>');
        }
    })
</script>
</head>
<body>
    <div id="container">
        <header id="titulo">
            <h3>Cargado Fotos Materiales Apoyo</h3>
        </header>
        <div class="row">
            <div class="ten columns centered">
                <form name="frmSearch" method="get" action="<?php $_SERVER['SCRIPT_NAME'];?>">
                    <table width="900" border="1">
                        <tr>
                            <th>
                                Palabra Clave
                                <input name="txtKeyword" type="text" id="txtKeyword" value="<?php $_GET["txtKeyword"];?>" style="box-shadow: none; width: 50%; border-radius : 0; text-align:center; margin: 10px auto; height:30px">
                                <input type="submit" value="Buscar">
                            </th>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <?php 
        if($_GET["txtKeyword"] != "") {
            $strSQL = "SELECT a.codigo_material, a.descripcion_material,a.url_imagen,a.codigo_linea, b.nombre_linea from material_apoyo a , lineas b where a.estado = 'Activo' and a.codigo_material <> 0 and a.codigo_linea = b.codigo_linea and (a.descripcion_material LIKE '%".$_GET["txtKeyword"]."%')  ";
            $objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
            $Num_Rows = mysql_num_rows($objQuery);
            $Per_Page = 15;   

            $Page = $_GET["Page"];
            if(!$_GET["Page"]) {
                $Page=1;
            }

            $Prev_Page = $Page-1;
            $Next_Page = $Page+1;

            $Page_Start = (($Per_Page*$Page)-$Per_Page);

            if($Num_Rows<=$Per_Page) {
                $Num_Pages =1;
            } else if(($Num_Rows % $Per_Page)==0) {
                $Num_Pages =($Num_Rows/$Per_Page) ;
            } else {
                $Num_Pages =($Num_Rows/$Per_Page)+1;
                $Num_Pages = (int)$Num_Pages;
            }


            $strSQL .=" ORDER BY descripcion_material ASC LIMIT $Page_Start , $Per_Page";
            $objQuery  = mysql_query($strSQL);

            ?>
            <div class="row">
                <div class="ten columns centered">
                    <table width="900" border="1">
                        <tr>
                            <th>Código Material</th>
                            <th>Nombre Material</th>
                            <th>Codigo Linea</th>
                            <th>Stock</th>
                            <th>Imagen</th>
                        </tr>
                        <?php 
                        while($objResult = mysql_fetch_array($objQuery)) {
                            ?>
                            <tr>
                                <td><a href="carga_imagen_ma.php?codigo=<?php echo $objResult["codigo_material"] ?>" data-fancybox-type="iframe" class="various"><?php echo $objResult["codigo_material"]; ?></a></td>
                                <td><a href="carga_imagen_ma.php?codigo=<?php echo $objResult["codigo_material"] ?>" data-fancybox-type="iframe" class="various"><?php echo $objResult["descripcion_material"] ?></a></td>
                                <td><a href="carga_imagen_ma.php?codigo=<?php echo $objResult["codigo_material"] ?>" data-fancybox-type="iframe" class="various"><?php echo $objResult["nombre_linea"] ?></a></td>
                                <td><a href="carga_imagen_ma.php?codigo=<?php echo $objResult["codigo_material"] ?>" data-fancybox-type="iframe" class="various"><?php echo "1"; ?></a></td>
                                <td align="right">
                                    <?php if ($objResult["url_imagen"] == ''){ ?>
                                    <a href="carga_imagen_ma.php?codigo=<?php echo $objResult["codigo_material"] ?>" data-fancybox-type="iframe" class="various"><img src="lib/assets/img/prohibido-queda-1.png" alt="No hay Imagen" style="width: 120px" /></a>
                                    <?php } else{ ?>
                                    <a class="fancybox" href="lib/assets/uploads/<?php echo $objResult["url_imagen"] ?>"><img src="lib/assets/uploads/<?php echo $objResult["url_imagen"] ?> " alt="<?php echo $objResult["url_imagen"] ?>" style="width: 120px" /></a> 
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <br>
                    Total <?php echo $Num_Rows; ?> Productos : <?php echo $Num_Pages; ?> Página :
                    <?php
                    if($Prev_Page) {
                        echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page&txtKeyword=$_GET[txtKeyword]'><< Atrás</a> ";
                    }

                    for($i=1; $i<=$Num_Pages; $i++){
                        if($i != $Page) {
                            echo "[ <a href='$_SERVER[SCRIPT_NAME]?Page=$i&txtKeyword=$_GET[txtKeyword]'>$i</a> ]";
                        } else {
                            echo "<b> $i </b>";
                        }
                    }
                    if($Page!=$Num_Pages) {
                        echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page&txtKeyword=$_GET[txtKeyword]'>Siguiente>></a> ";
                    }

                }   
                ?>
            </div>
        </div>
        <div class="modal"></div>
    </div>
</body>
</html>