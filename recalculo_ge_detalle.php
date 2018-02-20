<?php

set_time_limit(0);
error_reporting(0);
require("conexion.inc");
$ciclo = $_GET['ciclo'];
$territorios = $_GET["territorios"];
$explode_ciclo = explode("|", $ciclo);
$codigo_ciclo = $explode_ciclo[0];
$codigo_gestion = $explode_ciclo[1];
$global_linea = 1021;

//echo $territorios . " " . $ciclo;

?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Banco De Muestras</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="js/tableDnD/tablednd.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="js/tableDnD/js/jquery.tablednd.0.7.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {

        $("body").on({
            ajaxStart: function() { 
                $(this).addClass("loading"); 
            },
            ajaxStop: function() { 
                $(this).removeClass("loading"); 
            }    
        });
        
        $(".cajita").click(function(){
            var destino,cambio,destino_aux,destino_cod,destino_cod_aux,cambio_cod;
            
            destino = $(this).parent().parent().find(".cambio span")
            destino_aux = $(this).parent().parent().find(".cambio span").html()
            cambio = $(this)
            
            destino_cod = $(this).parent().parent().find(".cambio input")
            destino_cod_aux = $(this).parent().parent().find(".cambio input").val()
            cambio_cod = $(this).attr('cod')
            
            destino.html(cambio.attr('name'))
            cambio.html('<span>'+destino_aux+'</span>')
            cambio.attr('name',destino_aux)
            
            destino_cod.val(cambio_cod)
            cambio.attr('cod',destino_cod_aux)
        });
        $("#boton").click(function(){
            var valores='',valores_aux='';
            $(".valores").each(function(){
                valores_aux = '';
                $("table input",this).each(function(){
                    valores_aux += $(this).val()+",";
                })
                valores += valores_aux+"@";
            })
            // alert(valores);
            $.getJSON("ajax/grupos_especiales/enviarBK.php",{
                "valores" : valores,
                "ciclo": <?php echo $codigo_ciclo; ?>,
                "gestion": <?php echo $codigo_gestion; ?>,
                "territorios": '<?php echo $territorios; ?>'
            },response);
            
            return false;
        })
        function response(datos){
            alert(datos)
            window.location.href = "recalculo_ge.php";
        }
    })
</script>
<style type="text/css">
#container {
    position: relative
}
table tbody tr:nth-child(2n) {
    background:  #e8e8e8;
}
.cajita {
    padding: 3px;
    background: white;
    border: 1px solid activeborder;
    cursor: pointer;
    margin: 4px 0
}
.cajita:hover {
    padding: 3px;
    background: #29677e;
    border: 1px solid activeborder;
    cursor: pointer;
    color: white;
}
table thead tr th, table tfoot tr td {
    font-size: 12px;
}
#boton {
    position: fixed;
    right: 10px;
    top: 50px;
    background: #fff;
    border: 1px solid activeborder;
    padding: 4px;
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
#boton:hover span {
    color: white;
}
#enviar span {
    display: block;
}
.modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 0, 0, 0,.8 ) 
    url('http://i.stack.imgur.com/FhHRx.gif') 
    50% 50% 
    no-repeat;
}
body.loading {
    overflow: hidden;   
}

body.loading .modal {
    display: block;
}
</style>
</head>
<body>
    <div id="container">
        <?php require("estilos2.inc"); ?>
        <?php 
			$global_gestion = 1014; 
		?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Grupos Especiales</h3>
            <h3 style="color: #5F7BA9; font-size: 1.2em; font-family: Vernada">Asignaci&oacute;n de visitadores a m&eacute;dicos</h3>
            <?php
            $sql_territorio = mysql_query("SELECT descripcion from ciudades where cod_ciudad in ($territorios)");
            while ($row_ciudad = mysql_fetch_array($sql_territorio)) {
                $territorios_finales .= $row_ciudad[0] . ",";
            }
            $territorios_finales_sub = substr($territorios_finales, 0, -1);
            $territorios_finales_explode = explode(",", $territorios_finales_sub);
            ?>
            <p style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; text-transform: capitalize">Para el ciclo: <?php echo $codigo_ciclo; ?> | Territorio(s): <?php echo $territorios_finales_sub; ?></p>
        </header>
        <section role="main">
            <div class="row">
                <?php foreach ($territorios_finales_explode as $terri) { ?>
                <div class="twelve columns">
                    <h3 style="color: #5F7BA9; font-size: 1.3em; font-family: Vernada; text-align: left"><?php echo $terri; ?></h3>
                    <?php
                    $contador_row = 1;
                    $sql_cod_ci = mysql_query("SELECT cod_ciudad from ciudades where descripcion = '$terri'");
                    $cod_ciudad_final = mysql_result($sql_cod_ci, 0, 0);
					
					$txtGE="SELECT g.codigo_grupo_especial, g.nombre_grupo_especial, g.codigo_linea, g.codigo_linea1, g.codigo_linea2,
					g.codigo_linea3,  
					(select l.nombre_linea from lineas l where l.codigo_linea=g.codigo_linea)linea1,
					(select l.nombre_linea from lineas l where l.codigo_linea=g.codigo_linea1)linea2,
					(select l.nombre_linea from lineas l where l.codigo_linea=g.codigo_linea2)linea3,
					(select l.nombre_linea from lineas l where l.codigo_linea=g.codigo_linea3)linea4
					from grupo_especial g 
					where g.agencia='$cod_ciudad_final'order by g.nombre_grupo_especial ";
                    $sql_ge = mysql_query($txtGE);
                    if (mysql_num_rows($sql_ge) == 0) {
                        echo "<p>No hay grupos especiales en esta regional</p>";
                    }
                    while ($row_ge = mysql_fetch_array($sql_ge)) {
                        $codigoLineaGE=$row_ge[2];
						$codigoLineaGE2=$row_ge[3];
						$codigoLineaGE3=$row_ge[4];
						$codigoLineaGE4=$row_ge[5];
						$lineasX=$row_ge[6]." ".$row_ge[7]." ".$row_ge[8]." ".$row_ge[9];
						
						$txtMedico="SELECT concat(m.ap_pat_med, ' ',m.ap_mat_med,' ',m.nom_med) as nom, c.cod_especialidad, 
						c.categoria_med,m.cod_med from grupo_especial g, grupo_especial_detalle gd, medicos m, categorias_lineas c 
						where g.codigo_grupo_especial = gd.codigo_grupo_especial and gd.cod_med = m.cod_med and 
						c.codigo_linea = g.codigo_linea and c.cod_med = gd.cod_med 
						and g.agencia = '$cod_ciudad_final' AND g.codigo_grupo_especial = $row_ge[0] 
						ORDER BY g.nombre_grupo_especial, nom, c.cod_especialidad";
						//echo $txtMedico;
						$sql_medico = mysql_query($txtMedico);
                        $contadors = 0;
                        ?>
                        <div class="six columns end valores">
                            <h3 style="color: #5F7BA9; font-size: 1.0em; font-family: Vernada; text-align: left"><?php echo $row_ge[1]." (". $lineasX.")"; ?></h3>
                            <table border="1" id="<?php echo $row_ge[0] . "-" . $cod_ciudad_final ?>">
                                <input type="hidden" value="<?php echo $row_ge[0] . "-" . $cod_ciudad_final ?>" />
                                <thead>
                                    <tr>
                                        <th>Ruc</th>
                                        <th>M&eacute;dico</th>
                                        <th>Visitador Oficial</th>
                                        <th>Reemplazo</th>
                                        <th>N&deg; Visitadores</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $connt = 0;
                                    while ($row_medicos = mysql_fetch_array($sql_medico)) {
                                    $codigo_visitador_consulta = 1;
                                        ?>
                                        <?php
                                        
										$sqlVis = "SELECT DISTINCT rd.cod_visitador , concat(f.paterno,' ',f.materno,' ',f.nombres) as nom 
										from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado r, rutero_maestro_detalle_aprobado rd, 
										funcionarios f where rc.cod_rutero=r.cod_rutero AND r.cod_contacto=rd.cod_contacto and 
										f.codigo_funcionario=rd.cod_visitador and rc.cod_visitador=r.cod_visitador and 
										r.cod_visitador=rd.cod_visitador and rc.cod_visitador=rd.cod_visitador AND rd.cod_med=$row_medicos[3] AND 
										rc.codigo_ciclo=$codigo_ciclo AND rc.codigo_gestion=$global_gestion and 
										(rc.codigo_linea = '$codigoLineaGE' or rc.codigo_linea='$codigoLineaGE2' or rc.codigo_linea='$codigoLineaGE3' 
										or rc.codigo_linea='$codigoLineaGE4') 
										and f.estado = 1 ORDER BY nom ASC";
                                        
										/*$sqlVis = "select f.codigo_funcionario, concat(f.paterno,' ',f.materno,' ',f.nombres) 
										from funcionarios f where f.cod_ciudad='$cod_ciudad_final' and f.estado=1 and f.cod_cargo=1011";*/
										
										//echo $sqlVis;
										
										$cadena_visitador = '';
                                        $sql_vis = mysql_query($sqlVis);
                                        $numero_visitadores = mysql_num_rows($sql_vis);
                                        if ($numero_visitadores == 0) {
                                            $cadena_visitador = "<span style='color:red'>No hay visitador(es)</span>";
                                            $codigo_visitador_consulta = 0;
                                        }
                                        if ($numero_visitadores == 1) {
                                            $cadena_visitador = mysql_result($sql_vis, 0, 1);
                                            $codigo_visitador = mysql_result($sql_vis, 0, 0);
                                        }

                                        if ($numero_visitadores == 2) {
                                            if ($connt == 2) {
                                                $connt = 0;
                                            }
                                            $cadena_visitador = mysql_result($sql_vis, $connt, 1);
                                            $codigo_visitador = mysql_result($sql_vis, $connt, 0);
                                            $cadena_visitador = "<span>$cadena_visitador</span>";
                                            if($connt == 0){$conntt=1;}
                                            if($connt == 1){$conntt=0;}
                                            $cadena_visitador_reemplazo1 = mysql_result($sql_vis, $conntt, 1);
                                            $codigo_visitador_reemplazo = mysql_result($sql_vis, $conntt, 0);
                                            $cadena_visitador_reemplazo = "<div class='cajita' name='$cadena_visitador_reemplazo1' cod='$codigo_visitador_reemplazo'><span>" . $cadena_visitador_reemplazo1 .  "</span></div> ";
                                            $connt++;
											/*$sql_result = mysql_query($sqlVis);
											while ($row_prob = mysql_fetch_array($sql_result)) {
                                                $cadena_visitador_reemplazo .= "<div class='cajita' name='$row_prob[1]' cod='$row_prob[0]'>" . $row_prob[1] . "</div>";
                                            }*/
											
                                        } else {
                                            $cadena_visitador_reemplazo = "<span style='color:red'>No tiene reemplazos</span>";
                                        }

                                        if ($numero_visitadores > 2) {
                                            $cadena_visitador_reemplazo = '';
                                            $sql_result = mysql_query($sqlVis);
                                            $cadena_visitador = mysql_result($sql_vis, $contadors, 1);
                                            $codigo_visitador = mysql_result($sql_vis, $contadors, 0);
                                            $cadena_visitador = "<span>$cadena_visitador</span>";
                                            $cadena_visitador_reemplazo = '';
                                            while ($row_prob = mysql_fetch_array($sql_result)) {
                                                if ($codigo_visitador == $row_prob[0]) {
                                                    $cadena_visitador_reemplazo .= "";
                                                } else {
                                                    $cadena_visitador_reemplazo .= "<div class='cajita' name='$row_prob[1]' cod='$row_prob[0]'>" . $row_prob[1] . "</div>";
                                                }
                                            }
                                        }
										
										$sqlVeriExiste="select gd.cod_visitador,  
										 (select concat(paterno, ' ', nombres,' (G)') from funcionarios where codigo_funcionario=gd.cod_visitador) 
										 from grupos_especiales g, grupos_especiales_detalle gd where g.id=gd.id and g.gestion=$global_gestion 
										 and g.ciclo=$codigo_ciclo and gd.cod_med = $row_medicos[3] and g.codigo_grupo_especial='$row_ge[0]'";
										//echo $sqlVeriExiste."<br>";
										$respVeriExiste=mysql_query($sqlVeriExiste);
										$numFilasExiste=mysql_num_rows($respVeriExiste);
										$banderaExiste=0;
										if($numFilasExiste>0){
											$banderaExiste=1;
											$codVisGuardado=mysql_result($respVeriExiste,0,0);
											$nombreVisGuardado=mysql_result($respVeriExiste,0,1);
										}
										if($banderaExiste==1){
												$cadena_visitador = $nombreVisGuardado;
												$codigo_visitador = $codVisGuardado;
												$cadena_visitador = "<span style='color:blue'>$cadena_visitador</span>";
										}
										
										
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $row_medicos[3]; ?> 
                                                <?php if($codigo_visitador_consulta > 0) { ?>
                                                    <input type="hidden" value="<?php echo $row_medicos[3] ?>" />
                                                <?php } ?>
                                            </td>
                                            <td><?php echo $row_medicos[0]; ?></td>
                                            <td>
                                                <div class="cambio">
                                                    <span><?php echo $cadena_visitador; ?></span>
                                                    <?php if($codigo_visitador_consulta > 0) { ?> 
                                                        <input type="hidden" value="<?php echo $codigo_visitador ?>" /> 
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <td><?php echo $cadena_visitador_reemplazo; ?></td>
                                            <td><?php echo $numero_visitadores; ?></td>
                                        </tr>
                                        <?php
                                        if ($numero_visitadores > 2) {
                                            $contadors++;
                                        } else {
                                            $contadors = $contadors;
                                        }
                                        ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            $contador_row++;
                        }
                        ?>
                    </div>
                    <?php } ?>
                </div>
            </section>
            <div id="boton">
                <a href="javascript:void(0)" id="enviar">
                    <span> G </span>  
                    <span> U </span>  
                    <span> A </span>  
                    <span> R </span>  
                    <span> D </span>  
                    <span> A </span>  
                    <span> R </span>  
                </a>
            </div>
        </div>
        <div class="modal"></div>
    </body>
    </html>