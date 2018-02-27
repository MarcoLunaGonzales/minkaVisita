<?php
error_reporting(0);
set_time_limit(0);
require("estilos_administracion.inc");
require("conexion.inc");
$esta = $_GET['esta'];
?>
<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript">
$(document).ready(function(){
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
    })
    $('#table-principal tr td.cargar_categoria .name_farm').bind("change", ( change ));
    function change(){
        cambiarCategoria($(this).val(),$(this).attr('name'))
    }
    function cambiarCategoria(nombre,puesto){
        var nombre_farmacia = nombre;
        var puesto = puesto;
        $.getJSON("ajax/categorizacion_medicos/cambiar.php",{
            "nombre_farmacia":  nombre_farmacia,
            "puesto":  puesto
        },responseCambiar);

        return false;

    }
    function responseCambiar(datos){
        if(datos.nombre==null || datos.nombre == ""){
            $("input[name='dir_farm_"+datos.puesto+"']").attr('value','Sin categoria')
        }else{
            $("input[name='dir_farm_"+datos.puesto+"']").attr('value',datos.nombre)
        }
    }
})
</script>
<script type="text/javascript" language="Javascript">
function envia_select(form){
    form.submit();
    return(true);
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
function cargarAjaxZona1(f, zona){
    var contenedor;
    var distrito=f.distrito1.value;
    contenedor = document.getElementById('zona1');
    ajax=nuevoAjax();
    ajax.open('GET', 'ajaxZona1.php?distrito='+distrito+'&zona='+zona+'',true);
    ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null);
}
function cargarAjaxZona2(f, zona){
    var contenedor;
    var distrito=f.distrito2.value;
    contenedor = document.getElementById('zona2');
    ajax=nuevoAjax();
    ajax.open('GET', 'ajaxZona2.php?distrito='+distrito+'&zona='+zona+'',true);
    ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null);
}
function cargarAjaxZona3(f,zona){
    var contenedor;
    var distrito=f.distrito3.value;
    contenedor = document.getElementById('zona3');
    ajax=nuevoAjax();
    ajax.open('GET', 'ajaxZona3.php?distrito='+distrito+'&zona='+zona+'',true);
    ajax.onreadystatechange=function() {
        if (ajax.readyState==4) {
            contenedor.innerHTML = ajax.responseText
        }
    }
    ajax.send(null);
}
function validar(f) {
    if(f.ap_paterno.value=='' || f.ap_materno.value=='') {  
     alert('Debe escribir los apellidos del medico.');
     if(f.ap_paterno.value=='') {
        f.ap_paterno.focus();
    }
    return(false);
}
if(f.nombres.value=='') {   
    alert('El campo "Nombres" esta vacio');
    f.nombres.focus();
    return(false);
}
if(f.distrito1.value == 0){
    alert("El campo distrito esta vacio")
    f.distrito1.focus();
    return(false);
}
if(f.zona1.value == 0){
    alert("El campo zona esta vacio")
    f.distrito1.focus();
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
if(f.edad.value == 0 && f.consulta.value=='' ){
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
if(f.espe1.value==f.espe2.value){
		alert('No puede insertar dos especialidades iguales.');
		return(false);
}
// if(f.paci.value=='') {   
//     alert('Debe ingresar el numero de pacientes.');
//     f.paci.focus();
//     return(false);
// }
// if(f.consulta.value=='') {   
//     alert('Debe ingresar el costo.');
//     return(false);
// }
f.action='guardarEditarMedicoAgencia.php?estaa='+<?php echo $esta; ?>;
f.submit();
}
function cargarZonas(f, zona1, zona2, zona3){
    cargarAjaxZona1(f, zona1);
    cargarAjaxZona2(f, zona2);
    cargarAjaxZona3(f, zona3);
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
    color: #000;
    font-size: 1.2em;
    text-decoration: none;
}
ul.tabs li.active, ul.tabs li.active a:hover  {
    background: #fff;
    border-bottom: 1px solid #fff;
    color: #000;
    font-size: 1.2em;
    text-decoration: none;
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
#manto { width: 100%; height: 100%; background: #000; opacity:0.60; filter:Alpha(Opacity=60);  position: absolute; top: 0; left: 0; display: none; z-index: 100 }
</style>
<?php

$cod_med = $_GET['cod'];


$sql_linea_editado = mysql_query("SELECT codigo_linea, categoria_med from categorias_lineas where cod_med = $cod_med");
while ($row_linea_editado = mysql_fetch_assoc($sql_linea_editado)) {
    $codigo_final_linea = $row_linea_editado['codigo_linea'];
    $categoria_final_linea = $row_linea_editado['categoria_med'];
}


$sql_visitador_editado = mysql_query("SELECT codigo_visitador from medico_asignado_visitador where cod_med = $cod_med");
while ($row_visitador_editado = mysql_fetch_assoc($sql_visitador_editado)) {
    $codigo_final_visitador = $row_visitador_editado['codigo_visitador'];
}

$sql = "SELECT cod_med, ap_pat_med, ap_mat_med, nom_med, fecha_nac_med, telf_med, telf_celular_med, email_med, hobbie_med, hobbie_med2, estado_civil_med, nombre_secre_med, perfil_psicografico_med, cod_ciudad, cod_catcloseup, cod_closeup, categorizacion_medico FROM medicos WHERE cod_med='$cod_med'";
$resp = mysql_query($sql);
while ($dat = mysql_fetch_array($resp)) {
    $cod_med                        = $dat[0];
    $ap_pat_med                     = $dat[1];
    $ap_mat_med                     = $dat[2];
    $nom_med                        = $dat[3];
    $fecha_nac_med                  = $dat[4];
    $telf_med                       = $dat[5];
    $telf_celular_med               = $dat[6];
    $email_med                      = $dat[7];
    $hobbie_med                     = $dat[8];
    $hobbie_med2                    = $dat[9];
    $estado_civil_med               = $dat[10];
    $nombre_secre_med               = $dat[11];
    $perfil_psicografico            = $dat[12];
    $cod_ciudad                     = $dat[13];
    $codigocategoriacloseup         = $dat[14];
    $categoriacloseup               = $dat[15];
    $categorizacion_medcio_sistemaa = $dat[16];

    $vec = split("-", $fecha_nac_med);
    $fecha_nac_med = $vec[2] . "/" . $vec[1];
}
$sqlDirecciones = "SELECT d.cod_med, d.cod_zona, d.direccionvial, d.direccion, d.num_casa, d.numero_direccion, d.cod_tipo_consultorio, z.cod_dist,  d.cod_centro_medico from direcciones_medicos d, zonas z where d.cod_med = '$cod_med' and d.cod_zona = z.cod_zona";
$respDirecciones = mysql_query($sqlDirecciones);
$codZona1 = 0;
$codZona2 = 0;
$codZona3 = 0;
while ($datDirecciones = mysql_fetch_array($respDirecciones)) {
    $cod_zona = $datDirecciones[1];
    $direccionvial = $datDirecciones[2];
    $direccion = $datDirecciones[3];
    $num_casa = $datDirecciones[4];
    $nro_direccion = $datDirecciones[5];
    $cod_tipo_consultorio = $datDirecciones[6];
    $cod_dist = $datDirecciones[7];
    $centro_medico = $datDirecciones[8];
    if ($nro_direccion == 1) {
        $direccionvial1      = $direccionvial;
        $direccion1          = $direccion;
        $num_casa1           = $num_casa;
        $codTipoConsultorio1 = $cod_tipo_consultorio;
        $codZona1            = $cod_zona;
        $codDistrito1        = $cod_dist;
    }
}

$sqlEspe = "SELECT cod_med, cod_especialidad, cod_tipo_especialidad from especialidades_medicos where cod_med='$cod_med'";
$respEspe = mysql_query($sqlEspe);
$espe1 = "";
$espe2 = "";
$espe3 = "";
$espe4 = "";
$espe5 = "";
while ($datEspe = mysql_fetch_array($respEspe)) {
    $codEspe = $datEspe[1];
    $tipoEspe = $datEspe[2];
    if ($tipoEspe == 1) {
        $espe1 = $codEspe;
    }
    if ($tipoEspe == 2) {
        $espe2 = $codEspe;
    }
    if ($tipoEspe == 3) {
        if ($espe3 == "") {
            $espe3 = $codEspe;
        } else {
            if ($espe3 != "" && $espe4 == "") {
                $espe4 = $codEspe;
            } else {
                if ($espe4 != "" && $espe5 == "") {
                    $espe5 = $codEspe;
                }
            }
        }
    }
}
?>
<html>
<body onload="">

    <h1>Revisar Medico</h1>

    <ul class="tabs">
        <li><a href="#tab1">Datos Generales</a></li>
        <!--li><a href="#tab2">Datos Categorizacion</a></li-->
    </ul>
    <div class="tab_container">
        <div id="tab1" class="tab_content">
            <form action="registro_medico.php" method="post" name="form1">
                
				<table border="1" align="center" cellspacing="0" class="texto">
                    <tr><th colspan="5" class="texto"><br>Datos Obligatorios<br>&nbsp;</th></tr>
                    <tr>
                        <td align='center'><b>Apellido Paterno(*)</b><br><input name='ap_paterno' type='text' class='texto' value='<?php echo "$ap_pat_med"; ?>' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>
                        <td align='center'><b>Apellido Materno(*)</b><br><input name='ap_materno' type='text' class='texto' value='<?php echo "$ap_mat_med"; ?>' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>
                        <td align='center'><b>Nombres(*)</b><br><input name='nombres' type='text' class='texto' value='<?php echo "$nom_med"; ?>' onKeyUp='javascript:this.value=this.value.toUpperCase();'></td>
                        <td align='center' bgcolor='#ffffff'>
                            <b>Fecha de Nacimiento</b><br>
                            <input  type='text' class='texto' id='exafinicial' size='10' name='exafinicial' value='<?php echo "$fecha_nac_med"; ?>'>
                        </td>
                    </tr>
                    <tr>
                        <td align='center'>
                            <b>Agencia(*)</b><br>
                            <?php
                            $sql = "SELECT cod_ciudad,descripcion from ciudades where cod_ciudad=$cod_ciudad";
                            $resp = mysql_query($sql);
                            $dat = mysql_fetch_array($resp);
                            $cod_ciudad = $dat[0];
							if($cod_ciudad==125){
								$cod_ciudadFar=120;
							}else if($cod_ciudad==122 || $cod_ciudad==124){
								$cod_ciudadFar=116;
							}else{
								$cod_ciudadFar=$cod_ciudad;
							}
							
                            $nombre_ciudad = $dat[1];
                            ?>
                            <select name='cod_ciudad' class='texto' onChange='envia_select(this.form)'>";
                                <option value='<?php echo"$cod_ciudad"; ?>'><?php echo"$nombre_ciudad"; ?></option>
                            </select>
                        </td>
                        <td align='center'>
                            <b>Distrito</b><br>
                            <select name='distrito1' class='texto' onChange='cargarAjaxZona1(this.form,0)'>
                                <option value='0'>( ninguno )</option>
                                <?php
                                $sql = "SELECT * from distritos where cod_ciudad = '$cod_ciudad'";
                                $resp = mysql_query($sql);
                                while ($dat = mysql_fetch_array($resp)) {
                                    $p_ciudad = $dat[0];
                                    $p_dist = $dat[1];
                                    $p_desc = $dat[2];
                                    echo "<option value='$p_dist' ";
                                    if ($p_dist == $codDistrito1) {
                                        echo "selected";
                                    }
                                    echo ">$p_desc</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td align='center'>
                            <b>Zona</b><br>
                            <div id='zona1'>
                                <?php
                                $consulta = "SELECT cod_zona, zona from zonas where cod_dist = '$codDistrito1'";
                                $rs = mysql_query($consulta);
                                echo "<select name='zona1' class='texto'>";
                                echo "<option value='0'>( ninguno )</option>";
                                while ($reg = mysql_fetch_array($rs)) {
                                    $codZonaAux = $reg[0];
                                    $nomZonaAux = $reg[1];
                                    if ($codZonaAux == $codZona1) {
                                        echo "<option value='$codZonaAux' selected>$nomZonaAux</option>";
                                    } else {
                                        echo "<option value='$codZonaAux'>$nomZonaAux</option>";
                                    }
                                }
                                echo "</select>";
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align='center' colspan="2">
                            <b style="float:left; font-size: 10px;margin-left: 15px">Av./Calle/Plaza</b><b>Direcci&#243;n</b><br>
                            <select name='direccionvial1' class='texto'>
                                <?php
                                echo "<option value=''>( ninguno )</option>";
                                if ($direccionvial1 == "AVENIDA") {
                                    echo "<option value='AVENIDA' selected>AVENIDA</option>";
                                } else {
                                    echo "<option value='AVENIDA'>AVENIDA</option>";
                                }
                                if ($direccionvial1 == "CALLE") {
                                    echo "<option value='CALLE' selected>CALLE</option>";
                                } else {
                                    echo "<option value='CALLE'>CALLE</option>";
                                }
                                if ($direccionvial1 == "PLAZA") {
                                    echo "<option value='PLAZA' selected>PLAZA</option>";
                                } else {
                                    echo "<option value='PLAZA'>PLAZA</option>";
                                }
                                ?>
                            </select>
                            <input name='direccion1' type='text' class='texto' size='40' value='<?php echo "$direccion1"; ?>' onKeyUp='javascript:this.value=this.value.toUpperCase();'>
                            N&#176;<input name='num_casa1' type='text' class='texto' size='5' value='<?php echo "$num_casa1"; ?>' onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');">
                        </td>
                        <td align='center'>
                            <b>Centro M&eacute;dico</b><br>
                            <select name='centro_medico' class='texto' style="width:280px">
                                <?php
                                $sqlCentroMedico = "SELECT cod_centro_medico, nombre from centros_medicos where  cod_ciudad = $cod_ciudadFar order by 2 asc";
                                $respCentroMedico = mysql_query($sqlCentroMedico);
                                while ($datCentroMedico = mysql_fetch_array($respCentroMedico)) {
                                    $codCentroMedico    = $datCentroMedico[0];
                                    $nombreCentroMedico = $datCentroMedico[1];
                                    if ($codCentroMedico == $centro_medico) {
                                        echo "<option value='$codCentroMedico' selected>$nombreCentroMedico</option>";
                                    } else {
                                        echo "<option value='$codCentroMedico'>$nombreCentroMedico</option>";
                                    }
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
                                    echo "<option value='$p_cod_espe' ";
                                    if ($p_cod_espe == $espe1) {
                                        echo "selected";
                                    }
                                    echo ">$p_desc_espe</option>";
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
                                    echo "<option value='$p_cod_espe' ";
                                    if ($p_cod_espe == $espe2) {
                                        echo "selected";
                                    }
                                    echo ">$p_desc_espe</option>";
                                }
                                ?>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5" class="texto"><br>Datos Complementarios<br>&nbsp;</th>
                    </tr>
                    <tr>
                        <td align='center'>
                            <b>Categor&#237;a Close UP</b><br>
                            <select name='codigocategoriacloseup' class='texto'>
                                <?php
                                $consulta = "SELECT c.cod_cat, c.nombre_cat FROM categoria_closeup c ORDER BY c.nombre_cat ASC";
                                $rs = mysql_query($consulta);
                                while ($reg = mysql_fetch_array($rs)) {
                                    $codCatCloseUp = $reg[0];
                                    $desCatCloseUp = $reg[1];
                                    echo "<option value='$codCatCloseUp' ";
                                    if ($codCatCloseUp == $codigocategoriacloseup) {
                                        echo "selected";
                                    }
                                    echo ">$desCatCloseUp</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td align='center'>
                            <b>Codigo Close UP</b><br>
                            <input name='categoriacloseup' type='text' class='texto' value='<?php echo"$categoriacloseup"; ?>'>
                        </td>
                        <td align='center' >
                            <b>Correo Electronico</b><br><input name='email' type='text' class='texto' value='<?php echo "$email_med"; ?>'><br>
                        </td>
                        <td align='center' colspan="2"><b>Tel&eacute;fono / Tel&eacute;fono Celular</b><br><input name='telf_celular' type='text' class='texto' value='<?php echo "$telf_celular_med"; ?>'><input name='telefono' type='text' class='texto' id="celular" value="<?php echo $telf_med ?>"><br></td>
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
                                    if ($cod_estadocivil == $estado_civil_med) {
                                        echo "<option value='$cod_estadocivil' selected>$nom_estadocivil</option>";
                                    } else {
                                        echo "<option value='$cod_estadocivil'>$nom_estadocivil</option>";
                                    }
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
                                while ($dat_perfil = mysql_fetch_array($resp_perfil)) {
                                    $cod_perfil    = $dat_perfil[0];
                                    $nombre_perfil = $dat_perfil[1];
                                    if ($cod_perfil == $perfil_psicografico) {
                                        echo "<option value='$cod_perfil' selected>$nombre_perfil</option>";
                                    } else {
                                        echo "<option value='$cod_perfil'>$nombre_perfil</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td align='center'>
                            <b>Hobbie</b><br>Hobbie 1:<input name='hobbie' type='text' class='texto' value='<?php echo "$hobbie_med"; ?>' onKeyUp='javascript:this.value=this.value.toUpperCase();'><br>Hobbie 2:<input name='hobbie2' type='text' class='texto' value='<?php echo "$hobbie_med2"; ?>' onKeyUp='javascript:this.value=this.value.toUpperCase();'>
                        </td>
                        <td align='center' colspan="2">&nbsp;</td>
                    </tr>
                    <tr><th colspan="5" class="texto"><br>Asignar Linea<br>&nbsp;</th></tr>
                    <tr>
                        <td align="center" colspan="1">
                            <strong>Linea</strong>
                        </td>
                        <td align="center" colspan="1">
                            <select name='lineass' class='texto'>
                                <?php
                                $sql_linea = "SELECT codigo_linea, nombre_linea from lineas where linea_promocion = 1 order by 2";
                                $sql_linea_resp = mysql_query($sql_linea);
                                while ($dat_linea = mysql_fetch_array($sql_linea_resp)) {
                                    $cod_linea = $dat_linea[0];
                                    $nombre_linea = $dat_linea[1];
                                    if ($cod_linea == $codigo_final_linea) {
                                        echo "<option value='$cod_linea' selected>$nombre_linea</option>";
                                    } else {
                                        echo "<option value='$cod_linea'>$nombre_linea</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td align=" center">
                            Categoria M&eacute;dico
                        </td>
                        <td align=" center">
                            <select name='cate_medd' class='texto'>
                                <?php
                                $sql_cod_medd = "SELECT categoria_med from categorias_medicos  order by 1";
                                $sql_cod_medd_resp = mysql_query($sql_cod_medd);
                                while ($dat_cod_medd = mysql_fetch_array($sql_cod_medd_resp)) {
                                    $cod_cod_medd = $dat_cod_medd[0];
                                    if ($cod_cod_medd == $categoria_final_linea) {
                                        echo "<option value='$cod_cod_medd' selected>$cod_cod_medd</option>";
                                    } else {
                                        echo "<option value='$cod_cod_medd'>$cod_cod_medd</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td colspan="1">Categoria medico dada por el sistema <br />
                            <input type="text" readonly="readonly" value="<?php echo $categorizacion_medcio_sistemaa ?>"  />
                        </td>
                    </tr>
                    <tr>
                        <th colspan="5" class="texto"><br>Asignar Visitador<br>&nbsp;</th>
                    </tr>
                    <tr>
                        <td align="center" colspan="2">
                            <strong>Visitador</strong>
                        </td>
                        <td align="center" colspan="2">
                            <select name='visitadorr' class='texto'>
                                <?php
                                $sql_visitador = "SELECT codigo_funcionario, CONCAT(nombres,' ',paterno,' ',materno) as nombre  from funcionarios where cod_ciudad = $cod_ciudad and estado = 1 and cod_cargo = 1011 order by 2";
                                $sql_visitador_resp = mysql_query($sql_visitador);
                                while ($dat_visitador = mysql_fetch_array($sql_visitador_resp)) {
                                    $cod_visitador = $dat_visitador[0];
                                    $nombre_visitador = $dat_visitador[1];
                                    if ($cod_visitador == $codigo_final_visitador) {
                                        echo "<option value='$cod_visitador' selected>$nombre_visitador</option>";
                                    } else {
                                        echo "<option value='$cod_visitador'>$nombre_visitador</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
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
                <?php
                $sql_rf = mysql_query("SELECT * from farmacias_referencia_medico where cod_med = $cod_med ORDER BY id");
                while ($row_fr = mysql_fetch_assoc($sql_rf)) {
                    $nombre_farmacia .= $row_fr['nombre_farmacia'] . "@";
                    $direccion_farmacia .= $row_fr['direccion_farmacia'] . "@";
                }
                $nombre_far_final = explode('@', $nombre_farmacia);
                $direccion_far_final = explode('@', $direccion_farmacia);
                ?>
                <table width="99%" border="1" cellpadding="0" id="table-principal">
                    <tr id="1" class="row-style">
                        <td class="position" style="text-align: center; font-weight: bold" width="4%">1</td>
                        <td class="cargar_categoria" width="46%">
                            <select class="name_farm" name="name_farm_1" id="name_farm_1" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                                <option value="vacio">Selecionar una opcion</option>
                                <?php 
                                $query_farmacias = "SELECT nombre, cat_ventaetica from fclientes where cod_ciudad = $cod_ciudadFar order by nombre asc";
                                $resp_query_farmacias = mysql_query($query_farmacias);
                                while ($row = mysql_fetch_assoc($resp_query_farmacias)) {
                                    if ($row['nombre'] == $nombre_far_final[0]): 
                                        ?>
                                    <option value="<?php echo $nombre_far_final[0] ?>" selected="selected"><?php echo $nombre_far_final[0] ?></option>
                                    <?php 
                                    else: 
                                        ?>
                                    <option value="<?php echo $row['nombre'] ?>"><?php echo $row['nombre'] ?></option>
                                    <?php 
                                    endif; 
                                } 
                                ?>
                            </select>
                        </td>
                        <td width="46%">
                            <input type="text" class="dir_farm" name="dir_farm_1" id="dir_farm_1" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold " readonly="readonly" value="<?php echo $direccion_far_final[0] ?>" />
                        </td>
                    </tr>
                    <tr id="2" class="row-style">
                        <td class="position" style="text-align: center; font-weight: bold" width="4%">2</td>
                        <td class="cargar_categoria" width="46%">
                            <select class="name_farm" name="name_farm_2" id="name_farm_2" style="width: 100%; border: none; background: #F8E8DB; margin: 5px 0; ">
                                <option value="vacio">Selecionar una opcion</option>
                                <?php 
                                $query_farmacias = "SELECT nombre, cat_ventaetica from fclientes where cod_ciudad = $cod_ciudadFar order by nombre asc"; 
                                ?>
                                <?php 
                                $resp_query_farmacias = mysql_query($query_farmacias); 
                                while ($row = mysql_fetch_assoc($resp_query_farmacias)) { 
                                    if ($row['nombre'] == $nombre_far_final[1]): 
                                        ?>
                                    <option value="<?php echo $nombre_far_final[1] ?>" selected="selected"><?php echo $nombre_far_final[1] ?></option>
                                    <?php 
                                    else: 
                                        ?>
                                    <option value="<?php echo $row['nombre'] ?>"><?php echo $row['nombre'] ?></option>
                                    <?php 
                                    endif; 
                                } 
                                ?>
                            </select>
                        </td>
                        <td width="46%">
                            <input type="text" class="dir_farm" name="dir_farm_2" id="dir_farm_2" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold " readonly="readonly" value="<?php echo $direccion_far_final[1] ?>" />
                        </td>
                    </tr>
                    <tr id="3" class="row-style">
                        <td class="position" style="text-align: center; font-weight: bold" width="4%">3</td>
                        <td class="cargar_categoria" width="46%">
                            <select class="name_farm" name="name_farm_3" id="name_farm_3" style="width: 100%;border: none; background: #F8E8DB; margin: 5px 0; ">
                                <option value="vacio">Selecionar una opcion</option>
                                <?php 
                                $query_farmacias = "SELECT nombre, cat_ventaetica from fclientes where cod_ciudad = $cod_ciudadFar order by nombre asc"; 
                                $resp_query_farmacias = mysql_query($query_farmacias); 
                                while ($row = mysql_fetch_assoc($resp_query_farmacias)) { 
                                    if ($row['nombre'] == $nombre_far_final[2]): 
                                        ?>
                                    <option value="<?php echo $nombre_far_final[2] ?>" selected="selected"><?php echo $nombre_far_final[2] ?></option>
                                    <?php 
                                    else: 
                                        ?>
                                    <option value="<?php echo $row['nombre'] ?>"><?php echo $row['nombre'] ?></option>
                                    <?php
                                    endif; 
                                } 
                                ?>
                            </select>
                        </td>
                        <td width="46%">
                            <input type="text" class="dir_farm" name="dir_farm_3" id="dir_farm_3" style="width: 100%; border: none; margin: 5px 0; padding-left: 10px; font-weight: bold " readonly="readonly" value="<?php echo $direccion_far_final[2] ?>" />
                        </td>
                    </tr>
                </table>
                <?php
                $sql_datos_generales = mysql_query("SELECT * from categorizacion_medico where cod_med = $cod_med");
                while ($row_dg1 = mysql_fetch_array($sql_datos_generales)) {
                    $sexooo              = $row_dg1[2];
                    $edaddd              = $row_dg1[3];
                    $pacientesss         = $row_dg1[4];
                    $tiene_preferenciaaa = $row_dg1[5];
                    $nivelll             = $row_dg1[6];
                    $costooo             = $row_dg1[7];
                }
                ?>
                <table width="99%" border="0" cellpadding="5" style="margin-top: 10px">
                    <tr>
                        <th scope="row" style="width: 52%; text-align: left">Sexo</th>
                        <td>
                            <select name="sexo" id="sexo" style="width: 35%; background: #f8e8db; border: none">
                                <option value="Femenino" 
                                <?php
                                if ($sexooo == 'Femenino'): 
                                    echo "selected=selected";
                                endif
                                ?>>
                                Femenino</option>
                                <option value="Masculino" 
                                <?php
                                if ($sexooo == 'Masculino'): 
                                    echo "selected=selected";
                                endif
                                ?>>
                                Masculino</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="width: 52%; text-align: left">Edad</th>
                        <td>
                            <select name="edad" id="edad" style="width: 40%; background: #f8e8db; border: none">
                                <option value="1" <?php if ($edaddd == 1): echo "selected=selected";
                                endif ?>>Menor igual a 30 a&ntilde;os</option>
                                <option value="2" <?php if ($edaddd == 2): echo "selected=selected";
                                endif ?>>Mayor de 31 y menor igual a 50 a&ntilde;os</option>
                                <option value="3" <?php if ($edaddd == 3): echo "selected=selected";
                                endif ?>>Mayor de 51 y menor igual a 60 a&ntilde;os</option>
                                <option value="4" <?php if ($edaddd == 4): echo "selected=selected";
                                endif ?>>Mayor de 60 a&ntilde;os </option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="text-align: left">Nro. de pacientes d&iacute;a en el lugar de visita</th>
                        <td><input type="text" name="paci" id="paci" style="width: 35%; border: none; background: #F8E8DB" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" value="<?php echo $pacientesss ?>"  /></td>
                    </tr>
                    <tr>
                        <th scope="row"style="text-align: left">Tiene preferencia prescriptiva por productos de marca</th>
                        <td>
                            <select name="prescriptiva" id="prescriptiva" style="width: 35%; background: #f8e8db; border: none">
                                <option value="Alta" <?php if ($tiene_preferenciaaa == 'Alta'): echo "selected=selected";
                                endif
                                ?>>Alta</option>
                                <option value="Media" <?php if ($tiene_preferenciaaa == 'Media'): echo "selected=selected";
                                endif
                                ?> >Media</option>
                                <option value="Baja" <?php
                                if ($tiene_preferenciaaa == 'Baja'): echo "selected=selected";
                                endif
                                ?>>Baja</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"style="text-align: left">Nivel Sociecon&oacute;mico de los Pacientes</th>
                        <td>
                            <select name="nivel" id="nivel" style="width: 35%;background: #f8e8db; border: none">
                                <option value="Alta" <?php
                                if ($nivelll == 'Alta'): echo "selected=selected";
                                endif
                                ?>>Alta</option>
                                <option value="Media" <?php
                                if ($nivelll == 'Media'): echo "selected=selected";
                                endif
                                ?>>Media</option>
                                <option value="Baja" <?php
                                if ($nivelll == 'Baja'): echo "selected=selected";
                                endif
                                ?>>Baja</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="text-align: left">Costo consulta (Bs.)</th>
                        <td><input type="text" name="consulta" id="consulta" style="width: 35%; border: none; background: #F8E8DB" onkeyup="var no_digito = /\D/g;this.value = this.value.replace(no_digito , '');" value="<?php echo $costooo ?>" /></td>
                    </tr>
                </table>
            </div>
        </div>
        <input type='hidden' name='cod_med' value='<?php echo "$cod_med"; ?>'>
        <table align='center'>
            <tr>
                <td>
                    <a href='medicos_solicitados_lista_gerencia.php'><img  border='0'src='imagenes/back.png' width='40'></a>
                </td>
            </tr>
        </table>
        <table class="texto" border="0" align="center">
            <tr>
                <td align='center'>
                    <input type="button" class="boton" value="Aprobar" onClick='validar(this.form)'>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>