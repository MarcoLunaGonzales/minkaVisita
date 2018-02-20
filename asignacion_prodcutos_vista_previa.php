<?php
error_reporting(0);
set_time_limit(0);
require("conexion.inc");
require("funcion_nombres.php");
$ciclo_gestion = $_GET['ciclo_gestion'];
$conso = $_GET['conso'];
$lineaMkt = $_GET['linea'];
$nombreLinea=nombreLinea($lineaMkt);

$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo = $ciclo_gestion_explode[0];
$gestionn = $ciclo_gestion_explode[1];
$ciudades = array('0' => 116, '1' => 124, '2' => 122, '3' => 113, '4' => 114, '5' => 102, '6' => 120, '7' => 109 , '8' => 118, '9' => 104, '10' => 119, '11' => 121, '12' => 1023, '13' => 1022, '14' => 1009, '15' => 123, '16' => 1031 );
$linea_aux = '';
$esp_aux = '';
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion De Productos</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="lib/jquery.fixedtable.js"></script>
    <style type="text/css">
    h4 {
        text-align: left
    }
    table thead tr th {
        padding: 5px 10px;
        color: #084B8A 
    }
    table tbody tr td {
        padding: 5px 10px
    }
    table tbody tr th {
        color: #000
    }
    table .intermedio th {
        background: #53c4ee;
    }
    #boton {
        position: fixed;
        right: 10px;
        top: 50px;
        background: #fff;
        border: 1px solid activeborder;
        padding: 2px;
        border-radius: 5px 5px;
    }
    #boton:hover {
        zoom: 1;
        filter: alpha(opacity=60);
        opacity: 0.6;
        background: #29677e;
        border: 1px solid activeborder;
        cursor: pointer;
        color: white !important;
    }
    </style>
    <script>
    // jQuery(document).ready(function($) {
    //     $(".tableDiv").each(function() {      
    //         var Id = $(this).get(0).id;
    //         var maintbheight = 500;
    //         var maintbwidth = 1320;

    //         $("#" + Id + " .FixedTables").fixedTable({
    //             width : maintbwidth,
    //             height: maintbheight,
    //             // fixedColumns: 1,
    //             classHeader: "fixedHead",
    //             classFooter: "fixedFoot",
    //             // classColumn: "fixedColumn",
    //             // fixedColumnWidth: 200,
    //             outerId: Id,
    //             Contentbackcolor: "#ffffff",
    //             Contenthovercolor: "#99CCFF", 
    //             fixedColumnbackcolor:"#187BAF", 
    //             fixedColumnhovercolor:"#99CCFF"  
    //         });        
    //     });
    // });
    </script>
</head>
<body>
    <div id="container">
        <?php 
        require("estilos3.inc"); 
        ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Asignaci&oacute;n de Productos Vista Preliminar<br>Linea <?php echo $nombreLinea; ?></h3>
        </header>
        <?php 
        if($conso == 0){ 
            ?>
            <div id="tableDiv_Arrays" class="tableDiv twelve columns">
                <table border="1" id="table-principal" class="FixedTables">
                    <thead>
                        <tr>
                            <th>Especialidad</th>
                            <th>Linea</th>
                            <th>Posicion</th>
                            <th>Santa Cruz</th>
                            <th>Santa Cruz Periferie</th>
                            <th>Montero</th>
                            <th>La Paz</th>
                            <th>El Alto</th>
                            <th>Cochabamba</th>
                            <th>Quillacollo</th>
                            <th>Sucre</th>
                            <th>Tarija</th>
                            <th>Oruro</th>
                            <th>Potosi</th>
                            <th>Riberalta</th>
                            <th>Prov 4-4-2</th>
                            <th>Prov 2-2-2</th>
                            <th>Prov 1-1-1</th>
                            <th>Cobija</th>
                            <th>Sacaba-Punata</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  
                        $sql_asignacion = mysql_query("SELECT DISTINCT ad.posicion,ad.especialidad,ad.linea,ad.id from asignacion_productos_excel a, asignacion_productos_excel_detalle ad where a.id = ad.id and a.ciclo = $ciclo and a.gestion = $gestionn ORDER BY especialidad ASC , linea ASC,posicion ASC ");
                        while ($row_asignacion = mysql_fetch_array($sql_asignacion)) {
                            if ($linea_aux != $row_asignacion[2] or $esp_aux !=$row_asignacion[1]) {
                                ?>
                                <tr class="intermedio">
                                    <th>--</th>
                                    <th>--</th>
                                    <th>--</th>
                                    <?php 
                                    for ($i=0; $i < 17 ; $i++) { 
                                        ?>
                                        <th>Producto</th>
                                        <?php 
                                    } 
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                                <th><?php echo $row_asignacion[1]; ?></th>
                                <th><?php echo $row_asignacion[2]; ?></th>
                                <th><?php echo $row_asignacion[0]; ?></th>
                                <?php 
                                foreach ($ciudades as $ciudad) { 
                                    $sql_productos = mysql_query("SELECT producto from asignacion_productos_excel_detalle where ciudad = $ciudad and especialidad = '$row_asignacion[1]' and linea = '$row_asignacion[2]' and posicion = $row_asignacion[0] and id= $row_asignacion[3] ");
                                    $codigo_producto = mysql_result($sql_productos, 0, 0);
                                    $sql_nom_producto = mysql_query("SELECT CONCAT(descripcion,' ',presentacion) from muestras_medicas where codigo = '$codigo_producto'");
                                    $nom_producto = mysql_result($sql_nom_producto, 0, 0);
                                    ?>
                                    <td><?php echo $nom_producto; ?></td>
                                    <?php 
                                } 
                                ?>
                            </tr>
                            <?php 
                            $linea_aux = $row_asignacion[2]; 
                            $esp_aux = $row_asignacion[1];
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php 
        } else{
            ?>
            <style>
            .row .three {min-height: 500px;height: auto !important;height: 500px; }
            </style>
            <div class="row">
                <?php 
                /*$txtAsig="SELECT DISTINCT ad.linea, ad.especialidad from asignacion_productos_excel a, 
				asignacion_productos_excel_detalle ad where a.id = ad.id and a.ciclo = $ciclo and a.gestion = $gestionn";*/
				$txtAsig="SELECT DISTINCT ad.especialidad, ad.contacto, 
					ad.ciudad, (select c.descripcion from ciudades c where c.cod_ciudad=ad.ciudad)ciudad, ad.linea
					from asignacion_productos_excel a, 
					asignacion_productos_excel_detalle ad where a.id = ad.id and a.ciclo = $ciclo and a.gestion = $gestionn and ad.ciudad<>0 
					and ad.linea_mkt='$lineaMkt' group by ad.especialidad, ad.contacto, ad.ciudad, ad.linea order by ciudad, especialidad, ad.linea, contacto";
				//echo $txtAsig;
				$sql_asignacion = mysql_query($txtAsig);
				
                while ($row_asignacion = mysql_fetch_array($sql_asignacion)) { 
					$codEspe=$row_asignacion[0];
					$codContacto=$row_asignacion[1];
					$codCiudad=$row_asignacion[2];
					$nombCiudad=$row_asignacion[3];
					$lineaVis=$row_asignacion[4];
					
					if($lineaVis==0){
						$nombreCab="$codEspe $nombCiudad $codContacto";
					}else{
						$nombreCab="$codEspe LV$lineaVis $nombCiudad $codContacto";
					}
					
					
                    ?>
                    <div class="three columns end">
                        <h3 style="text-align:left"><?php echo $nombreCab; ?>
							<a href='asignaProductoEditar/lista.php?id=1&espe=<?php echo $codEspe;?>&contacto=<?php echo $codContacto; ?>&codCiudad=<?php echo $codCiudad;?>&lineaMkt=<?php echo $lineaMkt;?>&lineaVis=<?php echo $lineaVis;?>' target='_blank'>Edit</a>
						</h3>
                        <table>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Producto</th>
                            </tr>
                            <?php  
                            $sql_pro = mysql_query("SELECT DISTINCT a.producto, CONCAT(m.descripcion,' ',m.presentacion) 
							from asignacion_productos_excel_detalle a, muestras_medicas m where 
							a.producto = m.codigo and a.especialidad = '$row_asignacion[0]' and a.linea_mkt = '$lineaMkt' 
							and contacto='$row_asignacion[1]' and ciudad='$row_asignacion[2]' and a.linea='$lineaVis'");
                            $count = 1;
                            ?>
                            <?php 
                            while($row_nom = mysql_fetch_array($sql_pro)){ 
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row_nom[1]; ?></td>
                                </tr>
                                <?php
                                $count++;
                            } 
                            ?>
                        </table>
                    </div>
                    <?php
                } 
                ?>
            </div>
            <?php
        } 
        ?>
    </div>
	<div id="boton">
        <a href="asignaProductoEditar/eliminarAsignacionProd.php?lineaMkt=<?php echo $lineaMkt;?>" id="enviar" target="_blank">
			<span>Eliminar Producto</span>  
		</a>
		-----
		<a href="asignaProductoEditar/insertarAsignacionProd.php?lineaMkt=<?php echo $lineaMkt;?>" id="enviar" target="_blank">
			<span>Adicionar Producto</span>  
		</a>
		
	</div>

    <div class="modal"></div>
</body>
</html>