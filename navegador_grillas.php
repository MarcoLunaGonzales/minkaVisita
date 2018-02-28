<?php
	require("conexion.inc");
	require("estilos_gerencia.inc");

	echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='grilla.php?cod_ciudad=$cod_ciudad&codigo_linea=$codigo_linea';
		}
		function replicar(f)
		{					
			var i;
			var j=0;
			var j_codigo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_codigo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente una Grilla para replicarla.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar una Grilla para replicarla.');
				}
				else
				{
					window.open('replicar_grilla.php?codigo_grilla='+j_codigo+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=300,height=300');						
				}
			}			
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
			{	alert('Debe seleccionar al menos una Grilla para proceder a su eliminacion.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_grilla.php?datos='+datos+'&cod_ciudad=$cod_ciudad';
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_codigo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_codigo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente una Grilla para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar una Grilla para editar sus datos.');
				}
				else
				{
					location.href='editar_grilla.php?j_codigo='+j_codigo+'&cod_ciudad=$cod_ciudad&codigo_linea=$codigo_linea';
				}
			}
		}
		function activar(f,cod_distrito)
		{
			var i;
			var j=0;
			var j_codigo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_codigo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente una Grilla para activarla.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar una Grilla para activarla.');
				}
				else
				{
					location.href='activar_grilla.php?j_codigo='+j_codigo+'&cod_ciudad=$cod_ciudad&codigo_linea=$codigo_linea&cod_distrito='+cod_distrito;
				}
			}
		}
		</script>
	";

	$codigo_linea=$_GET['codigo_linea'];
	$global_linea=$codigo_linea;
	
	$resp_nombre_linea=mysql_query("select nombre_linea from lineas where codigo_linea=$codigo_linea");
	$dat_nombre_linea=mysql_fetch_array($resp_nombre_linea);
	$nombre_linea=$dat_nombre_linea[0];

	$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad='$cod_ciudad'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$ciudad=$dat[1];
	echo "<form>";
	
	echo "<h1>Registro de Grillas</h1>";
	
	echo "<h2>Linea: $nombre_linea <br> Territorio: $ciudad</h2>";
	
	echo "<center><table class='texto'>";	
	echo "<tr><th>&nbsp</th><th>Nombre Grilla</th><th>Distrito</th><th>Creacion</th>
	<th>Modificacion</th><th>Estado</th><th>&nbsp</th></tr>";
	
	$sql="select g.codigo_grilla, g.nombre_grilla, g.fecha_creacion, g.fecha_modificacion, g.estado, 
	(select d.descripcion from distritos d where d.cod_dist=g.cod_distrito)distrito, g.cod_distrito 
	from grilla g where g.agencia='$cod_ciudad' and g.codigo_linea='$global_linea'";

	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		$fecha_creacion=$dat[2];
		$fecha_modi=$dat[3];
		$estado=$dat[4];
		$distrito=$dat[5];
		$codDistrito=$dat[6];
		
		if($estado==0)
		{	$desc_estado="No Vigente";	}
		else
		{	$desc_estado="Vigente";	}	
		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td>
		<td align='center'>$nombre</td>
		<td align='center'>$distrito</td>
		<td align='center'>$fecha_creacion</td>
		<td align='center'>$fecha_modi</td>
		<td align='center'>$desc_estado</td>
		<td align='center'>
			<a href='ver_grilla.php?codigo_grilla=$codigo&codigo_linea=$codigo_linea'>
			<img src='imagenes/detalle.png' width='40'>
			</a></td></tr>";
	}
	echo "</table></center><br>";
	
	echo"\n<table align='center'><tr><td><a href='grilla_ciudades.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
	
	echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Replicar' class='boton' onclick='replicar(this.form)'>
		<input type='button' value='Activar Grilla' class='boton' onclick='activar(this.form,$codDistrito)'>
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
	</div>";
	echo "</form>";
?>