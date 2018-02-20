<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript" src="lib/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#myTable").tablesorter();
    });
</script>
<style>
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
    th.header {
        background-image: url(imagenes/bg.gif);
        cursor: pointer;
        font-weight: bold;
        background-repeat: no-repeat;
        background-position: center right;
        padding-right: 15px;
        border-right: 1px solid #dad9c7;
        margin-left: 1px;
    }
    table td { font-size: 12px }
</style>
<?php
require("conexion.inc");
require("estilos_visitador.inc");
?>
<center style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Registro de Categorizaci&oacute;n<br /> de M&eacute;dicos</center>
<table align='center'><tr><td><a href='ingreso_lineas_visitador_detalle.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>
<table width="99%" border="1" cellpadding="10" id="myTable" class="tablesorter">
    <thead>
        <tr>
            <th scope="col">Nro.Cont.</th>
            <th scope="col">M&eacute;dico</th>
            <th scope="col">Especialidad</th>
            <th scope="col">Categor&iacute;a</th>
            <th scope="col">Direcci&oacute;n</th>
            <th scope="col">Formulario</th>
            <th scope="col">Estado</th>
        </tr>
    </thead>
    <?php
//    $sql_medico_visitador = "Select DISTINCT  a.cod_med, a.cod_especialidad, a.categoria_med  from rutero_maestro_detalle a , rutero_maestro_cab_aprobado b where a.cod_visitador = $global_visitador and a.cod_visitador = b.cod_visitador and b.codigo_ciclo = $ciclo_global and b.estado_aprobado = 1  order by a.cod_med";
    $sql_gestion = mysql_query("Select max(codigo_gestion) as gestion from rutero_maestro_cab_aprobado");
    while($row_gestion = mysql_fetch_assoc($sql_gestion)){
        $codGestion = $row_gestion['gestion'];
    }
    
    $sql_medico_visitador = "select DISTINCT(rd.`cod_med`), rd.`cod_especialidad`, rd.`categoria_med` from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm, `rutero_maestro_detalle_aprobado` rd 
                                                    where
                                                    rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and 
                                                    rc.`codigo_ciclo`=6 and rc.`codigo_gestion`=$codGestion and 
                                                    rc.`cod_visitador`=$global_visitador and
                                                    rc.estado_aprobado = 1 order by rd.cod_med;
                                                    ";
	echo $sql_medico_visitador;
    $resp_sql_medico_visitador = mysql_query($sql_medico_visitador);
    $count = 1;
    while ($row = mysql_fetch_assoc($resp_sql_medico_visitador)) {
        $codigo_medico = $row['cod_med'];
        $sql_datos_medico = "Select a.ap_pat_med, a.ap_mat_med, a.nom_med, b.direccion from medicos a, direcciones_medicos b where a.cod_med = $codigo_medico and a.cod_med = b.cod_med order by a.ap_pat_med";
//		echo $sql_datos_medico."<br />";
        $resp_sql_datos_medico = mysql_query($sql_datos_medico);
        while ($row_m = mysql_fetch_assoc($resp_sql_datos_medico)) {
            ?>
            <tr>
                <td style="text-align: center"><?php echo $count ?></td>
                <td><?php echo $row_m['ap_pat_med'] ?> <?php echo $row_m['ap_mat_med'] ?> <?php echo $row_m['nom_med'] ?></td>
                <td style="text-align: center"><?php echo $row['cod_especialidad'] ?></td>
                <td style="text-align: center"><?php echo $row['categoria_med'] ?></td>
                <td><?php echo $row_m['direccion'] ?> </td>
                <td style="text-align: center"><a href="formulario_medico.php?cod_medico=<?php echo $codigo_medico ?>">Ir al formulario del m&eacute;dico</a></td>
                <?php $query_llenado  = mysql_query("select cod_med from categorizacion_medico where cod_med = $codigo_medico"); ?>
                <?php $num_query_llenado  = mysql_num_rows($query_llenado); ?>
                <td style="text-align: center"><?php if ($num_query_llenado == 1): echo "Formulario Llenado";
                                                                    else: echo "<a href='formulario_medico.php?cod_medico=$codigo_medico' style='color:red; font-weight:bold'>Formulario no llenado</a>";
                                                                    endif; ?></td>
            </tr>
            <?php
        }
        $count++;
    }
    ?>
</table>
