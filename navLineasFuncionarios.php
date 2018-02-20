<?php

echo "<script language='Javascript'>
		function enviar_nav(codLinea)
		{	location.href='registrarLineasFuncionarios.php?codLinea='+codLinea;
		}
		function eliminar_nav(codLinea, f)
		{
			var i;
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
			{	alert('Debe seleccionar al menos un visitador para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='deleteLineasFuncionarios.php?codigoLinea='+codLinea+'&datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}

	
		</script>
	";
	require("conexion.inc");
	require("funcion_nombres.php");
	require("estilos_administracion.inc");
	echo "<form method='post' action=''>";
	
	$codigoLinea=$_GET['codigoLinea'];
	$nombreLinea=nombreLinea($codigoLinea);
	
	echo "<center><table border='0' class='textotit'><tr><td>Lineas Asignadas a Funcionarios <br> Linea: $nombreLinea</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='70%'>";
	echo "<tr><td>&nbsp;</td><th>&nbsp;</th><th>Líneas</th><th>&nbsp;</th></tr>";
	
	$sql="select f.codigo_funcionario, concat(f.paterno,' ', f.materno, ' ', f.nombres)visitador, 
		(select c.descripcion from ciudades c where c.cod_ciudad=f.cod_ciudad)ciudad
		 from funcionarios_lineas fl, funcionarios f 
		where fl.codigo_linea=$codigoLinea and fl.codigo_funcionario=f.codigo_funcionario and f.cod_cargo=1011 
		order by ciudad, visitador";
	$resp=mysql_query($sql);

	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombreVisitador=$dat[1];
		$nombreAgencia=$dat[2];
		
		echo "<tr><td align='center'>$indice_tabla</td><td align='center'>
		<input type='checkbox' name='codigo' value='$codigo'></td>
		<td>$nombreVisitador</td>
		<td>$nombreAgencia</td>
		</tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav($codigoLinea)'></td>
	<td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav($codigoLinea, this.form)'></td>
	</tr></table></center>";
	echo "</form>";
?>
