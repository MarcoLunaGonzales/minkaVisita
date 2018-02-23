<script language='JavaScript'>
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
function ajaxVisitador(codigo, diaContacto, codGestion, codCiclo){
	var codVisitador=codigo.value;
	var contenedor;
	contenedor = document.getElementById('divDetalle');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxDetalleAsesoria.php?codVisitador='+codVisitador+'&diaContacto='+diaContacto+'&codGestion='+codGestion+'&codCiclo='+codCiclo,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}else{
			contenedor.innerHTML='Cargando...';
		}
	}
	ajax.send(null)
}
</script>
<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");

	
	echo "<script language='Javascript'>
	
		function guardarAsesoria(f, diaContacto, codGestion, codCiclo)
		{	
			var i;
			var j=0;
			datos=new Array();
			
			var codVisitadores=new Array();
			for(var i=0;i<=f.codVisitador.options.length-1;i++)
			{	if(f.codVisitador.options[i].selected)
				{	codVisitadores[j]=f.codVisitador.options[i].value;
					j++;
				}
			}
			
			alert(codVisitadores+' '+diaContacto+' '+codGestion+' '+codCiclo);
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un registro.');
			}
			else
			{	
				if(confirm('Esta seguro de guardar la Asesoria en consultorio.'))
				{
					location.href='guardarAsesoria.php?datos='+datos+'&codVisitador='+codVisitadores+'&diaContacto='+diaContacto+'&codGestion='+codGestion+'&codCiclo='+codCiclo;
				}
				else
				{
					return(false);
				}
			}
		}
	
		</script>";	

	
	$diaContacto=$_GET['diaContacto'];
	$codCiclo=$_GET['codCiclo'];
	$codGestion=$_GET['codGestion'];
	
	echo "<form method='post' action=''>";
	echo "<center><table border='0' class='textotit'><tr><th>Registro de Asesoria en Consultorio
	<br>"; 
	
	$sql="SELECT distinct(f.codigo_funcionario), concat(f.paterno,' ',f.materno,' ',f.nombres) from funcionarios f, 
	cargos c, ciudades ci, funcionarios_lineas cl where f.cod_cargo=c.cod_cargo and (f.cod_cargo = '1011' or f.cod_cargo =  1022) 
	and f.cod_ciudad='$global_agencia' and f.estado=1 and cl.codigo_funcionario=f.codigo_funcionario and 
	f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Dia Contacto: $diaContacto <br>Visitadores</td></tr></table></center><br>";
	
	echo "<select multiple size='4' id='codVisitador' onChange='ajaxVisitador(this, \"$diaContacto\", $codGestion, $codCiclo);'>";
	while($dat=mysql_fetch_array($resp)) {
		$codigo=$dat[0];
		$nombreFunc=$dat[1];
		echo "<option value='$codigo'>$nombreFunc</option>";
	}
	echo "</select>";
	echo "</th></tr></table></center><br>";
	
	echo "<div id='divDetalle'><center><table border='1' class='texto' cellspacing='0' width='60%'>";
	echo "<tr><th>&nbsp;</th><th>Linea</th><th>Medico</th><th>Especialidad</th><th>Direccion</th></tr>";	
	echo "</table></center></div>";
	
	
	echo"\n<table align='center'><tr><td><a href='navegador_territorios.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><td><input type='button' value='Guardar' name='guardar' class='boton' onclick='guardarAsesoria(this.form,\"$diaContacto\",$codGestion,$codCiclo)'></td></td></tr></table></center>";
	echo "</form>";
?>