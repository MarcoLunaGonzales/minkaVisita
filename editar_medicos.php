<html>
<body>
<?
	/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005
*/
	echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function activa_selects1(f)
		{	if(f.habilitar2.checked==true)
			{	f.direccion2.disabled=false;
				f.h_distrito2.disabled=false;
				f.h_zona2.disabled=false;
			}
			else
			{	f.direccion2.disabled=true;
				f.h_distrito2.disabled=true;
				f.h_zona2.disabled=true;
			}
		}

		function activa_selects2(f)
		{	if(f.habilitar3.checked==true)
			{	f.direccion_ins.disabled=false;
				f.h_distrito3.disabled=false;
				f.h_zona3.disabled=false;
			}
			else
			{	f.direccion_ins.disabled=true;
				f.h_distrito3.disabled=true;
				f.h_zona3.disabled=true;
			}
		}

		function activa_subespe(f)
		{	if(f.habilitar_espe.checked==true)
			{	f.h_subesp.disabled=false;
			}
			else
			{	f.h_subesp.disabled=true;
			}
		}
		function activa_espe3(f){
			if(f.habilitar_espe3.checked==true){
				f.h_esp3.disabled=false;
			}
			else{
				f.h_esp3.disabled=true;
			}
		}		
		function activa_espe4(f){
			if(f.habilitar_espe4.checked==true){
				f.h_esp4.disabled=false;
			}
			else{
				f.h_esp4.disabled=true;
			}
		}
		function validar(f)
		{
			var n=f.length;
			var i;
			vector_datos=new Array();
			var categoria,sub_categoria;
			var dir2,dir3,dir_cat;
			dir2=0;
			dir3=0;
			dir_cat=0;
			var cod_med;
			cod_med=f.codigo_medico.value;
			for(i=0;i<=n-1;i++)
			{
				//alert(f.elements[i].value+i);
				vector_datos[i]=f.elements[i].value+'|';
			}
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
			if(f.telefono.value=='' && f.telf_celular.value=='')
			{	alert('Al menos debe escribir uno de los telefonos (fijo o celular)');
				if(f.telefono.value==''){f.telefono.focus();}
				return(false);
			}
			if(f.cod_ciudad.value=='')
			{	alert('El campo Agencia esta vacio');
				f.cod_ciudad.focus();
				return(false);
			}
			if(f.direccion1.value=='')
			{	alert('El campo Consultorio I esta vacio');
				f.direccion1.focus();
				return(false);
			}
			if(f.h_esp.value=='')
			{	alert('Debe seleccionar al menos una especialidad.');
				return(false);
			}
			if(f.h_distrito.value=='')
			{	alert('Debe seleccionar un distrito.');
				return(false);
			}
			if(f.h_zona.value=='')
			{	alert('Debe seleccionar una zona.');
				return(false);
			}
			if(f.direccion2.value!='')
			{	dir2=1;
			}
			if(f.direccion_ins.value!='')
			{	dir3=1;
			}
			var vectorEspe=new Array(3);
			var indice=0;
			vectorEspe[indice]=f.h_esp.value;
			indice++;
			if(f.h_subesp.disabled==false){
				vectorEspe[indice]=f.h_subesp.value;
				indice++;
			}
			if(f.h_esp3.disabled==false){
				vectorEspe[indice]=f.h_esp3.value;
				indice++;
			}
			if(f.h_esp4.disabled==false){
				vectorEspe[indice]=f.h_esp4.value;
				indice++;
			}
			location.href='guarda_modi_medico.php?datos='+vector_datos+'&categoria='+categoria+'&dir2='+dir2+'&dir3='+dir3+'&dir_cat='+dir_cat+'&sub_categoria='+sub_categoria+'&cod_med='+cod_med+'&vectorEspe='+vectorEspe+'';
		}

		</script>";
	require("estilos_administracion.inc");
	require("conexion.inc");
	//esta parte saca todos los datos del medico
	//
if($j_cod_med!="")
{
  	$codigo_medico=$j_cod_med;
	$sql_master="select * from medicos where cod_med=$j_cod_med";
	$resp_master=mysql_query($sql_master);
	while($dat_m=mysql_fetch_array($resp_master))
	{
		$ap_paterno=$dat_m[1];
		$ap_materno=$dat_m[2];
		$nombres=$dat_m[3];
		$fecha_nac=$dat_m[4];
		$telefono=$dat_m[5];
		$telf_celular=$dat_m[6];
		$email=$dat_m[7];
		$hobbie=$dat_m[8];
		$estado_civil=$dat_m[9];
		$nombre_secretaria=$dat_m[10];
		$perfil_psicografico=$dat_m[11];
		$cod_ciudad=$dat_m[12];
		$exafinicial="$fecha_nac[8]$fecha_nac[9]/$fecha_nac[5]$fecha_nac[6]/$fecha_nac[0]$fecha_nac[1]$fecha_nac[2]$fecha_nac[3]";

		//esta parte es para las direcciones
		$sql_dir1=mysql_query("select z.cod_zona,d.direccion,z.cod_dist from direcciones_medicos d, zonas z
									where d.cod_med=$j_cod_med and d.descripcion='Consultorio I' and d.cod_zona=z.cod_zona");
		$num_filas=mysql_num_rows($sql_dir1);
		if($num_filas==1)
		{	$dat=mysql_fetch_array($sql_dir1);
			$direccion1=$dat[1];
			$h_zona=$dat[0];
			$h_distrito=$dat[2];
		}
		//segunda direccion
		$sql_dir2=mysql_query("select z.cod_zona,d.direccion,z.cod_dist from direcciones_medicos d, zonas z
									where d.cod_med=$j_cod_med and d.descripcion='Consultorio II' and d.cod_zona=z.cod_zona");
		$num_filas=mysql_num_rows($sql_dir2);
		if($num_filas==1)
		{	$dat2=mysql_fetch_array($sql_dir2);
			$direccion2=$dat2[1];
			$h_zona2=$dat2[0];
			$h_distrito2=$dat2[2];
		}
		//esta es la direccion institucional
		$sql_dir3=mysql_query("select z.cod_zona,d.direccion,z.cod_dist from direcciones_medicos d, zonas z
									where d.cod_med=$j_cod_med and d.descripcion='Consultorio III' and d.cod_zona=z.cod_zona");
		$num_filas=mysql_num_rows($sql_dir3);
		if($num_filas==1)
		{	$dat3=mysql_fetch_array($sql_dir3);
			$direccion_ins=$dat3[1];
			$h_zona3=$dat3[0];
			$h_distrito3=$dat3[2];
		}
		//esto para las especialidades
		$sql_espe=mysql_query("select cod_especialidad from especialidades_medicos where cod_med=$j_cod_med");
		
		$indice_espe=1;
		while($dat_espe=mysql_fetch_array($sql_espe))
		{	$cod_especialidad=$dat_espe[0];
			if($indice_espe==1){	
				$h_esp=$cod_especialidad;
			}
			if($indice_espe==2){
				$h_subesp=$cod_especialidad;
			}
			if($indice_espe==3){
				$h_esp3=$cod_especialidad;
			}
			if($indice_espe==4){
				$h_esp4=$cod_especialidad;
			}
			$indice_espe++;
		}
	}
}
//echo "$h_esp $h_subesp $h_esp3";
?>
<table width="100%"  border="0" cellspacing="0" class="textotit">
  <tr>
    <th>EDITAR DATOS DE M&Eacute;DICO</th>
  </tr>
</table>
<form name="" action="editar_medicos.php">
<table width="80%"  border="1" align="center" cellspacing="0" class="texto">
  <tr>
  	<th colspan="4" class="texto">Editar Datos Personales</th>
  </tr>
  <tr class="texto">
    <th width="22%">Apellido Paterno </th>
    <th width="28%">Apellido Materno </th>
    <th width="19%">Nombres</th>
    <th width="31%">Fecha de Nacimiento</th>
    </tr>
  <tr>
    <td><? echo "<center><input name='ap_paterno' type='text' class='texto' value='$ap_paterno'>";?></td>
    <td><? echo "<center><input name='ap_materno' type='text' class='texto' value='$ap_materno'>";?></td>
    <td><? echo "<center><input name='nombres' type='text' class='texto' value='$nombres'>";?></td>
	 <TD bgcolor='#ffffff'> <? echo" <center><INPUT  type='text' class='texto' value='$exafinicial' id='exafinicial' size='10' name='exafinicial'>";
    		echo" <IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
    		echo" <DLCALENDAR tool_tip='Seleccione la Fecha' ";
    		echo" daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
    		echo" navbar_style='background-color: 7992B7; color:ffffff;' ";
    		echo" input_element_id='exafinicial' ";
    		echo" click_element_id='imagenFecha'></DLCALENDAR>";

	?>
	</TD>
    </tr>
  <tr class="texto">
    <th>Tel&eacute;fono</th>
    <th>Tel&eacute;fono Celular (*) </th>
    <th>Correo Electronico(*)</th>
    <th>Hobbie(*)</th>
    </tr>
  <tr>
    <td><? echo "<center><input name='telefono' type='text' class='texto' value='$telefono'>";?></td>
    <td><? echo "<center><input name='telf_celular' type='text' class='texto' value='$telf_celular'>";?></td>
    <td><? echo "<center><input name='email' type='text' class='texto' value='$email'>";?></td>
    <td><? echo "<center><input name='hobbie' type='text' class='texto' value='$hobbie'>";?></td>
    </tr>
  <tr class="texto">
    <th>Estado Civil(*)</th>
    <th>
		Perfil Psicografico(*)</th>
    <th>Nombre Secretaria(*)</th>
    <th>Agencia</th>
    </tr>
  <tr>
    <td><?
			echo "<center><select name='estado_civil' class='texto'>
      <option value='$estado_civil' selected>$estado_civil</option>
      <option value='Soltero(a)'>Soltero(a)</option>
      <option value='Casado(a)'>Casado(a)</option>
      <option value='Divorciado(a)'>Divorciado(a)</option>
      <option value='Viudo(a)'>Viudo(a)</option>
    </select>";
	?></td>
    <td><? echo "<center><input name='perfil_psicografico' type='text' class='texto' value='$perfil_psicografico'>";?></td>
    <td><? echo "<center><input name='nombre_secretaria' type='text' class='texto' value='$nombre_secretaria'>";?></td>
	<td>
		<?

			$sql="select cod_ciudad,descripcion from ciudades order by descripcion";
			$resp=mysql_query($sql);
			echo "<center><select NAME='cod_ciudad' class='texto' onChange='envia_select(this.form)'>";
			echo "<option value=''></option>";
			while($dat=mysql_fetch_array($resp))
			{
				$p_cod_ciudad=$dat[0];
				$p_desc=$dat[1];
				if($cod_ciudad==$p_cod_ciudad)
				{	echo "<option value='$p_cod_ciudad' selected>$p_desc</option>";
				}
				else
				{	echo "<option value='$p_cod_ciudad'>$p_desc</option>";
				}
			}
		?>
		</select>
	</td>
  </tr></table><br>
  <center><table BORDER="1" class="texto" WIDTH="80%" cellspacing="0">
  <tr>
    <TH colspan="5"><div align="center" class="texto">Editar Direcciones</div></TH>
    </tr>
  	<tr class="texto">
		<th>&nbsp;</th><th>Direccion</th><th>Distrito</th><th>Zona</th><th>Habilitar</th>
	</tr>
  <tr>
    <?
	echo "<th class='texto'>Consultorio I </th>";
	echo "<td><input name='direccion1' type='text' class='texto' value='$direccion1' size='50'></td>";
	$sql="select * from distritos where cod_ciudad='$cod_ciudad'";
		$resp=mysql_query($sql);
		echo "<td><select name='h_distrito' class='texto' onChange=envia_select(this.form)>";
		echo "<option value=''></option>";
		while($dat=mysql_fetch_array($resp))
		{
			$p_ciudad=$dat[0];
			$p_dist=$dat[1];
			$p_desc=$dat[2];
			if($p_dist==$h_distrito)
			{
				echo "<option value='$p_dist' selected>$p_desc</option>";
			}
			else
			{
				echo "<option value='$p_dist'>$p_desc</option>";
			}
		}
		echo "</select>";
		echo "</td><td>";
		$sql1="select * from zonas where cod_dist='$h_distrito'";
		$resp1=mysql_query($sql1);
		echo "<select name='h_zona' class='texto'>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$p_cod_zona=$dat1[2];
			$p_zona=$dat1[3];
			if($h_zona==$p_cod_zona)
			{	echo "<option value='$p_cod_zona' selected>$p_zona</option>";
			}
			else
			{	echo "<option value='$p_cod_zona'>$p_zona</option>";
			}

		}
		echo "</select></td>";
		echo "<td>&nbsp;</td>";
  	?>
  <tr>
    <?
	echo "<th class='texto'>Consultorio II</th>";
	if($h_distrito2=="")
	{	echo "<td><input name='direccion2' type='text' class='texto' disabled='true' value='$direccion2' size='50'></td>";
	}
	else
	{		echo "<td><input name='direccion2' type='text' class='texto' value='$direccion2'></td>";
	}
	//echo "<td COLSPAN='3'><input name='direccion2' type='text' class='texto' disabled='true' value='$direccion2'>";
	echo "<td>";
	$sql="select * from distritos where cod_ciudad='$cod_ciudad'";
		$resp=mysql_query($sql);
		if($h_distrito2=="")
		{	echo "<select name='h_distrito2' class='texto' onChange=envia_select(this.form) disabled='true'>";
		}
		else
		{	echo "<select name='h_distrito2' class='texto' onChange=envia_select(this.form)>";
		}
		echo "<option value=''></option>";
		while($dat=mysql_fetch_array($resp))
		{
			$p_ciudad=$dat[0];
			$p_dist=$dat[1];
			$p_desc=$dat[2];
			if($p_dist==$h_distrito2)
			{
				echo "<option value='$p_dist' selected>$p_desc</option>";
			}
			else
			{
				echo "<option value='$p_dist'>$p_desc</option>";
			}
		}
		echo "</select>";
		echo "</td><td>";
		$sql1="select * from zonas where cod_dist='$h_distrito2'";
		$resp1=mysql_query($sql1);
		if($h_distrito2=="")
		{	echo "<select name='h_zona2' class='texto' disabled='true'>";
		}
		else
		{	echo "<select name='h_zona2' class='texto'>";
		}
		while($dat1=mysql_fetch_array($resp1))
		{
			$p_cod_zona=$dat1[2];
			$p_zona=$dat1[3];
			if($h_zona2==$p_cod_zona)
			{	echo "<option value='$p_cod_zona' selected>$p_zona</option>";
			}
			else
			{	echo "<option value='$p_cod_zona'>$p_zona</option>";
			}

		}
		echo "</select>";
		echo "</td><td>";
		echo "<input type='checkbox' name='habilitar2' onClick='activa_selects1(this.form)'></td>";
		echo "</td>";
  	?>
  </tr>
  <tr>
    <?
	echo "<th class='texto'>Consultorio III</th>";
    if($h_distrito3=="")
	{	echo "<td><input name='direccion_ins' type='text' class='texto' disabled='true' value='$direccion_ins' size='50'></td>";
	}
	else
	{	echo "<td><input name='direccion_ins' type='text' class='texto' value='$direccion_ins'></td>";
	}
	echo "</td><td>";
	$sql="select * from distritos where cod_ciudad='$cod_ciudad'";
		$resp=mysql_query($sql);
		if($h_distrito3=="")
		{	echo "<select name='h_distrito3' class='texto' onChange=envia_select(this.form) disabled='true'>";
		}
		else
		{	echo "<select name='h_distrito3' class='texto' onChange=envia_select(this.form)>";
		}
		echo "<option value=''></option>";
		while($dat=mysql_fetch_array($resp))
		{
			$p_ciudad=$dat[0];
			$p_dist=$dat[1];
			$p_desc=$dat[2];
			if($p_dist==$h_distrito3)
			{
				echo "<option value='$p_dist' selected>$p_desc</option>";
			}
			else
			{
				echo "<option value='$p_dist'>$p_desc</option>";
			}
		}
		echo "</select>";
		echo "</td><td>";
		$sql1="select * from zonas where cod_dist='$h_distrito3'";
		$resp1=mysql_query($sql1);
		if($h_distrito3=="")
		{	echo "<select name='h_zona3' class='texto' disabled='true'>";
		}
		else
		{	echo "<select name='h_zona3' class='texto'>";
		}
		while($dat1=mysql_fetch_array($resp1))
		{
			$p_cod_zona=$dat1[2];
			$p_zona=$dat1[3];
			if($h_zona3==$p_cod_zona)
			{	echo "<option value='$p_cod_zona' selected>$p_zona</option>";
			}
			else
			{	echo "<option value='$p_cod_zona'>$p_zona</option>";
			}

		}
		echo "</select>";
		echo "</td><td>";
		echo "<input type='checkbox' name='habilitar3' onClick='activa_selects2(this.form)'></td>";
		echo "</td>";
  	?>

  </tr></table></center><br>
  <center><table BORDER="1" class="texto" cellspacing="0">
  <tr>
    <th colspan="4" class="texto">Editar Especialidades</th>
    </tr>
  <tr class="texto">
    <th>Especialidad</th><th>Habilitar</th>
 </tr>
 <tr>
		<?
		$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp=mysql_query($sql);
		echo "<td><select name='h_esp' class='texto'>";
		echo "<option value=''></option>";
		while($dat=mysql_fetch_array($resp))
		{
			$p_cod_espe=$dat[0];
			$p_desc_espe=$dat[1];
			if($h_esp==$p_cod_espe)
			{	echo "<option value='$p_cod_espe' selected>$p_desc_espe</option>";
			}
			else
			{	echo "<option value='$p_cod_espe'>$p_desc_espe</option>";
			}

		}
		echo "</select>";
		echo "</td>";

	?>
  </tr>
  <tr>
		<?
		$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp=mysql_query($sql);
		if($h_subesp=="")
		{	echo "<td><select name='h_subesp' class='texto' disabled='true'>";
		}
		else
		{	echo "<td><select name='h_subesp' class='texto'>";
		}
		echo "<option value=''></option>";
		while($dat=mysql_fetch_array($resp))
		{
			$p_cod_espe=$dat[0];
			$p_desc_espe=$dat[1];
			if($h_subesp==$p_cod_espe)
			{	echo "<option value='$p_cod_espe' selected> $p_desc_espe </option>";
			}
			else
			{	echo "<option value='$p_cod_espe'> $p_desc_espe </option>";
			}

		}
		echo "</select>";
		echo "</td><td>";
		if($h_subesp=="")
		{	echo "<input type='checkbox' name='habilitar_espe' value='checkbox' onClick='activa_subespe(this.form)'></td>";
		}
		else
		{	echo "<input type='checkbox' name='habilitar_espe' value='checkbox' onClick='activa_subespe(this.form)' checked></td>";
		}
		echo "</td></tr>";
	?>
	<tr>
		<?
		$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp=mysql_query($sql);
		if($h_esp3=="")
		{	echo "<td><select name='h_esp3' class='texto' disabled='true'>";
		}
		else
		{	echo "<td><select name='h_esp3' class='texto'>";
		}
		echo "<option value=''></option>";
		while($dat=mysql_fetch_array($resp))
		{
			$p_cod_espe=$dat[0];
			$p_desc_espe=$dat[1];
			if($h_esp3==$p_cod_espe)
			{	echo "<option value='$p_cod_espe' selected> $p_desc_espe </option>";
			}
			else
			{	echo "<option value='$p_cod_espe'> $p_desc_espe </option>";
			}

		}
		echo "</select>";
		echo "</td><td>";
		if($h_esp3=="")
		{	echo "<input type='checkbox' name='habilitar_espe3' value='checkbox' onClick='activa_espe3(this.form)'></td>";
		}
		else
		{	echo "<input type='checkbox' name='habilitar_espe3' value='checkbox' onClick='activa_espe3(this.form)' checked></td>";
		}
		echo "</td></tr>";
	?>
	
	<tr>
		<?
		$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp=mysql_query($sql);
		if($h_esp4=="")
		{	echo "<td><select name='h_esp4' class='texto' disabled='true'>";
		}
		else
		{	echo "<td><select name='h_esp4' class='texto'>";
		}
		echo "<option value=''></option>";
		while($dat=mysql_fetch_array($resp))
		{
			$p_cod_espe=$dat[0];
			$p_desc_espe=$dat[1];
			if($h_esp4==$p_cod_espe)
			{	echo "<option value='$p_cod_espe' selected> $p_desc_espe </option>";
			}
			else
			{	echo "<option value='$p_cod_espe'> $p_desc_espe </option>";
			}

		}
		echo "</select>";
		echo "</td><td>";
		if($h_esp4=="")
		{	echo "<input type='checkbox' name='habilitar_espe4' value='checkbox' onClick='activa_espe4(this.form)'></td>";
		}
		else
		{	echo "<input type='checkbox' name='habilitar_espe4' value='checkbox' onClick='activa_espe4(this.form)' checked></td>";
		}
		echo "</td></tr>";
	?>


	<?
		echo "</table></center><br>";
		echo "<input type='hidden' name='codigo_medico' value='$codigo_medico'>";
		echo"\n<table align='center'><tr><td><a href='navegador_medicos2.php?cod_ciudad=$cod_ciudad'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";

	?>
  <table class="texto" border="0" align="center"><tr><td><input type="button" class="boton" value="Modificar" class='boton' onClick='validar(this.form)'></td></tr></table>
</form>
</div>
<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>
</body>
</html>
