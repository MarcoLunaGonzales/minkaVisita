<?php
error_reporting(0);
require("conexion.inc");
$estado      = $_GET['estado'];
$territorios = $_GET['territorios'];

$cod_ciudad   = array();
$nom_ciudades = array();

$sql_ciudades = mysql_query("SELECT cod_ciudad, descripcion from ciudades where cod_ciudad in ($territorios)");
while ($row_ciudades = mysql_fetch_array($sql_ciudades)) {
    $cod_ciudades[] = $row_ciudades[0];
    $nom_ciudades[] = $row_ciudades[1];
}
$regionales_finales = array_combine($cod_ciudades, $nom_ciudades);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">

    <!--link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css"-->
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
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
            $(".tab_content").hide();
            $("ul.tabs li:first").addClass("active").show();
            $(".tab_content:first").show();

            $("ul.tabs li").click(function() {
                $("ul.tabs li").removeClass("active");
                $(this).addClass("active");
                $(".tab_content").hide();

                var activeTab = $(this).find("a").attr("href");
                $(activeTab).fadeIn();
                return false;
            });
            $("#aprobar").click(function(){
                var checked = "";
                $('table input[type=checkbox]:checked').each(function(){
                    checked +=$(this).val()+"@";
                });
                /*$.getJSON("ajax/bajas/aprobar_dr.php",{
                    "contactos": checked
                },response);*/
				$.ajax({
					type: "POST",
					url: "ajax/bajas/aprobar_dr.php",
					dataType : 'json',
					data: { 
						contactos: checked					}
				}).done(function(data) {
					response(data);
				});
				
                return false;
            })
            function response(datos){
                alert(datos)
                window.location = "aprobar_bajas_medicos_dr.php"
            }

            $("#rechazar").click(function(){
                var checked = "";
                $('table input[type=checkbox]:checked').each(function(){
                    checked +=$(this).val()+"@";
                });
                $.getJSON("ajax/bajas/rechazar_dr.php",{
                    "contactos": checked
                },response);

                return false;
            })
            function response2(datos){
            // alert(datos)
            window.location = "aprobar_registro_promotores.php"
        }


    });
function toggleCheckedA(status) {
    $(".checkbox.aprobados").each( function() {
        $(this).attr("checked",status);
    })
}
function toggleCheckedD(status) {
    $(".checkbox.rechazados").each( function() {
        $(this).attr("checked",status);
    })
}
</script>
<style type="text/css">
    table tr th {
        padding: 5px 10px    
    } 
    table tbody tr td {
        padding: 5px 10px !important   
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
    ul.tabs {
        margin: 10px 0 0 0;
        padding: 0;
        float: left;
        list-style: none;
        height: 32px;
        border-bottom: 1px solid #999;
        border-left: 1px solid #999;
        width: 100%;
    }
    ul.tabs li {
        float: left;
        margin: 0;
        padding: 0;
        height: 31px;
        line-height: 31px;
        border: 1px solid #999;
        border-left: none;
        margin-bottom: -1px;
        overflow: hidden;
        position: relative;
        background: #e0e0e0;
    }
    ul.tabs li a {
        text-decoration: none;
        color: #000;
        display: block;
        font-size: 1.2em;
        padding: 0 20px;
        border: 1px solid #fff;
        outline: none;
    }
    ul.tabs li a:hover {
        background: #ccc;
    }
    ul.tabs li.active, ul.tabs li.active a:hover  {
        background: #fff;
        border-bottom: 1px solid #fff;
    }
    .tab_container {
        border: 1px solid #999;
        border-top: none;
        overflow: hidden;
        clear: both;
        float: left; width: 100%;
        background: #fff;
        margin-bottom: 10px
    }
    .tab_content {
    }
    th.headerSortUp {
        background-image: url(imagenes/descd.gif) !important;
        background-position: center right;
        background-color: #3399FF;
    }
    th.headerSortDown {
        background-image: url(imagenes/desc.gif)  !important;
        background-position: center right;
        background-color: #3399FF;
    }
</style>
</head>
<body>
    <div id="container">
        <?php require("estilos_gerencia.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h1>Aprobar Baja Medicos</h1>
            <h2>Para las regionales: <?php 
                foreach ($regionales_finales as $cod_ciudades => $nom_ciudades) {
                    echo $nom_ciudades.", ";
                }
                ?> 
			</h2>
        </header>
        
		<ul class="tabs">
                <li><a href="#tab1">Aprobados</a></li>
                <li><a href="#tab2">Rechazados</a></li>
            </ul>
            <div class="tab_container">
                <div id="tab1" class="tab_content">
                    <div class="row" style="margin-top: 20px">
                        <div class="four columns">
                            <input type="checkbox" onclick="toggleCheckedA(this.checked)"> Seleccionar TODO (Aprobados)
                        </div>
                    </div>
                    <div class="row">
                        <?php 
                        foreach($regionales_finales as $cod_ciudades => $nom_ciudades){ 
                            ?>
                            <div class="twelve columns centered">
                                <h2 style="color: #5F7BA9; font-size: 1.3em; font-family: Vernada; text-align: left"><?php echo $nom_ciudades; ?></h2>
                                <center><table class="texto">
                                    <tr>
                                        <th></th>
                                        <th>Nombre Funcionario</th>
                                        <th>Ciclo</th>
                                        <th>Gestion</th>
                                        <th>Dia Contacto</th>
                                        <th>Nombre Medico</th>
                                        <th>Motivo Baja</th>
                                        <th>Estado Supervisor</th>
                                        <th></th>
                                    </tr>
                                    <?php  
                                    $count = 1;
                                    
									$sqlFuncX="SELECT r.cod_contacto,r.cod_visitador,CONCAT(f.nombres,' ',f.paterno,' ',f.materno) as nom_fun ,r.cod_med, 
									CONCAT(m.nom_med,' ',m.ap_pat_med,' ',m.ap_mat_med) as nom_med, r.orden_visita, r.estado from 
									rutero_detalle r, funcionarios f, medicos m where f.codigo_funcionario = r.cod_visitador and m.cod_med = r.cod_med 
									and f.cod_ciudad = $cod_ciudades and r.estado in (4) order by r.cod_contacto desc";
									
									$sql_funcionarios = mysql_query($sqlFuncX);
                                    while ($row = mysql_fetch_array($sql_funcionarios)) {
                                        $sql_datos_cab = mysql_query("SELECT r.cod_ciclo, g.nombre_gestion, CONCAT(r.dia_contacto,' ',r.turno) from rutero r, gestiones g where g.codigo_gestion = r.codigo_gestion and cod_contacto = $row[0]");
                                        $sql_motivo = mysql_query("SELECT m.descripcion_motivo from registro_no_visita r, motivos_baja m where r.codigo_motivo = m.codigo_motivo and r.cod_contacto = $row[0]");
                                        ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td><?php echo $row[2]; ?></td>
                                            <td><?php echo mysql_result($sql_datos_cab, 0, 0); ?></td>
                                            <td><?php echo mysql_result($sql_datos_cab, 0, 1); ?></td>
                                            <td><?php echo mysql_result($sql_datos_cab, 0, 2); ?></td>
                                            <td><?php echo $row[4]; ?></td>
                                            <td><?php echo mysql_result($sql_motivo, 0, 0); ?></td>
                                            <td>
                                                <?php 
                                                if($row[6] == 4){
                                                    echo "Aprobado por Supervisor";
                                                }else{
                                                    echo "Rechazado por Supervisor";
                                                } 
                                                ?>
                                            </td>
                                            <td><input type="checkbox" value="<?php echo $row[0] ?>|<?php echo $row[5] ?>" class="checkbox aprobados"></td>
                                        </tr>
                                        <?php  
                                    }
                                    ?>
                                </table></center>
                            </div>
                            <?php
                        } 
                        ?>
                    </div>
                </div>
                <div id="tab2" class="tab_content">
                    <div class="row" style="margin-top: 20px">
                        <div class="four columns">
                        <input type="checkbox" onclick="toggleCheckedD(this.checked)"> Seleccionar TODO (Rechazados)
                        </div>
                    </div>
                    <div class="row">
                        <?php 
                        foreach($regionales_finales as $cod_ciudades => $nom_ciudades){ 
                            ?>
                            <div class="twelve columns centered">
                                <h2 style="color: #5F7BA9; font-size: 1.3em; font-family: Vernada; text-align: left"><?php echo $nom_ciudades; ?></h2>
                                <center><table class="texto">
                                    <tr>
                                        <th></th>
                                        <th>Nombre Funcionario</th>
                                        <th>Ciclo</th>
                                        <th>Gestion</th>
                                        <th>Dia Contacto</th>
                                        <th>Nombre Medico</th>
                                        <th>Motivo Baja</th>
                                        <th>Estado Supervisor</th>
                                        <th></th>
                                    </tr>
                                    <?php  
                                    $count = 1;
                                    $sql_funcionarios = mysql_query("SELECT r.cod_contacto,r.cod_visitador,CONCAT(f.nombres,' ',f.paterno,' ',f.materno) as nom_fun ,r.cod_med, CONCAT(m.nom_med,' ',m.ap_pat_med,' ',m.ap_mat_med) as nom_med, r.orden_visita, r.estado from rutero_detalle r, funcionarios f, medicos m where f.codigo_funcionario = r.cod_visitador and m.cod_med = r.cod_med and f.cod_ciudad = $cod_ciudades and r.estado in (5)");
                                    while ($row = mysql_fetch_array($sql_funcionarios)) {
                                        $sql_datos_cab = mysql_query("SELECT r.cod_ciclo, g.nombre_gestion, CONCAT(r.dia_contacto,' ',r.turno) from rutero r, gestiones g where g.codigo_gestion = r.codigo_gestion and cod_contacto = $row[0]");
                                        $sql_motivo = mysql_query("SELECT m.descripcion_motivo from registro_no_visita r, motivos_baja m where r.codigo_motivo = m.codigo_motivo and r.cod_contacto = $row[0]");
                                        ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td><?php echo $row[2]; ?></td>
                                            <td><?php echo mysql_result($sql_datos_cab, 0, 0); ?></td>
                                            <td><?php echo mysql_result($sql_datos_cab, 0, 1); ?></td>
                                            <td><?php echo mysql_result($sql_datos_cab, 0, 2); ?></td>
                                            <td><?php echo $row[4]; ?></td>
                                            <td><?php echo mysql_result($sql_motivo, 0, 0); ?></td>
                                            <td>
                                                <?php 
                                                if($row[6] == 4){
                                                    echo "Aprobado por Supervisor";
                                                }else{
                                                    echo "Rechazado por Supervisor";
                                                } 
                                                ?>
                                            </td>
                                            <td><input type="checkbox" value="<?php echo $row[0] ?>|<?php echo $row[5] ?>" class="checkbox rechazados"></td>
                                        </tr>
                                        <?php  
                                    }
                                    ?>
                                </table></center>
                            </div>
                            <?php
                        } 
                        ?>
                    </div>
                </div>

            </div>
            
            <div class="divBotones">
                    <input type="button" onclick='javascript:void(0)' class='boton' id='aprobar' value="Aprobar">
                    <input type="button" onclick='javascript:void(0)' class='boton2' id='rechazar' value="Rechazar">
            </div>
        </div>
        <div class="modal"></div>
    </body>
    </html>