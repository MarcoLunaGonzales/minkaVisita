<?php
/**
 * Desarrollado por Datanet.
 * 
 * @autor : Marco Antonio Luna Gonzales
 * @copyright 2005
 */
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function nuevoAjax()
		{	var xmlhttp=false;
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
		function cargarAjaxZona1(f){
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
	 		ajax.send(null)
		}
		function cargarAjaxZona2(f){
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
	 		ajax.send(null)
		}
		function cargarAjaxZona3(f){
			var contenedor;
			var distrito=f.distrito3.value;
			contenedor = document.getElementById('zona3');
			ajax=nuevoAjax();
			ajax.open('GET', 'ajaxZona3.php?distrito='+distrito+'',true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
	 		ajax.send(null)
		}		
		function validar(f)
		{
			if(f.ap_paterno.value=='' && f.ap_materno.value=='')
			{	alert('Al menos debe escribir un apellido del Medico.');
				if(f.ap_paterno.value==''){	f.ap_paterno.focus();}
				return(false);
			}
			if(f.nombres.value=='')
			{	alert('El campo Nombres esta vacio');
				f.nombres.focus();
				return(false);
			}
			if(f.direccion1.value=='')
			{	alert('El campo Consultorio I esta vacio');
				f.direccion1.focus();
				return(false);
			}
			if(f.espe1.value=='')
			{	alert('Debe seleccionar al menos una especialidad.');
				return(false);
			}
			f.action='guardar_medico.php';
			f.submit();			
		}
		</script>";
require("estilos_administracion.inc");
require("conexion.inc");

?>
<table width="100%"  border="0" cellspacing="0" class="textotit">
  <tr>
    <td align='center'><div align="center">REGISTRO DE M&Eacute;DICOS </div></td>
  </tr>
</table>
<form action="registro_medico.php" method="post">
<table width="80%"  border="1" align="center" cellspacing="0" class="texto">
  <tr>
  	<th colspan="4" class="texto">Editar Datos Personales</th>
  </tr>
  <tr class="texto">
    <th width="22%">Apellido Paterno(*) </th>
    <th width="28%">Apellido Materno(*) </th>
    <th width="19%">Nombres(*)</th>
    <th width="31%">Fecha de Nacimiento</th>
    </tr>
  <tr>
    <td align='center'>
	<input name='ap_paterno' type='text' class='texto'>
</td>
    <td align='center'>
	<input name='ap_materno' type='text' class='texto'>
</td>
    <td align='center'>
	<input name='nombres' type='text' class='texto'>
</td>
	 <TD  align='center' bgcolor='#ffffff'>
	 <INPUT  type='text' class='texto' id='exafinicial' size='10' name='exafinicial'>
<IMG id='imagenFecha' src='imagenes/fecha.bmp'>
<DLCALENDAR tool_tip='Seleccione la Fecha'
daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' 
navbar_style='background-color: 7992B7; color:ffffff;' 
input_element_id='exafinicial' 
click_element_id='imagenFecha'></DLCALENDAR>
	</TD>
    </tr>
  <tr class="texto">
    <th>Tel&eacute;fono(*)</th>
    <th>Tel&eacute;fono Celular</th>
    <th>Correo Electronico</th>
    <th>Hobbie</th>
    </tr>
  <tr>
 	<td align='center'>
		<input name='telefono' type='text' class='texto'>
	</td>
    <td align='center'>
		<input name='telf_celular' type='text' class='texto'>
	</td>
    <td align='center'>
		<input name='email' type='text' class='texto'>
	</td>
    <td align='center'>
		<input name='hobbie' type='text' class='texto'>
	</td>
    </tr>
  	<tr class="texto">
    	<th>Estado Civil</th>
    	<th>Perfil Psicografico</th>
    	<th>Nombre Secretaria</th>
    	<th>Agencia(*)</th>
    </tr>
  	<tr>
    <td align='center'>
		<select name='estado_civil' class='texto'>
      		<option value='Soltero(a)'>Soltero(a)</option>
      		<option value='Casado(a)'>Casado(a)</option>
      		<option value='Divorciado(a)'>Divorciado(a)</option>
      		<option value='Viudo(a)'>Viudo(a)</option>
    	</select>
	</td>
    <td align='center'>
<?php
$sql_perfil = "select cod_perfil_psicografico, nombre_perfil_psicografico from perfil_psicografico order by 2";
$resp_perfil = mysql_query($sql_perfil);

?>
<select name='perfil_psicografico' class='texto'>
<?php
while ($dat_perfil = mysql_fetch_array($resp_perfil)) {
    $cod_perfil = $dat_perfil[0];
    $nombre_perfil = $dat_perfil[1];

    ?>
    <option value='<?=$cod_perfil?>'><?=$nombre_perfil?></option>";
<?php
} 

?> 
</select>
</td>
    <td align='center'>
		<input name='nombre_secretaria' type='text' class='texto'>
	</td>
	<td align='center'>
<?php
$sql = "select cod_ciudad,descripcion from ciudades where cod_ciudad=$cod_ciudad";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$cod_ciudad = $dat[0];
$nombre_ciudad = $dat[1];

?>
<select NAME='cod_ciudad' class='texto' onChange='envia_select(this.form)'>";
	<option value='<?=$cod_ciudad?>'><?=$nombre_ciudad?></option>
</select>
</td>
  </tr></table><br>
  <center><table BORDER="1" class="texto" WIDTH="80%" cellspacing="0">
  <tr>
    <th colspan="5" class="texto">Direcciones</div></th>
    </tr>
  	<tr class="texto">
		<th>Tipo Consultorio</th><th>Direccion</th><th>Distrito</th><th>Zona</th>
	</tr>
  <tr>
<?php
echo "<td align='center'>";
$sqlTipoConsultorio = "select cod_tipo_consultorio, nombre_tipo_consultorio from tipos_consultorio order by 2";
$respTipoConsultorio = mysql_query($sqlTipoConsultorio);

?>
<select name='tipoConsultorio1' class='texto'>
<?php
while ($datTipoConsultorio = mysql_fetch_array($respTipoConsultorio)) {
    $codTipoConsultorio = $datTipoConsultorio[0];
    $nombreTipoConsultorio = $datTipoConsultorio[1];

    ?>
	<option value='<?=$codTipoConsultorio?>'><?=$nombreTipoConsultorio?></option>
<?php
} 

?> 
</select>
</td>
<td align='center'><input name='direccion1' type='text' class='texto' size='50'></td>
<?php
$sql = "select * from distritos where cod_ciudad='$cod_ciudad'";
$resp = mysql_query($sql);

?>
<td align='center'><select name='distrito1' class='texto' onChange='cargarAjaxZona1(this.form)'>
<option value=''></option>
<?php
while ($dat = mysql_fetch_array($resp)) {
    $p_ciudad = $dat[0];
    $p_dist = $dat[1];
    $p_desc = $dat[2];

    ?>        
<option value='<?=$p_dist?>'><?=$p_desc?></option>
<?php
} 

?> 
</select>
</td>
<td align='center'>
<div id='zona1'></div>
</td>
<tr>
<td align='center'>
<select name='tipoConsultorio2' class='texto'>
<?php
$sqlTipoConsultorio = "select cod_tipo_consultorio, nombre_tipo_consultorio from tipos_consultorio order by 2";
$respTipoConsultorio = mysql_query($sqlTipoConsultorio);
while ($datTipoConsultorio = mysql_fetch_array($respTipoConsultorio)) {
    $codTipoConsultorio = $datTipoConsultorio[0];
    $nombreTipoConsultorio = $datTipoConsultorio[1];

    ?>
	<option value='<?=$codTipoConsultorio?>'><?=$nombreTipoConsultorio?></option>
<?php
} 

?> 
</select>
</td>
<td align='center'><input name='direccion2' type='text' class='texto' size='50'></td>
<td align='center'>
<?php
$sql = "select * from distritos where cod_ciudad='$cod_ciudad'";
$resp = mysql_query($sql);

?>
<select name='distrito2' class='texto' onChange=cargarAjaxZona2(this.form)>
<option value=''></option>
<?php
while ($dat = mysql_fetch_array($resp)) {
    $p_ciudad = $dat[0];
    $p_dist = $dat[1];
    $p_desc = $dat[2];

    ?>
    <option value='<?=$p_dist?>'><?=$p_desc?></option>
<?php
} 

?>
</select>
</td>
<td align='center'>
<div id='zona2'></div>
</td>
</tr>
<tr>
<td align='center'>
<?php
$sqlTipoConsultorio = "select cod_tipo_consultorio, nombre_tipo_consultorio from tipos_consultorio order by 2";
$respTipoConsultorio = mysql_query($sqlTipoConsultorio);

?>
<select name='tipoConsultorio3' class='texto'>
<?php
while ($datTipoConsultorio = mysql_fetch_array($respTipoConsultorio)) {
    $codTipoConsultorio = $datTipoConsultorio[0];
    $nombreTipoConsultorio = $datTipoConsultorio[1];

    ?>
	<option value='<?=$codTipoConsultorio?>'><?=$nombreTipoConsultorio?></option>
<?php
} 

?> 
</select>
</td>
<td align='center'>
	<input name='direccion3' type='text' class='texto' size='50'>
</td>
<td align='center'>
<?php
$sql = "select * from distritos where cod_ciudad='$cod_ciudad'";
$resp = mysql_query($sql);

?>
	<select name='distrito3' class='texto' onChange=cargarAjaxZona3(this.form)>";
	<option value=''></option>
<?php
while ($dat = mysql_fetch_array($resp)) {
    $p_ciudad = $dat[0];
    $p_dist = $dat[1];
    $p_desc = $dat[2];

    ?>
	<option value='<?=$p_dist?>'><?=$p_desc?></option>
<?php
} 

?> 
</select>
</td>
<td align='center'>
<div id='zona3'></div>
</td>
</tr>
</table>
</center>
<center><table BORDER="1" class="texto" cellspacing="0">
  <tr>
    <th  class="texto" colspan="2">Especialidades</th>
    </tr>
  <tr class="texto">
    <th colspan="2">Especialidad</th>
 </tr>
 <tr>
<?php
$sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
$resp = mysql_query($sql);

?>
<td align='center'><select name='espe1' class='texto'>
<option value=''></option>
<?php
while ($dat = mysql_fetch_array($resp)) {
    $p_cod_espe = $dat[0];
    $p_desc_espe = $dat[1];

    ?>
	<option value='<?=$p_cod_espe?>'><?=$p_desc_espe?></option>
<?php
} 

?>
</select>
</td>
<td>Especialidad Real</td>
</tr>
<tr>
<?php
$sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
$resp = mysql_query($sql);

?>
<td align='center'><select name='espe2' class='texto'>
<option value=''></option>
<?php
while ($dat = mysql_fetch_array($resp)) {
    $p_cod_espe = $dat[0];
    $p_desc_espe = $dat[1];

    ?>
	<option value='<?=$p_cod_espe?>'><?=$p_desc_espe?></option>
<?php
} 

?>
</select>
</td><td>Sub-Especialidad</td>
</tr>
<tr>
<?php
$sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
$resp = mysql_query($sql);

?>
<td align='center'><select name='espe3' class='texto'>
<option value=''></option>
<?php
while ($dat = mysql_fetch_array($resp)) {
    $p_cod_espe = $dat[0];
    $p_desc_espe = $dat[1];

    ?>
<option value='<?=$p_cod_espe?>'><?=$p_desc_espe?></option>
<?php
} 

?>
</select>
</td>
<td>Visitado Como</td>
</tr>
<tr>
<?php
$sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
$resp = mysql_query($sql);

?>
<td align='center'><select name='espe4' class='texto'>
<option value=''></option>
<?php
while ($dat = mysql_fetch_array($resp)) {
    $p_cod_espe = $dat[0];
    $p_desc_espe = $dat[1];

    ?>
	<option value='<?=$p_cod_espe?>'><?=$p_desc_espe?></option>
<?php
} 

?> 
</select>
</td><td>Visitado Como</td>
</tr>
<tr>
<?php
$sql = "select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
$resp = mysql_query($sql);

?>
<td align='center'><select name='espe5' class='texto'>";
<option value=''></option>
<?php
while ($dat = mysql_fetch_array($resp)) {
    $p_cod_espe = $dat[0];
    $p_desc_espe = $dat[1];

    ?>
	<option value='<?=$p_cod_espe?>'><?=$p_desc_espe?></option>
<?php
} 

?>
</select>
</td><td>Visitado Como</td>
</tr>
</table></center><br>
<table align='center'><tr><td><a href='navegador_medicos2.php?cod_ciudad=<?=$cod_ciudad?>'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>
<table class="texto" border="0" align="center"><tr><td align='center'><input type="button" class="boton" value="Registrar" onClick='validar(this.form)'></td></tr></table>
</form>
</div>
<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>
</body>
</html>
