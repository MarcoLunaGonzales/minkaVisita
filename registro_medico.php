<script type="text/javascript" language="Javascript">
function envia_select(form) {
    form.submit();
    return(true);
}
function escogerInstitucion(nroDireccion, cod_ciudad) {
    var rpt_nro=nroDireccion;
    var rpt_territorio=cod_ciudad;
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
    if(f.ap_paterno.value=='' && f.ap_materno.value=='')
    {   alert('Al menos debe escribir un apellido del medico.');
        if(f.ap_paterno.value=='') {f.ap_paterno.focus();}
        return(false);
    }
    if(f.nombres.value=='')
    {   alert('El campo Nombres esta vacio');
        f.nombres.focus();
        return(false);
    }
    if(f.direccion1.value=='')
    {   alert('El campo Consultorio I esta vacio');
        f.direccion1.focus();
        return(false);
    }
    if(f.espe1.value=='')
    {   alert('Debe seleccionar al menos una especialidad.');
        return(false);
    }
    f.action='guardar_medico.php';
    f.submit();
}
</script>
<?php
/**
 * Desarrollado por Datanet.
 * 
 * @autor : Marco Antonio Luna Gonzales
 * @copyright 2005
 */
require("estilos_administracion.inc");
require("conexion.inc");

?>
<table width="100%"  border="0" cellspacing="0" class="textotit">
    <tr><td align='center'><div align="center">REGISTRO DE M&Eacute;DICOS</div><br></td></tr>
</table>
<form action="registro_medico.php" method="post" name="form1">
    <table border="1" align="center" cellspacing="0" class="texto">
        <tr><th colspan="5" class="texto"><br>Datos Obligatorios<br>&nbsp;</th></tr>
        <tr>
            <td align='center'><b>Apellido Paterno(*)</b><br><input name='ap_paterno' type='text' class='texto'></td>
            <td align='center'><b>Apellido Materno(*)</b><br><input name='ap_materno' type='text' class='texto'></td>
            <td align='center'><b>Nombres(*)</b><br><input name='nombres' type='text' class='texto'></td>
            <td align='center' bgcolor='#ffffff'>
                <b>Fecha de Nacimiento</b><br>
                <INPUT  type='text' class='texto' id='exafinicial' size='10' name='exafinicial'>
                <IMG id='imagenFecha' src='imagenes/fecha.bmp'>
                <DLCALENDAR tool_tip='Seleccione la Fecha'
                daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;'
                navbar_style='background-color: 7992B7; color:ffffff;'
                input_element_id='exafinicial'
                click_element_id='imagenFecha'></DLCALENDAR>
            </td>
            <td align='center'><b>Tel&eacute;fono(*)</b><br><input name='telefono' type='text' class='texto'><br></td>
        </tr>
        <tr>
            <td align='center'>
                <b>Agencia(*)</b><br>
                <select name='cod_ciudad' class='texto' onChange='envia_select(this.form)'>
<?php
 $sql = "select cod_ciudad,descripcion from ciudades where cod_ciudad=$cod_ciudad";
 $resp = mysql_query($sql);
 $dat = mysql_fetch_array($resp);
 //while () {
 $cod_ciudad = $dat[0];
 $nombre_ciudad = $dat[1];
 echo "<option value='$cod_ciudad'>$nombre_ciudad</option>";
 //}
?>
                </select>
            </td>
            <td align='center'>
                <b>Distrito</b><br>
                <select name='distrito1' class='texto' onChange='cargarAjaxZona1(this.form)'>
                    <option value='0'>( ninguno )</option>
<?php
 $sql = "select * from distritos where cod_ciudad='$cod_ciudad'";
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
            <td align='center'>
                <b>Zona</b><br>
                <div id='zona1'></div>
            </td>
            <td align='center' colspan="2">
                <b>Direcci&#243;n</b><br>
<?php
 //echo "<a href='javascript:escogerInstitucion(1,$cod_ciudad);'>Seleccionar</a> ";
 echo "<select name='direccionvial1' class='texto'>";
 echo "<option value=''>( ninguno )</option>";
 echo "<option value='AVENIDA'>AVENIDA</option>";
 echo "<option value='CALLE'>CALLE</option>";
 echo "<option value='PLAZA'>PLAZA</option>";
 echo "</select> ";
?>
                <input name='direccion1' type='text' class='texto' size='40'>
                N&#176;<input name='num_casa1' type='text' class='texto' size='5'>
            </td>
        </tr>
        <tr>
            <td align='center'>
                <b>Tipo Consultorio</b><br>
                <select name='tipoConsultorio1' class='texto'>
<?php
 $sqlTipoConsultorio = "select cod_tipo_consultorio, nombre_tipo_consultorio from tipos_consultorio order by 2 asc";
 $respTipoConsultorio = mysql_query($sqlTipoConsultorio);
 while ($datTipoConsultorio = mysql_fetch_array($respTipoConsultorio)) {
     $codTipoConsultorio = $datTipoConsultorio[0];
     $nombreTipoConsultorio = $datTipoConsultorio[1];
     echo "<option value='$codTipoConsultorio'>$nombreTipoConsultorio</option>";
 }
?>
                </select>
            </td>
            <td align='center' colspan="2">
                <b>Especialidad</b><br>
                <select name='espe1' class='texto'>
                    <option value=''></option>
<?php
 $sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
 $resp = mysql_query($sql);
 while ($dat = mysql_fetch_array($resp)) {
     $p_cod_espe = $dat[0];
     $p_desc_espe = $dat[1];
     echo "<option value='$p_cod_espe'>$p_desc_espe</option>";
 }
?>
                </select> Especialidad Real
            </td>
            <td align='center' colspan="2">&nbsp;</td>
        </tr>
        <!-- xxxxxxxx -->
        <tr><th colspan="5" class="texto"><br>Datos Complementarios<br>&nbsp;</th></tr>
        <!-- xxxxxxxx -->
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
     echo "<option value='$codCatCloseUp'>$desCatCloseUp</option>";
 }
?>
                </select>
            </td>
            <td align='center'>
                <b>Codigo Close UP</b><br>
                <input name='categoriacloseup' type='text' class='texto'>
            </td>
            <td align='center'>
                <b>Correo Electronico</b><br><input name='email' type='text' class='texto'><br>
            </td>
            <td align='center'>
                <b>Nombre Secretaria</b><br>
                <input name='nombre_secretaria' type='text' class='texto'>
            </td>
            <td align='center'><b>Tel&eacute;fono Celular</b><br><input name='telf_celular' type='text' class='texto'><br></td>
        </tr>
        <!-- xxxxxxxx -->
        <tr><th colspan="5" class="texto"><br>Datos Opcionales<br>&nbsp;</th></tr>
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
 $sql_perfil = "select cod_perfil_psicografico, nombre_perfil_psicografico from perfil_psicografico order by 2";
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
                <b>Hobbie</b><br>Hobbie 1:<input name='hobbie' type='text' class='texto'><br>Hobbie 2:<input name='hobbie2' type='text' class='texto'>
            </td>
            <td align='center' colspan="2">&nbsp;</td>
        </tr>
        <!-- xxxxxxxx -->
    </table><br>
    <!--<center>-->
        <!--<table border="1" class="texto" cellspacing="0">-->
<!--            <tr>
                <td align='center'>
                    <select name='tipoConsultorio2' class='texto'>
<?php
// $sqlTipoConsultorio = "select cod_tipo_consultorio, nombre_tipo_consultorio from tipos_consultorio order by 2 asc";
// $respTipoConsultorio = mysql_query($sqlTipoConsultorio);
// while ($datTipoConsultorio = mysql_fetch_array($respTipoConsultorio)) {
//     $codTipoConsultorio = $datTipoConsultorio[0];
//     $nombreTipoConsultorio = $datTipoConsultorio[1];
//     echo "<option value='$codTipoConsultorio'>$nombreTipoConsultorio</option>";
// }
?> 
                    </select>
                </td>
                <td align='center'>
<?php
// echo "<a href='javascript:escogerInstitucion(2,$cod_ciudad);'>Seleccionar</a> ";
// echo "<select name='direccionvial2' class='texto'>";
// echo "<option value=''>( ninguno )</option>";
// echo "<option value='AVENIDA'>AVENIDA</option>";
// echo "<option value='CALLE'>CALLE</option>";
// echo "<option value='PLAZA'>PLAZA</option>";
// echo "</select> ";
?>
                    <input name='direccion2' type='text' class='texto' size='40'>
                    N&#176;<input name='num_casa2' type='text' class='texto' size='5'>
                </td>
                <td align='center'>
                    <select name='distrito2' class='texto' onChange=cargarAjaxZona2(this.form)>
                        <option value='0'>( ninguno )</option>
<?php
// $sql = "select * from distritos where cod_ciudad='$cod_ciudad'";
// $resp = mysql_query($sql);
// while ($dat = mysql_fetch_array($resp)) {
//     $p_ciudad = $dat[0];
//     $p_dist = $dat[1];
//     $p_desc = $dat[2];
//     echo "<option value='$p_dist'>$p_desc</option>";
// }
?>
                    </select>
                </td>
                <td align='center'><div id='zona2'></div></td>
            </tr>-->
<!--            <tr>
                <td align='center'>
                    <select name='tipoConsultorio3' class='texto'>
<?php
// $sqlTipoConsultorio = "select cod_tipo_consultorio, nombre_tipo_consultorio from tipos_consultorio order by 2 asc";
// $respTipoConsultorio = mysql_query($sqlTipoConsultorio);
// while ($datTipoConsultorio = mysql_fetch_array($respTipoConsultorio)) {
//     $codTipoConsultorio = $datTipoConsultorio[0];
//     $nombreTipoConsultorio = $datTipoConsultorio[1];
//     echo "<option value='$codTipoConsultorio'>$nombreTipoConsultorio</option>";
// }
?>
                    </select>
                </td>
                <td align='center'>
<?php
// echo "<a href='javascript:escogerInstitucion(3,$cod_ciudad);'>Seleccionar</a> ";
// echo "<select name='direccionvial3' class='texto'>";
// echo "<option value=''>( ninguno )</option>";
// echo "<option value='AVENIDA'>AVENIDA</option>";
// echo "<option value='CALLE'>CALLE</option>";
// echo "<option value='PLAZA'>PLAZA</option>";
// echo "</select> ";
?>
                    <input name='direccion3' type='text' class='texto' size='40'>
                    N&#176;<input name='num_casa3' type='text' class='texto' size='5'>
                </td>
                <td align='center'>
                    <select name='distrito3' class='texto' onChange=cargarAjaxZona3(this.form)>
                        <option value='0'>( ninguno )</option>
<?php
// $sql = "select * from distritos where cod_ciudad='$cod_ciudad'";
// $resp = mysql_query($sql);
// while ($dat = mysql_fetch_array($resp)) {
//     $p_ciudad = $dat[0];
//     $p_dist = $dat[1];
//     $p_desc = $dat[2];
//     echo "<option value='$p_dist'>$p_desc</option>";
// }
?> 
                    </select>
                </td>
                <td align='center'><div id='zona3'></div></td>
            </tr>-->
        <!--</table><br>-->
        <!--<table border="1" class="texto" cellspacing="0">-->
            <!--<tr><th class="texto" colspan="2">Especialidades</th></tr>-->
<!--            <tr>
                <td align='center'>
                    <select name='espe2' class='texto'>
                        <option value=''></option>
<?php
// $sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
// $resp = mysql_query($sql);
// while ($dat = mysql_fetch_array($resp)) {
//     $p_cod_espe = $dat[0];
//     $p_desc_espe = $dat[1];
//     echo "<option value='$p_cod_espe'>$p_desc_espe</option>";
// }
?>
                    </select>
                </td>
                <td>Sub-Especialidad</td>
            </tr>-->
<!--            <tr>
                <td align='center'>
                    <select name='espe3' class='texto'>
                        <option value=''></option>
<?php
// $sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
// $resp = mysql_query($sql);
// while ($dat = mysql_fetch_array($resp)) {
//     $p_cod_espe = $dat[0];
//     $p_desc_espe = $dat[1];
//     echo "<option value='$p_cod_espe'>$p_desc_espe</option>";
// }
?>
                    </select>
                </td>
                <td>Visitado Como</td>
            </tr>-->
        <!--</table>-->
    <!--</center><br>-->
<?php
 echo "<table align='center'><tr><td><a href='navegador_medicos2.php?cod_ciudad=$cod_ciudad'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
?>
    <table class="texto" border="0" align="center"><tr><td align='center'><input type="button" class="boton" value="Registrar" onClick='validar(this.form)'></td></tr></table>
</form>
<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>
</body>
</html>
