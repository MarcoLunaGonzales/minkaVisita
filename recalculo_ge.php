<?php
error_reporting(0);
require("conexion.inc");
$year = date('Y');
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#enviar").click(function(){
                    var ciclo_de,territorios, lineas;
                    ciclo_de = $("#ciclo_de").val();
                    territorios = $("#territorio").val();
					lineas = $("#lineas").val();
//                    alert(cuantos);
                    window.location.href = "recalculo_ge_detalle.php?ciclo="+ciclo_de+"&territorios="+territorios+"&lineas="+lineas;
                })        
            });
        </script>
    </head>
    <body>
            <?php require("estilos_regional_pri.inc"); ?>
                
				<h1>Recalculo de Grupos Especiales</h1>

                <center>
                    <h2>Seleccione el ciclo y territorio(s) para hacer el c&aacute;lculo en dicho ciclo y territorio(s).</h2>
                    
					<table class="texto">
                        <tr>
                            <th>Ciclo</th>
							<th>Territorio</th>
						</tr>
						<tr>
                            <td align="center">
                                <select name="ciclo_de" id="ciclo_de">
                                    <?php
                                    $sql_gestion = mysql_query("select distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,11");
                                    while ($dat = mysql_fetch_array($sql_gestion)) {
                                        $codCiclo = $dat[0];
                                        $codGestion = $dat[1];
                                        $nombreGestion = $dat[2];
                                        ?>
                                        <option value="<?php echo $codCiclo . "|" . $codGestion ?>"><?php echo $codCiclo . " " . $nombreGestion ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td align="center">
                                <select name="territorio" id="territorio" multiple size="15">
                                    <?php
                                    if($global_usuario==1052 || $global_usuario==1179 || $global_usuario==1011){
										$sql_territorio = mysql_query("select c.cod_ciudad, c.descripcion from ciudades c order by c.descripcion");
									}else{
										$sqlCiudad="select cod_ciudad from funcionarios where codigo_funcionario=$global_usuario";
										$respCiudad=mysql_query($sqlCiudad);
										$codCiudadX=mysql_result($respCiudad, 0,0);
										$sql_territorio = mysql_query("select c.cod_ciudad, c.descripcion from ciudades c where cod_ciudad=$codCiudadX order by c.descripcion");
									}
									
                                    while ($dat_t = mysql_fetch_array($sql_territorio)) {
                                        $codigo_ciudad = $dat_t[0];
                                        $nombre_ciudad = $dat_t[1];
                                        ?>
                                        <option value="<?php echo $codigo_ciudad ?>"> <?php echo $nombre_ciudad ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
					<div class="divBotones">
						<input type="button" id="enviar" value="Ver" class="boton" />
					</div>
                </center>
    </body>
</html>