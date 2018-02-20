<?php

require("conexion.inc");
require("estilos_visitador.inc");

echo "<script language='Javascript'>
		function recuperar(f)
		{	location.href='guardar_recuperacion_contactos.php?rutero_rec='+$rutero_rec+'&rutero_trabajo=$rutero_trabajo';
		}	
		</script>";

echo "<form action=''>";
echo "<center><table class='textotit'><tr><th>Seleccionar Rutero para replicar sus datos.<br>Rutero de Trabajo: $rutero_trabajo</th></tr></table>";
echo"\n<br><table align='center'><tr><td><a href='rutero_maestro_todo.php?rutero=$rutero_trabajo'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<br><table border=1 cellspacing='0' class='texto' width='80%'>";
$sql = "select cod_rutero, nombre_rutero from rutero_maestro_cab where cod_visitador='$global_visitador' and codigo_linea='$global_linea' 
	and cod_rutero<>$rutero_trabajo order by nombre_rutero";
$resp = mysql_query($sql);
$filas_sql = mysql_num_rows($resp);
echo "<tr><th colspan='$filas_sql'>Seleccione el rutero maestro que desee replicar.</th></tr>";
echo "<tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo_rutero = $dat[0];
    $nombre_rutero = $dat[1];
    echo "<td align='center'><a href='recuperacion_contactos.php?rutero_rec=$codigo_rutero&rutero_trabajo=$rutero_trabajo'>$nombre_rutero</td>";
}
echo "</tr><table></center>";
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Recuperar' name='adicionar' class='boton' onclick='recuperar(this.form)'></td></tr>";

$sql = "select * from rutero_maestro rm, orden_dias o where rm.dia_contacto=o.dia_contacto and rm.cod_rutero='$rutero_rec' and rm.cod_visitador=$global_visitador order by o.id";
$resp = mysql_query($sql);
echo "<br><center><table border='1' class='textomini' cellspacing='0' width='90%'>";
echo "<tr><th>D&iacute;a de Contacto</th><th>Turno</th><th>Contactos</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $cod_contacto = $dat[0];
    $cod_ciclo = $dat[1];
    $fecha = $dat[3];
    $turno = $dat[4];
    $sql1 = "select c.orden_visita, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, c.estado
				from rutero_maestro_detalle c, medicos m, direcciones_medicos d
					where (c.cod_contacto=$cod_contacto) and (c.cod_visitador=$global_visitador) and (c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and (c.cod_zona=d.numero_direccion) order by c.orden_visita";
    $resp1 = mysql_query($sql1);
    $contacto = "<table class='textomini' width='100%'>";
    $contacto = $contacto . "<tr><th width='5%'>Orden</th><th width='35%'>M&eacute;dico</th><th width='10%'>Especialidad</th><th width='10%'>Categor&iacute;a</th><th width='30%'>Direcci&oacute;n</th></tr>";
    while ($dat1 = mysql_fetch_array($resp1)) {
        $orden_visita = $dat1[0];
        $pat = $dat1[1];
        $mat = $dat1[2];
        $nombre = $dat1[3];
        $direccion = $dat1[4];
        $nombre_medico = "$pat $mat $nombre";
        $espe = $dat1[5];
        $cat = $dat1[6];
        $estado = $dat1[7];
        if ($estado == 0) {
            $det_estado = "No";
        } else {
            $det_estado = "Si";
        }
        $contacto = $contacto . "<tr><td align='center'>$dat1[0]</td><td align='left'>$nombre_medico</td><td align='center'>$espe</td><td align='center'>$cat</td><td align='left'>$direccion</td></tr>";
    }
    $contacto = $contacto . "</table>";
    echo "<tr><td align='left'>$fecha</td><td align='center'>$turno</td><td align='center'>$contacto</td></tr>";
}

echo "<input type='hidden' name='rutero_trabajo' value='$rutero_trabajo'>";
echo "</table>";
echo"\n<br><table align='center'><tr><td><a href='rutero_maestro_todo.php?rutero=$rutero_trabajo'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>";
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Recuperar' name='adicionar' class='boton' onclick='recuperar(this.form)'></td></tr>";
echo "</form>";
echo "</table></center><br>";
?>