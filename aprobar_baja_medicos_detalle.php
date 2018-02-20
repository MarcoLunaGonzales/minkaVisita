<?php
error_reporting(0);
require("conexion.inc");
$estado = $_GET['estado'];
$territorios = $_GET['territorios'];

$cod_ciudad = array();
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
    <title>Aprobar Baja Medicos</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
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
        $("#aprobar").click(function(){
            var checked = "";
            $('table input[type=checkbox]:checked').each(function(){
                checked +=$(this).val()+"@";
            });
            $.getJSON("ajax/bajas/aprobar.php",{
                "contactos": checked
            },response);

            return false;
        })
        function response(datos){
            alert(datos)
            window.location = "aprobar_bajas_medicos.php"
        }

        $("#rechazar").click(function(){
            var checked = "";
            $('table input[type=checkbox]:checked').each(function(){
                checked +=$(this).val()+"@";
            });
            $.getJSON("ajax/bajas/rechazar.php",{
                "contactos": checked
            },response);

            return false;
        })
        function response2(datos){
            alert(datos)
            // window.location = "aprobar_registro_promotores.php"
        }
    });
	
	function toggleCheckedA(status) {
    $(".checkbox.aprobados").each( function() {
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
</style>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Aprobar Baja Medicos</h3>
            <h3 style="color: #5F7BA9; font-size: 1.0em; font-family: Vernada">Para las regionales: <?php 
            foreach ($regionales_finales as $cod_ciudades => $nom_ciudades) {
                echo $nom_ciudades.", ";
            }
            ?> </h3>
        </header>
        <div class="row">
		
			<div class="row" style="margin-top: 20px">
                        <div class="four columns">
                            <input type="checkbox" onclick="toggleCheckedA(this.checked)"> Seleccionar TODO (Aprobados)
                        </div>
            </div>
            <?php 
            foreach($regionales_finales as $cod_ciudades => $nom_ciudades){ 
                ?>
                <div class="twelve columns centered">
                    <h2 style="color: #5F7BA9; font-size: 1.3em; font-family: Vernada; text-align: left"><?php echo $nom_ciudades; ?></h2>
                    <table border="1">
                        <tr>
                            <th></th>
                            <th>Nombre Funcionario</th>
                            <th>Ciclo</th>
                            <th>Gestion</th>
                            <th>Dia Contacto</th>
                            <th>Nombre Medico</th>
                            <th>Motivo Baja</th>
                            <th></th>
                        </tr>
                        <?php  
                        $count = 1;
						$sqlX="SELECT r.cod_contacto,r.cod_visitador,CONCAT(f.nombres,' ',f.paterno,' ',f.materno) as nom_fun ,r.cod_med, 
						CONCAT(m.nom_med,' ',m.ap_pat_med,' ',m.ap_mat_med) as nom_med, r.orden_visita from rutero_detalle r, 
						funcionarios f, rutero rr, medicos m where f.codigo_funcionario = r.cod_visitador and m.cod_med = r.cod_med and 
						f.cod_ciudad = $cod_ciudades and r.estado = 3 and rr.cod_contacto=r.cod_contacto and rr.codigo_gestion in 
						(select codigo_gestion from gestiones where estado='Activo') order by cod_contacto desc";
						
						$sql_funcionarios = mysql_query($sqlX);
                        while ($row = mysql_fetch_array($sql_funcionarios)) {
                            $sql_datos_cab = mysql_query("SELECT r.cod_ciclo, g.nombre_gestion, CONCAT(r.dia_contacto,' ',r.turno) from rutero r, gestiones g where g.codigo_gestion = r.codigo_gestion and cod_contacto = $row[0]");
                            $sql_motivo = mysql_query("SELECT m.descripcion_motivo from registro_no_visita r, motivos_baja m where r.codigo_motivo = m.codigo_motivo and r.cod_contacto = $row[0]");
							$sql_observacion = mysql_query("select b.observacion from boletas_visita_cabXXX b where b.estado=2 and b.cod_contacto=$row[0]");
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $row[2]."(".$row[0]."-".$row[5].")"; ?></td>
                                <td><?php echo mysql_result($sql_datos_cab, 0, 0); ?></td>
                                <td><?php echo mysql_result($sql_datos_cab, 0, 1); ?></td>
                                <td><?php echo mysql_result($sql_datos_cab, 0, 2); ?></td>
                                <td><?php echo $row[4]; ?></td>
                                <td><?php echo mysql_result($sql_motivo, 0, 0); echo "<span style='font-size:9px;color:red;'>(".mysql_result($sql_observacion,0,0).")</span>"; ?></td>
                                <td><input type="checkbox" value="<?php echo $row[0] ?>|<?php echo $row[5] ?>" class="checkbox aprobados"></td>
                            </tr>
                            <?php  
                        }
                        ?>
                    </table>
                </div>
                <?php
            } 
            ?>
        </div>
        <div class="row centered">
            <center>
                <a href='javascript:void(0)' class='button' id='aprobar'>Aprobar Seleccionados</a>
                <a href='javascript:void(0)' class='button' id='rechazar'>Rechazar Seleccionados</a>
            </center>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>