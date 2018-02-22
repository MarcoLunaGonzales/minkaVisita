<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005
*/
	echo "<script language='JavaScript'>
		function envia_select(menu,form){
			var j=0;
			for(j=0;j<=form.length-1;j++)
			{	form.elements[j].disabled=false;
			}
			form.submit();
			return(true);
		}
		function envia_turno(menu,form){
		  	var turn;
		  	var dia;
		  	turn=form.turno.value;
			dia=form.diasciclo.value;
			location.href='editar_contactos_dia.php?dia_contacto='+dia+'&turno='+turn+'';
			return(true);
		}
		function relacion_fechas_s(form)
		{	form.input_FechaConsulta.value='';
			form.submit();
			return(true);
		}
		function relacion_fechas_t(form)
		{	form.diasciclo.value='';
			form.submit();
			return(true);
		}
		function envia(f){
		var i;
		var el='h_orden';
		var valor;
		var suma=0;
		var suma_real=0;
		var numero;
		vector = new Array(3);
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
			
			for(var j=1;j<=f.num_medicos.value;j++)
			{
				valor=el+j;
				suma_real=suma_real+j;
				for(i=0;i<=f.length-2;i++)
				{	if(f.elements[i].name==valor)
					{	numero=(f.elements[i].value)*1;
						suma=suma+numero;
					}
				}
			}
			if(suma!=suma_real)
			{	alert('El orden de visita debe ser correlativo y no debe repetirse');
				return(false);
			}
			//extraemos los codigos de los medicos en un vector
			var comp='h_cod_med';
			vector_medicos=new Array(30);
			var indice;
			indice=0;
			for(j=0;j<=f.length-1;j++)
			{
				if(f.elements[j].name.indexOf(comp)!=-1)	
				{	vector_medicos[indice]=f.elements[j].value;
					indice++;	
				}
			}
//fin extraer medicos vector
//inicio validar muestras repetidas
			var buscado,cant_buscado;
			for(k=0;k<=indice;k++)
			{	buscado=vector_medicos[k];
				cant_buscado=0;
				for(m=0;m<=indice;m++)
				{	if(buscado==vector_medicos[m])
					{	cant_buscado=cant_buscado+1;
					}
				}
				if(cant_buscado>1)
				{	alert('Los M&eacute;dicos no pueden repetirse en un mismo d&iacute;a de contacto.');
					return(false);
				}
			}
//fin validar muestras repetidas
			location.href='guardar_modi_ruteroactual.php?variables='+variables+'&rutero=$rutero';
			return(true);
		}
		</script>";

	require("estilos_visitador.inc");
	require("conexion.inc");
	

	//$dia_contacto=$_GET['$dia_contacto'];
	if($dia_contacto!="")
	{
		//sacamos los datos para editarlos
		$sql_pre="select cod_contacto, dia_contacto, turno from rutero where cod_ciclo='$ciclo_global' and codigo_gestion='$codigo_gestion' and dia_contacto='$dia_contacto' and turno='$turno' and codigo_linea='$global_linea' and cod_visitador='$global_visitador'";
		$resp_pre=mysql_query($sql_pre);
		$filas_pre=mysql_num_rows($resp_pre);
		if($filas_pre==0)
		{	$sql_pre="select cod_contacto, dia_contacto, turno from rutero where cod_ciclo='$ciclo_global' and codigo_gestion='$codigo_gestion' and dia_contacto='$dia_contacto' and turno='Pm' and codigo_linea='$global_linea' and cod_visitador='$global_visitador'";
			$resp_pre=mysql_query($sql_pre);
		}
		$dat_pre=mysql_fetch_array($resp_pre);
		$codigo_contacto=$dat_pre[0];
		$diasciclo=$dat_pre[1];
		$turno=$dat_pre[2];

		$sql_pre2="select orden_visita,cod_med,cod_especialidad,categoria_med,cod_zona, estado
					from rutero_detalle where cod_contacto='$codigo_contacto' order by orden_visita asc";
		$resp_pre2=mysql_query($sql_pre2);
		$num_medicos=mysql_num_rows($resp_pre2);
		while($dat_pre2=mysql_fetch_array($resp_pre2))
		{
			$i=$dat_pre2[0];
			$cod_med=$dat_pre2[1];
			$cod_esp=$dat_pre2[2];
			$cat_med=$dat_pre2[3];
			$cod_zona=$dat_pre2[4];
			$estado=$dat_pre2[5];
			$h_orden_visita="h_orden$i";
			$h_cod_med="h_cod_med$i";
			$h_cod_zona="h_cod_zona$i";
			$h_especialidad_med="h_especialidad_med$i";
			$h_categoria_med="h_categoria_med$i";
			$h_estado_med="h_estado_med$i";
			$$h_orden_visita=$i;
			$$h_cod_med=$cod_med;
			$$h_cod_zona=$cod_zona;
			$$h_especialidad_med=$cod_esp;
			$$h_categoria_med=$cat_med;
			$$h_estado_med=$estado;
			$v_h_orden_visita=$$h_orden_visita;
			$v_h_cod_med=$$h_cod_med;
			$v_h_cod_zona=$$h_cod_zona;
			$v_h_especialidad_med=$$h_especialidad_med;
			$v_h_categoria_med=$$h_categoria_med;
		}
	}
	//hasta aca
	$dias_contacto=array("Lunes 1","Martes 1","Miercoles 1","Jueves 1","Viernes 1","Lunes 2","Martes 2","Miercoles 2","Jueves 2","Viernes 2","Lunes 3","Martes 3","Miercoles 3","Jueves 3","Viernes 3","Lunes 4","Martes 4","Miercoles 4","Jueves 4","Viernes 4");
	echo "<table class='textotit' align='center'><tr><th>Edici&oacute;n de Rutero M&eacute;dico Actual</th></tr></table>";
	echo "<form name='' action=''>";
	echo "<center>";
	echo "<table class='texto' border='1' cellspacing='0' width='50%'>
	<tr><th>D&iacute;a de Contacto</th><th>Turno</th><th>N&uacute;mero de Contactos</th><tr>
	<tr><td align='center'>$diasciclo</td>";
	echo "<input type='hidden' name='diasciclo' value='$diasciclo'>";
	echo "<input type='hidden' name='fecha' value='fechilla'>";
	echo "<td align='center'><select class='texto' name='turno' onChange='envia_turno(this,this.form)'>";
	if($turno=='Am') 
	{	echo"<option value='Am' selected>Ma&ntilde;ana</option>";
		echo"<option value='Pm'>Tarde</option>";
	}
	else
	{	echo"<option value='Am'>Ma&ntilde;ana</option>";
		echo"<option value='Pm' selected>Tarde</option>";
	}
	echo "</select></td>";
	echo "<td align='center'><select class='texto' name='num_medicos' onChange='envia_select(this,this.form)'>";
		echo "<option></option>";
		for($i=1;$i<=20;$i++)
		{
			if($num_medicos==$i)
			{	echo "<option value=$i selected>$i </option>";
			}
			else
			{	echo "<option value=$i>$i </option>";
			}

		}
		echo "</select></td></tr>";
	echo "</table></center>";

	//creamos los elementos de formulario necesario para completar el proceso de adicion

echo "<br>";
echo "<center><table border='1' span class='texto' cellspacing='0'>";
echo "<tr><th>Orden Visita</th><th>Nombre M&eacute;dico</th><th>Direcci&oacute;n</th><th>Especialidad</th><th>Categor&iacute;a</th></tr>";
for($i=1;$i<=$num_medicos;$i++)
{
	$h_orden_visita="h_orden$i";
	$h_cod_med="h_cod_med$i";
	$h_cod_zona="h_cod_zona$i";
	$h_especialidad_med="h_especialidad_med$i";
	$h_categoria_med="h_categoria_med$i";
	$h_estado_med="h_estado_med$i";
	$v_h_orden_visita=$$h_orden_visita;
	$v_h_cod_med=$$h_cod_med;
	$v_h_cod_zona=$$h_cod_zona;
	$v_h_especialidad_med=$$h_especialidad_med;
	$v_h_categoria_med=$$h_categoria_med;
	$v_h_estado_med=$$h_estado_med;
//        echo $v_h_cod_med." ";
		$sql_estado="select estado from rutero_detalle where cod_visitador='$global_visitador' and cod_contacto='$codigo_contacto' and orden_visita='$v_h_orden_visita'";
		$resp_estado=mysql_query($sql_estado);
		$dat_estado=mysql_fetch_array($resp_estado);
		$v_h_estado_med=$dat_estado[0];
	//en la variable $v_h_estado_med se tiene el estado para habilitar o deshabilitar ese contacto
	$sql="select DISTINCT(m.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c, medico_asignado_visitador v
			where c.cod_med=m.cod_med and c.codigo_linea='$global_linea' and c.cod_med=v.cod_med and v.codigo_visitador='$global_visitador' order by m.ap_pat_med";
	$res=mysql_query($sql);
	if($v_h_estado_med==1)
	{	echo "<tr><td align='center'><input type='text' class='texto' maxlength='2' size='2' name='$h_orden_visita' value='$v_h_orden_visita' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;' disabled='true'></td>";
	}
	else
	{	echo "<tr><td align='center'><input type='text' class='texto' maxlength='2' size='2' name='$h_orden_visita' value='$v_h_orden_visita' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
	}
	if($v_h_estado_med==1)
	{	echo "<td><select class='texto' name='$h_cod_med' onChange='envia_select(this,this.form)' disabled='true'>";}
	else
	{ 	echo "<td><select class='texto' name='$h_cod_med' onChange='envia_select(this,this.form)'>";}
	echo "<option>--Seleccionar Medico--</option>";
	while($dat=mysql_fetch_array($res))
	{	$codigo=$dat[0];
		$paterno=$dat[1];
		$materno=$dat[2];
		$nombre=$dat[3];
		$nombre_completo="$paterno $materno $nombre";
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
	$sql2="select cod_zona, direccion, numero_direccion from direcciones_medicos where cod_med='$v_h_cod_med'";
	$res2=mysql_query($sql2);
	if($v_h_estado_med==1)
	{	echo "<td><select class='texto' name='$h_cod_zona' disabled='true'>";  }
	else
	{	echo "<td><select class='texto' name='$h_cod_zona'>";	}
	while($dat2=mysql_fetch_array($res2))
	{
		$zona=$dat2[0];
		$direccion=$dat2[1];
		$numero_direccion=$dat2[2];
		if($numero_direccion==$$h_cod_zona)
		{	echo "<option value='$numero_direccion' selected>$direccion</option>";
		}
		else
		{	echo "<option value='$numero_direccion'>$direccion</option>";
		}
	}
	echo "</select></td>";
	$sql3="select e.cod_especialidad, e.desc_especialidad, c.categoria_med
	       from especialidades e, categorias_lineas c
		   where e.cod_especialidad=c.cod_especialidad and c.cod_med='$v_h_cod_med' and c.codigo_linea='$global_linea'";
	$resp3=mysql_query($sql3);
	if($v_h_estado_med==1)
	{	echo "<td><select class='texto' name='$h_especialidad_med' onChange='envia_select(this,this.form)' disabled='true'>";	}
	else
	{	echo "<td><select class='texto' name='$h_especialidad_med' onChange='envia_select(this,this.form)'>";	}
	//echo "<option>--Seleccionar Especialidad--</option>";
	while($dat3=mysql_fetch_array($resp3))
	{
		$cod_esp=$dat3[0];
		$desc_esp=$dat3[1];
		$cat_med=$dat3[2];
		if($cod_esp==$v_h_especialidad_med)
		{
			echo "<option value='$cod_esp' selected>$desc_esp</option>";
		}
		else
		{	echo "<option value='$cod_esp'>$desc_esp</option>";
		}
	}
	echo "</select></td>";
//	$sql4="select categoria_med from categorias_lineas where cod_especialidad='$v_h_especialidad_med' and cod_med='$v_h_cod_med'";
	$sql4="select categoria_med from categorias_lineas where codigo_linea='$global_linea' and cod_med='$v_h_cod_med'";
	$resp4=mysql_query($sql4);
	$dat=mysql_fetch_array($resp4);
	$p_categoria_med=$dat[0];
	echo "<td><input type='text' class='texto' size='5' name='$h_categoria_med' value='$p_categoria_med' disabled='true'></td></tr>";

}
echo "</table></center><br>";
echo"\n<table align='center'><tr><td><a href='registrar_visita_medica.php?dia_registro=$dia_contacto&cod_ciclo=$ciclo_global'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
echo "<input type='hidden' name='codigo_contacto' value=$codigo_contacto>";
echo "<center><input type='button' value='Modificar' onClick='envia(this.form)' class='boton'></center>";
echo "<input type='hidden' name='rutero' value='$rutero'>";
echo "</form>";
echo "</div>";
?>