<?php
require("conexion.inc");
require("estilos_gerencia.inc");
echo "<script language='JavaScript'>
function abrirModuloOperativo(usuario,clave){
	window.open('cookie.php?usuario='+usuario+'&contrasena='+clave+'');
}
</script>
";
echo "<form name='form1' method='post'>";
$sql_ciudades = "select cod_ciudad, descripcion from ciudades where cod_ciudad<>115";
$resp_ciudades = mysql_query($sql_ciudades);
while ($dat_ciudades = mysql_fetch_array($resp_ciudades)) {
    $cod_ciudad = $dat_ciudades[0];
    $descripcion_ciudad = $dat_ciudades[1];
    echo "<table class='texto' align='center' border='1' cellpading='0' cellspacing='0' width='90%'><tr><th>$descripcion_ciudad</th></tr>";
    echo "<tr><td>";
    $sql = "select f.codigo_funcionario,c.cargo,f.paterno,f.materno,f.nombres,f.fecha_nac,f.direccion,f.telefono, f.celular,f.email,ci.descripcion,f.estado
        from funcionarios f, cargos c, ciudades ci
        where f.cod_cargo=1011 and f.cod_cargo=c.cod_cargo and f.cod_ciudad=ci.cod_ciudad and f.cod_ciudad='$cod_ciudad' 
		and f.estado='1' order by c.cargo,f.paterno";
    $resp = mysql_query($sql);
    echo "<table class='texto' align='center' border='1' width='100%' cellpading='0' cellspacing='0'>
	<tr><th width='20%'>Cargo</th><th width='40%'>Usuario</th></th>";
    while ($dat = mysql_fetch_array($resp)) {
        $codigo = $dat[0];
        $cargo = $dat[1];
        $paterno = $dat[2];
        $materno = $dat[3];
        $nombre = $dat[4];
        $nombre_f = "$paterno $materno $nombre";
        $fecha_nac = $dat[5];
        $direccion = $dat[6];
        $telf = $dat[7];
        $cel = $dat[8];
        $email = $dat[9];
        $ciudad = $dat[10];
        $estado = $dat[11];
        $sql_datos_sistema = "select * from usuarios_sistema where codigo_funcionario='$codigo'";
        $resp_datos_sistema = mysql_query($sql_datos_sistema);
        $datos_sistema = mysql_fetch_array($resp_datos_sistema);
        $usuario_sistema = $datos_sistema[0];
        $clave_sistema = $datos_sistema[1]; 
        // echo "<tr bgcolor='$fondo_fila'><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='cod_contacto' value='$codigo'></td><td>&nbsp;$cargo</td><td>$nombre_f</td><td align='left'>&nbsp;$email</td><td align='center'>$alta_sistema</td><td align='center'>$ver_lineas</td><td align='center'>$dar_alta</td><td align='center'>$restablecer</td></tr>";
        echo "<tr><td>$cargo</td><td><a href=javascript:abrirModuloOperativo($usuario_sistema,'$clave_sistema');>$nombre_f</td></a></tr>";
    } 
    echo "</table></td></tr>";
} 
echo "</table>";
echo "</form>";
?>
