<?php

echo "<script language='Javascript'>		
	function enviar_nav()		
	{	location.href='adicionar_bajas_visita.php';		
	}		
	function editar_nav(f)		
	{			
		var i;			
		var j=0;			
		var j_linea;			
		for(i=0;i<=f.length-1;i++)			
		{	if(f.elements[i].type=='checkbox')				
			{	if(f.elements[i].checked==true)					
				{	j_linea=f.elements[i].value;						
					j=j+1;					
				}				
			}			
		}			
		if(j>1)			
		{	alert('Debe seleccionar solamente un Dia de contacto para editar sus datos.');			
		}			
		else			
		{	if(j==0)				
			{	alert('Debe seleccionar un dia de contacto para editar sus datos.');
			}
			else				
			{	location.href='editar_baja_dias.php?cod_baja='+j_linea+'';				
			}			
		}		
	}		
	function eliminar_nav(f)		
	{	var i;			
		var j=0;			
		datos=new Array();			
		for(i=0;i<=f.length-1;i++)			
		{	if(f.elements[i].type=='checkbox')				
			{	if(f.elements[i].checked==true)					
				{	datos[j]=f.elements[i].value;						
					j=j+1;					
				}				
			}			
		}			
		if(j==0)			
		{	alert('Debe seleccionar al menos un Dia de contacto para proceder a su eliminación.');			
		}			
		else			
		{	if(confirm('Esta seguro de eliminar los datos.'))				
			{	location.href='eliminar_baja_dias.php?datos='+datos+'';				
			}
			else				
			{	return(false);				
			}			
		}		
	}		
	</script>	";
require("conexion.inc");
require("estilos_regional.inc");
echo "<form>";
$sql_ciclo = "select cod_ciclo, codigo_gestion from ciclos where estado='Activo'";
$resp_ciclo = mysql_query($sql_ciclo);
$datos_ciclo = mysql_fetch_array($resp_ciclo);
$ciclo = $datos_ciclo[0];
$gestion = $datos_ciclo[1];
$sql = "select distinct(b.codigo_baja) ,b.dia_contacto, b.turno, b.codigo_motivo from baja_dias b, orden_dias o 	where b.ciclo='$ciclo' and b.gestion='$gestion' and b.codigo_ciudad='$global_agencia' and o.dia_contacto=b.dia_contacto order by o.id";
$resp = mysql_query($sql);
echo "<center><table border='0' class='textotit'><tr><td>Baja de Dias de Visita</td></tr></table></center><br>";
$indice_tabla = 1;
echo "<center><table border='1' class='texto' cellspacing='0' width='80%'>";
echo "<tr><td>&nbsp;</td><td>&nbsp;</td><th>Dia Contacto</th><th>Turno</th><th>Motivo</th><th>Líneas Afectadas</th></tr>";
$linea = "<table border=0 class='texto'>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo_baja = $dat[0];
    $dia = $dat[1];
    $turno = $dat[2];
    $codigo_motivo = $dat[3];
    $sql_motivo = "select descripcion_motivo from motivos_baja where codigo_motivo='$codigo_motivo'";
    $resp_motivo = mysql_query($sql_motivo);
    $dat_motivo = mysql_fetch_array($resp_motivo);
    $nombre_motivo = $dat_motivo[0];
    $sql_baja_detallado = "select codigo_linea from baja_dias_detalle where codigo_baja='$codigo_baja'";
    $resp_baja_detallado = mysql_query($sql_baja_detallado);
    $linea = "<table border=1 class='texto'  width='100%'>";
    while ($datos_baja_detallado = mysql_fetch_array($resp_baja_detallado)) {
        $codigo_linea = $datos_baja_detallado[0];
        $sql_visitadores = "select f.paterno, f.materno, f.nombres from funcionarios f, baja_dias_detalle_visitador b			where b.codigo_visitador=f.codigo_funcionario and b.codigo_linea='$codigo_linea' and b.codigo_baja='$codigo_baja'";
        $resp_visitadores = mysql_query($sql_visitadores);
        $visitadores = "<table border=0 class='texto'>";
        while ($dat_visitadores = mysql_fetch_array($resp_visitadores)) {
            $nombre_visitador = "$dat_visitadores[0] $dat_visitadores[1] $dat_visitadores[2]";
            $visitadores = "$visitadores<tr><td>$nombre_visitador</td></tr>";
        } 
        $visitadores = "$visitadores</table>";
        $sql_nombre_lineas = "select nombre_linea from lineas where codigo_linea='$codigo_linea'";
        $resp_nombre_lineas = mysql_query($sql_nombre_lineas);
        $dat_nombre_lineas = mysql_fetch_array($resp_nombre_lineas);
        $nombre_linea = $dat_nombre_lineas[0];
        $linea = "$linea<tr><td width='30%'>$nombre_linea</td><td width='70%'>$visitadores</td></tr>";
    } 
    $linea = "$linea</table>";
    echo "<tr><td>$indice_tabla</td><td><input type='checkbox' name='codigo' value='$codigo_baja'></td><td>$dia</td><td align='center'>$turno</td><td>$nombre_motivo</td><td>$linea</td></tr>";
    $indice_tabla++;
} 
echo "</table></center><br>";
require("home_regional.inc");
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td></tr></table></center>"; //echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td><td><input type='button' value='Editar' class='boton' onclick='editar_nav(this.form)'></td></tr></table></center>";	echo "</form>";
?>