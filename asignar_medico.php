<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");

	echo "<script language='Javascript'>
		function asignar_med(f)
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
			{	alert('Debe seleccionar al menos un Medico para asignarlo.');
			}
			else
			{	  location.href='asignar_medico_asignado.php?datos='+datos+'&visitador=$visitador';
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
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	$indice=0;
	for($i=0;$i<$n;$i++){
		$sql="insert into medico_asignado_visitador values('$vector[$i]','$visitador','$global_linea')";
		$resp=mysql_query($sql);
	}

	echo "<script language='Javascript'>
	alert('Los datos fueron insertados correctamente.');
	location.href='medicos_asignados.php?visitador=$visitador';
	</script>";

	
?>