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
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <!--link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
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

</head>
<body>
        <?php 
		require("estilos_regional_pri.inc"); 		
		?>
        <h1>
            Aprobar Baja Medicos
            Para las regionales: <?php 
            foreach ($regionales_finales as $cod_ciudades => $nom_ciudades) {
                echo $nom_ciudades.", ";
            }
            ?> 
		</h1>
        
		
		<input type="checkbox" onclick="toggleCheckedA(this.checked)"> Seleccionar TODO (Aprobados)
        
		<?php 
            foreach($regionales_finales as $cod_ciudades => $nom_ciudades){ 
                ?>
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
                            <th></th>
                        </tr>
                        <?php  
                        $count = 1;
						
						$sqlX="SELECT rm.cod_contacto,rc.cod_visitador,CONCAT(f.nombres,' ',f.paterno,' ',f.materno) as nom_fun,
						rd.cod_med, CONCAT(m.nom_med,' ',m.ap_pat_med,' ',m.ap_mat_med) as nom_med, rd.orden_visita, rd.estado
						from rutero_maestro_detalle_aprobado rd, funcionarios f, rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, medicos m 
						where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.cod_visitador=rd.cod_visitador and rm.cod_visitador=rd.cod_visitador
						and f.codigo_funcionario = rc.cod_visitador and 
						m.cod_med = rd.cod_med and f.cod_ciudad = $cod_ciudades and rd.estado = 3 and rc.codigo_gestion in 
						(select codigo_gestion from gestiones where estado='Activo') order by cod_contacto desc";
						
						$sql_funcionarios = mysql_query($sqlX);
                        while ($row = mysql_fetch_array($sql_funcionarios)) {
                            
							$sqlTxtCab="SELECT rc.codigo_ciclo, g.nombre_gestion, CONCAT(rm.dia_contacto,' ',rm.turno) 
							from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, 
							gestiones g where rc.cod_rutero=rm.cod_rutero and rc.cod_visitador=rm.cod_visitador and 
							g.codigo_gestion = rc.codigo_gestion and cod_contacto = $row[0]";
							
							//echo $sqlTxtCab;
							$sql_datos_cab = mysql_query($sqlTxtCab);
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
                    </table></center>
                <?php
            } 
            ?>
		
        <div class="divBotones">
                <input type="button" onclick='javascript:void(0)' class='boton' id='aprobar' value="Aprobar Seleccionados">
                <input type="button" onclick='javascript:void(0)' class='boton2' id='rechazar' value="Rechazar Seleccionados">
        </div>
</body>
</html>