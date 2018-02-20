<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript" src="lib/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="lib/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="lib/script.js"></script>
<script type="text/javascript" language="Javascript">
function envia_select(form) {
    form.submit();
    return(true);
}
function escogerInstitucion(nroDireccion, cod_ciudad) {
    var rpt_nro        = nroDireccion;
    var rpt_territorio = cod_ciudad;
    window.open('navegador_instituciones.php?rpt_territorio='+rpt_territorio+'&rpt_nro='+rpt_nro+'','','');
}
function nuevoAjax() {
    var xmlhttp=false;
    try {
        xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}
function cargarAjaxZona1(f) {
    var contenedor;
    var distrito=f.distrito1.value;
    contenedor = document.getElementById('zona1');
    ajax=nuevoAjax();
    ajax.open('GET', 'ajaxZona1.php?distrito='+distrito+'',true);
    ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null);
}
function cargarAjaxZona2(f) {
    var contenedor;
    var distrito=f.distrito2.value;
    contenedor = document.getElementById('zona2');
    ajax=nuevoAjax();
    ajax.open('GET', 'ajaxZona2.php?distrito='+distrito+'',true);
    ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null);
}
function cargarAjaxZona3(f) {
    var contenedor;
    var distrito=f.distrito3.value;
    contenedor = document.getElementById('zona3');
    ajax=nuevoAjax();
    ajax.open('GET', 'ajaxZona3.php?distrito='+distrito+'',true);
    ajax.onreadystatechange=function() {
        if(ajax.readyState==4) {
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null);
}
function validar(f) {
    if(f.ap_paterno.value == '' || f.ap_materno.value == '') {   
        alert('Debe escribir los apellidos del medico.');
        if(f.ap_paterno.value == '') {
            f.ap_paterno.focus();
        }
        return(false);
    }
    if(f.nombres.value=='') {   
        alert('El campo Nombres esta vacio');
        f.nombres.focus();
        return(false);
    }
    if(f.distrito1.value == 0 || f.distrito1.value==''){
        alert("El campo distrito esta vacio")
        f.distrito1.focus();
        return(false);
    }
    if(f.direccionvial1.value=='' || f.direccionvial1.value==0) {   
        alert('El campo Av/Calle/Plaza esta vacio');
        f.direccion1.focus();
        return(false);
    }
    if(f.direccion1.value=='') {   
        alert('El campo direccion esta vacio');
        f.direccion1.focus();
        return(false);
    }
    if(f.num_casa1.value=='') {   
        alert('El campo N de casa esta vacio');
        f.direccion1.focus();
        return(false);
    }
    if(f.espe1.value=='') {   
        alert('Debe seleccionar al menos una especialidad.');
        f.espe1.focus();
        return(false);
    }
    if(f.zona1.value=='' || f.zona1.value == 0) {   
        alert('Debe seleccionar una Zona.');
        f.espe1.focus();
        return(false);
    }
    if(f.name_farm_1.value == 'vacio' && f.name_farm_2.value == 'vacio' && f.name_farm_3.value == 'vacio' && f.edad.value == 0 && f.consulta.value=='' ){
        alert("Debe llenar los datos de la pestana DATOS DE CATEGORIZACION");
        f.name_farm_1.focus();
        return(false);
    }
    /*if(f.name_farm_1.value == 'vacio' ){
        alert("Debe ingresar al menos una farmacia de referencia")
        f.name_farm_1.focus();
        return(false);
    }*/
    if(f.edad.value==0) {   
        alert('Debe seleccionar el rango de edad al que pertenece.');
        f.espe1.focus();
        return(false);
    }
    if(f.paci.value=='') {   
        alert('Debe ingresar el numero de pacientes.');
        f.paci.focus();
        return(false);
    }
    if(f.consulta.value=='') {  
       alert('Debe ingresar el costo.');
       return(false);
	}
	if(f.espe1.value==f.espe2.value){
		alert('No puede insertar dos especialidades iguales.');
		return(false);
	}
   f.action = 'guardar_medico_solicitud.php';
   f.submit();
}
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
    padding: 20px 0
}
#add-row {
    background: transparent url('imagenes/add-slide.png') no-repeat scroll 50% 50%;
    cursor: pointer;
    margin: 20px 0 10px 10px;
    width: 102px;
    height: 32px;
    float: left;
}
#add-row:hover { 
    background: transparent url('imagenes/add-slide-hover.png') no-repeat scroll 50% 50%;
    cursor: pointer;
    margin: 20px 0 10px 10px;
    width: 102px;
    height: 32px;
}
td.deleteSlide	{
    background: transparent url('imagenes/delete-slide.png') no-repeat scroll 50% 50%;
    cursor:pointer;
}
#loader { display: none;  position: absolute; z-index: 110; top: 50%; left: 50% }
#manto  { width: 100%; height: 100%; background: #000; opacity:0.60; filter:Alpha(Opacity=60);  position: absolute; top: 0; left: 0; display: none; z-index: 100 }
</style>
<?php
require("estilos_administracion.inc");
require("conexion.inc");
$sql_cod_ciudad = mysql_query("SELECT cod_ciudad from funcionarios where codigo_funcionario = $global_visitador");
while ($row_cod_ciudad = mysql_fetch_assoc($sql_cod_ciudad)) {
    $cod_ciudad = $row_cod_ciudad['cod_ciudad'];
	if($cod_ciudad==125){
		$cod_ciudadFar=120;
	}else if($cod_ciudad==122 || $cod_ciudad==124){
		$cod_ciudadFar=116;
	}else{
		$cod_ciudadFar=$cod_ciudad;
	}
}
?>
<table width="100%"  border="0" cellspacing="0" class="textotit">
    <tr><td align='center'><div align="center">REGISTRO DE M&Eacute;DICOS</div><br></td></tr>
</table>
<ul class="tabs">
    <li><a href="#tab1">Datos Generales</a></li>
    <li><a href="#tab2">Datos Categorizacion</a></li>
</ul>
<div class="tab_container">
    <div id="tab1" class="tab_content">
        <form action="registro_medico.php" method="post" name="form1">
            <table border="1" align="center" cellspacing="0" class="texto">
                <tr><th colspan="5" class="texto"><br>Datos Obligatorios<br>&nbsp;</th></tr>
                <tr>
                    <td align='center'><b>Apellido Paterno(*)</b><br><input name='ap_paterno' type='text' class='texto' onKeyUp='javascript:this.value=this.value.toUpperCase();' ></td>
                    <td align='center'><b>Apellido Materno(*)</b><br><input name='ap_materno' type='text' class='texto' onKeyUp='javascript:this.value=this.value.toUpperCase();' ></td>
                    <td align='center' colspan="2"><b>Nombres(*)</b><br><input name='nombres' type='text' class='texto' onKeyUp='javascript:this.value=this.value.toUpperCase();'  ></td>
                    <td align='center' bgcolor='#ffffff'>
                        <b>Fecha de Cumplea&ntilde;os</b><br>
                        <input  type='text' class='texto' id='exafinicial' size='10' name='exafinicial' placeholder="DD/MM" />
                    </td>
                </tr>
                <tr>
                    <td align='center'>
                        <b>Agencia(*)</b><br>
                        <select name='cod_ciudad' class='texto' onChange='envia_select(this.form)'>
                            <?php
                            $sql = "SELECT cod_ciudad,descripcion from ciudades where cod_ciudad=$cod_ciudad";
                            $resp = mysql_query($sql);
                            $dat = mysql_fetch_array($resp);
                            $cod_ciudad = $dat[0];
                            $nombre_ciudad = $dat[1];
                            echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
                            ?>
                        </select>
                    </td>
                    <td align='center'>
                        <b>Distrito</b><br>
                        <select name='distrito1' class='texto' onChange='cargarAjaxZona1(this.form)'>
                            <option value='0'>( ninguno )</option>
                            <?php
                            $sql = "SELECT * from distritos where cod_ciudad='$cod_ciudad'";
                            $resp = mysql_query($sql);
                            while ($dat = mysql_fetch_array($resp)) {
                                $p_ciudad = $dat[0];
                                $p_dist = $dat[1];
                                $p_desc = $dat[2];
                                echo "<option value='$p_dist'>$p_desc</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td align='center' colspan="2">
                        <b>Zona</b><br>
                        <div id='zona1'></div>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td align='center' colspan="2">
                        <b style="float:left; font-size: 10px; margin-left: 15px">Av./Calle/Plaza</b><b>Direcci&#243;n</b><br>
                        <?php
                        echo "<select name='direccionvial1' class='texto'>";
                        echo "<option value=''>( ninguno )</option>";
                        echo "<option value='AVENIDA'>AVENIDA</option>";
                        echo "<option value='CALLE'>CALLE</option>";
                        echo "<option value='PLAZA'>PLAZA</option>";
                        echo "</select> ";
                        ?>
                        <input name='direccion1' type='text' class='texto' size='40' placeholder="Sea lo mas especifico posible" onKeyUp='javascript:this.value=this.value.toUpperCase();' >
                        N&#176;<input name='num_casa1' type='text' class='texto' size='5' onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');">
                    </td>
                    <td align='center'>
                        <b>Centro M&eacute;dico</b><br>
                        <select name='centro_medico' class='texto' style="width:280px">
                            <?php
                            $sqlCentroMedico = "SELECT cod_centro_medico, nombre from centros_medicos  where cod_ciudad = $cod_ciudadFar order by 2 asc";
                            $respCentroMedico = mysql_query($sqlCentroMedico);
                            while ($datCentroMedico = mysql_fetch_array($respCentroMedico)) {
                                $codCentroMedico = $datCentroMedico[0];
                                $nombreCentroMedico = $datCentroMedico[1];
                                echo "<option value='$codCentroMedico'>$nombreCentroMedico</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td align='center' colspan="2">
                        <b>Especialidad</b><br>
                        <select name='espe1' class='texto'>
                            <option value=''>Especialidad 1</option>
                            <?php
                            $sql = "SELECT cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
                            $resp = mysql_query($sql);
                            while ($dat = mysql_fetch_array($resp)) {
                                $p_cod_espe = $dat[0];
                                $p_desc_espe = $dat[1];
                                echo "<option value='$p_cod_espe'>$p_desc_espe</option>";
                            }
                            ?>
                        </select>
						<select name='espe2' class='texto'>
                            <option value=''>Especialidad 2</option>
                            <?php
                            $sql = "SELECT cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
                            $resp = mysql_query($sql);
                            while ($dat = mysql_fetch_array($resp)) {
                                $p_cod_espe = $dat[0];
                                $p_desc_espe = $dat[1];
                                echo "<option value='$p_cod_espe'>$p_desc_espe</option>";
                            }
                            ?>
                        </select> 						
                    </td>
                    <td align='center' colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <th colspan="5" class="texto"><br>Datos Complementarios<br>&nbsp;</th>
                </tr>
                <tr>
                    <td align='center' colspan="2">
                        <b>Correo Electronico</b><br><input name='email' type='text' class='texto' placeholder="ejemplo@ejmplo.com"><br>
                    </td>
                    <td align='center' colspan="3"><b>Tel&eacute;fono / Tel&eacute;fono Celular</b><br><input name='telf_celular' type='text' class='texto' id="telefono"> <input name='telefono' type='text' class='texto' id="celular"><br></td>
                </tr>
                <tr>
                    <td align='center'>
                        <b>Estado Civil</b><br>
                        <select name='estado_civil' class='texto'>
                            <?php
                            $consulta = "SELECT e.cod_estadocivil, e.nombre_estadocivil FROM estado_civil e ORDER BY e.nombre_estadocivil ASC";
                            $rs = mysql_query($consulta);
                            echo "<option value='0'>( ninguno )</option>";
                            while ($reg = mysql_fetch_array($rs)) {
                                $cod_estadocivil = $reg[0];
                                $nom_estadocivil = $reg[1];
                                echo "<option value='$cod_estadocivil'>$nom_estadocivil</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td align='center'>
                        <b>Perfil Psicografico</b><br>
                        <select name='perfil_psicografico' class='texto'>
                            <?php
                            $sql_perfil = "SELECT cod_perfil_psicografico, nombre_perfil_psicografico from perfil_psicografico order by 2";
                            $resp_perfil = mysql_query($sql_perfil);
                            echo "<option value='0'>( ninguno )</option>";
                            while ($dat_perfil = mysql_fetch_array($resp_perfil)) {
                                $cod_perfil = $dat_perfil[0];
                                $nombre_perfil = $dat_perfil[1];
                                echo "<option value='$cod_perfil'>$nombre_perfil</option>";
                            }
                            ?>
                        </select>
                    </td>
                    <td align='center'>
                        <b>Hobbie</b><br>Hobbie 1:<input name='hobbie' type='text' class='texto' onKeyUp='javascript:this.value=this.value.toUpperCase();' ><br>Hobbie 2:<input name='hobbie2' type='text' class='texto' onKeyUp='javascript:this.value=this.value.toUpperCase();' >
                    </td>
                    <td align='center' colspan="2">
                        <b>Linea</b><br>
                        <select name='lineass' class='texto'>
                            <?php
                            $sql_linea = "SELECT codigo_linea, nombre_linea from lineas where linea_promocion = 1 and estado=1 order by 2";
                            $sql_linea_resp = mysql_query($sql_linea);
                            while ($dat_linea = mysql_fetch_array($sql_linea_resp)) {
                                $cod_linea = $dat_linea[0];
                                $nombre_linea = $dat_linea[1];
                                echo "<option value='$cod_linea'>$nombre_linea</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
            </table><br>

        </div>
        <div id="tab2" class="tab_content">
            <div style="clear: both; width: 99%;">
                <center><strong>Datos complementarios</strong></center>
            </div>
            <table width="99%" border="1" cellpadding="0" id="">
                <tr>
                    <th width="4%" scope="col">&nbsp;</th>
                    <th width="46%" scope="col">Farmacias de Referencia</th>
                    <th width="46%" scope="col">Categoria l&iacute;nea &eacute;tica</th>
                </tr>
            </table>
            <table width="99%" border="1" cellpadding="0" id="table-principal">
                <tr id="1" class="row-style">
                    <td class="position" style="text-align: center; font-weight: bold" width="4%">1</td>
                    <td class="cargar_categoria" width="46%">
                        <select class="name_farm" name="name_farm_1" id="name_farm_1" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                            <option value="vacio">Selecionar una opcion</option>
                            <?php 
                            $query_farmacias = "SELECT nombre, cat_ventaetica from fclientes where cod_ciudad = $cod_ciudadFar  order by nombre ASC"; 
                            $resp_query_farmacias = mysql_query($query_farmacias); 
                            while ($row = mysql_fetch_assoc($resp_query_farmacias)) { 
                                ?>
                                <option value="<?php echo str_replace('"', "", $row['nombre']) ?>"><?php echo $row['nombre'] ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
                    <td width="46%">
                        <input type="text" class="dir_farm" name="dir_farm_1" id="dir_farm_1" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold " readonly="readonly" value="" />
                    </td>
                </tr>
                <tr id="2" class="row-style">
                    <td class="position" style="text-align: center; font-weight: bold" width="4%">2</td>
                    <td class="cargar_categoria" width="46%">

                        <select class="name_farm" name="name_farm_2" id="name_farm_2" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                            <option value="vacio">Selecionar una opcion</option>
                            <?php 
                            $query_farmacias = "SELECT nombre, cat_ventaetica from fclientes where cod_ciudad = $cod_ciudadFar  order by nombre ASC"; 
                            $resp_query_farmacias = mysql_query($query_farmacias); 
                            while ($row = mysql_fetch_assoc($resp_query_farmacias)) { 
                                ?>
                                <option value="<?php echo str_replace('"', "", $row['nombre']) ?>"><?php echo $row['nombre'] ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
                    <td width="46%">
                        <input type="text" class="dir_farm" name="dir_farm_2" id="dir_farm_2" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold " readonly="readonly" value="" />
                    </td>
                </tr>
                <tr id="3" class="row-style">
                    <td class="position" style="text-align: center; font-weight: bold" width="4%">3</td>
                    <td class="cargar_categoria" width="46%">
                        <select class="name_farm" name="name_farm_3" id="name_farm_3" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                            <option value="vacio">Selecionar una opcion</option>
                            <?php 
                            $query_farmacias = "SELECT nombre, cat_ventaetica from fclientes where cod_ciudad = $cod_ciudadFar  order by nombre ASC"; 
                            $resp_query_farmacias = mysql_query($query_farmacias); 
                            while ($row = mysql_fetch_assoc($resp_query_farmacias)) { 
                                ?>
                                <option value="<?php echo str_replace('"', "", $row['nombre']) ?>"><?php echo $row['nombre'] ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </td>
                    <td width="46%">
                        <input type="text" class="dir_farm" name="dir_farm_3" id="dir_farm_3" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold " readonly="readonly" value="" />
                    </td>
                </tr>
            </table>
            <table width="99%" border="0" cellpadding="5" style="margin-top: 10px">
                <tr>
                    <th scope="row" style="width: 52%; text-align: left">Sexo</th>
                    <td>
                        <select name="sexo" id="sexo" style="width: 35%; background: #f8e8db; border: none">
                            <option value="Femenino">Femenino</option>
                            <option value="Masculino">Masculino</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" style="width: 52%; text-align: left">Edad</th>
                    <td>
                        <?php $sql_edad = mysql_query(" SELECT * from edad_categorizacion_medico ") ?>
                        <select name="edad" id="edad" style="width: 40%; background: #f8e8db; border: none">
                            <option value="0">Elegir...</option>
                            <?php 
                            while ($row_edad = mysql_fetch_array($sql_edad)) { 
                                $codigo_edad = $row_edad[0];
                                $nombre_edad = $row_edad[1];
                                ?>
                                <option value="<?php echo $codigo_edad ?>"><?php echo $nombre_edad ?></option>
                                <?php 
                            } 
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" style="text-align: left">Nro. de pacientes d&iacute;a en el lugar de visita</th>
                    <td><input type="text" name="paci" id="paci" style="width: 35%; border: none; background: #F8E8DB" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');"  /></td>
                </tr>
                <tr>
                    <th scope="row"style="text-align: left">Tiene preferencia prescriptiva por productos de marca</th>
                    <td>
                        <select name="prescriptiva" id="prescriptiva" style="width: 35%; background: #f8e8db; border: none">
                            <option value="Alta">Alta</option>
                            <option value="Media">Media</option>
                            <option value="Baja">Baja</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"style="text-align: left">Nivel Sociecon&oacute;mico de los Pacientes</th>
                    <td>
                        <select name="nivel" id="nivel" style="width: 35%;background: #f8e8db; border: none">
                            <option value="Alta">Alta</option>
                            <option value="Media">Media</option>
                            <option value="Baja">Baja</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row" style="text-align: left">Costo consulta (Bs.)</th>
                    <td><input type="text" name="consulta" id="consulta" style="width: 35%; border: none; background: #F8E8DB" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" /></td>
                </tr>
            </table>
        </div>
    </div>
    <?php
    echo "<table align='center'><tr><td><a href='medicos_solicitados_lista.php?cod_ciudad=$cod_ciudad'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
    ?>
    <table class="texto" border="0" align="center">
        <tr>
            <td align='center'>
                <input type="button" class="boton" value="Enviar" onClick='validar(this.form)'>
            </td>
        </tr>
    </table>
</form>
</body>
</html>