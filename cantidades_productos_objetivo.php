<?php
error_reporting(0);
require("conexion.inc");
$year = date('Y');
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Cantidades Productos Objetivo</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/calendar.css" type="text/css" />
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="lib/cal.js"></script>
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

        var cadena='';
        $('#enviar').click(function(){

            $("#sacar_info tr").each(function(){
                cadena += $(this).find('.ciudadd').val()+"-"+$(this).find('.cantidades').val()+"@";
            })
            $.ajax({
                type: "POST",
                url: "ajax/productos_objetivo/cantidades.php",
                dataType : 'json',
                data: { 
                    datos: cadena
                }
            }).done(function() { 
                alert("Datos Ingresados Satisfactoriamente.")
                window.location.href = "cantidades_productos_objetivo.php";
            });
        })
        
    });
</script>
<style type="text/css">
#contenido tr th {
    padding: 5px
}
.controls input, .controls select {
    padding: 0
}
input[type="button"] {
    margin: 10px 0;
    cursor: pointer;
    background: #fff;
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
</head>
<body>
    <div id="container">
        <?php require("estilos2.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Cantidades Productos Objetivo x Territorio</h3>
        </header>
        <div id="contenido">
            <center>
                <table border="1" id="sacar_info">
                    <?php  
                    $sql_ciudades = mysql_query("SELECT cod_ciudad,descripcion from ciudades where cod_ciudad <> 115 order by 2 ASC");
                    while($row_ciudades = mysql_fetch_array($sql_ciudades)){
                        ?>
                        <tr>
                            <th>
                                <?php echo $row_ciudades[1]; ?>
                                <input type="hidden" value="<?php echo $row_ciudades[0]; ?>" class="ciudadd">
                            </th>
                            <td>
                                <?php  
                                $sql_veri = mysql_query("SELECT cantidad from productos_objetivo_cantidad where cod_ciudad = $row_ciudades[0]");
                                $num_row  = mysql_num_rows($sql_veri);
                                if($num_row != 0){
                                    $canti = mysql_result($sql_veri, 0, 0);
                                }
                                ?>
                                <select name="cantidades" class="cantidades">
                                    <option value="1" <?php echo $canti==1 ? 'selected="selected"' : ''; ?>>1</option>
                                    <option value="2" <?php echo $canti==2 ? 'selected="selected"' : ''; ?>>2</option>
                                    <option value="3" <?php echo $canti==3 ? 'selected="selected"' : ''; ?>>3</option>
                                    <option value="4" <?php echo $canti==4 ? 'selected="selected"' : ''; ?>>4</option>
                                    <option value="5" <?php echo $canti==5 ? 'selected="selected"' : ''; ?>>5</option>
                                    <option value="6" <?php echo $canti==6 ? 'selected="selected"' : ''; ?>>6</option>
                                    <option value="7" <?php echo $canti==7 ? 'selected="selected"' : ''; ?>>7</option>
                                    <option value="8" <?php echo $canti==8 ? 'selected="selected"' : ''; ?>>8</option>
                                    <option value="9" <?php echo $canti==9 ? 'selected="selected"' : ''; ?>>9</option>
                                    <option value="10" <?php echo $canti==10 ? 'selected="selected"' : ''; ?>>10</option>
                                    <option value="11" <?php echo $canti==11 ? 'selected="selected"' : ''; ?>>11</option>
                                    <option value="12" <?php echo $canti==12 ? 'selected="selected"' : ''; ?>>12</option>
                                    <option value="13" <?php echo $canti==13 ? 'selected="selected"' : ''; ?>>13</option>
                                    <option value="14" <?php echo $canti==14 ? 'selected="selected"' : ''; ?>>14</option>
                                    <option value="15" <?php echo $canti==15 ? 'selected="selected"' : ''; ?>>15</option>
                                </select>
                            </td>
                        </tr>
                        <?php  
                    }
                    ?>
                </table>
                <input type="button" id="enviar" value="Guardar" />
            </center>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>