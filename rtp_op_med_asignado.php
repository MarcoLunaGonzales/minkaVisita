<?php 	
	echo "<script language='JavaScript'>
		function envia_select(f){
			var formato, ver;
			formato=f.formato.value;
			ver=f.ver.value;
			location.href='rtp_op_med_asignado.php?ver='+ver+'&formato='+formato+'';
			return(true);
		}
		function envia_formulario(f)   
		{	var formato, ver, parametro;
			formato=f.formato.value;
			ver=f.ver.value;
			if(ver=='Especialidad' || ver=='Categoria')
			{	parametro=f.parametro.value;
				window.open('rpt_med_asignado.php?formato='+formato+'&ver='+ver+'&parametro='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
			}
			else
			{	window.open('rpt_med_asignado.php?formato='+formato+'&ver='+ver+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');			
			}
			return(true);
			//f.submit();
		}
		function envia_formulario_pdf(f)
		{	var formato, ver, parametro;
			formato=f.formato.value;
			ver=f.ver.value;
			if(ver=='Especialidad' || ver=='Categoria')
			{	parametro=f.parametro.value;
				window.open('rpt_med_asignado_pdf.php?formato='+formato+'&ver='+ver+'&parametro='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			}
			else
			{	window.open('rpt_med_asignado_pdf.php?formato='+formato+'&ver='+ver+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			}
			return(true);
			//f.submit();
		}
		function envia_formulario_xls(f)
		{	var formato, ver, parametro;
			formato=f.formato.value;
			ver=f.ver.value;
			if(ver=='Especialidad' || ver=='Categoria')
			{	parametro=f.parametro.value;
				window.open('rpt_med_asignado_xls.php?formato='+formato+'&ver='+ver+'&parametro='+parametro+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			}
			else
			{	window.open('rpt_med_asignado_xls.php?formato='+formato+'&ver='+ver+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			}
			return(true);
			//f.submit();
		}
		</script>";
	require("conexion.inc");
	require("estilos_visitador.inc");
	require("espacios_visitador.inc");
	echo "<form action='rpt_med_asignado.php' method='get'>";
	echo "<center><table class='textotit'><tr><th>Reporte Medicos Asignados</th></tr></table><br>";
	echo "<center><table class='texto' border='1' cellspacing='0'>";
	echo "<tr><th align='left'>Ver:</th><td><select name='formato' class='texto'>";
					if($formato=="Resumido"){echo "<option value='Resumido' selected>Resumido</option>";}else{echo "<option value='Resumido'>Resumido</option>";}
					if($formato=="Detallado"){echo "<option value='Detallado' selected>Detallado</option></select></td></tr>";}else{echo "<option value='Detallado'>Detallado</option></select></td></tr>";}

	echo "<tr><th align='left'>Ordenado por:</th><td align='center'><select OnChange='envia_select(this.form)' name='ver' class='texto'>";
					if($ver==""){}else{}echo "<option></option>";
					if($ver=="Alfabetico"){echo "<option value='Alfabetico' selected>Alfabetico</option>";}else{echo "<option value='Alfabetico'>Alfabetico</option>";}
					if($ver=="Categoria"){echo "<option value='Categoria' selected>Categoria</option>";}else{echo "<option value='Categoria'>Categoria</option>";}
					if($ver=="Especialidad"){echo "<option value='Especialidad' selected>Especialidad</option>";}else{echo "<option value='Especialidad'>Especialidad</option>";}
					if($ver=="RUC"){echo "<option value='RUC' selected>Codigo</option></select></td></tr>";}else{echo "<option value='RUC'>Codigo</option></select></td></tr>";}

	echo "</table><br>";
	
	if($ver=="Especialidad")
	{
	  	echo "<table class='texto' border='1' cellspacing='0'>";
		echo "<tr><th align='left'>Parametro de ordenación:</th><td align='center'><select name='parametro' class='texto'>";
		$sql_espe="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp_espe=mysql_query($sql_espe);
	  	while($dat_espe=mysql_fetch_array($resp_espe))
	  	{
		 	$cod_espe=$dat_espe[0];
		 	$desc_espe=$dat_espe[1];
			echo "<option value='$cod_espe'>$desc_espe</option>";   
		}
		echo "</select>";
		echo "</td></tr></table><br>";
	}
	if($ver=="Categoria")
	{
	  	echo "<table class='texto' border='1' cellspacing='0'>";
		echo "<tr><th align='left'>Parametro de ordenación:</th><td><select name='parametro' class='texto'>";
		$sql="select categoria_med from categorias_medicos order by categoria_med";
		$resp=mysql_query($sql);
	  	while($dat=mysql_fetch_array($resp))
	  	{
		 	$cat_med=$dat[0];
		 	echo "<option value='$cat_med'>$cat_med</option>";   
		}
		echo "</select>";
		echo "</td></tr></table><br>";
	}
	echo"\n<table align='center'><tr><td><a href='principal_visitador.php'><img  border='0' src='imagenes/home.gif'>Principal</a></td></tr></table>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center>";
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center>";
	echo "</form>";	
?> 