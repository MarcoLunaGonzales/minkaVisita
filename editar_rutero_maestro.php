<?php
	require("estilos_visitador.inc");
	require("conexion.inc");

	echo "<script language='JavaScript'>
		function envia_select(menu,form){
			form.submit();
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
				{	alert('Los Medicos no pueden repetirse en un mismo Dia de contacto.');
					return(false);
				}
			}
//fin validar muestras repetidas
			location.href='guardar_modi_rutero_maestro.php?variables='+variables+'&rutero=$rutero';
			return(true);
		}
		function envia_nuevo(f){
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
				{	alert('Los Medicos no pueden repetirse en un mismo Dia de contacto.');
					return(false);
				}
			}
//fin validar muestras repetidas
			location.href='guardar_rutero_maestro.php?variables='+variables+'&rutero=$rutero';
			return(true);
		}
		</script>";

	//saca el nombre del rutero maestro
	$sql_nom_rutero=mysql_query("select nombre_rutero from rutero_maestro_cab where cod_rutero='$rutero' and cod_visitador='$global_visitador'");
	$dat_nom_rutero=mysql_fetch_array($sql_nom_rutero);
	$nombre_rutero=$dat_nom_rutero[0];
	//fin sacar nombre
	if($j_contacto!="")
	{
		//sacamos los datos para editarlos
		$codigo_contacto=$j_contacto;
		$sql_pre="select dia_contacto, turno from rutero_maestro where cod_contacto='$j_contacto' and cod_visitador='$global_visitador'";
		//echo $sql_pre;
		$resp_pre=mysql_query($sql_pre);
		$dat_pre=mysql_fetch_array($resp_pre);
		$diasciclo=$dat_pre[0];
		$turno=$dat_pre[1];			

		$sql_pre2="select orden_visita,cod_med,cod_especialidad,categoria_med,cod_zona
					from rutero_maestro_detalle where cod_contacto=$j_contacto and cod_visitador='$global_visitador' order by orden_visita";
		$resp_pre2=mysql_query($sql_pre2);
		$num_medicos=mysql_num_rows($resp_pre2);
		while($dat_pre2=mysql_fetch_array($resp_pre2))
		{
			$i=$dat_pre2[0];
			$cod_med=$dat_pre2[1];
			$cod_esp=$dat_pre2[2];
			$cat_med=$dat_pre2[3];
			$cod_zona=$dat_pre2[4];			
			$h_orden_visita="h_orden$i";
			$h_cod_med="h_cod_med$i";
			$h_cod_zona="h_cod_zona$i";
			$h_especialidad_med="h_especialidad_med$i";
			$h_categoria_med="h_categoria_med$i";
			$$h_orden_visita=$i;
			$$h_cod_med=$cod_med;
			$$h_cod_zona=$cod_zona;
			$$h_especialidad_med=$cod_esp;
			$$h_categoria_med=$cat_med;
			$v_h_orden_visita=$$h_orden_visita;
			$v_h_cod_med=$$h_cod_med;
			$v_h_cod_zona=$$h_cod_zona;
			$v_h_especialidad_med=$$h_especialidad_med;
			$v_h_categoria_med=$$h_categoria_med;	
		}
	}
	//hasta aca
	$dias_contacto  = array("Lunes 1","Martes 1","Miercoles 1","Jueves 1","Viernes 1","Lunes 2","Martes 2","Miercoles 2","Jueves 2","Viernes 2","Lunes 3","Martes 3","Miercoles 3","Jueves 3","Viernes 3","Lunes 4","Martes 4","Miercoles 4","Jueves 4","Viernes 4","Lunes 5","Martes 5","Miercoles 5","Jueves 5","Viernes 5","Lunes 6","Martes 6","Miercoles 6","Jueves 6","Viernes 6","Lunes 7","Martes 7","Miercoles 7","Jueves 7","Viernes 7","Lunes 8","Martes 8","Miercoles 8","Jueves 8","Viernes 8","Lunes 9","Martes 9","Miercoles 9","Jueves 9","Viernes 9","Lunes 10","Martes 10","Miercoles 10","Jueves 10","Viernes 10");	
	echo "<h1>Edicion de Rutero Maestro<br>Nro de Rutero: $nombre_rutero</h1>";

	echo "<form name='' action=''>";
	echo "<center>";
	echo "<table class='texto'>
	<tr><th>Dia de Contacto</th><th>Turno</th><th>Nro. de Contactos</th><tr>
	<tr><td align='center'>";
	echo "<select name='diasciclo' class='texto'>";
	for($j=0;$j<=49;$j++)
	{
		if($diasciclo==$dias_contacto[$j])
		{		echo "<option value='$dias_contacto[$j]' selected>$dias_contacto[$j]</option>";
		}
		else
		{		echo "<option value='$dias_contacto[$j]'>$dias_contacto[$j]</option>";
		}	
	}	
	echo "</select></td>";
	echo "<input type='hidden' name='fecha' value='fechilla'>";
	echo "<td align='center'><select class='texto' name='turno'>";
	if($turno=='Am')
	{	echo"<option value='Am' selected>Manana</option>";
		echo"<option value='Pm'>Tarde</option>";
	}
	else
	{	echo"<option value='Am'>Manana</option>";
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
echo "<center><table class='texto'>";
echo "<tr><th>Orden Visita</th><th>Medico</th><th>Direccion</th><th>Especialidad</th><th>Categoria</th></tr>";
for($i=1;$i<=$num_medicos;$i++)
{
	$h_orden_visita="h_orden$i";
	$h_cod_med="h_cod_med$i";
	$h_cod_zona="h_cod_zona$i";
	$h_especialidad_med="h_especialidad_med$i";
	$h_categoria_med="h_categoria_med$i";
	$v_h_orden_visita=$$h_orden_visita;
	$v_h_cod_med=$$h_cod_med;
	$v_h_cod_zona=$$h_cod_zona;
	$v_h_especialidad_med=$$h_especialidad_med;
	$v_h_categoria_med=$$h_categoria_med;
	$sql="select DISTINCT(m.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c, medico_asignado_visitador v
			where c.cod_med=m.cod_med and c.codigo_linea='$global_linea' and c.cod_med=v.cod_med and v.codigo_visitador='$global_visitador' order by m.ap_pat_med";
	$res=mysql_query($sql);
	echo "<tr><td align='center'><input type='text' class='texto' maxlength='2' size='2' name='$h_orden_visita' value='$v_h_orden_visita' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
	echo "<td><select class='texto' name='$h_cod_med' onChange='envia_select(this,this.form)'>";
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
	echo "<td><select class='texto' name='$h_cod_zona'>";
	while($dat2=mysql_fetch_array($res2))
	{
		$zona=$dat2[0];
		$direccion=$dat2[1];
		$numero_direccion=$dat2[2];
		if($v_h_cod_zona==$numero_direccion)
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
	echo "<td><select class='texto' name='$h_especialidad_med' onChange='envia_select(this,this.form)'>";
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
	$sql4="select categoria_med from categorias_lineas where codigo_linea='$global_linea' and cod_med='$v_h_cod_med'";
	$resp4=mysql_query($sql4);
	$dat=mysql_fetch_array($resp4);
	$p_categoria_med=$dat[0];
	echo "<td><input type='text' class='texto' size='5' name='$h_categoria_med' value='$p_categoria_med' disabled='true'></td></tr>";

}
echo "</table></center><br>";
echo "<input type='hidden' name='codigo_contacto' value=$codigo_contacto>";
echo"\n<table align='center'><tr><td><a href='rutero_maestro_todo.php?rutero=$rutero'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";

echo "<div class='divBotones'>
		<input type='button' value='Modificar' onClick='envia(this.form)' class='boton'>
		<input type='button' value='Guardar como' onClick='envia_nuevo(this.form)' class='boton'>
	</div>";

echo "<input type='hidden' name='rutero' value='$rutero'>";
echo "</form>";
echo "</div>";
echo "<script type='text/javascript' language=javascript' src='dlcalendar1.js'></script>";
?>