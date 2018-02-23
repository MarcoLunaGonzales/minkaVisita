<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Medica
 * * @copyright 2005
 */
require("conexion.inc");
require("estilos_gerencia.inc");
$gestion = $_GET['gestion'];
$codigo_gestion = $gestion;
$sql_cab = "SELECT l.nombre_l_visita, lv.cod_especialidad from lineas_visita l, lineas_visita_especialidad lv where l.codigo_l_visita='$cod_linea_vis' and l.codigo_l_visita = lv.codigo_l_visita";
$resp_cab = mysql_query($sql_cab);
$dat_cab = mysql_fetch_array($resp_cab);
$nombre_linea_visita = $dat_cab[0];
$nombre_especialidad = $dat_cab[1];
$sql_esp = mysql_query("SELECT DISTINCT codigo_l_visita, cod_especialidad from lineas_visita_especialidad where cod_especialidad = '$nombre_especialidad'");
while($row_espe = mysql_fetch_array($sql_esp)){
    $cad_cod_espe .= $row_espe[0].","; 
}
$cad_cod_espe = substr($cad_cod_espe, 0, -1);
?>

<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title></title>
    <script language='Javascript'>
    function enviar_nav(){	
        location.href='registrarVisitadorLineaVisita.php?cod_linea_vis=<?php echo $cod_linea_vis; ?>&ciclo=<?php echo $ciclo; ?>&gestion=<?php echo $codigo_gestion; ?>&familia=<?php echo $cad_cod_espe;  ?>';
    }
    function replicar_nav(){
        location.href='replicarLineaVisitaxCiclo.php?cod_linea_vis=<?php echo $cod_linea_vis; ?>&ciclo=<?php echo $ciclo; ?>&gestion=<?php echo $codigo_gestion; ?>';
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
                {	alert('Debe seleccionar al menos un Visitador.');
        }
        else
        {
            if(confirm('Esta seguro de eliminar los datos.'))
            {
                location.href='eliminarVisitadorLinea.php?datos='+datos+'&cod_linea_vis=<?php echo $cod_linea_vis; ?>&ciclo=<?php echo $ciclo; ?>&gestion=<?php echo $codigo_gestion; ?>';
            }
            else
            {
                return(false);
            }
        }
    }
    </script>
</head>
<body>
    <?php
    echo "<form>";
    
    $sql = "SELECT f.codigo_funcionario, f.paterno, f.materno, f.nombres, c.descripcion from funcionarios f, 
		lineas_visita_visitadores fl, ciudades c where f.cod_cargo=1011 and f.estado=1 and f.codigo_funcionario=fl.codigo_funcionario and f.cod_ciudad=c.cod_ciudad and fl.codigo_l_visita = '$cod_linea_vis' and fl.codigo_ciclo = $ciclo and codigo_gestion = $codigo_gestion order by c.descripcion, f.paterno";
    
	//echo $sql;
	
	$resp = mysql_query($sql);
    echo "<center><table border='0' class='textotit'><tr><td align='center'>Visitadores en L&iacute;nea de Visita<br>L&iacute;nea de Visita: <strong>$nombre_linea_visita</strong></td></tr></table></center>";
    echo "<center><h3>Ciclo: $ciclo</h3></center>";
    $indice_tabla = 1;
    echo "<center><table border='1' class='texto' cellspacing='0' width='30%'>";
    echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Territorio</th><th>Visitador</th></tr>";
    while ($dat = mysql_fetch_array($resp)) {
        $codigo_vis = $dat[0];
        $nombre_completo = "$dat[1] $dat[2] $dat[3]";
        $nombreCiudad = $dat[4];
        echo "<tr><td align='center'>$indice_tabla</td><td align='center'>
        <input type='checkbox' name='codigo' value='$codigo_vis'></td>
        <td>$nombreCiudad</td>
        <td>$nombre_completo</td></tr>";
        $indice_tabla++;
    }
    echo "</table></center><br>";
    echo"\n<table align='center'><tr><td><a href='navegador_lineas_visita.php?ciclo=$ciclo&gestion=$codigo_gestion'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
    $sql_veri = mysql_query("SELECT estado from lineas_visitadores_estados where ciclo = $ciclo and gestion = $codigo_gestion");
    $num_veri = mysql_num_rows($sql_veri);
    if ($num_veri >= 1) {
        echo "<center><h1>El ciclo se encuentra cerrado</h1></center>";
    } else {
        echo "<center><table border='0' class='texto'>";
        echo "<tr><td>
        <input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
        </td><td>
        <input type='button' value='Eliminar' name='eliminar' class='boton' onclick='eliminar_nav(this.form)'>
        </td>
        <td>
        <input type='button' value='Replicar' name='replicar' class='boton' onclick='replicar_nav()'>
        </td>
        </tr>
        </table></center>";
    }

    echo "</form>";
    ?>
</body>
</html>