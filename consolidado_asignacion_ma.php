<?php
error_reporting(0);
require("conexion.inc");
$data = $_POST['mydata'];
$cadena_ult = $_POST['cadena'];

$data_explode = explode(",", $data);
$data_sin_duplicados = array_unique($data_explode);

$cadena_ult_sub = substr($cadena_ult, 0, -1);
$cadena_ult_explode = explode(",", $cadena_ult_sub);

$datos_combinado = array_combine($cadena_ult_explode, $data_sin_duplicados); 

// echo "<pre>";
// print_r($datos_combinado);
// echo "</pre>";

$cantidad_total = 0;
$existencia_final_final = 0;
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion de Material De Apoyo detalle</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
</head>
<body>
    <div id="container">
        <table border=1>
            <tr>
                <th>Producto</th>
                <th>Total Planificado</th>  
                <th>Existencias</th>
            </tr>
            <?php foreach ($datos_combinado as $key => $value ) { ?>
            <?php 

                $key_explode = explode("@", $key);
                $codigo = $key_explode[0];
                $cantidad = $key_explode[1];

                $value_explode = explode("@", $value);
                $nombre = $value_explode[0];
                $codigo_n = $value_explode[1];

                $sql_fechas_ingresos="SELECT sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
                where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='1000' and 
                i.grupo_ingreso=2 and i.ingreso_anulado=0 and id.cod_material='$codigo_n'";
                            
                $resp_fechas_ingresos=mysql_query($sql_fechas_ingresos);
                $dat_kardex_ingresos=mysql_fetch_array($resp_fechas_ingresos);
                $cantidad_ingreso_kardex=$dat_kardex_ingresos[0];

                $sql_fechas_salidas="SELECT sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
                where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='1000' and 
                s.grupo_salida=2 and s.salida_anulada=0 and sd.cod_material='$codigo_n'";
                            
                $resp_fechas_salidas=mysql_query($sql_fechas_salidas);
                $dat_kardex_salidas=mysql_fetch_array($resp_fechas_salidas);
                $cantidad_salida_kardex=$dat_kardex_salidas[0];

                $existencia_final=$cantidad_ingreso_kardex-$cantidad_salida_kardex;
            ?>
            <tr>
                <th><?php echo $nombre; ?></th>
                <td>
                    <?php  
                    if($codigo_n == $codigo){
                        if($cantidad > $existencia_final){
                            echo "<span style='color:red;font-wwight:bold'>".$cantidad."</span>";
                        }else{
                            echo "<span style='color:green;font-wwight:bold'>".$cantidad."</span>";
                        }
                    }else{
                        echo "---";
                    }
                    ?>
                </td>
                <td>
                    <?php 
                        echo $existencia_final;
                    ?>
                </td>
            </tr>
            <?php 
                $cantidad_total = $cantidad_total + $cantidad;
                $existencia_final_final = $existencia_final_final + $existencia_final;
            ?>
            <?php } ?>
            <tr>
                <th>TOTAL</th>
                <th style="text-align:center"><?php echo "<span style='color:blue;font-weight:bold'>".$cantidad_total."</span>"; ?></th>
                <th style="text-align:center"><?php echo "<span style='color:blue;font-weight:bold'>".$existencia_final_final."</span>"; ?></th>
            </tr>
        </table>
    </div>
</body>
</html>