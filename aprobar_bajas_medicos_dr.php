<?php
error_reporting(0);
require("conexion.inc");
?>
<html lang="en-US">
    <meta charset="iso-8859-1">
    <title>Aprobar Bajas</title>
    <!--link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css"-->
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#continuar").click(function(){
            var url,territorios;
            url = $("#continuar").attr('rel');
            territorios = $("#territorio").val();
            window.location.href = url+"?&territorios="+territorios;
        })  
    });
    </script>
<body>
        <?php require("estilos_gerencia.inc"); ?>
        <h1>
            Aprobar Bajas
        </h1>
		
                    <center>
                        <table class="texto">
                            <tr>
                                <th>Territorio</th>
                                <td>
                                    <?php $sql_territorio = mysql_query("SELECT c.cod_ciudad, c.descripcion from ciudades c where c.cod_ciudad <> 115 
									order by c.descripcion") ?>
                                    <select name="territorio" id="territorio" multiple size="12">
                                        <?php while ($row_territorio = mysql_fetch_array($sql_territorio)) { ?>
                                        <option value="<?php echo $row_territorio[0]; ?>"><?php echo $row_territorio[1]; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
						<input type="button" onclick="javascript:void(0)" rel="aprobar_baja_medicos_detalle_dr.php" class="boton" id="continuar" value="Continuar">
                        <!--a href="javascript:void(0)" rel="aprobar_baja_medicos_detalle_dr.php" class="button" id="continuar">Continuar</a-->
                    </center>
</body>
</html>