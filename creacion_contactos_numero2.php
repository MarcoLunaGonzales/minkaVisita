<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2006
*/
//esto es el JS
echo "<script language='JavaScript'>
		function envia_select(menu,form){
			form.submit();
			return(true);
		}
		function envia(f){
		var i;
		variables=new Array(f.length-1);
			for(i=0;i<=f.length-2;i++)
			{
				variables[i]=f.elements[i].value;
				if(f.elements[i].value=='')
				{
					alert('Algun elemento no tiene valor');
					return(false);
				}

			}
			location.href='guardar_contactos.php?variables='+variables+'';
			return(true);
		}
		</script>";
//hasta aqui JS

require("estilos_visitador.inc");
require("conexion.inc");
echo "<center><table class='textotit' width='80%'><tr><td><center>Creación de Contactos<br> Fecha: $input_FechaConsulta <br>Turno: $turno </center></td></tr></table></center>";
echo "<form name='' action=''>";
echo "<center><table border='1' span class='texto' cellspacing='0'>";
echo "<tr><th>Nombre Medico</th><th>Direccion</th><th>Especialidad</th><th>Categoria</th></tr>";
for($i=1;$i<=$num_medicos;$i++)
{
	$h_cod_med="h_cod_med$i";
	$h_cod_zona="h_cod_zona$i";
	$h_especialidad_med="h_especialidad_med$i";
	$h_categoria_med="h_categoria_med$i";
	$v_h_cod_med=$$h_cod_med;
	$v_h_cod_zona=$$h_cod_zona;
	$v_h_especialidad_med=$$h_especialidad_med;
	$v_h_categoria_med=$$h_categoria_med;
	$sql="select cod_med, ap_pat_med, ap_mat_med, nom_med from medicos";
	$res=mysql_query($sql);
	echo "<tr><td><select class='texto' name='$h_cod_med' onChange='envia_select(this,this.form)'>";
	echo "<option>--Seleccionar Medico--</option>";
	while($dat=mysql_fetch_array($res))
	{	$codigo=$dat[0];
		$paterno=$dat[1];
		$materno=$dat[2];
		$nombre=$dat[3];
		$nombre_completo="$nombre $paterno $materno";
		if($codigo==$v_h_cod_med)
		{
			echo "<option value='$codigo' selected>$nombre_completo</option>";
		}
		else
		{
			echo "<option value='$codigo'>$nombre_completo</option>";
		}
	}
		echo "</select></td>";
		//esta parte recibe el codigo del medico y forma su direccion y su especialidad
	//echo "<form name='form_recibe_cod_med' action=''>";
	$sql2="select cod_zona, direccion from direcciones_medicos where cod_med='$v_h_cod_med'";
	$res2=mysql_query($sql2);
	echo "<td><select class='texto' name='$h_cod_zona'>";
	while($dat2=mysql_fetch_array($res2))
	{
		$zona=$dat2[0];
		$direccion=$dat2[1];
		echo "<option value='$zona'>$direccion</option>";
	}
	echo "</select></td>";
	$sql3="select e.cod_especialidad, e.desc_especialidad, em.categoria_med
	       from especialidades e, especialidades_medicos em
		   where e.cod_especialidad=em.cod_especialidad and em.cod_med='$v_h_cod_med'";
	$resp3=mysql_query($sql3);
	echo "<td><select class='texto' name='$h_especialidad_med' onChange='envia_select(this,this.form)'>";
	echo "<option>--Seleccionar Especialidad--</option>";
	while($dat3=mysql_fetch_array($resp3))
	{
		$cod_esp=$dat3[0];
		$desc_esp=$dat3[1];
		$cat_med=$dat3[2];
		if($cod_esp==$v_h_especialidad_med)
		{
			echo "<option value='$cod_esp' selected>$desc_esp</option>";
		}
		echo "<option value='$cod_esp'>$desc_esp</option>";
	}
	echo "</select></td>";
	$sql4="select categoria_med from especialidades_medicos where cod_especialidad='$v_h_especialidad_med'";
	$resp4=mysql_query($sql4);
	$dat=mysql_fetch_array($resp4);
	$p_categoria_med=$dat[0];
	echo "<td><input type='text' class='texto' size='5' name='$h_categoria_med' value='$p_categoria_med' disabled='true'></td></tr>";

}
echo "</table></center>";
echo "<input type=hidden name='num_medicos' value=$num_medicos>";
echo "<input type=hidden name='turno' value=$turno>";
echo "<input type=hidden name='input_FechaConsulta' value=$input_FechaConsulta>";
echo "<center><input type='button' value='Guardar' onClick='envia(this.form)' class='boton'></center>";
echo "</form>";
?>