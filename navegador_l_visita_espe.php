<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
 */
echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_prod_linea_espe.php?cod_linea_vis=$cod_linea_vis';
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
			{	alert('Debe seleccionar al menos un Producto para eliminarlo de la Línea de Visita.');
			}
			else
			{	
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_prod_linea_espe.php?datos='+datos+'&cod_linea_vis=$cod_linea_vis';
				}
				else
				{
					return(false);
				}
			}
		}
		</script>
	";
require("conexion.inc");
require("estilos_gerencia.inc");
echo "<form>";
$gestion = $_GET['gestion'];
//$sql_gestion = mysql_query("Select codigo_gestion, nombre_gestion from gestiones where estado = 'Activo' ");
//$codigo_gestion = mysql_result($sql_gestion, 0, 0);
$codigo_gestion = $gestion;
//formamos la cabecera
$sql_cab = "select nombre_l_visita from lineas_visita where codigo_l_visita='$cod_linea_vis'";
$resp_cab = mysql_query($sql_cab);
$dat_cab = mysql_fetch_array($resp_cab);
$nombre_linea_visita = $dat_cab[0];
//fin formar cabecera
$sql = "select e.cod_especialidad, e.desc_especialidad from especialidades e, lineas_visita_especialidad l where l.cod_especialidad=e.cod_especialidad and codigo_l_visita='$cod_linea_vis' order by e.desc_especialidad";
$resp = mysql_query($sql);
echo "<center><table border='0' class='textotit'><tr><td align='center'>Registro de Líneas de Visita<br>Línea de Visita: <strong>$nombre_linea_visita</strong></td></tr></table></center><br>";
$indice_tabla = 1;
echo "<center><table border='1' class='texto' cellspacing='0' width='30%'>";
echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Especialidad</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo_esp = $dat[0];
    $esp = $dat[1];
    echo "<tr><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigo' value='$codigo_esp'></td><td>$esp</td></tr>";
    $indice_tabla++;
}
echo "</table></center><br>";
echo"\n<table align='center'><tr><td><a href='navegador_lineas_visita.php?ciclo=$ciclo&gestion=$codigo_gestion'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><table border='0' class='texto'>";
$sql_veri = mysql_query("select estado from lineas_visitadores_estados where ciclo = $ciclo and gestion = $codigo_gestion");
$num_veri = mysql_num_rows($sql_veri);
if ($num_veri >= 1) {
    echo "<center><h1>El ciclo se encuentra cerrado</h1></center>";
} else {
    echo "<tr><td><input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'></td><td><input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'></td></tr></table></center>";
}
echo "</form>";
?>