<?php
require("conexion.inc");
require("estilos_visitador.inc");

echo "<script language='Javascript'>
		function enviar_nav(n)
		{	
		  	var num_ruteros=n;
		  	if(n>=3)
		  	{
			    alert('No puede crear mas de 3 ruteros maestros.');
			    return(false);
			}
			location.href='registrar_cab_rutero_maestro.php';
		}
		function aprobar_rutero(f)
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
			{	alert('Debe seleccionar solamente un Rutero Maestro para aprobarlo.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Rutero Maestro para aprobarlo.');
				}
				else
				{
					location.href='validarRuteroMaestro.php?cod_rutero='+j_cargo+'&visitador=$global_visitador';
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
			{	alert('Debe seleccionar al menos un Rutero Maestro para proceder a su eliminaci�n.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_cab_rutero_maestro.php?datos='+datos+'';
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
			{	alert('Debe seleccionar solamente un Rutero Maestro para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un Maestro para editar sus datos.');
				}
				else
				{
					location.href='editar_cab_rutero_maestro.php?cod_rutero='+j_cargo+'';
				}
			}
		}
		</script>";
echo "<form method='post' action=''>";

echo "<h1>Registro de Rutero Maestro</h1>";
echo "<center><table class='texto'>
<tr><th>&nbsp;</th><th>Nombre de Rutero</th>
<th>Ciclo Asociado</th><th>Estado</th><th>Ver Detalle</th><th>Reducir Frecuencia</th><th>Intercambiar Semanas</th></tr>";
$sql = "SELECT r.cod_rutero, r.nombre_rutero, r.estado_aprobado, r.codigo_ciclo, r.codigo_gestion from rutero_maestro_cab r 
where r.cod_visitador='$global_visitador' and r.codigo_linea='$global_linea' and r.cvs = 0 order by r.nombre_rutero";
$resp = mysql_query($sql);
$filas_ruteros = mysql_num_rows($resp);
$nombreGestion = "";
while ($dat = mysql_fetch_array($resp)) {
    $cod_rutero = $dat[0];
    $nombre_rutero = $dat[1];
    $estado = $dat[2];
    $codCiclo = $dat[3];
    $codigoGestion = $dat[4];

    $sqlGestion = "SELECT nombre_gestion from gestiones where codigo_gestion=$codigoGestion";
    $respGestion = mysql_query($sqlGestion);
    $datGestion = mysql_fetch_array($respGestion);
    $nombreGestion = $datGestion[0];

    if ($estado == 0) {
        $estado_desc = "No Aprobado";
        echo"<tr><td><input type='checkbox' name='codigo' value='$cod_rutero'></td><td>&nbsp;$nombre_rutero</td>
		<td>$codCiclo/$nombreGestion</td><td align='center'>$estado_desc</td>
		<td align='center'><a href='rutero_maestro_todo.php?rutero=$cod_rutero'><img src='imagenes/detalle.png' width='40'></a></td>
		<td align='center'><a href='detalleMedicosFrecEspecial.php?rutero=$cod_rutero&cod_ciclo=$codCiclo&codigo_gestion=$codigoGestion'><img src='imagenes/reducir.png' width='40'></a></td>
		<td align='center'><a href='intercambiarSemanas.php?rutero=$cod_rutero' target='_blank'><img src='imagenes/change.png' width='40'></a></td></tr>";
    }
    if ($estado == 1) {
        $estado_desc = "Aprobado";
        echo"<tr><td>&nbsp;</td><td>&nbsp;$nombre_rutero</td><td>$codCiclo/$nombreGestion</td>
		<td align='center'>$estado_desc</td><td align='center'><a href='rutero_maestro_todo.php?rutero=$cod_rutero&aprobado=1'><img src='imagenes/detalle.png' width='40'></a></td>
		<td align='center'><a href='detalleMedicosFrecEspecial.php?rutero=$cod_rutero&cod_ciclo=$codCiclo&codigo_gestion=$codigoGestion'><img src='imagenes/reducir.png' width='40'></a></td>
		<td align='center'>-</td></tr>";
    }
    if ($estado == 2) {
        $estado_desc = "En Aprobacion";
        echo"<tr><td>&nbsp;</td><td>&nbsp;$nombre_rutero</td><td>$codCiclo/$nombreGestion</td><td align='center'>$estado_desc</td>
		<td align='center'><a href='rutero_maestro_todo.php?rutero=$cod_rutero&aprobado=1'><img src='imagenes/detalle.png' width='40'></a></td>
		<td align='center'><a href='detalleMedicosFrecEspecial.php?rutero=$cod_rutero&cod_ciclo=$codCiclo&codigo_gestion=$codigoGestion'><img src='imagenes/reducir.png' width='40'></a></td>
		<td align='center'>-</td></tr>";
    }
}
echo "</table></center><br>";

echo "<div class='divBotones'>
<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav($filas_ruteros)'>
<input type='button' value='Enviar a Aprobacion' name='aprobar' class='boton' onclick='aprobar_rutero(this.form)'>
<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
</div>";

echo "</form>";
?>
<!--<td><input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'></td>-->