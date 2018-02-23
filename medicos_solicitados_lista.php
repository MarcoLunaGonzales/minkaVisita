<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript" src="ajax/adicionar_medico/send.data.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".eliminar").click(function(){
            var codd = $("input:checked").attr('value');
			if(codd == undefined || codd == ''){
                alert("Debe seleccionar un registro.");
            }else{
                if(confirm('Esta seguro de eliminar los datos')){
				var codd = $("input:checked").attr('value');
				sendData(codd)
				}	
            }
        })
        $(".editar").click(function(){
            var codd = $("input:checked").attr('value');
            if(codd == undefined || codd == ''){
                alert("Debe seleccionar por lo menos un medico");
            }else{
                window.location = "editar_medico_vm.php?cod="+codd;
            }
        })
    })
</script>
<?php
error_reporting(0);
require("conexion.inc");
require("estilos_administracion.inc");
?>
<?php
$sql = mysql_query("SELECT * from medicos where cod_visitador = $global_visitador order by cod_med DESC");
?>
<h1>M&#233;dicos solicitados para alta</h1>


    <div class="divBotones">
		<input type="button" onclick="javascript:location.href='adicionar_medico.php'" class="boton" value="Adicionar">
        <input type="button" onclick="javascript:void(0)" class="boton editar" value="Editar">
        <input type="button" onclick="javascript:void(0)" class="boton2 eliminar" value="Eliminar">
			<!--a href="javascript:void(0)" class="boton editar">Editar</a>
            <a href="adicionar_medico.php" class="boton">Adicionar</a>
			<a href="javascript:void(0)" class="boton eliminar">Eliminar</a-->
    </div>
<!--table align='center'>
    <tr>
        <td><a href='navegador_medicos1.php'><img  border='0'src='imagenes/back.png' width='40'></a></td>
    </tr>
</table-->



<center>
    <table class='texto'>
        <tr>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Especialidades</th>
            <th>Direcciones</th>
            <th>Estado Aprobaci&oacute;n</th>
<!--                <th>Estado</th>-->
        </tr>
        <?php
        $indice_tabla = 1;
        while ($dat = mysql_fetch_array($sql)) {
            $cod = $dat[0];
            $pat = $dat[1];
            $mat = $dat[2];
            $nom = $dat[3];
            $fecha = $dat[4];
            $fecha = explode('-', $fecha);
            $fecha_nac = $fecha[2]."/".$fecha[1];
            $telf = $dat[5];
            $cel = $dat[6];
            $email = $dat[7];
            $hobbie = $dat[8];
            $hobbie2 = $dat[9];
            $est_civil = $dat[10];
            $perfil = $dat[12];
            $estado_registro = $dat[16];
            $nombre_completo = "$pat $mat $nom";
            $sql1 = "select direccion from direcciones_medicos where cod_med=$cod order by direccion asc";
            $resp1 = mysql_query($sql1);

            /* Inicio.. para el estado del registro de medicos  */
            $sql_estado = mysql_query("Select estado from estado_medico_registro where id = $estado_registro ");
            while ($row_estado = mysql_fetch_assoc($sql_estado)) {
                $estado_registro_medico = $row_estado['estado'];
            }

            /* Fin.. para el estado del registro de medicos  */

            $direccion_medico = "<table border=0 width='100%'>";
            while ($dat1 = mysql_fetch_array($resp1)) {
                $dir = $dat1[0];
                $direccion_medico = "$direccion_medico<tr><td align='left'>$dir</td></tr>";
            }
            $direccion_medico = "$direccion_medico</table>";
            $sql2 = "select cod_especialidad from especialidades_medicos where cod_med=$cod order by cod_especialidad asc";
            $resp2 = mysql_query($sql2);
            $especialidad = "<table border=0 width='50%'>";
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
                $color_reg = "#B39DDB";
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
            <tr bgcolor='<?php echo $color_reg ?>'>
                <td align='center'><?php echo $indice_tabla ?></td>
                <td align='center'>
                    <?php if($estado_registro == 3 || $estado_registro == 4 ) : ?>
                        <input type='radio' name='codigos_ciclos' value='<?php echo $cod ?>'>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </td>
                <td align='center'><?php echo $cod ?></td>
                <td align='left'><?php echo $nombre_completo ?></td>
                <td align='center'>&nbsp;<?php echo $especialidad ?></td>
                <td align='center'>&nbsp;<?php echo $direccion_medico ?></td>
                <td align='center'>&nbsp;<?php 
                    if($estado_registro == 4){ 
						echo "<span style='color:red; font-weight: bold'> $estado_registro_medico </span>";
					}else{
						echo $estado_registro_medico;
					}
                ?></td>
            </tr>
            <?php
            $indice_tabla++;
        }
        ?>
    </table>
</center>
<table align='center'>
    <tr>
        <td><a href='navegador_medicos1.php'><img  border='0'src='imagenes/back.png' width='40'></a></td>
    </tr>
</table>


    <div class="divBotones">
		<input type="button" onclick="javascript:location.href='adicionar_medico.php'" class="boton" value="Adicionar">
        <input type="button" onclick="javascript:void(0)" class="boton editar" value="Editar">
        <input type="button" onclick="javascript:void(0)" class="boton2 eliminar" value="Eliminar">
	</div>
