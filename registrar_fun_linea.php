<?php
	require("conexion.inc");
	require("estilos_administracion.inc");

echo "<script language='Javascript'>
		function anadir_categoria(f)
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
			{	alert('Debe seleccionar al menos una Línea.');
				return(false);
			}
			else
			{	 location.href='guardar_fun_linea.php?datos='+datos+'&j_funcionario=$j_funcionario';
		    }
		}
		</script>";
	$sql_cab=mysql_query("select paterno, materno, nombres from funcionarios where codigo_funcionario='$j_funcionario'");
	$dat_cab=mysql_fetch_array($sql_cab);
	$nombre_funcionario="$dat_cab[2] $dat_cab[0] $dat_cab[1]";
	echo "<center><table border='0' class='textotit'><tr><td>Adicionar Líneas x Funcionario</td></tr></table></center>";
	echo "<center><table border='0' class='textotit'><tr><td>Funcionario: $nombre_funcionario</td></tr></table></center><br>";
	echo "<form method='post'>";
	$sql="select codigo_linea, nombre_linea from lineas where linea_promocion=1 and estado=1 order by nombre_linea";
	$resp=mysql_query($sql);
	echo "<center><table border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Producto</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_linea=$dat[0];
		$nombre_linea=$dat[1];
		$ban=0;
		$sql_filtro="select codigo_linea from funcionarios_lineas where codigo_linea='$codigo_linea' and codigo_funcionario='$j_funcionario'";
		$resp_filtro=mysql_query($sql_filtro);
		while($dat_filtro=mysql_fetch_array($resp_filtro))
		{	$codigo_filtro=$dat_filtro[0];
			if($codigo_filtro==$codigo_linea)
			{	$ban=1;
			}		
		}
		if($ban==0)
		{	echo "<tr><td align='center'><input type='checkbox' name='codigo' value='$codigo_linea'></td><td>$nombre_linea</td></tr>";
		}		
	}
		echo "</table></center><br>";
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Adicionar' class='boton' onclick='anadir_categoria(this.form)'></td></tr></table></center>";
		echo "</form>";
?>