<?php
require("conexion.inc");
echo"<link href='stilos.css' rel='stylesheet' type='text/css'>";  
echo "<table class='textotitulo' align='center'><tr><th>Reporte Ruteros Maestros Aprobados x Visitador</th></tr></table>";
$sql_ciudades="select cod_ciudad, descripcion from ciudades where cod_ciudad<>115";
$resp_ciudades=mysql_query($sql_ciudades);
while($dat_ciudades=mysql_fetch_array($resp_ciudades))
{
	$cod_ciudad=$dat_ciudades[0];
	$descripcion_ciudad=$dat_ciudades[1];
	echo "<table class='texto' align='center' border='1' width='90%' cellspacing=0><tr><th>$descripcion_ciudad</th></tr>";
	echo "<tr><td>";
	$sql="select f.codigo_funcionario,c.cargo,f.paterno,f.materno,f.nombres,f.fecha_nac,f.direccion,f.telefono, f.celular,f.email,ci.descripcion,f.estado
        from funcionarios f, cargos c, ciudades ci
        where f.cod_cargo=c.cod_cargo and f.cod_ciudad=ci.cod_ciudad and f.cod_ciudad='$cod_ciudad' and f.estado='1' and f.cod_cargo=1011 order by c.cargo,f.paterno";
    $resp=mysql_query($sql);
	echo "<table class='texto' align='center' border='1' cellspacing='0' width='100%'><tr><th width='40%'>Usuario</th><th width='20%'>Ruteros Maestros Aprobados</th></th>";
	while($dat=mysql_fetch_array($resp))
    {
                $codigo=$dat[0];
                $cargo=$dat[1];
                $paterno=$dat[2];
                $materno=$dat[3];
                $nombre=$dat[4];
                $nombre_f="$paterno $materno $nombre";
                $fecha_nac=$dat[5];
                $direccion=$dat[6];
                $telf=$dat[7];
                $cel=$dat[8];
                $email=$dat[9];
                $ciudad=$dat[10];
                $estado=$dat[11];
				$sqlRutero="select l.nombre_linea, r.nombre_rutero, r.codigo_ciclo from rutero_maestro_cab r, lineas l where
					r.codigo_linea=l.codigo_linea and r.cod_visitador=$codigo and r.estado_aprobado=1 order by l.nombre_linea";
				$respRutero=mysql_query($sqlRutero);
				$cadRutero="<table border='0' class='texto' width='100%'>";
				while($datRutero=mysql_fetch_array($respRutero)){
					$nombreLinea=$datRutero[0];
					$nombreRutero=$datRutero[1];
					$codigoCiclo=$datRutero[2];
					$cadRutero.="<tr><td align='left' width='40%'>$nombreLinea</td><td align='left' width='40%'>$nombreRutero</td>$codigoCiclo</td>
								<td align='center' width='20%'>$codigoCiclo</td></tr>";
				}
				$cadRutero.="</table>";
				//echo "<tr bgcolor='$fondo_fila'><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='cod_contacto' value='$codigo'></td><td>&nbsp;$cargo</td><td>$nombre_f</td><td align='left'>&nbsp;$email</td><td align='center'>$alta_sistema</td><td align='center'>$ver_lineas</td><td align='center'>$dar_alta</td><td align='center'>$restablecer</td></tr>";
				echo "<tr><td>$nombre_f</td><td align='center'>$cadRutero</td></tr>";
	}
	echo "</table></td></tr>";
}
echo "</table>";
?>