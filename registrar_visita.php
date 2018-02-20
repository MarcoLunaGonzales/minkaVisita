<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	echo '<script LANGUAGE="JavaScript">
			function activa_select(f,i)
			{	
				var indice_chk=(i*3)-1;
				var indice_sel=(i*3)-2;
				var indice_sel1=(i*3)-3;
				if(f.elements[indice_chk].checked==true)
				{	
					f.elements[indice_sel].disabled=false;
					f.elements[indice_sel1].disabled=false;
				}
				else
				{	
					f.elements[indice_sel].disabled=true;
					f.elements[indice_sel1].disabled=true;
				}
			}
		</script>';
	
	require("estilos.inc");
	require("conexion.inc");
		//sacamos el nombre del medico
		$sql_aux="select * from medicos where cod_med='$medico'";
		$resp_aux=mysql_query($sql_aux);
		$dat_aux=mysql_fetch_array($resp_aux);
		$nombre_medico="$dat_aux[3] $dat_aux[1] $dat_aux[2]";
		//sacamos la descripcion de la especialidad del medico
		$sql_aux1="select desc_especialidad from especialidades where cod_especialidad='$especialidad'";
		$resp_aux1=mysql_query($sql_aux1);
		$dat_aux1=mysql_fetch_array($resp_aux1);
		$desc_especialidad=$dat_aux1[0];
		
		echo "<center><table border='0' class='textotit'>";
		echo "<tr><td>Medico:</td><td>$nombre_medico</td></tr>";
		echo "<tr><td>Especialidad:</td><td>$desc_especialidad</td></tr>";
		echo "<tr><td>Categoria</td><td>$categoria</td></tr>";
		echo "</table></center><br>";		
		$sql="select m.descripcion, p.cantidad_muestra, a.descripcion_material, p.cantidad_material
		from parrilla p, muestras_medicas m,especialidades e, material_apoyo a
		where p.cod_ciclo='$ciclo' and p.cod_especialidad='$especialidad' and p.categoria_med='$categoria' and p.codigo_muestra=m.codigo and p.codigo_material=a.codigo_material and e.cod_especialidad=p.cod_especialidad order by prioridad";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><center>Muestra</center></td><td>Cantidad</td><td>Cantidad Entregada</td><td><center>Material de Apoyo</center></td><td>Cantidad</td><td>Cantidad Entregada</td></tr>";
	echo "<form name='form' method='post' action=''>";
	$i=1;
	while($dat=mysql_fetch_array($resp))
	{
		$muestra=$dat[0];
		$cantidad=$dat[1];
		$apoyo=$dat[2];
		$cant_apoyo=$dat[3];
		echo "<tr><td><center>$muestra</center></td><td><center>$cantidad</center></td>";
		echo "<td><center><select name='cantidad_entregada$i' class='textomini' disabled='true'>
				<option value='1'>1</option>
				<option value='2'>2</option>
				<option value='3'>3</option>
				<option value='4'>4</option>
				<option value='5'>5</option>
				<option value='6'>6</option>
				<option value='7'>7</option></center></td>";
		echo "<td><center>$apoyo</center></td><td><center>$cant_apoyo</center></td>";
		echo "<td><center><select name='cantidad_apoyo$i' class='textomini' disabled='true'>
				<option value='1'>1</option>
				<option value='2'>2</option>
				<option value='3'>3</option>
				<option value='4'>4</option>
				<option value='5'>5</option>
				<option value='6'>6</option>
				<option value='7'>7</option></center></td>";
		
		
		echo "<td><input type=checkbox name='constancia$i' value='1' onClick='activa_select(this.form,$i)'>Entregado</td></tr>";
		$i++;
	}
	echo "<tr><td colspan='4'><center><input type='submit' class='texto' value='Guardar'></center></td></tr>";
	echo "</form>";
	echo "</table></center>";
//echo "$ciclo $especialidad $categoria";

?>