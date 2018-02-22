<?php
		require("conexion.inc");
	require("estilos_administracion.inc");

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_fun_linea.php?j_funcionario=$j_funcionario';
		}
		function eliminar_nav(f)
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
			{	alert('Debe seleccionar al menos una Línea para eliminarla del acceso al Funcionario.');
			}
			else
			{	
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_fun_linea.php?datos='+datos+'&j_funcionario=$j_funcionario';
				}
				else
				{
					return(false);
				}
			}
		}
		function definir_lineaclave(f)
		{	
			var i;
			var j=0;
			var cod_linea;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	cod_linea=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar una Línea para definirla como clave.');
				return(false);
			}
			if(j>1)
			{	alert('Debe seleccionar una Línea para definirla como clave.');
				return(false);
			}
			if(j==1)
			{	
				if(confirm('Esta seguro de definir esta Línea como clave'))
				{
					location.href='guarda_definirlineaclave.php?cod_linea='+cod_linea+'&j_funcionario=$j_funcionario';
				}
				else
				{
					return(false);
				}
			}
		}
		</script>";
	$sql_cab=mysql_query("select paterno, materno, nombres from funcionarios where codigo_funcionario='$j_funcionario'");
	$dat_cab=mysql_fetch_array($sql_cab);
	$nombre_funcionario="$dat_cab[2] $dat_cab[0] $dat_cab[1]";
	echo "<center><table border='0' class='textotit'><tr><th>Líneas asignadas a Funcionario</th></tr></table></center>";
	echo "<center><table border='0' class='textotit'><tr><th>Funcionario: $nombre_funcionario</th></tr></table></center><br>";
	echo "<form>";
	$sql="select l.codigo_linea, l.nombre_linea
		 from lineas l,funcionarios_lineas f where f.codigo_linea=l.codigo_linea and f.codigo_funcionario='$j_funcionario' order by l.nombre_linea";
	$resp=mysql_query($sql);
	$numero_filas=mysql_num_rows($resp);
	if($numero_filas!=0)
	{
		echo "<center><table border='1' class='texto' cellspacing='0' width='25%'>";
		echo "<tr><th>&nbsp;</th><th>Línea</th><th>Línea Clave</th></tr>";
		while($dat=mysql_fetch_array($resp))
		{
			$codigo_linea=$dat[0];
			$nombre_linea=$dat[1];
			$sql_lineaclave="select * from funcionarios where codigo_funcionario=$j_funcionario and codigo_lineaclave=$codigo_linea";
			$resp_lineaclave=mysql_query($sql_lineaclave);
			$filas_lineaclave=mysql_num_rows($resp_lineaclave);
			if($filas_lineaclave==1)
			{	echo "<tr><td align='center'><input type='checkbox' name='codigo' value='$codigo_linea'></td><td>$nombre_linea</td><td align='center'><img src='imagenes/si.png'></td></tr>";
			}
			else
			{	echo "<tr><td align='center'><input type='checkbox' name='codigo' value='$codigo_linea'></td><td>$nombre_linea</td><td align='center'><img src='imagenes/no.png'></td></tr>";
			}			
		}
		echo "</table></center><br>";
		echo"\n<table align='center'><tr><td><a href='navegador_funcionarios.php?cod_ciudad=$cod_territorio'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Definir Línea Clave' class='boton' onclick='definir_lineaclave(this.form)'></td></tr></table></center>";
		echo "</form>";
	}
	else
	{	echo "<center><table border='0' class='texto' cellspacing='0'>";
		echo "<tr><th>No existen Líneas definidas para este Funcionario</th></tr></table><br>";
		echo"\n<table align='center'><tr><td><a href='navegador_funcionarios.php?cod_ciudad=$cod_territorio'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
		echo "<center><table border='0' class='texto'>";
		echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td></tr></table></center>";
		echo "</form>";
	}
	
?>