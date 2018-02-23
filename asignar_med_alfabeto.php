<?php
		require("conexion.inc");
		require("estilos_regional_pri.inc");

echo "<script language='Javascript'>
function asignar_medico(f)
{	var i;
	var j=0;
	datos=new Array();
	for(i=0;i<=f.length-1;i++)
	{
	if(f.elements[i].type=='checkbox' && f.elements[i].name=='codigos_ciclos')
			{	if(f.elements[i].checked==true)
				{	datos[j]=f.elements[i].value;
					j=j+1;
				}
			}
		}
		if(j==0)
			{	alert('Debe seleccionar al menos un Medico para Asignarlo al Visitador.');
	}
	else
		{	 location.href='asignar_medico.php?datos='+datos+'&visitador=$visitador';			
}
}
function sel_todo(f)
{
	var i;
	var j=0;
	for(i=0;i<=f.length-1;i++)
	{
		if(f.elements[i].type=='checkbox')
			{	if(f.todo.checked==true)
				{	f.elements[i].checked=true;
				}
				else
					{	f.elements[i].checked=false;
					}
					
				}
			}
		}
		</script>";
	//formamos el nombre del funcionario
		$sql_cab="SELECT paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
		$resp_cab=mysql_query($sql_cab);
		$dat_cab=mysql_fetch_array($resp_cab);
		$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	//fin formar nombre funcionario
		echo "<form method='post'>";
		$sql="SELECT distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med, c.cod_especialidad, c.categoria_med 
		from medicos m, 
		categorias_lineas c
		where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and 
		c.codigo_linea=$global_linea and c.cod_med not in 
		(select mv.cod_med from medico_asignado_visitador mv where mv.cod_med=c.cod_med and c.codigo_linea=mv.codigo_linea 
		and codigo_visitador not in ($visitador))
		order by m.ap_pat_med";
		
		//echo $sql;
		
		$resp=mysql_query($sql);
		echo "<h1>Asignar Medicos<br>Visitador: $nombre_funcionario</h1>";
		
		echo "<div class='divBotones'>
		<input type='button' value='Asignar' class='boton' onclick='asignar_medico(this.form)'>
		</div>";
		
		echo "<center><table class='texto' cellspacing='0'>";
		echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table></center>";
		
		echo "<center><table class='texto'>";
		echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidades</th></tr>";
		$cadena = '';
		while($dat=mysql_fetch_array($resp))
		{
			$cod=$dat[0];
			$pat=$dat[1];
			$mat=$dat[2];
			$nom=$dat[3];
			$cadena .= $cod.",";
			$nombre_completo="$pat $mat $nom";
			$espe=$dat[4];
			$cat=$dat[5];
			echo "<tr><td align='center'><input type='checkbox' name='codigos_ciclos' value=$cod></td>
			<td align='center'>$cod</td><td class='texto'>$nombre_completo</td>
			<td align='center'>$espe $cat</td></tr>";		
		}
		echo "</table></center><br>";
		echo"\n<table align='center'><tr><td><a href='asignar_med_fun.php?j_funcionario=$visitador'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";

		echo "<div class='divBotones'>
		<input type='button' value='Asignar' class='boton' onclick='asignar_medico(this.form)'>
		</div>";
		
		echo "</form>";

?>