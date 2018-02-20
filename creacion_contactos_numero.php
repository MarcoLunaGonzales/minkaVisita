<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
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
		var fecha, fecha_java;
		var dia_java,dia,mes,anio;
		vector = new Array(3);
		fecha=f.input_FechaConsulta.value;
		vector = fecha.split('/');
		dia=vector[0];
		mes=vector[1]-1;
		anio=vector[2];
		fecha_java=new Date(anio,mes,dia);
		dia_java=fecha_java.getDay();
		if(dia_java==0)
		{	alert('El Dia de contacto no puede ser Domingo');
			return(false);
		}
		if(dia_java==6)
		{	alert('El Dia de contacto no puede ser Sabado');
			return(false);
		}
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
			location.href='guardar_contactos.php?variables='+variables+'&ciclo_trabajo=$ciclo_trabajo';
			return(true);
		}
		</script>";

	require("estilos_visitador.inc");
	require("conexion.inc");
	$sql=mysql_query("select cod_ciclo,fecha_ini,fecha_fin from ciclos where cod_ciclo='$ciclo_trabajo' and codigo_linea=$global_linea");
	$dat=mysql_fetch_array($sql);
	$ciclo_actual=$dat[0];
	$fecha_ini_actual=$dat[1];
	$fecha_fin_actual=$dat[2];
	$fecha_actual=$fecha_ini_actual;
	$inicio=$fecha_ini_actual;
	$k=0;
	list($anio,$mes,$dia)=explode("-",$fecha_actual);
	$dia1=$dia;
		while($inicio<$fecha_fin_actual)
		{
			//echo $inicio."<br>";
			$ban=0;
			while($ban==0)
			{	$nueva1 = mktime(0,0,0, $mes,$dia1,$anio);
				$dia_semana=date("l",$nueva1);
				if($dia_semana=='Sunday' or $dia_semana=='Saturday')
				{	$dia1=$dia1+1;
				}
				else
				{	$ban=1;
				}
			}
			$num_dia=intval($k/5)+1;
			if($dia_semana=='Monday'){$dias[$k]="Lunes $num_dia";}
			if($dia_semana=='Tuesday'){$dias[$k]="Martes $num_dia";}
			if($dia_semana=='Wednesday'){$dias[$k]="Miercoles $num_dia";}
			if($dia_semana=='Thursday'){$dias[$k]="Jueves $num_dia";}
			if($dia_semana=='Friday'){$dias[$k]="Viernes $num_dia";}
			
			$fecha_actual=date("Y-m-d",$nueva1);
			$inicio=$fecha_actual;
			list($anio,$mes,$dia)=explode("-",$fecha_actual);
			$dia1=$dia+1;			
			$fecha_actual_formato="$dia/$mes/$anio";
			$fechas[$k]=$fecha_actual_formato;
			$k++;
		}
	//fin vectores dias y fechas
	if($input_FechaConsulta!="")
	{	for($j=0;$j<=$k-1;$j++)
		{	if($input_FechaConsulta==$fechas[$j])
			{	$diasciclo=$dias[$j];
			}
		}
	}
	if($diasciclo!="")
	{	for($j=0;$j<=$k-1;$j++)
		{	if($diasciclo==$dias[$j])
			{	$input_FechaConsulta=$fechas[$j];
			}
		}
	}
	echo "<center><table class='textotit'><tr><th>Creación de Rutero<br>Ciclo de Trabajo:$ciclo_trabajo</th></tr></table></center>";
	echo "<form name='' action=''>";
	echo "<center>";
	echo "<table class='texto' border='1' cellspacing='0'>
	<tr><th>Dia</th><th>Fecha</th><th>Turno</th><th>Número de Contactos</th><tr>
	<tr><td>";
	echo "<select name='diasciclo' class='texto' onChange='relacion_fechas_s(this.form)'>";
	for($j=0;$j<=$k-1;$j++)
	{
		if($diasciclo==$dias[$j])
		{	echo "<option value='$dias[$j]' selected>$dias[$j]</option>";
		}
		else
		{	echo "<option value='$dias[$j]'>$dias[$j]</option>";
		}	
	}	
	echo "</select></td>";
	echo "<td>
				<table border=0><tr><!-- INI fecha con javascript -->
                <TD><INPUT id=input_FechaConsulta size=10 class='texto' onChange='relacion_fechas_t(this.form)' name=input_FechaConsulta value='$input_FechaConsulta'></TD>
                <TD><IMG id=imagenFecha src='imagenes/fecha.bmp'></TD>
                <TD>
                <DLCALENDAR tool_tip='Seleccione la Fecha' 
                daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' 
                navbar_style='background-color: 7992B7; color:ffffff;' 
                input_element_id='input_FechaConsulta' 
                click_element_id='imagenFecha'></DLCALENDAR>
                </TD></tr></table>";
	echo "<td><select class='texto' name='turno'>";
	if($turno=='Am')
	{	echo"<option value='Am' selected>Mañana</option>";
		echo"<option value='Pm'>Tarde</option>";
	}
	else
	{	echo"<option value='Am'>Mañana</option>";
		echo"<option value='Pm' selected>Tarde</option>";
	}
	echo "</select></td>";
	echo "<td align='center'><select class='texto' name='num_medicos' onChange='envia_select(this,this.form)'>";
		echo "<option></option>";
		for($i=1;$i<=20;$i++)
		{
			if($num_medicos==$i)
			{	echo "<option value=$i selected>$i</option>";
			}
			else
			{	echo "<option value=$i>$i</option>";
			}
			
		}
		echo "</select></td></tr>";
	echo "</table></center>";
	
	//creamos los elementos de formulario necesario para completar el proceso de adicion
	
echo "<br>";
echo "<center><table border='1' span class='texto' cellspacing='0'>";
echo "<tr><th>Orden Visita</th><th>Nombre Medico</th><th>Direccion</th><th>Especialidad</th><th>Categoria</th></tr>";
for($i=1;$i<=$num_medicos;$i++)
{
	$h_orden_visita="h_orden$i";
	$h_cod_med="h_cod_med$i";
	$h_cod_zona="h_cod_zona$i";
	$h_especialidad_med="h_especialidad_med$i";
	$h_categoria_med="h_categoria_med$i";
	$v_h_cod_med=$$h_cod_med;
	$v_h_cod_zona=$$h_cod_zona;
	$v_h_especialidad_med=$$h_especialidad_med;
	$v_h_categoria_med=$$h_categoria_med;
	$sql="select DISTINCT(m.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c
		where c.cod_med=m.cod_med and c.codigo_linea='$global_linea' order by m.ap_pat_med";
	$res=mysql_query($sql);
	echo "<tr><td align='center'><input type='text' class='texto' maxlength='2' size='2' name='$h_orden_visita' value='$i' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
	echo "<td><select class='texto' name='$h_cod_med' onChange='envia_select(this,this.form)'>";
	echo "<option>Seleccionar Medico</option>";
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
	$sql2="select cod_zona, direccion from direcciones_medicos where cod_med='$v_h_cod_med'";
	$res2=mysql_query($sql2);
	echo "<td><select class='texto' name='$h_cod_zona'>";
	while($dat2=mysql_fetch_array($res2))
	{
		$zona=$dat2[0];
		$direccion=$dat2[1];
		if($$h_cod_zona==$zona)
		{
		 		echo "<option value='$zona' selected>$direccion</option>"; 
		}
		else
		{
		 		echo "<option value='$zona'>$direccion</option>"; 
		}
	}
	echo "</select></td>";
	$sql3="select e.cod_especialidad, e.desc_especialidad, c.categoria_med
	       from especialidades e, categorias_lineas c
		   where e.cod_especialidad=c.cod_especialidad and c.cod_med='$v_h_cod_med'";
	$resp3=mysql_query($sql3);
	echo "<td><select class='texto' name='$h_especialidad_med' onChange='envia_select(this,this.form)'>";
	echo "<option>Seleccionar Especialidad</option>";
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
	$sql4="select categoria_med from categorias_lineas where cod_especialidad='$v_h_especialidad_med' and cod_med='$v_h_cod_med'";
	$resp4=mysql_query($sql4);
	$dat=mysql_fetch_array($resp4);
	$p_categoria_med=$dat[0];
	echo "<td><input type='text' class='texto' size='5' name='$h_categoria_med' value='$p_categoria_med' disabled='true'></td></tr>";

}
echo "</table></center><br>";
echo "<center><input type='button' value='Guardar' onClick='envia(this.form)' class='boton'></center>";
echo "<input type='hidden' name='ciclo_trabajo' value='$ciclo_trabajo'>";
echo "</form>";
echo "</div>";
echo "<script type='text/javascript' language=javascript' src='dlcalendar1.js'></script>";
?>