<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	echo "<script language='Javascript'>
		function adicionar(f)
		{	var i;
			var j=0;
			datos=new Array();
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
			{	alert('Debe seleccionar al menos un visitador para adicionarlo al Grupo.');
			}
			else
			{	location.href='guardar_visitador_grupoespe.php?datos='+datos+'&cod_grupo=$cod_grupo';
			}
		}
		</script>";
	require("conexion.inc");
	require("estilos_gerencia.inc");
	echo "<form method='post' name='form1'>";
	//formamos la cabecera
	$sql_cab="select nombre_grupo_especial,cod_especialidad from grupo_especial where codigo_grupo_especial='$cod_grupo'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_grupo_espe=$dat_cab[0];
	$cod_espe=$dat_cab[1];
	//fin formar cabecera
	echo "<center><table border='0' class='textotit'><tr><td align='center'>Adicionar Visitador de Grupos Especiales<br>Grupo Especial:<strong>$nombre_grupo_espe</strong></td></tr></table></center><br>";
	$sql="select f.codigo_funcionario, f.paterno, f.materno, f.nombres from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and f.cod_cargo=1011 and f.estado=1 and f.cod_ciudad='$global_agencia' and fl.codigo_linea='$global_linea'";
	$resp=mysql_query($sql);
	echo "<center><table border='1' class='textomini' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Visitador</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		$pat=$dat[1];
		$mat=$dat[2];
		$nom=$dat[3];
		$nombre_visitador="$pat $mat $nom";
		$sql_verificacion="select * from grupo_especial_detalle_visitadores where codigo_funcionario='$cod' and
		codigo_grupo_especial='$cod_grupo'";
		$resp_verificacion=mysql_query($sql_verificacion);
		$filas_verificacion=mysql_num_rows($resp_verificacion);
		if($filas_verificacion==0)
		{	echo "<tr><td><input type='checkbox' name='codigo' value='$cod'></td><td>$nombre_visitador</td></tr>";
		}

	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' class='boton' onclick='adicionar(this.form)'></td></tr></table></center>";
	echo "</form>";
?>