<?php
//header("Content-Type: text/html; charset=UTF-8");
set_time_limit(0);
include('conexion.inc');
//mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$codigo_linea = 1021;
$codigo_linea_entra = 0;
$sql = mysql_query("select DISTINCT a.cod_med, a.ap_pat_med, a.ap_mat_med, a.nom_med, a.cod_ciudad, a.cod_catcloseup, a.cod_closeup
    from medicos a ORDER BY 1");
?>
<!DOCTYPE HTML>
<html lang="es">
    <head>
        <meta charset="iso-8859-1">
        <title></title>
    </head>
    <body>
        <table border="1" width="80%">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Paterno</th>
                    <th>Materno</th>
                    <th>Nombre</th>
                    <th>Cod_Ciudad</th>
                    <th>Cod_CatCloseup</th>
                    <th>Cod Closeup</th>
                    <th>Especialidad</th>
                    <th>Categoria</th>
                    <th>Frecuencia</th>
                    <th>Direccion</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysql_fetch_array($sql)) { ?>
                    <?php
                    $sql_especialidad = mysql_query("select c.cod_especialidad, c.categoria_med, c.frecuencia_linea from categorias_lineas c where c.cod_med = $row[0]");
                    if (mysql_num_rows($sql_especialidad) > 0) {
                        $especialidad = mysql_result($sql_especialidad, 0, 0);
                        $categoria = mysql_result($sql_especialidad, 0, 1);
                        $frecuencia = mysql_result($sql_especialidad, 0, 2);
                    } else {
                        $especialidad = '-';
                        $categoria = '-';
                        $frecuencia = '-';
                    }
                    ?>
                    <tr>
                        <td><?php echo $row[0]; ?></td>
                        <td><?php echo $row[1]; ?></td>
                        <td><?php echo $row[2]; ?></td>
                        <td><?php echo $row[3]; ?></td>
                        <td><?php
                if ($row[4] == 102) {
                    echo "49";
                }
                if ($row[4] == 114) {
                    echo "47";
                }
                if ($row[4] == 113) {
                    echo "46";
                }
                if ($row[4] == 104) {
                    echo "63";
                }
                if ($row[4] == 119) {
                    echo "48";
                }
                if ($row[4] == 120) {
                    echo "51";
                }
                if ($row[4] == 121) {
                    echo "52";
                }
                if ($row[4] == 116) {
                    echo "53";
                }
                if ($row[4] == 109) {
                    echo "54";
                }
                if ($row[4] == 118) {
                    echo "56";
                }
                if ($row[4] == 117) {
                    echo "55";
                }
                    ?></td>
                        <td><?php echo $row[5]; ?></td>
                        <td><?php echo $row[6]; ?></td>
                        <td><?php echo $especialidad; ?></td>
                        <td><?php echo $categoria; ?></td>
                        <td><?php echo $frecuencia; ?></td>
                        <td>
                            <?php
                            $sql_direcciones = mysql_query("select direccion from direcciones_medicos where cod_med = $row[0]");
                            if (mysql_num_rows($sql_direcciones) > 0) {
                                $direccion = mysql_result($sql_direcciones, 0, 0);
                            } else {
                                $direccion = '-';
                            }
                            echo $direccion;
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </body>
</html>