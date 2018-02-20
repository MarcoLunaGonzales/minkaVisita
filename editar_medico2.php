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
function validar(f)
{
    if(f.ap_paterno.value=='' && f.ap_materno.value=='')
    {   alert('Al menos debe escribir un apellido del m�dico.');
        if(f.ap_paterno.value==''){	f.ap_paterno.focus();}
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
    f.action='guardar_editarmedico.php';
    f.submit();
}
function cargarZonas(f, zona1, zona2, zona3){
    /*alert(f);
    alert(zona1);
    alert(zona2);
    alert(zona3);*/
    cargarAjaxZona1(f, zona1);
    cargarAjaxZona2(f, zona2);
    cargarAjaxZona3(f, zona3);
}
</script>
<?php

 require("estilos_administracion.inc");
 require("conexion.inc");
 $cod_med = $_GET['j_cod_med'];
 
 $sql = "SELECT cod_med, ap_pat_med, ap_mat_med, nom_med, fecha_nac_med, telf_med, telf_celular_med, email_med,
         hobbie_med, hobbie_med2, estado_civil_med, nombre_secre_med, perfil_psicografico_med, cod_ciudad, cod_catcloseup, cod_closeup, estado_registro 
		 FROM medicos WHERE cod_med='$cod_med'";
 
 $resp = mysql_query($sql);
 while ($dat = mysql_fetch_array($resp)) {
     $cod_med = $dat[0];
     $ap_pat_med = $dat[1];
     $ap_mat_med = $dat[2];
     $nom_med = $dat[3];
     $fecha_nac_med = $dat[4];
     $telf_med = $dat[5];
     $telf_celular_med = $dat[6];
     $email_med = $dat[7];
     $hobbie_med  = $dat[8];
     $hobbie_med2 = $dat[9];
     $estado_civil_med = $dat[10];
     $nombre_secre_med = $dat[11];
     $perfil_psicografico = $dat[12];
     $cod_ciudad = $dat[13];
     //
     $codigocategoriacloseup = $dat[14];
     $categoriacloseup       = $dat[15];
     //
     $vec=split("-", $fecha_nac_med);
     $fecha_nac_med=$vec[2]."/".$vec[1]."/".$vec[0];
     //
	 $estadoMedico=$dat[16];
 }
 // ahora vemos las direcciones
 $sqlDirecciones = "
     select d.cod_med, d.cod_zona, d.direccionvial, d.direccion, d.num_casa, d.numero_direccion, d.cod_tipo_consultorio, z.cod_dist from
     direcciones_medicos d, zonas z where d.cod_med='$cod_med' and d.cod_zona=z.cod_zona";
 $respDirecciones = mysql_query($sqlDirecciones);
 //echo $sqlDirecciones;
 $codZona1=0;
 $codZona2=0;
 $codZona3=0;
 while ($datDirecciones = mysql_fetch_array($respDirecciones)) {
     $cod_zona = $datDirecciones[1];
     $direccionvial = $datDirecciones[2];
     $direccion = $datDirecciones[3];
     $num_casa = $datDirecciones[4];
     $nro_direccion = $datDirecciones[5];
     $cod_tipo_consultorio = $datDirecciones[6];
     $cod_dist = $datDirecciones[7];
     if ($nro_direccion == 1) {
         $direccionvial1 = $direccionvial;
         $direccion1 = $direccion;
         $num_casa1 = $num_casa;
         $codTipoConsultorio1 = $cod_tipo_consultorio;
         $codZona1 = $cod_zona;
         $codDistrito1 = $cod_dist;
     }
     if ($nro_direccion == 2) {
         $direccionvial2 = $direccionvial;
         $direccion2 = $direccion;
         $num_casa2 = $num_casa;
         $codTipoConsultorio2 = $cod_tipo_consultorio;
         $codZona2 = $cod_zona;
         $codDistrito2 = $cod_dist;
     }
     if ($nro_direccion == 3) {
         $direccionvial3 = $direccionvial;
         $direccion3 = $direccion;
         $num_casa3 = $num_casa;
         $codTipoConsultorio3 = $cod_tipo_consultorio;
         $codZona3 = $cod_zona;
         $codDistrito3 = $cod_dist;
     }
 }
 //echo $codZona1." ".$codZona2." ".$codZona3;

 $sqlEspe="select cod_med, cod_especialidad, cod_tipo_especialidad from especialidades_medicos where cod_med='$cod_med'";
 $respEspe=mysql_query($sqlEspe);
 $espe1="";
 $espe2="";
 $espe3="";
 $espe4="";
 $espe5="";
 while($datEspe=mysql_fetch_array($respEspe)){
     $codEspe=$datEspe[1];
     $tipoEspe=$datEspe[2];
     if($tipoEspe==1){
         $espe1=$codEspe;
     }
     if($tipoEspe==2){
         $espe2=$codEspe;
     }
     if($tipoEspe==3){
         if($espe3==""){
             $espe3=$codEspe;
         }
         else{
             if($espe3!="" && $espe4==""){
                 $espe4=$codEspe;
             }else{
                 if($espe4!="" && $espe5==""){
                     $espe5=$codEspe;
                 }
             }
         }
     }
 }
 /*
<body onload="cargarZonas(form1, <?php echo "$codZona1"; ?>, <?php echo "$codZona2"; ?>, <?php echo "$codZona3"; ?>);">
 */
?>
<html>
<body onload="">
<table width="100%"  border="0" cellspacing="0" class="textotit"><tr><td align='center'><div align="center">EDITAR MEDICO</div><br></td></tr></table>
<form action="registro_medico.php" method="post" name="form1">
    <table border="1" align="center" cellspacing="0" class="texto">
        <tr><th colspan="5" class="texto"><br>Datos Obligatorios<br>&nbsp;</th></tr>
        <tr>
            <td align='center'><b>Apellido Paterno(*)</b><br><input name='ap_paterno' type='text' class='texto' value='<?php echo "$ap_pat_med"; ?>'></td>
            <td align='center'><b>Apellido Materno(*)</b><br><input name='ap_materno' type='text' class='texto' value='<?php echo "$ap_mat_med"; ?>'></td>
            <td align='center'><b>Nombres(*)</b><br><input name='nombres' type='text' class='texto' value='<?php echo "$nom_med"; ?>'></td>
            <td align='center' bgcolor='#ffffff'>
                <b>Fecha de Nacimiento</b><br>
                <INPUT  type='text' class='texto' id='exafinicial' size='10' name='exafinicial' value='<?php echo "$fecha_nac_med"; ?>'>
                <IMG id='imagenFecha' src='imagenes/fecha.bmp'>
                <DLCALENDAR tool_tip='Seleccione la Fecha'
                daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;'
                navbar_style='background-color: 7992B7; color:ffffff;'
                input_element_id='exafinicial'
                click_element_id='imagenFecha'></DLCALENDAR>
            </td>
            <td align='center'><b>Tel&eacute;fono(*)</b><br><input name='telefono' type='text' class='texto' value='<?php echo "$telf_med"; ?>'><br></td>
        </tr>
        <tr>
            <td align='center'>
                <b>Agencia(*)</b><br>
<?php
 $sql = "select cod_ciudad,descripcion from ciudades where cod_ciudad=$cod_ciudad";
 $resp = mysql_query($sql);
 $dat = mysql_fetch_array($resp);
 $cod_ciudad = $dat[0];
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
 $sql = "select * from distritos where cod_ciudad='$cod_ciudad'";
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
 $consulta="select cod_zona, zona from zonas where cod_dist='$codDistrito1'";
 $rs=mysql_query($consulta);
 echo "<select name='zona1' class='texto'>";
 echo "<option value='0'>( ninguno )</option>";
 while($reg=mysql_fetch_array($rs)) {
     $codZonaAux=$reg[0];
     $nomZonaAux=$reg[1];
     if($codZonaAux==$codZona1) {
         echo "<option value='$codZonaAux' selected>$nomZonaAux</option>";
     } else {
         echo "<option value='$codZonaAux'>$nomZonaAux</option>";
     }
 }
 echo "</select>";
?>
                </div>
            </td>
            <td align='center' colspan="2">
                <b>Direcci&#243;n</b><br>
                <select name='direccionvial1' class='texto'>
<?php
 echo "<option value=''>( ninguno )</option>";
 if($direccionvial1=="AVENIDA")
    {echo "<option value='AVENIDA' selected>AVENIDA</option>";
    }
 else
    {echo "<option value='AVENIDA'>AVENIDA</option>";
    }
 if($direccionvial1=="CALLE")
    {echo "<option value='CALLE' selected>CALLE</option>";
    }
 else
    {echo "<option value='CALLE'>CALLE</option>";
    }
 if($direccionvial1=="PLAZA")
    {echo "<option value='PLAZA' selected>PLAZA</option>";
    }
 else
    {echo "<option value='PLAZA'>PLAZA</option>";
    }
?>
                </select>
                <input name='direccion1' type='text' class='texto' size='40' value='<?php echo "$direccion1"; ?>'>
                N&#176;<input name='num_casa1' type='text' class='texto' size='5' value='<?php echo "$num_casa1"; ?>'>
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
     if ($codTipoConsultorio == $codTipoConsultorio1) {
         echo "<option value='$codTipoConsultorio' selected>$nombreTipoConsultorio</option>";
     } else {
         echo "<option value='$codTipoConsultorio'>$nombreTipoConsultorio</option>";
     }
 }
?>
                </select>
            </td>
            <td align='center' colspan="2">
                <b>Especialidad 1</b><br>
                <select name='espe1' class='texto'>
                    <option value=''></option>
<?php
 $sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
 $resp = mysql_query($sql);
 while ($dat = mysql_fetch_array($resp)) {
     $p_cod_espe = $dat[0];
     $p_desc_espe = $dat[1];
     echo "<option value='$p_cod_espe' ";
     if($p_cod_espe==$espe1){
         echo "selected";
     }
     echo ">$p_desc_espe</option>";
 }
?>
                </select>
            </td>
			
			            <td align='center' colspan="2">
                <b>Especialidad 2</b><br>
                <select name='espe2' class='texto'>
                    <option value=''></option>
<?php
 $sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
 $resp = mysql_query($sql);
 while ($dat = mysql_fetch_array($resp)) {
     $p_cod_espe = $dat[0];
     $p_desc_espe = $dat[1];
     echo "<option value='$p_cod_espe' ";
     if($p_cod_espe==$espe2){
         echo "selected";
     }
     echo ">$p_desc_espe</option>";
 }
?>
                </select>
            </td>
			
            <td align='center' colspan="1">&nbsp;</td>
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
     echo "<option value='$codCatCloseUp' ";
     if($codCatCloseUp==$codigocategoriacloseup) {
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
            <td align='center'>
                <b>Correo Electronico</b><br><input name='email' type='text' class='texto' value='<?php echo "$email_med"; ?>'><br>
            </td>
            <td align='center'>
                <b>Nombre Secretaria</b><br>
                <input name='nombre_secretaria' type='text' class='texto' value='<?php echo"$nombre_secre_med"; ?>'>
            </td>
            <td align='center'><b>Tel&eacute;fono Celular</b><br><input name='telf_celular' type='text' class='texto' value='<?php echo "$telf_celular_med"; ?>'><br></td>
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
     if($cod_estadocivil==$estado_civil_med) {
         echo "<option value='$cod_estadocivil' selected>$nom_estadocivil $estado_civil_med</option>";
     } else {
         echo "<option value='$cod_estadocivil'>$nom_estadocivil $estado_civil_med</option>";
     }
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
 while ($dat_perfil = mysql_fetch_array($resp_perfil)) {
     $cod_perfil = $dat_perfil[0];
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
                <b>Hobbie</b><br>Hobbie 1:<input name='hobbie' type='text' class='texto' value='<?php echo "$hobbie_med"; ?>'><br>Hobbie 2:<input name='hobbie2' type='text' class='texto' value='<?php echo "$hobbie_med2"; ?>'>
            </td>
			
			<td colspan="2"><b>Estado Medico</b>
			<select class='texto' name='estadoMedico'>
			<?php
				$sqlEstado="select id, estado from estado_medico_registro";
				$respEstado=mysql_query($sqlEstado);
				while($datEstado=mysql_fetch_array($respEstado)){
					$idEstado=$datEstado[0];
					$estadoE=$datEstado[1];
					if($idEstado==$estadoMedico){
			?>
					<option value="<?php echo $idEstado;?>" selected><?php echo $estadoE;?></option>
			<?php
					}else{
			?>
					<option value="<?php echo $idEstado;?>"><?php echo $estadoE;?></option>
			<?php		
					}
				}
			?>
			</select>
			</td>
			
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
//     if($codTipoConsultorio==$codTipoConsultorio2){
//         echo "<option value='$codTipoConsultorio' selected>$nombreTipoConsultorio</option>";
//     } else {
//         echo "<option value='$codTipoConsultorio'>$nombreTipoConsultorio</option>";
//     }
// }
?> 
                    </select>
                </td>
                <td align='center'>
                    <select name='direccionvial2' class='texto'>
<?php
// echo "<option value=''>( ninguno )</option>";
// if($direccionvial2=="AVENIDA")
//    {echo "<option value='AVENIDA' selected>AVENIDA</option>";
//    }
// else
//    {echo "<option value='AVENIDA'>AVENIDA</option>";
//    }
// if($direccionvial2=="CALLE")
//    {echo "<option value='CALLE' selected>CALLE</option>";
//    }
// else
//    {echo "<option value='CALLE'>CALLE</option>";
//    }
// if($direccionvial2=="PLAZA")
//    {echo "<option value='PLAZA' selected>PLAZA</option>";
//    }
// else
//    {echo "<option value='PLAZA'>PLAZA</option>";
//    }
?>
                    </select>
                    <input name='direccion2' type='text' class='texto' size='40' value='<?php /*echo "$direccion2";*/ ?>'>
                    N&#176;<input name='num_casa2' type='text' class='texto' size='5' value='<?php /*echo "$num_casa2";*/ ?>'>
                </td>
                <td align='center'>
                    <select name='distrito2' class='texto' onChange="cargarAjaxZona2(this.form,0)">
                        <option value='0'>( ninguno )</option>
<?php
// $sql = "select * from distritos where cod_ciudad='$cod_ciudad'";
// $resp = mysql_query($sql);
// while ($dat = mysql_fetch_array($resp)) {
//     $p_ciudad = $dat[0];
//     $p_dist = $dat[1];
//     $p_desc = $dat[2];
//     echo "<option value='$p_dist' ";
//     if($p_dist==$codDistrito2) {
//         echo "selected";
//     }
//     echo ">$p_desc</option>";
// }
?>
                    </select>
                </td>
                <td align='center'>
                    <div id='zona2'>
<?php
// $consulta="select cod_zona, zona from zonas where cod_dist='$codDistrito2'";
// $rs=mysql_query($consulta);
// echo "<select name='zona2' class='texto'>";
// echo "<option value='0'>( ninguno )</option>";
// while($reg=mysql_fetch_array($rs)) {
//     $codZonaAux=$reg[0];
//     $nomZonaAux=$reg[1];
//     if($codZonaAux==$codZona2) {
//         echo "<option value='$codZonaAux' selected>$nomZonaAux</option>";
//     } else {
//         echo "<option value='$codZonaAux'>$nomZonaAux</option>";
//     }
// }
// echo "</select>";
?>
                    </div>
                </td>
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
//     if($codTipoConsultorio==$codTipoConsultorio3){
//         echo "<option value='$codTipoConsultorio' selected>$nombreTipoConsultorio</option>";
//     } else {
//         echo "<option value='$codTipoConsultorio'>$nombreTipoConsultorio</option>";
//     }
// }
?> 
                    </select>
                </td>
                <td align='center'>
                    <select name='direccionvial3' class='texto'>
<?php
// echo "<option value=''>( ninguno )</option>";
// if($direccionvial3=="AVENIDA")
//    {echo "<option value='AVENIDA' selected>AVENIDA</option>";
//    }
// else
//    {echo "<option value='AVENIDA'>AVENIDA</option>";
//    }
// if($direccionvial3=="CALLE")
//    {echo "<option value='CALLE' selected>CALLE</option>";
//    }
// else
//    {echo "<option value='CALLE'>CALLE</option>";
//    }
// if($direccionvial3=="PLAZA")
//    {echo "<option value='PLAZA' selected>PLAZA</option>";
//    }
// else
//    {echo "<option value='PLAZA'>PLAZA</option>";
//    }
?>
                    </select>
                    <input name='direccion3' type='text' class='texto' size='40' value='<?php /*echo "$direccion3";*/ ?>'>
                    N&#176;<input name='num_casa3' type='text' class='texto' size='5' value='<?php /*echo "$num_casa3";*/ ?>'>
                </td>
                <td align='center'>
                    <select name='distrito3' class='texto' onChange=cargarAjaxZona3(this.form,0)>
                        <option value='0'>( ninguno )</option>
<?php
// $sql = "select * from distritos where cod_ciudad='$cod_ciudad'";
// $resp = mysql_query($sql);
// while ($dat = mysql_fetch_array($resp)) {
//     $p_ciudad = $dat[0];
//     $p_dist = $dat[1];
//     $p_desc = $dat[2];
//     echo "<option value='$p_dist' ";
//     if($p_dist==$codDistrito3){
//         echo "selected";
//     }
//     echo ">$p_desc</option>";
// }
?> 
                    </select>
                </td>
                <td align='center'>
                    <div id='zona3'>
<?php
// $consulta="select cod_zona, zona from zonas where cod_dist='$codDistrito3'";
// $rs=mysql_query($consulta);
// echo "<select name='zona3' class='texto'>";
// echo "<option value='0'>( ninguno )</option>";
// while($reg=mysql_fetch_array($rs)) {
//     $codZonaAux=$reg[0];
//     $nomZonaAux=$reg[1];
//     if($codZonaAux==$codZona3) {
//         echo "<option value='$codZonaAux' selected>$nomZonaAux</option>";
//     } else {
//         echo "<option value='$codZonaAux'>$nomZonaAux</option>";
//     }
// }
// echo "</select>";
?>
                    </div>
                </td>
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
//     echo "<option value='$p_cod_espe' ";
//     if($p_cod_espe==$espe2) {
//         echo "selected";
//     }
//     echo ">$p_desc_espe</option>";
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
//     echo "<option value='$p_cod_espe' ";
//     if($p_cod_espe==$espe3){
//         echo "selected";
//     }
//     echo ">$p_desc_espe</option>";
// }
?>
                    </select>
                </td>
                <td>Visitado Como</td>
            </tr>-->
<!--            <tr>
                <td align='center'>
                    <select name='espe4' class='texto'>
                        <option value=''></option>
<?php
// $sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
// $resp = mysql_query($sql);
// while ($dat = mysql_fetch_array($resp)) {
//     $p_cod_espe = $dat[0];
//     $p_desc_espe = $dat[1];
//     echo "<option value='$p_cod_espe' ";
//     if($p_cod_espe==$espe4) {
//         echo "selected";
//     }
//     echo ">$p_desc_espe</option>";
// }
?> 
                    </select>
                </td>
                <td>Visitado Como</td>
            </tr>-->
<!--            <tr>
                <td align='center'>
                    <select name='espe5' class='texto'>";
                        <option value=''></option>
<?php
// $sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
// $resp = mysql_query($sql);
// while ($dat = mysql_fetch_array($resp)) {
//     $p_cod_espe = $dat[0];
//     $p_desc_espe = $dat[1];
//     echo "<option value='$p_cod_espe' ";
//     if($p_cod_espe==$espe5) {
//         echo "selected";
//     }
//     echo ">$p_desc_espe</option>";
// }
?>
                    </select>
                </td>
                <td>Visitado Como</td>
            </tr>-->
        <!--</table>-->
    <!--</center><br>-->
    <input type='hidden' name='cod_med' value='<?php echo "$cod_med"; ?>'>
    <table align='center'><tr><td><a href='navegador_medicos2.php?cod_ciudad=<?php echo "$cod_ciudad"; ?>'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>
    <table class="texto" border="0" align="center"><tr><td align='center'><input type="button" class="boton" value="Registrar" onClick='validar(this.form)'></td></tr></table>
</form>
<script type='text/javascript' language='javascript' src='dlcalendar.js'></script>
</body>
</html>
