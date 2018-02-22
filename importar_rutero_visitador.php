<?php

	require("conexion.inc");
	require("estilos_regional_pri.inc");

	echo "<script language='Javascript'>
		function importar_rutero(f)
		{
			var i;
			var j=0;
			var j_cargo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_cargo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un Rutero Maestro para importarlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Rutero Maestro para importarlo.');
				}
				else
				{
					location.href='importar_rutero.php?rutero_importado='+j_cargo+'&visitador_importado=$visitador_importado&linea_importada=$linea_importada&visitador=$j_funcionario';
				}
			}
		}
		</script>";
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$j_funcionario'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	$sql_lineas="select nombre_linea from lineas where codigo_linea='$linea_importada'";
	$resp_lineas=mysql_query($sql_lineas);
	$dat_lineas=mysql_fetch_array($resp_lineas);
	$nombre_linea=$dat_lineas[0];
	$sql_visitador="select paterno, materno, nombres from funcionarios 
					where codigo_funcionario='$visitador_importado'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_vis=mysql_fetch_array($resp_visitador);
	$nombre_vis_impor="$dat_vis[0] $dat_vis[1] $dat_vis[2]";
	echo "<center><table border='0' class='textotit'><tr><th>Importar Rutero Maestro<br>Visitador: $nombre_funcionario<br>Línea a importar el rutero maestro: $nombre_linea<br>Visitador a importar el rutero maestro: $nombre_vis_impor</th></tr></table></center><br>";
	echo "<form method='post' action=''>";
	echo "<center><table border='1' width='50%' cellspacing='0' class='texto'><tr><th>&nbsp;</th><th>Nombre de Rutero</th><th>Estado</th><th>&nbsp;</th></tr>";
	$sql="select cod_rutero, nombre_rutero, estado_aprobado from rutero_maestro_cab  where cod_visitador='$visitador_importado' and codigo_linea='$linea_importada' order by nombre_rutero";
	$resp=mysql_query($sql);
	$filas_ruteros=mysql_num_rows($resp);
	while($dat=mysql_fetch_array($resp))
	{
		$cod_rutero=$dat[0];
		$nombre_rutero=$dat[1];
		$estado=$dat[2];
		if($estado==0)
		{$estado_desc="No Aprobado";}
		else{$estado_desc="Aprobado";}
		echo"<tr><td><input type='checkbox' name='codigo' value='$cod_rutero'></td><td align='center'>$nombre_rutero</td><td align='center'>$estado_desc</td><td align='center'><a href='funcionario_rutero_maestro_importar.php?j_funcionario=$j_funcionario&linea_importada=$linea_importada&rutero_importado=$cod_rutero&visitador_importado=$visitador_importado'>Ver Todo >></a></td></tr>";
	}
	echo "</table><br>";
echo"\n<table align='center'><tr><td><a href='importar_rutero_linea?visitador=$j_funcionario&linea_importada=$linea_importada'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Importar' class='boton' onclick='importar_rutero(this.form)'></td></tr></table></center>";
echo "</form>";
?>