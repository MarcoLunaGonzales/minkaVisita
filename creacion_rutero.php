<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
	require('estilos.inc');
	echo "<script language='JavaScript'>
		function envia_select(menu,form){
			form.submit();
			return(true);
		}
		function prueba(f,num_el){
			var i,j;
			var n=num_el;
			var extra='muestra';
			var extra1='cantidad';
			var valor,valor1;
			var tipo,j_cod_muestra,j_cantidad;
			vector_muestras=new Array(n-1);
			vector_cantidad=new Array(n-1);
			var j_cod_med,j_cod_zona,j_cod_especialidad,j_cat_med;
			var j_fecha,j_turno;
			j_fecha=f.elements[0].value;
			j_turno=f.elements[1].value;
			j_cod_med=f.elements[2].value;
			j_cod_zona=f.elements[3].value;
			j_cod_especialidad=f.elements[4].value;
			j_cat_med=f.elements[5].value;
			for(i=1;i<=n;i++)
			{	
				valor=extra+i;
				valor1=extra1+i;
				for(j=0;j<=f.length-1;j++)
				{
					if(f.elements[j].name==valor)
					{
						tipo=f.elements[j];
						j_cod_muestra=tipo.options[tipo.selectedIndex].value;
						vector_muestras[i-1]=j_cod_muestra;
					}
					if(f.elements[j].name==valor1)
					{
						tipo=f.elements[j];
						j_cantidad=tipo.options[tipo.selectedIndex].value;
						vector_cantidad[i-1]=j_cantidad;
					}
				}
				
			}
			location.href='2.php?fecha='+j_fecha+'&turno='+j_turno+'&cod_med='+j_cod_med+'&cod_zona='+j_cod_zona+'&cod_especialidad='+j_cod_especialidad+'&cat_med='+j_cat_med+'&vec_muestras='+vector_muestras+'&vec_cant='+vector_cantidad+'&cantidad='+n+'';
			return(true);
		}
		</script>";

	require("conexion.inc");
	$sql="select cod_med, ap_pat_med, ap_mat_med, nom_med from medicos";
	$res=mysql_query($sql);
	echo "<center><table class='textotit'><tr><td><center>Creación de Rutero Medico</center></td></tr></table></center>";
	echo "<form name='' action='creacion_rutero.php'>";
	echo "<center>";
	echo "<table class='texto'>
	<tr>
	<!-- INI fecha con javascript -->
                <TD>Seleccione la Fecha: <INPUT id=input_FechaConsulta size=10 class='texto' name=input_FechaConsulta value='$input_FechaConsulta'></TD>
                <TD><IMG id=imagenFecha src='imagenes/fecha.bmp'></TD>
                <TD>
                <DLCALENDAR tool_tip='Seleccione la Fecha' 
                daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' 
                navbar_style='background-color: 7992B7; color:ffffff;' 
                input_element_id='input_FechaConsulta' 
                click_element_id='imagenFecha'></DLCALENDAR>
                </TD></TR>";
	echo "<tr><td>Seleccione el Turno:  <select class='texto' name='turno'>";
	if($turno="am")
	{ 	echo"<option value='am' selected>Mañana</option>";
		echo"<option value='pm'>Tarde</option>";
	}
	else
	{	echo"<option value='am'>Mañana</option>";
		echo"<option value='pm' selected>Tarde</option>";
	}
	echo "</select></td></tr></table><br>";
	echo "<table border='0' span class='texto'>";
	echo "<tr><td>";
	echo "Nombre Medico</td><td><select class='texto' name='h_cod_med' onChange='envia_select(this,this.form)'>";
	echo "<option>--Seleccionar Medico--</option>";
	while($dat=mysql_fetch_array($res))
	{	$codigo=$dat[0];
		$paterno=$dat[1];
		$materno=$dat[2];
		$nombre=$dat[3];
		$nombre_completo="$nombre $paterno $materno";
		if($codigo==$h_cod_med)
		{	
			echo "<option value='$codigo' selected>$nombre_completo</option>"; 	
		}
		else
		{	
			echo "<option value='$codigo'>$nombre_completo</option>";
		}	 
	}
		echo "</select></td></tr>";
		//esta parte recibe el codigo del medico y forma su direccion y su especialidad
	//echo "<form name='form_recibe_cod_med' action=''>";
	$sql2="select cod_zona, direccion from direcciones_medicos where cod_med='$h_cod_med'";
	$res2=mysql_query($sql2);
	echo "<tr><td>Direccion</td><td><select class='texto' name='h_cod_zona'>";
	while($dat2=mysql_fetch_array($res2))
	{	
		$zona=$dat2[0];
		$direccion=$dat2[1];
		echo "<option value='$zona'>$direccion</option>";
	}
	echo "</select></td></tr>";
	$sql3="select e.cod_especialidad, e.desc_especialidad, em.categoria_med
	       from especialidades e, especialidades_medicos em
		   where e.cod_especialidad=em.cod_especialidad and em.cod_med='$h_cod_med'";
	$resp3=mysql_query($sql3);
	echo "<tr><td>Especialidad</td><td><select class='texto' name=h_especialidad_med onChange='envia_select(this,this.form)'>";
	echo "<option>--Seleccionar Especialidad--</option>";
	while($dat3=mysql_fetch_array($resp3))
	{	
		$cod_esp=$dat3[0];
		$desc_esp=$dat3[1];
		$cat_med=$dat3[2];
		if($cod_esp==$h_especialidad_med)
		{	
			echo "<option value='$cod_esp' selected>$desc_esp</option>";
		}
		echo "<option value='$cod_esp'>$desc_esp</option>";
	} 
	echo "</select></td></tr>";
	$sql4="select categoria_med from especialidades_medicos where cod_especialidad='$h_especialidad_med'";
	$resp4=mysql_query($sql4);
	$dat=mysql_fetch_array($resp4);
	$p_categoria_med=$dat[0];
	echo "<td>Categoria</td><td><input type='text' class='texto' size='5' name='h_categoria_med' value='$p_categoria_med' disabled='true'></td></tr></table></center>";
	//esto es para las muestras a entregar
	echo "<br><center><table class='texto' border='0'>";
	echo "<tr><td>Número de muestras a dejar:  </td>";
	echo "<td><select class='texto' name='h_numero_muestras' onChange='envia_select(this,this.form)'>";
	echo "<option>-- Seleccionar --</option>";
	echo "<option value='1'>1</option>";
	echo "<option value='2'>2</option>";
	echo "<option value='3'>3</option>";
	echo "<option value='4'>4</option>";
	echo "<option value='5'>5</option>";
	echo "<option value='6'>6</option>";
	echo "<option value='7'>7</option>";
	echo "<option value='8'>8</option>";
	echo "<option value='9'>9</option>";
	echo "<option value='10'>10</option>";
	echo "</td></tr></table></center>";
	//desde aqui formamos el select con el numero de muestras que se eligio
	echo "<center><table span class='texto' border='1' cellspacing='0'><tr><td>Muestras</td><td>   </td><td>Cantidad</td></tr>";
	for($i=1;$i<=$h_numero_muestras;$i++)
	{
		$p_muestras="muestra";
		$p_m="$p_muestras$i";
		echo "<tr><td><select name='$p_m' class='texto'>";
		$sql5="select * from muestras_medicas";
		$resp5=mysql_query($sql5);
		while($dat5=mysql_fetch_array($resp5))
		{
			$p_codigo_muestra=$dat5[0];
			$p_desc_muestra=$dat5[1];
			echo "<option value='$p_codigo_muestra'>$p_desc_muestra</option>";
		}
		echo "</select></td><td>  </td>";
		
		$p_cantidad="cantidad";
		echo "<td><select class='texto' name='$p_cantidad$i'>";
			echo "<option value='1'>1</option>";
			echo "<option value='2'>2</option>";
			echo "<option value='3'>3</option>";
			echo "<option value='4'>4</option>";
			echo "<option value='5'>5</option>";
			echo "<option value='6'>6</option>";
			echo "<option value='7'>7</option>";
			echo "<option value='8'>8</option>";
			echo "<option value='9'>9</option>";
			echo "<option value='10'>10</option>";
			echo "</select></td></tr>";
	}
	echo "</table>";
	echo "<br><input type='button' value='Enviar->>' onClick='prueba(this.form,$h_numero_muestras)'></center>";
	echo "</form>";
	echo "<script type='text/javascript' language=javascript' src='dlcalendar.js'></script>";
?>