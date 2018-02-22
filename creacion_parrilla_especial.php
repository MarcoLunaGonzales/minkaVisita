<?php


require("estilos.inc");
require("conexion.inc");

		echo "<script language='JavaScript'>
			function envia_select(menu,form){
			form.submit();
			return(true);}
			function abrir_medicos()
			{	ventana_medicos=open('medicos_grupo_espe.php?cod_grupo=$grupo_especial','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=500,height=200')
			}
			function prueba(f,num_el){
			var i,j;
			var n=num_el;
			var extra='muestra';
			var extra1='cantidad';
			var extra2='apoyo';
			var extra3='cantidad_a';
			var extra4='obs';
			var extra5='prioridad';
			var extra6='extra';
			var valor,valor1,valor2,valor3,valor4,valor5,valor6;
			var tipo,j_cod_muestra,j_cantidad,j_num_visita,j_agencia,j_linea_visita;
			vector_muestras=new Array(n-1);
			vector_cantidad=new Array(n-1);
			vector_apoyo=new Array(n-1);
			vector_cantidad_a=new Array(n-1);
			vector_obs=new Array(n-1);
			vector_categoria=new Array(50);
			vector_priori=new Array(n-1);
			vector_extra=new Array(n-1);
			var j_ciclo, j_especialidad,j_grupoespe,j_categoria,j_obs,j_muestras_extras,j_prioridad,j_extra;
			j_ciclo=f.elements[0].value;
			j_especialidad=f.h_espe.value;
			j_num_visita=f.num_visita.value;
			j_agencia=f.agencia.value;
			j_grupoespe=f.grupo_especial.value;
			j_muestras_extras=f.h_numero_extras.value;
			i=1;
			//esta parte valida el formulario para mandarlo
			var comp='obs';
			var comp1='linea_visita';
			for(j=0;j<=f.length-1;j++)
				{
					if(f.elements[j].name.indexOf(comp1)==-1)
					{	if(f.elements[j].name.indexOf(comp)==-1)
						{	if(f.elements[j].value=='')
							{
								alert('Algun elemento no tiene valor.');
								return(false);
							}
						}
					}
				}
			//hasta aca validar formulario
			//valida las prioridades
			var el='prioridad';
			var suma_real=0;
			var valor_v,numero,suma=0;
			var num_productos;
			num_productos=(f.h_numero_muestras.value*1)+(f.h_numero_extras.value*1);
			for(var j=1;j<=num_productos;j++)
			{
				valor_v=el+j;
				suma_real=suma_real+j;
				for(i=0;i<=f.length-2;i++)
				{	if(f.elements[i].name==valor_v)
					{	numero=(f.elements[i].value)*1;
						suma=suma+numero;
					}
				}
			}
			if(suma!=suma_real)
			{	alert('El Orden Promocional no puede repetirse ni debe ser cero.');
				alert('suma real'+suma_real+'suma'+suma+j);
				return(false);
			}
			//fin prioridades
			//validar sin material de apoyo producto no puede ser cero
			var cant_prod_comp,material_comp;
			cant_prod_comp='cantidad';
			material_comp='apoyo';
			for(j=0;j<=f.length-1;j++)
			{
				if(f.elements[j].name.indexOf(material_comp)!=-1)
				{
					if(f.elements[j].value==0 && f.elements[j-1].value==0)
					{	alert('Si no se lleva Material de Apoyo, la cantidad del Producto no puede ser cero.');
						return(false);
					}
					if(f.elements[j].value==0 && f.elements[j+1].value!=0)
					{	alert('Si no se lleva Material de Apoyo, su cantidad debe ser cero.');
						return(false);
					}
					if(f.elements[j-1].value==0 && f.elements[j+1].value==0)
					{	alert('Si se lleva Producto y Material de Apoyo alguna de las cantidades debe ser distinta de cero.');
						return(false);
					}
				}
			}
			//fin validar sin material de apoyo
			for(j=0;j<=f.length-1;j++)
				{
					if(f.elements[j].type=='checkbox')
					{
						if(f.elements[j].checked==true)
						{	vector_categoria[i]=f.elements[j].value;
							i=i+1;
						}

					}
					//alert(f.elements[j].checked);
				}
			for(i=1;i<=n;i++)
			{
				valor=extra+i;
				valor1=extra1+i;
				valor2=extra2+i;
				valor3=extra3+i;
				valor4=extra4+i;
				valor5=extra5+i;
				valor6=extra6+i;
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
					if(f.elements[j].name==valor2)
					{
						tipo=f.elements[j];
						j_cod_muestra=tipo.options[tipo.selectedIndex].value;
						vector_apoyo[i-1]=j_cod_muestra;
					}
					if(f.elements[j].name==valor3)
					{
						tipo=f.elements[j];
						j_cantidad=tipo.options[tipo.selectedIndex].value;
						vector_cantidad_a[i-1]=j_cantidad;
					}
					if(f.elements[j].name==valor4)
					{
						tipo=f.elements[j];
						j_obs=tipo.value;
						vector_obs[i-1]=j_obs;
					}
					if(f.elements[j].name==valor5)
					{
						tipo=f.elements[j];
						j_prioridad=tipo.value;
						vector_priori[i-1]=j_prioridad;
					}
					if(f.elements[j].name==valor6)
					{
						tipo=f.elements[j];
						j_extra=tipo.value;
						vector_extra[i-1]=j_extra;
					}
				}

			}
			//inicio validar muestras repetidas
			var buscado,cant_buscado;
			for(k=0;k<=(n-1);k++)
			{	buscado=vector_muestras[k];
				cant_buscado=0;
				for(m=0;m<=(n-1);m++)
				{	if(buscado==vector_muestras[m])
					{	cant_buscado=cant_buscado+1;
					}
				}
				if(cant_buscado>1)
				{	alert('Los Productos a llevar no pueden repetirse');
					return(false);
				}
			}
			//fin validar muestras repetidas
			location.href='guarda_parrilla_especial.php?j_grupoespe='+j_grupoespe+'&j_agencia='+j_agencia+'&j_ciclo='+j_ciclo+'&j_especialidad='+j_especialidad+'&vec_categoria='+vector_categoria+'&vec_muestras='+vector_muestras+'&vec_cant='+vector_cantidad+'&vec_apoyo='+vector_apoyo+'&vec_cant_a='+vector_cantidad_a+'&vector_obs='+vector_obs+'&vector_priori='+vector_priori+'&vector_extra='+vector_extra+'&cantidad='+n+'&j_num_visita='+j_num_visita+'&j_linea_visita='+j_linea_visita+'&j_muestras_extras='+j_muestras_extras+'';
			return(true);
			}
			</script>";
			
	$sql="select * from ciclos where estado<>'Activo' and estado<>'Cerrado' and codigo_gestion=1014 order by fecha_ini";
	$resp=mysql_query($sql);
		$sql1="select * from ciclos where cod_ciclo='$h_ciclo'";
		$resp1=mysql_query($sql1);
		$dat1=mysql_fetch_array($resp1);
		$p_inicio=$dat1[1];
		$p_fin=$dat1[2];
		echo "<center><table border='0' class='textotit'>";
	echo "<tr><td>Creación de Parrilla Médica Especial</td></tr>";
	echo "</table></center>";
	echo "<form action=''>";
	echo "<center>";
	echo "<table border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>Ciclo</th><th>Agencia</th><th>Grupo Especial</th><th>Especialidad</th><th>Medicos del Grupo</th></tr>";
	echo "<tr><td align='center'><select name='h_ciclo' class='texto' onChange='envia_select(this,this.form)'>";
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
	{	$p_ciclo=$dat[0];
		$p_estado=$dat[3];
		if($p_estado=="Activo")
		{	$desc_estado="En Curso"; }
		if($p_estado=="Inactivo")
		{	$desc_estado="Programado"; }
		if($p_estado=="Cerrado")
		{	$desc_estado="Cerrado"; }
		if($h_ciclo==$p_ciclo)
		{	echo "<option value=$p_ciclo selected>$p_ciclo $desc_estado</option>";
		}
		else
		{	echo "<option value=$p_ciclo>$p_ciclo $desc_estado</option>";
		}

	}
	echo "</select>&nbsp;";
	echo "<input type='text' name='h_inicio' value='$p_inicio' disabled='true' size='10' class='texto'>&nbsp;<input type='text' name='h_fin' value='$p_fin' disabled='true' size='10' class='texto'></td>";
	echo "<td align='center'><select name='agencia' class='texto' onChange='envia_select(this,this.form)'>";
			$sql_agencia=mysql_query("select cod_ciudad,descripcion from ciudades order by descripcion asc");
			while($dat_agencia=mysql_fetch_array($sql_agencia))
			{	$cod_ciudad=$dat_agencia[0];
				$descripcion=$dat_agencia[1];
				if($cod_ciudad==$agencia)
				{	echo "<option value='$cod_ciudad' selected>$descripcion</option>";
				}
				else
				{	echo "<option value='$cod_ciudad'>$descripcion</option>";
				}
			}
	echo "</select></td>";
	echo "<td align='center'><select name='grupo_especial' class='texto' onChange='envia_select(this,this.form)'>";
	$sql_grupoespe="select codigo_grupo_especial, nombre_grupo_especial from grupo_especial
					where agencia='$agencia' and codigo_linea='$global_linea' order by nombre_grupo_especial";
	$resp_grupoespe=mysql_query($sql_grupoespe);
	echo "<option value=''></option>";
	while($dat_grupoespe=mysql_fetch_array($resp_grupoespe))
	{	$cod_grupoespe=$dat_grupoespe[0];
		$nombre_grupoespe=$dat_grupoespe[1];
		if($grupo_especial==$cod_grupoespe)
		{	echo "<option value='$cod_grupoespe' selected>$nombre_grupoespe</option>";
		}
		else
		{	echo "<option value='$cod_grupoespe'>$nombre_grupoespe</option>";
		}
	}
	echo "</select></td>";
	//vemos que especialidad tiene el grupo
	/*$sql2="select e.cod_especialidad, e.desc_especialidad from especialidades e, grupo_especial g
		where e.cod_especialidad=g.cod_especialidad and g.codigo_grupo_especial='$grupo_especial'";
	$resp2=mysql_query($sql2);
	$dat2=mysql_fetch_array($resp2);
	$p_cod_espe=$dat2[0];
	$p_desc_espe=$dat2[1];*/
	
	echo "<td align='center'><input type='text' name='h_espefalso' class='texto' value='0' disabled='true'></td>";
	
	$sql_num_medicos_grupo="select * from grupo_especial_detalle where codigo_grupo_especial='$grupo_especial'";
	$resp_num_medicos_grupo=mysql_query($sql_num_medicos_grupo);
	$numero_medicos_grupo=mysql_num_rows($resp_num_medicos_grupo);
	echo "<td align='center'><table width='100%' class='texto'><tr><td width='50%' align='center'>$numero_medicos_grupo</td><td align='right'><a href='javascript:abrir_medicos()'>Ver>></a></td></tr></table></td></tr>";
	
	echo "<input type='hidden' name='h_espe' value='0'>";
	
	echo "<tr><th>Número Productos</th><th>Número Visita</th><th>Productos Extra</th></tr>";
	/*desde aqui registramos el numero de muestras que se debe dejar y lo enviamos
	a este mismo modulo para crear dinamicamente las muestras*/
	//vemos cuantos productos existen ya sea por linea o por especialidad
	$sql5="select codigo, descripcion, presentacion from muestras_medicas order by descripcion";
	$resp5=mysql_query($sql5);
	$numero_de_muestras=mysql_num_rows($resp5);
	if($numero_de_muestras>10)
	{	$numero_de_muestras=10;
	}
	//fin contar cuantos productos hay

	echo "<tr><td align='center'><select class='texto' name='h_numero_muestras' onChange='envia_select(this,this.form)'>";
	for($i=1;$i<=$numero_de_muestras;$i++)
	{	if($h_numero_muestras==$i)
		{	echo "<option value='$i' selected>$i</option>";
		}
		else
		{	echo "<option value='$i'>$i</option>";
		}

	}
	echo "</td>";
	echo "<td align='center'><select name='num_visita' class='texto'>";
		for($k=1;$k<=8;$k++)
		{
		  	if($num_visita==$k)
		  	{
				echo "<option value='$k' selected>Visita $k</option>";
			}
			else
			{
		  		echo "<option value='$k'>Visita $k</option>";
			}
		}
	echo "</select></td>";

	echo "<td align='center'><select class='texto' name='h_numero_extras' onChange='envia_select(this,this.form)'>";
	for($i=0;$i<=10;$i++)
	{	if($h_numero_extras==$i)
		{	echo "<option value='$i' selected>$i</option>";
		}
		else
		{	echo "<option value='$i'>$i</option>";
		}

	}
	echo "</td>";
	echo "</tr>";
	echo "</table></center>";
	echo "<br>";
	/*desde aqui construimos dinamicamente el numero de muestras con la
	* variable $h_numero_muestras*/
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><td>Producto Extra</td><td bgcolor='#66CCFF' width='30%'></td></tr></table></center><br>";

	echo "<center><table span class='texto' border='1' cellspacing='0'>";
	echo "<tr><th>Orden Promocional</th><th>Producto</th><th> </th><th>Cantidad</th><th> </th><th>Material de Apoyo</th><th> </th><th>Cantidad</th><th>Observaciones</th></tr>";
	$numero_total=$h_numero_muestras+$h_numero_extras;
	for($i=1;$i<=$numero_total;$i++)
	{
		$p_muestras="muestra";
		$prioridad="prioridad";
		$p_m="$p_muestras$i";
		if($i<=$h_numero_muestras)
		{	echo "<input type='hidden' name='extra$i' value='0'>";
			echo "<tr>";
			/*if($linea_visita!="")
			{	$sql5="select l.codigo_mm,m.descripcion,m.presentacion from muestras_medicas m, lineas_visita_detalle l
				where m.codigo=l.codigo_mm and l.codigo_l_visita=$linea_visita order by m.descripcion";
			}
			else
			{	$sql5="select p.codigo_mm,m.descripcion, m.presentacion from muestras_medicas m, producto_especialidad p
				where m.codigo=p.codigo_mm and p.codigo_linea='$global_linea' and p.cod_especialidad='$h_espe' order by m.descripcion";
			}*/
			$sql5="select codigo, descripcion, presentacion from muestras_medicas order by descripcion";
		}
		else
		{	echo "<input type='hidden' name='extra$i' value='1'>";
			echo "<tr bgcolor='#66CCFF'>";
			$sql5="select codigo, descripcion, presentacion from muestras_medicas order by descripcion";
		}
		echo "<td align='center'><input type='text' name='$prioridad$i' value='$i' class='texto' size='1' maxlength='2' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;'></td>";
		echo "<td align='center'><select name='$p_m' class='textomini'>";

		$resp5=mysql_query($sql5);
		$numero_prod=mysql_num_rows($resp5);
		echo "<option value=''></option>";
		while($dat5=mysql_fetch_array($resp5))
		{
			$p_codigo_muestra=$dat5[0];
			$p_desc_muestra=$dat5[1];
			$p_pres_muestra=$dat5[2];
			echo "<option value='$p_codigo_muestra'>$p_desc_muestra $p_pres_muestra</option>";
		}
		echo "</select></td><td>  </td>";

		$p_cantidad="cantidad";
		echo "<td align='center'><select class='textomini' name='$p_cantidad$i'>";
			for($k=0;$k<=10;$k++)
			{	echo "<option value='$k'>$k</option>";
			}
			echo "</select></td>";
			echo "<td></td>";

				$p_apoyo="apoyo";
				$p_a="$p_apoyo$i";
				echo "<td><select name='$p_a' class='textomini'>";
				$sql6="select * from material_apoyo order by descripcion_material";
				$resp6=mysql_query($sql6);
				echo "<option value=''></option>";
				while($dat6=mysql_fetch_array($resp6))
				{
					$p_codigo_apoyo=$dat6[0];
					$p_desc_apoyo=$dat6[1];
					echo "<option value='$p_codigo_apoyo'>$p_desc_apoyo</option>";
				}
				echo "</select></td><td>  </td>";

				$p_cantidad_a="cantidad_a";
				echo "<td align='center'><select class='textomini' name='$p_cantidad_a$i'>";
				for($j=0;$j<=10;$j++)
				{	echo "<option value='$j'>$j</option>";
				}
				echo "</select></td>";
				$obs="obs";
				echo "<td><input type='text' class='texto' name='$obs$i' size='30'></td>";
				echo "</tr>";
			}
	echo "</table><br>";

	echo"\n<table align='center'><tr><td><a href='navegador_parrilla_especial_ciclos.php?ciclo_trabajo=$ciclo_trabajo'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
	echo "<input type='button' value='Guardar' class='boton' onClick='prueba(this.form,$numero_total)'>";
	echo "<center><table border='0' class='texto' width='90%'><tr><th>Nota: El Orden Promocional puede ser cambiado de acuerdo a necesidad, especialmente en el caso de Productos Extras.</th></tr></table></center><br>";
?>