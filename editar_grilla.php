<?php
		require("conexion.inc");
		require("estilos_gerencia.inc");

	/**
	 * Desarrollado por Datanet-Bolivia.
	 * @autor: Marco Antonio Luna Gonzales
	 * Sistema de Visita M&eacute;dica
	 * * @copyright 2005
	*/
	echo "<script language='JavaScript'>
	function valida(f)
	{	for(j=0;j<=f.length-1;j++)
		{	if(f.elements[j].value=='')
		{	alert('Debe llenar todos los campos.');
		alert(f.elements[j].name);
		return(false);
	}
	else
		{	f.elements[j].disabled=false;
		}
	}	
	f.submit();
	return(true);
}
function calcula_frecuencia(f,n)
{	/*var i;
	var contador=0;
	var aux;
	var vis;
	var frec='frecuencia';
	var con='contactos';
	for(i=1;i<=n;i++)
		{	frecuencia=frec+i;
			contacto=con+i;
			for(j=0;j<=f.length-1;j++)
				{	if(frecuencia==f.elements[j].name)
					{	f.elements[j+2].value=(f.elements[j].value*f.elements[j+1].value);
						aux=(f.elements[j+2].value*1);
						contador=contador+aux;
					}
				}		
			}
			f.cant_contactos.value=contador;
			vis=(f.cant_visitadores.value*1);
			f.contacto_vis.value=contador/vis;*/
			return(true);		
		}
		</script>";
		$codigo_linea=$_GET['codigo_linea'];
		
		$global_linea=$codigo_linea;
		
	//sacamos los datos para editarlos de la tabla grilla
		$sql_datos="SELECT g.nombre_grilla, g.cod_distrito from grilla g where codigo_grilla='$j_codigo'";
		$resp_datos=mysql_query($sql_datos);
		$datos=mysql_fetch_array($resp_datos);
		$nombre_grilla=$datos[0];
		$codDistritoRecup=$datos[1];
		
	//fin sacar los datos
		$sql="SELECT cod_ciudad,descripcion from ciudades where cod_ciudad='$cod_ciudad'";
		$resp=mysql_query($sql);
		$dat=mysql_fetch_array($resp);
		$ciudad=$dat[1];
		echo "<center><table border='0' class='textotit'>";
		echo "<tr><th>Edicion de Grillas</center></th></tr></table><br>";
		echo "<center><table border='0' cellspacing='0' class='texto'>";
		
		echo "<tr><th>Territorio $ciudad</th></tr></table><br></center>";
		
		echo "<form name='formu' action='guarda_editar_grilla.php' method='post'>";
		
		echo "<center><table border='0' class='texto'>";
		echo "<tr><th>Nombre Grilla</th><th>Distrito</th></tr>";
		echo "<tr><th><input type='text' name='nombre_grilla' class='texto' value='$nombre_grilla'></th>";
		
		echo "<th><select name='idDistrito' id='idDistrito'>
			<option value='0'>General</option>";
		$sqlDistritos="select cod_dist, descripcion from distritos d where d.cod_ciudad=$cod_ciudad";
		$respDistritos=mysql_query($sqlDistritos);
		while($datDistritos=mysql_fetch_array($respDistritos)){
			$codDistrito=$datDistritos[0];
			$nombreDistrito=$datDistritos[1];
			if($codDistrito==$codDistritoRecup){
				echo "<option value='$codDistrito' selected>$nombreDistrito</option>";
			}else{
				echo "<option value='$codDistrito'>$nombreDistrito</option>";
			}
		}
		echo "</select></th></tr>";
		
		echo "</table></center><br>";
		
		
		echo "<center><table border=1 cellspacing=0 class='texto'>";
		echo "<tr><th>Especialidad</th><th>Categor&iacute;a</th><th>Frecuencia</th><th>Cantidad M&eacute;dicos</th></tr>";
		
		
		$sql="SELECT * from especialidades order by desc_especialidad asc";
		$resp=mysql_query($sql);
		$i=1;
		$frecuencia="frecuencia";
		$contactos="contacto";
		$num_medicos="medicos";
		$cantidad_medicos=0;
		while($dat=mysql_fetch_array($resp))
		{
			$cod_espe=$dat[0];
			$desc_espe=$dat[1];
			
			
			
			$sql_cat="SELECT categoria_med from categorias_medicos order by categoria_med";
			$resp_cat=mysql_query($sql_cat);
			while($dat_cat=mysql_fetch_array($resp_cat))
			{
				$categoria=$dat_cat[0];
				if($cod_ciudad==0)
					{	
						$sql_med="SELECT c.cod_especialidad, c.categoria_med from categorias_lineas c,medicos m 
						where c.cod_med=m.cod_med and c.categoria_med='$categoria' and c.cod_especialidad='$cod_espe' and c.codigo_linea=$global_linea";
				}
				else
					{	$sql_med="SELECT c.cod_especialidad, c.categoria_med from categorias_lineas c,medicos m 
				where c.cod_med=m.cod_med and m.cod_ciudad='$cod_ciudad' and c.categoria_med='$categoria' and c.cod_especialidad='$cod_espe' and c.codigo_linea=$global_linea";
				}


				$resp_med=mysql_query($sql_med);
				$num_filas=mysql_num_rows($resp_med);
				$cantidad_medicos=$cantidad_medicos+$num_filas;
				if($num_filas==0)
					{	$color_fondo="#ff8080";
				}
				else
					{	$color_fondo="";
				}
				$sql_detalle="SELECT frecuencia from grilla_detalle where codigo_grilla='$j_codigo' and cod_especialidad='$cod_espe' and cod_categoria='$categoria'";
				$resp_detalle=mysql_query($sql_detalle);
				$datos_detalle=mysql_fetch_array($resp_detalle);
				$frec=$datos_detalle[0];
				if($frec == ''){
					$frec = 0;
				}

				echo "<tr bgcolor='$color_fondo'><td>$cod_espe</td><td align='center'>$categoria</td>";
				echo "<td align='center'><input type='text' name='$frecuencia$i' value='$frec' class='texto' size='3' onKeypress='if (event.keyCode < 48 || event.keyCode > 57 ) event.returnValue = false;' onKeyUp='calcula_frecuencia(this.form,$i+1)'></td>";
				echo "<td align='center'><input type='text' name='$num_medicos$i' class='texto' disabled=true size='3' value='$num_filas'></td></tr>";
				echo "<input type='hidden' name='lineaVisita$i' value='0'>";
				echo "<input type='hidden' name='especialidad$i' value='$cod_espe'>";
				echo "<input type='hidden' name='categoria$i' value='$categoria'>";
				$i++;
			}
}
echo "</table></center><br>";

echo "<input type='hidden' name='cantidad' value='$i'>";
echo "<input type='hidden' name='codigo' value='$j_codigo'>";
echo "<input type='hidden' name='cod_ciudad' value='$cod_ciudad'>";
echo "<input type='hidden' name='codigo_linea' value='$global_linea'>";

echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1);'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
echo "<center><input type='button' class='boton' value='Guardar' onClick='valida(this.form)'></center>";
echo "</form>";
?>