<?php
error_reporting(0);
require("conexion.inc");
require("estilos_administracion.inc");
//if ($global_agencia == ''){
//    $aux = mysql_query("select cod_ciudad from funcionarios where codigo_funcionario = $global_usuario ");
//    while($row_c = mysql_fetch_assoc($aux)){
//        $global_agencia  = $row_c['cod_ciudad'];
//    }
//}else{
//    $global_agencia = $global_agencia;
//}
?>
<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript" src="lib/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="lib/jquery.tablesorter.pager.js"></script>
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
        $(".tab_content").hide();
        $("ul.tabs li:first").addClass("active").show();
        $(".tab_content:first").show();

        $("ul.tabs li").click(function()
        {
            $("ul.tabs li").removeClass("active");
            $(this).addClass("active");
            $(".tab_content").hide();

            var activeTab = $(this).find("a").attr("href");
            $(activeTab).fadeIn();
            return false;
        });
        $("#myTable").tablesorter();
        $("#myTable").tablesorterPager({container: $("#pager")});
        $(".editar").click(function(){
            var codd = $("input:checked").attr('value');
            var estadoo = $("input:checked").attr('estadoo');
            window.location = "editar_medico_gerencia.php?cod="+codd+"&esta=2";
        })
        $(".rechazar").click(function(){
            var codd = $("input:checked").attr('value')
            $.getJSON("ajax/rechazar_medcio/rechazar.php",{
                "codigo" : codd
            },response);
    
            return false;
        })
        function response(datos){
            if(datos.mensaje == 'bien'){
               alert("Datos Ingresados satisfactoriamente")
               location.reload(true)
            }
            if(datos.mensaje == 'mal'){
                alert("Datos No Ingresados intentelo de nuevo por favor.")
                location.reload(true)
            }
        }
    });
</script>
<style type="text/css">
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
    /* When the body has the loading class, we turn
       the scrollbar off with overflow:hidden */
    body.loading {
        overflow: hidden;   
    }

    /* Anytime the body has the loading class, our
       modal element will be visible */
    body.loading .modal {
        display: block;
    }
</style>

<h1>Aprobar alta de Medicos</h1>


<div class="divBotones">
	<input type="button" class="boton editar"  value="Aprobar">
	<input type="button" class="boton2 rechazar"  value="Rechazar">
</div>

<ul class="tabs">
    <li><a href="#tab1">Revisados</a></li>
    <li><a href="#tab2">Aprobados</a></li>
    <!--<li><a href="#tab3">Aprobados</a></li>-->
</ul>

<div class="tab_container">
    <div id="tab1" class="tab_content">
        <center style="padding:10px 0">
            <table class='texto'>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Nacimiento</th>
                    <th>Especialidades</th>
                    <th>Direcciones</th>
                    <th>Funcionario</th>
                    <th>Ciudad</th>
					<th>Estado</th>
                </tr>
                <?php
//                $sql = mysql_query("SELECT * from medicos where estado_registro = 3 and cod_ciudad = $global_agencia ORDER BY cod_med DESC ");
                $sql = mysql_query("SELECT * from medicos where estado_registro = 2  ORDER BY cod_med DESC ");
                $indice_tabla = 1;
                while ($dat = mysql_fetch_array($sql)) {
                    $cod = $dat[0];
                    $pat = $dat[1];
                    $mat = $dat[2];
                    $nom = $dat[3];
                    $fecha = $dat[4];
                    $fecha = explode('-', $fecha);
                    $fecha_nac = $fecha[2] . "/" . $fecha[1];
                    $telf = $dat[5];
                    $cel = $dat[6];
                    $email = $dat[7];
                    $hobbie = $dat[8];
                    $hobbie2 = $dat[9];
                    $est_civil = $dat[10];
                    $secre = $dat[11];
                    $perfil = $dat[12];
                    $ciudad = $dat[13];
                    $funcionario = $dat[17];
					
					$codEstadoMedico=$dat[16];
                    
					$nombre_completo = "$pat $mat $nom";
                    $sql1 = "select direccion from direcciones_medicos where cod_med=$cod order by direccion asc";
                    $resp1 = mysql_query($sql1);

                    /* Inicio.. para el estado del registro de medicos  */
                    $sql_estado = mysql_query("Select estado from estado_medico_registro where id = $codEstadoMedico ");
                    while ($row_estado = mysql_fetch_assoc($sql_estado)) {
                        $estado_registro_medico = $row_estado['estado'];
                    }

                    /* Fin.. para el estado del registro de medicos  */


                    /* Ciudad */

                    $sql_ciudad = mysql_query("SELECT descripcion from ciudades where cod_ciudad = $ciudad ");
                    while ($row_ciudad = mysql_fetch_assoc($sql_ciudad)) {
                        $ciudad_final = $row_ciudad['descripcion'];
                    }

                    /* Fin Ciudad */

                    /* Funcionario */

                    $sql_func = mysql_query(" SELECT CONCAT(nombres,'  ',paterno,'  ',materno) as nombree from funcionarios WHERE codigo_funcionario = $funcionario ");
                    while ($row_func = mysql_fetch_assoc($sql_func)) {
                        $funcionario_final = $row_func['nombree'];
                    }
//                    $sql_visitador_editado = mysql_query("Select codigo_visitador from medico_asignado_visitador where cod_med = $cod");
//                    while ($row_visitador_editado = mysql_fetch_assoc($sql_visitador_editado)) {
//                        $funcionario_final = $row_visitador_editado['codigo_visitador'];
//                    }

                    /* Fin Funcionario */

                    $direccion_medico = "<table border=0 class='textosupermini' width='100%'>";
                    while ($dat1 = mysql_fetch_array($resp1)) {
                        $dir = $dat1[0];
                        $direccion_medico = "$direccion_medico<tr><td align='left'>$dir</td></tr>";
                    }
                    $direccion_medico = "$direccion_medico</table>";
                    $sql2 = "select cod_especialidad from especialidades_medicos where cod_med=$cod order by cod_especialidad asc";
                    $resp2 = mysql_query($sql2);
                    $especialidad = "<table border=0 class='textomini' width='50%'>";
                    while ($dat2 = mysql_fetch_array($resp2)) {
                        $espe = $dat2[0];
                        $especialidad = "$especialidad<tr><td align='left'>$espe</td></tr>";
                    }
                    $especialidad = "$especialidad</table>";
                    $sql_auxiliar = "select * from categorias_lineas where cod_med='$cod'";
                    $resp_auxiliar = mysql_query($sql_auxiliar);
                    $registrado_en_linea = mysql_num_rows($resp_auxiliar);
                    if ($registrado_en_linea == 0) {
                        $color_reg = "";
                    } else {
                        $color_reg = "#FAD7A0";
                    }
                    $lineas_medico = "select distinct(l.nombre_linea), c.cod_especialidad, c.categoria_med from lineas l, 
	 categorias_lineas c where c.cod_med='$cod' and l.codigo_linea=c.codigo_linea and l.estado=1";
                    $resp_lineas = mysql_query($lineas_medico);
                    $cad_lineas = "<table class='textosupermini' border='0'>";
                    while ($dat_lineas = mysql_fetch_array($resp_lineas)) {
                        $nombre_linea = "$dat_lineas[0] $dat_lineas[1] $dat_lineas[2]";
                        $cad_lineas = "$cad_lineas <tr><td>$nombre_linea</td></tr>";
                    }
                    $cad_lineas = "$cad_lineas</table>";
                    $sqlMedAsignado = "select f.paterno, f.materno, f.nombres from funcionarios f, medico_asignado_visitador m where
                                     f.codigo_funcionario=m.codigo_visitador and m.cod_med=$cod and f.estado=1";
                    $respMedAsignado = mysql_query($sqlMedAsignado);
                    $visitadoresAsignados = "";
                    while ($datMedAsignado = mysql_fetch_array($respMedAsignado)) {
                        $visitadoresAsignados = $visitadoresAsignados . "- $datMedAsignado[0] $datMedAsignado[2]";
                    }
                    //
                    $consulta = "select nombre_perfil_psicografico from perfil_psicografico where cod_perfil_psicografico=$perfil";
                    $rs = mysql_query($consulta);
                    if (mysql_num_rows($rs) == 1) {
                        $reg = mysql_fetch_array($rs);
                        $perfil = $reg[0];
                    } else {
                        $perfil = "&nbsp;";
                    }
                    //
                    $consulta = "SELECT e.nombre_estadocivil FROM estado_civil e WHERE e.cod_estadocivil=$est_civil";
                    $rs = mysql_query($consulta);
                    if (mysql_num_rows($rs) == 1) {
                        $reg = mysql_fetch_array($rs);
                        $est_civil = $reg[0];
                    } else {
                        $est_civil = "&nbsp;";
                    }
                    ?>
                    <tr>
                        <td align='center'><?php echo $indice_tabla ?></td>
                        <td align='center'><input type='radio' name='codigos_ciclos' value='<?php echo $cod ?>' class="chekboxx" estadoo="3" /></td>
                        <td align='center'><?php echo $cod ?></td>
                        <td align='left'><?php echo $nombre_completo ?></td>
                        <td align='center'>&nbsp;<?php echo $fecha_nac ?></td>
                        <td align='center'>&nbsp;<?php echo $especialidad ?></td>
                        <td align='center'>&nbsp;<?php echo $direccion_medico ?></td>
                        <td align='center'>&nbsp;<?php echo $funcionario_final ?></td>
                        <td align='center'>&nbsp;<?php echo $ciudad_final ?></td>
						<td align='center'>&nbsp;<?php echo $estado_registro_medico ?></td>
						
                    </tr>
                    <?php
                    $indice_tabla++;
                }
                ?>
            </table>
        </center>
    </div>
    <div id="tab2" class="tab_content">
        <center style="padding:10px 0">
            <table class='texto'>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Nacimiento</th>
                    <th>Especialidades</th>
                    <th>Direcciones</th>
                    <th>Funcionario que Solicito</th>
                    <th>Ciudad</th>
					<th>Estado</th>
                </tr>
                <?php
//                $sql = mysql_query("SELECT * from medicos where estado_registro = 2 and cod_ciudad = $global_agencia ORDER BY cod_med DESC");
                $sql = mysql_query("SELECT * from medicos where estado_registro = 1 ORDER BY cod_med DESC");
                $indice_tabla = 1;
                while ($dat = mysql_fetch_array($sql)) {
                    $cod = $dat[0];
                    $pat = $dat[1];
                    $mat = $dat[2];
                    $nom = $dat[3];
                    $fecha = $dat[4];
                    $fecha = explode('-', $fecha);
                    $fecha_nac = $fecha[2] . "/" . $fecha[1];
                    $telf = $dat[5];
                    $cel = $dat[6];
                    $email = $dat[7];
                    $hobbie = $dat[8];
                    $hobbie2 = $dat[9];
                    $est_civil = $dat[10];
                    $secre = $dat[11];
                    $perfil = $dat[12];
                    $ciudad = $dat[13];
					$codEstadoMedico=$dat[16];
                    $funcionario = $dat[17];
                    $nombre_completo = "$pat $mat $nom";
                    $sql1 = "select direccion from direcciones_medicos where cod_med=$cod order by direccion asc";
                    $resp1 = mysql_query($sql1);

                    /* Inicio.. para el estado del registro de medicos  */
                    $sql_estado = mysql_query("Select estado from estado_medico_registro where id = $codEstadoMedico ");
                    while ($row_estado = mysql_fetch_assoc($sql_estado)) {
                        $estado_registro_medico = $row_estado['estado'];
                    }

                    /* Fin.. para el estado del registro de medicos  */

                    /* Ciudad */

                    $sql_ciudad = mysql_query("SELECT descripcion from ciudades where cod_ciudad = $ciudad ");
                    while ($row_ciudad = mysql_fetch_assoc($sql_ciudad)) {
                        $ciudad_final = $row_ciudad['descripcion'];
                    }

                    /* Fin Ciudad */

                    /* Funcionario */

                    $sql_func = mysql_query(" SELECT CONCAT(nombres,' ',paterno,' ',materno) as nombree from funcionarios WHERE codigo_funcionario = $funcionario ");
                    while ($row_func = mysql_fetch_assoc($sql_func)) {
                        $funcionario_final = $row_func['nombree'];
                    }

                    /* Fin Funcionario */


                    $direccion_medico = "<table border=0 class='textosupermini' width='100%'>";
                    while ($dat1 = mysql_fetch_array($resp1)) {
                        $dir = $dat1[0];
                        $direccion_medico = "$direccion_medico<tr><td align='left'>$dir</td></tr>";
                    }
                    $direccion_medico = "$direccion_medico</table>";
                    $sql2 = "select cod_especialidad from especialidades_medicos where cod_med=$cod order by cod_especialidad asc";
                    $resp2 = mysql_query($sql2);
                    $especialidad = "<table border=0 class='textomini' width='50%'>";
                    while ($dat2 = mysql_fetch_array($resp2)) {
                        $espe = $dat2[0];
                        $especialidad = "$especialidad<tr><td align='left'>$espe</td></tr>";
                    }
                    $especialidad = "$especialidad</table>";
                    $sql_auxiliar = "select * from categorias_lineas where cod_med='$cod'";
                    $resp_auxiliar = mysql_query($sql_auxiliar);
                    $registrado_en_linea = mysql_num_rows($resp_auxiliar);
                    if ($registrado_en_linea == 0) {
                        $color_reg = "";
                    } else {
                        $color_reg = "#2ECC71";
                    }
                    $lineas_medico = "select distinct(l.nombre_linea), c.cod_especialidad, c.categoria_med from lineas l, 
	 categorias_lineas c where c.cod_med='$cod' and l.codigo_linea=c.codigo_linea and l.estado=1";
                    $resp_lineas = mysql_query($lineas_medico);
                    $cad_lineas = "<table class='textosupermini' border='0'>";
                    while ($dat_lineas = mysql_fetch_array($resp_lineas)) {
                        $nombre_linea = "$dat_lineas[0] $dat_lineas[1] $dat_lineas[2]";
                        $cad_lineas = "$cad_lineas <tr><td>$nombre_linea</td></tr>";
                    }
                    $cad_lineas = "$cad_lineas</table>";
                    $sqlMedAsignado = "select f.paterno, f.materno, f.nombres from funcionarios f, medico_asignado_visitador m where
                                     f.codigo_funcionario=m.codigo_visitador and m.cod_med=$cod and f.estado=1";
                    $respMedAsignado = mysql_query($sqlMedAsignado);
                    $visitadoresAsignados = "";
                    while ($datMedAsignado = mysql_fetch_array($respMedAsignado)) {
                        $visitadoresAsignados = $visitadoresAsignados . "- $datMedAsignado[0] $datMedAsignado[2]";
                    }
                    //
                    $consulta = "select nombre_perfil_psicografico from perfil_psicografico where cod_perfil_psicografico=$perfil";
                    $rs = mysql_query($consulta);
                    if (mysql_num_rows($rs) == 1) {
                        $reg = mysql_fetch_array($rs);
                        $perfil = $reg[0];
                    } else {
                        $perfil = "&nbsp;";
                    }
                    //
                    $consulta = "SELECT e.nombre_estadocivil FROM estado_civil e WHERE e.cod_estadocivil=$est_civil";
                    $rs = mysql_query($consulta);
                    if (mysql_num_rows($rs) == 1) {
                        $reg = mysql_fetch_array($rs);
                        $est_civil = $reg[0];
                    } else {
                        $est_civil = "&nbsp;";
                    }
                    ?>
                    <tr>
                        <td align='center'><?php echo $indice_tabla ?></td>
                        <td align='center'>-</td>
                        <td align='center'><?php echo $cod ?></td>
                        <td align='left'><?php echo $nombre_completo ?></td>
                        <td align='center'>&nbsp;<?php echo $fecha_nac ?></td>
                        <td align='center'>&nbsp;<?php echo $especialidad ?></td>
                        <td align='center'>&nbsp;<?php echo $direccion_medico ?></td>
                        <td align='center'>&nbsp;<?php echo $funcionario_final ?></td>
                        <td align='center'>&nbsp;<?php echo $ciudad_final ?></td>
                        <td align='center'>&nbsp;<?php echo $estado_registro_medico ?></td>
                    </tr>
                    <?php
                    $indice_tabla++;
                }
                ?>
            </table>
        </center>
    </div>
    <!--    <div id="tab3" class="tab_content">
            <center style="padding:10px 0">
                <table border='1' class='textosupermini' cellspacing='0' width='100%' id="myTable">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Nacimiento</th>
                            <th>Especialidades</th>
                            <th>Direcciones</th>
                            <th>Funcionario</th>
                            <th>Ciudad</th>
                                    <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php
//                    $sql = mysql_query ("SELECT * from medicos where estado_registro = 1 and cod_ciudad = $global_agencia  ORDER BY cod_med DESC  ");
    $sql = mysql_query("SELECT * from medicos where estado_registro = 1  ORDER BY cod_med DESC limit 300  ");
    $indice_tabla = 1;
    while ($dat = mysql_fetch_array($sql)) {
        $cod = $dat[0];
        $pat = $dat[1];
        $mat = $dat[2];
        $nom = $dat[3];
        $fecha = $dat[4];
        $fecha = explode('-', $fecha);
        $fecha_nac = $fecha[2] . "/" . $fecha[1];
        $telf = $dat[5];
        $cel = $dat[6];
        $email = $dat[7];
        $hobbie = $dat[8];
        $hobbie2 = $dat[9];
        $est_civil = $dat[10];
        $secre = $dat[11];
        $perfil = $dat[12];
        $ciudad = $dat[13];
        $funcionario = $dat[17];


        $nombre_completo = "$pat $mat $nom";
        $sql1 = "select direccion from direcciones_medicos where cod_med=$cod order by direccion asc";
        $resp1 = mysql_query($sql1);

        /* Inicio.. para el estado del registro de medicos  */
        $sql_estado = mysql_query("Select estado from estado_medico_registro where id = $estado_registro ");
        while ($row_estado = mysql_fetch_assoc($sql_estado)) {
            $estado_registro_medico = $row_estado['estado'];
        }
        /* Fin.. para el estado del registro de medicos  */

        /* Ciudad */

        $sql_ciudad = mysql_query("SELECT descripcion from ciudades where cod_ciudad = $ciudad ");
        while ($row_ciudad = mysql_fetch_assoc($sql_ciudad)) {
            $ciudad_final = $row_ciudad['descripcion'];
        }

        /* Fin Ciudad */

        /* Funcionario */

        $sql_func = mysql_query(" SELECT CONCAT(nombres,' ',paterno,' ',materno) as nombree from funcionarios WHERE codigo_funcionario = $funcionario ");
        while ($row_func = mysql_fetch_assoc($sql_func)) {
            $funcionario_final = $row_func['nombree'];
        }

        /* Fin Funcionario */

        $direccion_medico = "<table border=0 class='textosupermini' width='100%'>";
        while ($dat1 = mysql_fetch_array($resp1)) {
            $dir = $dat1[0];
            $direccion_medico = "$direccion_medico<tr><td align='left'>$dir</td></tr>";
        }
        $direccion_medico = "$direccion_medico</table>";
        $sql2 = "select cod_especialidad from especialidades_medicos where cod_med=$cod order by cod_especialidad asc";
        $resp2 = mysql_query($sql2);
        $especialidad = "<table border=0 class='textomini' width='50%'>";
        while ($dat2 = mysql_fetch_array($resp2)) {
            $espe = $dat2[0];
            $especialidad = "$especialidad<tr><td align='left'>$espe</td></tr>";
        }
        $especialidad = "$especialidad</table>";
        $sql_auxiliar = "select * from categorias_lineas where cod_med='$cod'";
        $resp_auxiliar = mysql_query($sql_auxiliar);
        $registrado_en_linea = mysql_num_rows($resp_auxiliar);
        if ($registrado_en_linea == 0) {
            $color_reg = "";
        } else {
            $color_reg = "#aaaaaa";
        }
        $lineas_medico = "select distinct(l.nombre_linea), c.cod_especialidad, c.categoria_med from lineas l, 
	 categorias_lineas c where c.cod_med='$cod' and l.codigo_linea=c.codigo_linea and l.estado=1";
        $resp_lineas = mysql_query($lineas_medico);
        $cad_lineas = "<table class='textosupermini' border='0'>";
        while ($dat_lineas = mysql_fetch_array($resp_lineas)) {
            $nombre_linea = "$dat_lineas[0] $dat_lineas[1] $dat_lineas[2]";
            $cad_lineas = "$cad_lineas <tr><td>$nombre_linea</td></tr>";
        }
        $cad_lineas = "$cad_lineas</table>";
        $sqlMedAsignado = "select f.paterno, f.materno, f.nombres from funcionarios f, medico_asignado_visitador m where
                                     f.codigo_funcionario=m.codigo_visitador and m.cod_med=$cod and f.estado=1";
        $respMedAsignado = mysql_query($sqlMedAsignado);
        $visitadoresAsignados = "";
        while ($datMedAsignado = mysql_fetch_array($respMedAsignado)) {
            $visitadoresAsignados = $visitadoresAsignados . "- $datMedAsignado[0] $datMedAsignado[2]";
        }
        //
        $consulta = "select nombre_perfil_psicografico from perfil_psicografico where cod_perfil_psicografico=$perfil";
        $rs = mysql_query($consulta);
        if (mysql_num_rows($rs) == 1) {
            $reg = mysql_fetch_array($rs);
            $perfil = $reg[0];
        } else {
            $perfil = "&nbsp;";
        }
        //
        $consulta = "SELECT e.nombre_estadocivil FROM estado_civil e WHERE e.cod_estadocivil=$est_civil";
        $rs = mysql_query($consulta);
        if (mysql_num_rows($rs) == 1) {
            $reg = mysql_fetch_array($rs);
            $est_civil = $reg[0];
        } else {
            $est_civil = "&nbsp;";
        }
        ?>
                                                <tr>
                                                    <td align='center'><?php echo $indice_tabla ?></td>
                                                    <td align='center'><input type='radio' name='codigos_ciclos' value='<?php echo $cod ?>' class="chekboxx" estadoo="2" /></td>
                                                    <td align='center'><?php echo $cod ?></td>
                                                    <td align='left' class='textomini'><?php echo $nombre_completo ?></td>
                                                    <td align='center'>&nbsp;<?php echo $fecha_nac ?></td>
                                                    <td align='center'>&nbsp;<?php echo $especialidad ?></td>
                                                    <td align='center'>&nbsp;<?php echo $direccion_medico ?></td>
                                                    <td align='center'>&nbsp;<?php echo $funcionario_final ?></td>
                                                    <td align='center'>&nbsp;<?php echo $ciudad_final ?></td>
                                                </tr>
        <?php
        $indice_tabla++;
    }
    ?>
                    </tbody>
                    <tfoot></tfoot>
                </table>
                <div id="pager" class="pager">
                    <form>
                        <img src="icons/first.png" class="first"/>
                        <img src="icons/prev.png" class="prev"/>
                        <input type="text" class="pagedisplay"/>
                        <img src="icons/next.png" class="next"/>
                        <img src="icons/last.png" class="last"/>
                        <select class="pagesize">
                            <option value="10" selected="selected">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                        </select>
                    </form>
            </center>
        </div>-->
</div>

<div class="divBotones">
	<input type="button" class="boton editar"  value="Aprobar">
	<input type="button" class="boton2 rechazar"  value="Rechazar">
</div>

<div class="modal"></div>
