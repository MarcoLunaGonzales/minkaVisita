<style type="text/css">
    .resul  th { color: #000; font-size: 14px}
</style>
<?php
set_time_limit(0);
require("conexion.inc");
require("estilos_reportes_central.inc");

$territorio = $_REQUEST['territorio'];
$ciclo_gestion = $_REQUEST['gestion'];

$ciclo_gestion_aux = explode("|", $ciclo_gestion);
$ciclo = $ciclo_gestion_aux[0];
$gestion = $ciclo_gestion_aux[1];

$sql_nombre_territorio = mysql_query(" Select cod_ciudad,descripcion from ciudades where cod_ciudad in ($territorio) ");

while ($row_ciudad = mysql_fetch_array($sql_nombre_territorio)) {
    $nombre_territorios .= $row_ciudad[1] . ",";
}
$nombre_territorios = substr($nombre_territorios, 0, -1);
?>
<center>
    <table class='textotit'>
        <tr>
            <th>
                Reporte Categorizaci&oacute;n de M&eacute;dicos <br />
                Territorios : <?php echo $nombre_territorios; ?> <br />
            </th>
        </tr>
    </table>
</center>
<?php
$sql_nombre_territorio = mysql_query(" Select cod_ciudad,descripcion from ciudades where cod_ciudad in ($territorio) ");
while ($row_ciudad = mysql_fetch_array($sql_nombre_territorio)) {
    $codigo_territorio = $row_ciudad[0];
    $nombre_territorio = $row_ciudad[1];
    $sql_medico_lista = mysql_query(" SELECT DISTINCT m.cod_med, rd.cod_especialidad, rd.categoria_med, m.categorizacion_medico from rutero_maestro_cab_aprobado rc , rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd,
        medicos m where rc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rd.cod_contacto and rc.codigo_ciclo = $ciclo  and rc.codigo_gestion = $gestion and rc.estado_aprobado = 1 
        and rd.cod_med = m.cod_med and m.cod_ciudad = $codigo_territorio  ");
    ?>
    <center>
        <h2><?php echo $nombre_territorio ?></h2>
    </center>
    <center>
        <table class="resul" border="1" width="95%">
            <tr>
                <th>&nbsp;</th>
                <th>Regional</th>
                <th>Nombre M&eacute;dico</th>
                <th>Especialidad</th>
                <th>Categor&iacute;a Actual Sistema</th>
                <th>Nueva Categor&iacute;a</th>
                <th>Edad</th>
                <th>&numero; Pacientes</th>
                <th>PX marca</th>
                <th>Nivel</th>
                <th>Costo</th>
            </tr>
            <?php
            $count = 1;
            while ($row = mysql_fetch_assoc($sql_medico_lista)) {
                $codigo_medico = $row['cod_med'];
                $categorizacion_medico_ponderacion = $row['categorizacion_medico'];
                if ($categorizacion_medico_ponderacion == '') {
                    $categorizacion_medico_ponderacion = "<span style='color:red; font-weight:bold'>SD</span>";
                }
                $sql_datos_medico = "Select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med = $codigo_medico order by ap_pat_med";
                $resp_sql_datos_medico = mysql_query($sql_datos_medico);
                while ($row_m = mysql_fetch_assoc($resp_sql_datos_medico)) {
                    /* Datos a categorizar */
                    $sql_datos_categorizacion = mysql_query(" SELECT cm.sexo, cm.edad, cm.n_pacientes, cm.tiene_preferencia, cm.nivel, cm.costo from categorizacion_medico cm where
                                                                                                cm.cod_med = $codigo_medico " );
                    if (mysql_num_rows($sql_datos_categorizacion) == 0) {
                        $edad = "&nbsp;";
                        $n_pacientes = $edad = "&nbsp;";
                        $preferencia = $edad = "&nbsp;";
                        $nivel = $edad = "&nbsp;";
                        $costo = $edad = "&nbsp;";
                    } else {
                        while ($row_datos_categorizacion = mysql_fetch_array($sql_datos_categorizacion)) {
                            $edad = $row_datos_categorizacion[1];
                            $n_pacientes = $row_datos_categorizacion[2];
                            $preferencia = $row_datos_categorizacion[3];
                            $nivel = $row_datos_categorizacion[4];
                            $costo = $row_datos_categorizacion[5];
                        }
                    }
                    /* Fin Datos a categorizar */
                    ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $nombre_territorio; ?></td>
                        <td><?php echo $row_m['ap_pat_med'] ?> <?php echo $row_m['ap_mat_med'] ?> <?php echo $row_m['nom_med'] ?></td>
                        <td><?php echo $row['cod_especialidad'] ?></td>
                        <td align="center" style="text-align: center"><?php echo $row['categoria_med'] ?></td>
                        <td align="center" style="text-align: center"><?php echo $categorizacion_medico_ponderacion ?></td>
                        <td align="center" style="text-align: center"><?php 
                        if($edad == '' || $edad == 0){
                            echo "<span style='color:red'>S/E</span>";
                        }
                        if($edad == 1){
                            echo "Menor o igual a 30";
                        }
                        if($edad == 2){
                            echo "Mayor de 31 y menor igual a 50";
                        }
                        if($edad == 3){
                            echo "Mayor de 51 y menor igual a 60";
                        }
                        if($edad == 4){
                            echo "Mayor de 60";
                        }
                        ?></td>
                        <td align="center" style="text-align: center"><?php echo $n_pacientes ?></td>
                        <td align="center" style="text-align: center"><?php echo $preferencia ?></td>
                        <td align="center" style="text-align: center"><?php echo $nivel ?></td>
                        <td align="center" style="text-align: center"><?php echo $costo ?></td>
                    </tr>
                    <?php
                }
                $count++;
            }
            ?>
        </table>
    </center>
    <br />
    <br />
<?php } ?>
<br /><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>