<?php
error_reporting(0);
require("conexion.inc");

$ciclo_gestion = $_GET['ciclo_gestion'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final = $ciclo_gestion_explode[0];
$gestion_final = $ciclo_gestion_explode[1];

$productos_lista = $_GET['productos'];
$productos_lista_explode = explode(",", $productos_lista);

$nombres_productos = $_GET['codigos_productos'];
$nombres_productos = substr($nombres_productos, 0, -2);
$nombres_productos_explode = explode(",", $nombres_productos);

$productos_combinado = array_combine($productos_lista_explode, $nombres_productos_explode);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion de Material De Apoyo detalle</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="css/style_panel.css" rel="stylesheet" />
    <link type="text/css" href="css/acordeon.css" rel="stylesheet" />
    <link type="text/css" href="css/jquery-ui-1.8.9.custom.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link type="text/css" href="js/fancybox/jquery.fancybox.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <style type="text/css" href="lib/noty/buttons.css"></style>
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="lib/sticky.js"></script>
    <script type="text/javascript" src="lib/autocomplete/jquery-ui-1.8.9.custom.min.js"></script>
    <script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="js/fancybox/jquery.fancybox.js"></script>
    <script type="text/javascript" src="lib/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="lib/noty/center.js"></script>
    <script type="text/javascript" src="lib/noty/default.js"></script>
    <script type="text/javascript">
	var globalCiudades="";
    Array.prototype.unique=function(a){
        return function(){return this.filter(a)}
    }(function(a,b,c){return c.indexOf(a,b+1)<0});
    jQuery(document).ready(function($) {

        $("body").on({
            ajaxStart: function() { 
                $(this).addClass("loading"); 
            },
            ajaxStop: function() { 
                $(this).removeClass("loading"); 
            }    
        });
        $("#filtro_especialidad").click(function(){
            $(".panell").toggle("fast");
            $(this).toggleClass("active");
            return false;
        })
        $("#filtro_especialidad_linea").click(function(){
            $(".panell_lineas").toggle("fast");
            $(this).toggleClass("active");
            return false;
        })
        $('.espe2').toggle(function(){
            $(this).addClass("active");
            $('.espe2').not('.active').each(function(){
                especialidad_filtro = $(this).find('p').text()
                $("#ciudadess .var.selected").each(function(index){
                    espe_para_filtrar = $(this).find('.especialidad_a_filtrar').val()
                    if(especialidad_filtro == espe_para_filtrar){
                        $(this).removeClass('selected')
                        $(this).hide()
                    }
                })
            })
            $('.espe2.active').each(function(){
                especialidad_filtro = $(this).find('p').text()
				$("#ciudadess .var").each(function(index){
                    espe_para_filtrar = $(this).find('.especialidad_a_filtrar').val()
                    if(especialidad_filtro == espe_para_filtrar){
                        $(this).addClass('selected')
                        $(this).show()
                    }
                })
            })
        }, function () {
            $(this).removeClass("active");
            $('.espe2.active').each(function(){
                especialidad_filtro = $(this).find('p').text()
                $("#ciudadess .var").each(function(index){
                    espe_para_filtrar = $(this).find('.especialidad_a_filtrar').val()
                    if(especialidad_filtro == espe_para_filtrar){
                        $(this).addClass('selected')
                        $(this).show()
                    }
                })
            })
            $('.espe2').not('.active').each(function(){
                especialidad_filtro = $(this).find('p').text()
                $("#ciudadess .var.selected").each(function(index){
                    espe_para_filtrar = $(this).find('.especialidad_a_filtrar').val()
                    if(especialidad_filtro == espe_para_filtrar){
                        $(this).removeClass('selected')
                        $(this).hide()
                    }
                })
            })
        });

$('.espe3').toggle(function(){
    globalCiudades="";
	
	$(this).addClass("active");
    $('.espe3').not('.active').each(function(){
		especialidad_filtro = $(this).find('p').text()
        $("#ciudadess .var.selected").each(function(index){
            espe_para_filtrar = $(this).find('.esp_lin_filtrar').val()
            if(especialidad_filtro == espe_para_filtrar){
                $(this).removeClass('selected')
                $(this).hide()
            }
        })
    })
	$('.espe3.active').each(function(){
		especialidad_filtro = $(this).find('p').text()
		globalCiudades=globalCiudades+"|"+especialidad_filtro;
        $("#ciudadess .var").each(function(index){
            espe_para_filtrar = $(this).find('.esp_lin_filtrar').val()
            if(especialidad_filtro == espe_para_filtrar){
                $(this).addClass('selected')
                $(this).show()
            }
        })
		console.log(globalCiudades+"   1");
    })
},

function () {
    globalCiudades="";
    $(this).removeClass("active");
    $('.espe3.active').each(function(){
        especialidad_filtro = $(this).find('p').text()
        globalCiudades=globalCiudades+"|"+especialidad_filtro;
		$("#ciudadess .var").each(function(index){
            espe_para_filtrar = $(this).find('.esp_lin_filtrar').val()
            if(especialidad_filtro == espe_para_filtrar){
                $(this).addClass('selected')
                $(this).show()
            }
        })
		console.log(globalCiudades+"   2");
    })
    $('.espe3').not('.active').each(function(){
        especialidad_filtro = $(this).find('p').text()
		$("#ciudadess .var.selected").each(function(index){
            espe_para_filtrar = $(this).find('.esp_lin_filtrar').val()
            if(especialidad_filtro == espe_para_filtrar){
                $(this).removeClass('selected')
                $(this).hide()
            }
        })
    })
});
var cadena_ult = '';
$("#consolidado").live('click',function(){
    $('div#totaless').html('');
    var cadena = ''
    $(".espe").each(function(){
        cadena += $(this).find('p').text()+",";
    })
    cadena = cadena.slice(0,-1);
    cadena = cadena.split(",");
    cadena_final = cadena.unique()
    cadena_final = cadena_final + '';
    var cadena_final_split = new Array();
    cadena_final_split = cadena_final.split(",")
    var total = 0;
    $.each(cadena_final_split, function(index, value) { 
        total = 0;
        var codigos_finales = value + '';
        var codigos_finales_split = new Array();
        codigos_finales_split = codigos_finales.split("@");
        $("."+codigos_finales_split[1]).each(function(){
            var cantidad_medicos_a = $(this).find('.cma').text();
            var cantidad_medicos_b = $(this).find('.cmb').text();
            var cantidad_medicos_c = $(this).find('.cmc').text();
            var caa_a = $(this).find('.ca').val();
            var caa_b = $(this).find('.cb').val();
            var caa_c = $(this).find('.cc').val();
            if(cantidad_medicos_a == '-' || cantidad_medicos_a == ''){cantidad_medicos_a = 0;}
            if(cantidad_medicos_b == '-' || cantidad_medicos_b == ''){cantidad_medicos_b = 0;}
            if(cantidad_medicos_c == '-' || cantidad_medicos_c == ''){cantidad_medicos_c = 0;}
            if(caa_a == '-' || caa_a == '' || caa_a === undefined || caa_a == null){caa_a = 0;}
            if(caa_b == '-' || caa_b == '' || caa_b === undefined || caa_a == null){caa_b = 0;}
            if(caa_c == '-' || caa_c == '' || caa_c === undefined || caa_a == null){caa_c = 0;}
            total = total + ( (cantidad_medicos_a*caa_a) + (cantidad_medicos_b*caa_b) + (cantidad_medicos_c*caa_c) )
        })
$('div#totaless').append('<input type="hidden" class="'+codigos_finales_split[1]+' canti_final" value="'+codigos_finales_split[1]+'@'+total+'" />');
});
cadena_ult = '';
$(".canti_final").each(function(){
    cadena_ult += $(this).val()+",";
})
$("#consolidado").fancybox({
    ajax : {
        type    : "POST",
        data    : {
            'mydata':cadena_final,
            'cadena':cadena_ult
        }
    },
    fitToView   : true,
    width       : '90%',
    height      : '90%'
});
return false;
})

var ciclo_gestion = '<?php echo $ciclo_gestion; ?>';
var territorios = '<?php echo $territorios ?>';

$("#busca_producto").autocomplete( { source: "js/tags/material_apoyo.php" });
$('.acc_container').hide(); 
$('.acc_trigger:first').addClass('active').next().show(); 

$('.acc_trigger').click(function(){
    if( $(this).next().is(':hidden') ) { 
        $('.acc_trigger').removeClass('active').next().slideUp(); 
        $(this).toggleClass('active').next().slideDown(); 
    }
    return false; 
});
$('.block h3').toggle(function(){
    $(this).parent().removeClass("selected");
},
function () {
    $(this).parent().addClass("selected");
});
$("#agregar").live('click',function(e){
    if($("#busca_producto").val() == ''){
        alert("No Ingreso Ningun Producto")
        return false
    }
    if($("#cantidad_generica").val() == ''){
        alert("No ingreso Ninguna Cantidad")
        return false
    }
    var medicos_totales = 0;
    var material_totales = 0;
    var material_totales_aux = 0;
    var producto = $("#busca_producto").val();
    var cantidad = $("#cantidad_generica").val();
    var categoria_filtro_finales  = "";
    $(".cat_filtro:checked").each(function(){
        categoria_filtro_finales +=$(this).val()+","
    })
    var categoria_filtro_finales_f = categoria_filtro_finales.slice(0,-1);
    if(categoria_filtro_finales_f.indexOf("A") >= 0 ){
        cantidad_a = cantidad;
        class_a = "tiene_filtro";
    }else{
        cantidad_a = '0';
        class_a = "no_tiene_filtro";
    }
    if(categoria_filtro_finales_f.indexOf("B") >= 0 ){
        cantidad_b = cantidad;
        class_b = "tiene_filtro";
    }else{
        cantidad_b = '0';
        class_b = "no_tiene_filtro";
    }
    if(categoria_filtro_finales_f.indexOf("C") >= 0 ){
        cantidad_c = cantidad;
        class_c = "tiene_filtro";
    }else{
        cantidad_c = '0';
        class_c = "no_tiene_filtro";
    }

	//alert("ciudades: "+globalCiudades);
                
    $("#ciudadess .var.selected").each(function(index){
        var producto_split = producto.split('@');
        
        $("div.caja_apoyo",this).append('<div class="espe '+producto_split[1]+' para_guardar"><input type="hidden" value="" ><p>'+producto+'</p><img class="eliminar_espe" alt="Cerrar" src="imagenes/no.png"> <input type="checkbox" value="" class="input_si_va" style="margin-left:10px" /> Va Muestra? <div class="cant-exi"> <input type="hidden" value="'+ producto_split[1] +'" class="codigo_ma_cad" /> <span class="dd">NM&deg; A:</span> <span class="cantidad_medicos_rutero cma">-</span> <input type="text" class="cantidades ca '+class_a+'" placeholder="A" value="'+ cantidad_a +'" /><span class="dd">NM&deg; B:</span> <span class="cantidad_medicos_rutero cmb">-</span> <input type="text" class="cantidades cb '+class_b+'" placeholder="B"  value="'+ cantidad_b +'" /> <span class="dd">NM&deg; C:</span> <span class="cantidad_medicos_rutero cmc">-</span> <input type="text" class="cantidades cc '+class_c+'" placeholder="C"  value="'+ cantidad_c +'" /> <!--<span class="cantidad_existencia_actualizar">--</span>--> <span class="cantidad_material_final_x_medico">--</span>   <img src="imagenes/ajax-loader2.gif" class="img-cargar"> <span class="cantidad_original" style="display:none"></span> </div> </div>');   

        var cadena = $(this).find('input.esp_lin_prod').val();
		var ciudades = $(this).find('input.ciudades_plan_lineas').val();
		var linea_mkt= $(this).find('input.linea_mkt').val();
		var thiss = $(this);
		
        $.ajax({
            type: "POST",
            url: "ajax/parrilla_excel/numero_medicos_rutero.php",
            dataType : 'json',
            data: { 
                ciclo: '<?php echo $ciclo_final ?>',
                gestion: '<?php echo $gestion_final ?>',
                cadena : cadena,
                ciudades : globalCiudades,
                ma: producto,
				linea_mkt: linea_mkt
            }
        }).done(function(data) {
            var aux = 0;
            thiss.find('.cma').last().html(data.cantidadd_a);
            thiss.find('.cmb').last().html(data.cantidadd_b);
            thiss.find('.cmc').last().html(data.cantidadd_c);
            thiss.find('.cantidad_original').last().html(data.existencia);
            var total_final = data.existencia - (data.cantidadd_a*thiss.find('.ca').last().val()) - (data.cantidadd_b*thiss.find('.cb').last().val()) - (data.cantidadd_c*thiss.find('.cc').last().val());
            var total_final_x_medico = (data.cantidadd_a*thiss.find('.ca').last().val()) + (data.cantidadd_b*thiss.find('.cb').last().val()) + (data.cantidadd_c*thiss.find('.cc').last().val());
            material_totales = material_totales + total_final_x_medico;
            thiss.find('.cantidad_existencia_actualizar').last().html(total_final);
            thiss.find('.cantidad_material_final_x_medico').last().html(total_final_x_medico);
            $("#existencia_final").html(data.existencia)
            if(data.existencia<material_totales){
                $("#material_total").css('color','red')
                $("#material_total").css('font-weight','bold')
            }else{
                $("#material_total").css('color','green')
                $("#material_total").css('font-weight','bold')
            }
            $("#material_total").html(material_totales)
        });
}) 

var todas_especialidades = '',ciudades='';
$("#ciudadess .var.selected").each(function(index){
    todas_especialidades  += $(this).find('.cad_espe_lin').text()+",";
    ciudades += $(this).find('input.ciudades_plan_lineas').val()+",";
})
todas_especialidades = todas_especialidades.slice(0,-1);
ciudades = ciudades.slice(0,-1);
$.ajax({
    type: "POST",
    url : "ajax/parrilla_excel/numero_total_medicos.php",
    dataType : 'json',
    data : {
        ciclo: '<?php echo $ciclo_final ?>',
        gestion: '<?php echo $gestion_final ?>',
        candea: todas_especialidades,
        ciudades: ciudades
    }

}).done(function(data) {
    $("#medicos_total").html(data.cantidadd)
});
$("#busca_producto").val("");
})

$(".eliminar_espe").live("click", function(){ 
    var parent = $(this);
    var codigo_ma_p_b =  parent.parent().find('.codigo_ma_cad').val();
    var cantidad_a =  parent.parent().find('.ca').val();
    var cantidad_b =  parent.parent().find('.cb').val();
    var cantidad_c =  parent.parent().find('.cc').val();
    var cab_pa_b = parent.parent().parent().parent().find('h3 .esp_lin_prod').val()
    var n = noty({
        text: "Desea eliminar la muestra?",
        type: "error",
        dismissQueue: true,
        layout: "center",
        theme: 'defaultTheme',
        modal:true,
        buttons: [{
            addClass: 'btn btn-primary', 
            text: 'Ok', 
            onClick: function($noty) {
                $noty.close();
                parent.parent().remove();
                $.ajax({
                    type: "POST",
                    url : "ajax/parrilla_excel/borrar_ma.php",
                    dataType : 'json',
                    data : {
                        ciclo: '<?php echo $ciclo_final ?>',
                        gestion: '<?php echo $gestion_final ?>',
                        codigo_ma: codigo_ma_p_b,
                        cab_pa_b: cab_pa_b,
                        cantidad_a: cantidad_a,
                        cantidad_b: cantidad_b,
                        cantidad_c: cantidad_c
                    }
                }).done(function(data) {
                    parent.parent().remove();
                });
            }}, {
                addClass: 'btn btn-danger', 
                text: 'Cancel', 
                onClick: function($noty) {
                    $noty.close();
                    alert("No se borro ningun dato.");
                }
            } ]
        });

}); 

$(".cantidades").live('change',function(){
    if($(this).val()>0){
        $(this).removeClass('no_tiene_filtro')
        $(this).removeClass('tiene_filtro')
        $(this).addClass('tiene_filtro')
    }else{
        $(this).removeClass('no_tiene_filtro')
        $(this).removeClass('tiene_filtro')
        $(this).addClass('no_tiene_filtro')
    }
    var material_totales = 0;
    $(this).parent().find('.img-cargar').fadeIn();
    var cantidad_existencia = $(this).parent().find('.cantidad_original').text();
    var cantidad_medicos_a = $(this).parent().find('.cma').text();
    var cantidad_medicos_b = $(this).parent().find('.cmb').text();
    var cantidad_medicos_c = $(this).parent().find('.cmc').text();
    var cantidad_final = cantidad_existencia - (cantidad_medicos_a*$(this).parent().find('.ca').val()) - (cantidad_medicos_b*$(this).parent().find('.cb').val()) - (cantidad_medicos_c*$(this).parent().find('.cc').val());
    var total_final_x_medico = (cantidad_medicos_a*$(this).parent().find('.ca').val()) + (cantidad_medicos_b*$(this).parent().find('.cb').val()) + (cantidad_medicos_c*$(this).parent().find('.cc').val());
    $(this).parent().find('.cantidad_existencia_actualizar').html(cantidad_final);            
    $(this).parent().find('.cantidad_material_final_x_medico').html(total_final_x_medico);            
    $(this).parent().find('.img-cargar').fadeOut();
    var suma_medicos;
    var total_final_x_medico_f;
    $("#ciudadess .var.selected").each(function(index){

        var cantidad_medicos_a = $(this).find('.cma').text();
        var cantidad_medicos_b = $(this).find('.cmb').text();
        var cantidad_medicos_c = $(this).find('.cmc').text();
        if(cantidad_medicos_a == '-'){cantidad_medicos_a = 0;}
        if(cantidad_medicos_b == '-'){cantidad_medicos_b = 0;}
        if(cantidad_medicos_c == '-'){cantidad_medicos_c = 0;}
        
        total_final_x_medico_f = (cantidad_medicos_a*$(this).find('.ca').val()) + (cantidad_medicos_b*$(this).find('.cb').val()) + (cantidad_medicos_c*$(this).find('.cc').val());
        material_totales = material_totales + total_final_x_medico_f;
    })
    if($("#existencia_final").text()<material_totales){
        $("#material_total").css('color','red')
        $("#material_total").css('font-weight','bold')
    }else{
        $("#material_total").css('color','green')
        $("#material_total").css('font-weight','bold')
    }
    $("#material_total").html(material_totales) 

});

$("#continuar").click(function(){


	if($("#nro_contacto").val() == ''){
        alert("No ingreso el contacto en el que ira el Material de Apoyo")
        return false
    }
    $("#ciudadess .var").each(function(index){
        $(this).addClass('selected')
        $(this).show();
    });
	
    var primera_cadena = '', segunda_cadena = '', codigo_linea;
    var ciclo_final = <?php echo $ciclo_final; ?>;
    var gestion_final = <?php echo $gestion_final; ?>;
	var numeroContacto = $("#nro_contacto").val();
	
    $(".codigo_producto_mm").each(function(){
        primera_cadena += $(this).val()+","+ciclo_final+","+gestion_final+"@"; 
    })
    $("#ciudadess .var.selected").each(function(index){
        codigo_linea = $(this).find('.cad_espe_lin').text();
        var codigo_mmm = '',codigo_ma = '',dist_a = '',dist_b = '', dist_c = '', va_muestra = 0, espe_linea = '',ciu, lineaMkt; 
        codigo_mmm = $(this).find('.jalar_codigo_producto').val();
        espe_linea = $(this).find('.esp_lin_filtrar').val();
        ciu        = $(this).find('.ciudades_plan_lineas').val();
		lineaMkt = $(this).find('.linea_mkt').val();
		
        $("div.caja_apoyo .espe.para_guardar",this).each(function(index){
            segunda_cadena += codigo_linea+",";
            
            codigo_ma = $(this).find('.codigo_ma_cad').val();
            dist_a = $(this).find('.ca').val();
            dist_b = $(this).find('.cb').val();
            dist_c = $(this).find('.cc').val();
            if($(this).find('.input_si_va').is(":checked")){
                va_muestra = 1;
            }else{
                va_muestra = 0;
            }

            segunda_cadena += codigo_mmm+","+codigo_ma+","+dist_a+","+dist_b+","+dist_c+","+va_muestra+","+espe_linea+","+ciu+","+lineaMkt+"@";
        })
    })
	
$.ajax({
    type: "POST",
    url : "ajax/parrilla_excel/agrega_ma.php",
    dataType : 'json',
    data : {
        primera_cadena: primera_cadena,
        segunda_cadena: segunda_cadena,
		ciudades: globalCiudades,
		numeroContacto: numeroContacto
    }
}).done(function(data) {
    alert(data.mensaje)
                        // window.location.href = "asignacion_ma_vista_previap.php?ciclo=<?php echo $ciclo_final ?>&gestion=<?php echo $gestion_final ?>&territorio=123,102,114,113,122,104,119,120,121,116,124,109,118,117";
                        window.close();
                    });
})

});
</script>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Asignaci&oacute;n de Material De Apoyo</h3>
            <h3 style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; font-weight: normal;">Productos: <?php echo $nombres_productos; ?></h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="twelve columns">
                    <div id="sticky_navigation_wrapper" class="twelve columns">
                        <div id="sticky_navigation">
                            <div class="demo_container">
                                <input type="text" class="busca_producto" id="busca_producto" placeholder="Ingrese el nombre del material de apoyo." />   
								<a class="button" href="javascript:void(0)" id="continuar">Guardar</a>
                            </div>
                            <div class="row collapse" style="margin-left:50px">
                                <div class="twelve columns">
                                    <input type="text" placeholder="Cantidad" style="margin-left:0; margin-top:0; height: 25px; width:8%" id="cantidad_generica" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                    
									<div class="cant-exi" style="margin-top:0; line-height:30px">
                                        <input type="checkbox" name="categoria_filtro" class="cat_filtro" value="A"> A
                                        <input type="checkbox" name="categoria_filtro" class="cat_filtro" value="B"> B
                                        <input type="checkbox" name="categoria_filtro" class="cat_filtro" value="C"> C
                                    </div>
									
                                    <a class="button" href="javascript:void(0)" id="agregar" style="padding:5px; line-height:16px">Agregar</a>
									<input type="text" placeholder="Contacto" style="margin-top:0; height: 25px; width:8%" id="nro_contacto" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;">
                                    
                                    <a style="margin-left:10px ;line-height:10px" href="consolidado_asignacion_ma.php" class="consolidado fancybox.ajax rayita_hover button" id="consolidado">Ver Consolidado</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="ciudadess">
                <div class="twelve columns" style="padding-top: 30px">
                    <?php 
					
					/*echo "prod Combinado $productos_combinado";
					echo "codigoprod $codigo_prod";
					echo "nombreprod $nombre_prod";*/
					
					foreach ($productos_combinado as $codigo_prod => $nombre_prod) { ?>
                    <?php $ca .= "'" . $codigo_prod . "',"; ?>
                    <h2 class="acc_trigger">
                        <a href="javascript:void(0)"><?php echo $nombre_prod; ?></a>
                        <input type="hidden" value="<?php echo $codigo_prod; ?>" class="codigo_producto_mm" />
                    </h2>
                    <div class="acc_container">
                        <div class="block">
                            <?php

							/*$txt_lineas="SELECT DISTINCT CONCAT(ad.especialidad, ' - ', l.nombre_linea)as nombre, ad.especialidad, 
								ad.linea, ad.linea_mkt from asignacion_productos_excel a, asignacion_productos_excel_detalle ad , 
								lineas l where ad.id = a.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final 
								and ad.producto = '$codigo_prod' and ad.linea_mkt=l.codigo_linea 
								group by nombre, especialidad, linea_mkt order by 1";*/
								
							$txt_lineas="SELECT distinct CONCAT(cl.cod_especialidad, ' ( ', l.nombre_linea, ' )')as nombre, cl.cod_especialidad, 
								cl.codigo_linea, cl.codigo_linea from parrilla_personalizada p, 
								lineas l, categorias_lineas cl where p.cod_gestion='$gestion_final' and p.cod_ciclo='$ciclo_final' and 
								p.cod_mm='$codigo_prod' and p.cod_linea=l.codigo_linea and l.codigo_linea=cl.codigo_linea and 
								p.cod_med=cl.cod_med order by 1";
							
							//echo $txt_lineas;
							
							$sql_lineas = mysql_query($txt_lineas);

                            while ($row_lineas = mysql_fetch_array($sql_lineas)) {
                                ?>
                                <div class="var selected">
                                    <h3>
                                        <?php echo $row_lineas[0]; ?> <input type="hidden" class="especialidad_a_filtrar" value="<?php echo $row_lineas[1]; ?>"> <input type="hidden" class="esp_lin_filtrar" value="<?php echo $row_lineas[1];/* . " " . $row_lineas[2];*/?>"><input type="hidden" class="esp_lin_prod" value="<?php echo $row_lineas[1] . "@" . $row_lineas[2] . "@" . $codigo_prod; ?>"> <span class="cad_espe_lin" style="display:none"><?php echo $row_lineas[0]; ?></span>  <input type="hidden" value="<?php echo $codigo_prod ?>" class="jalar_codigo_producto" />
                                        <?php
                                        $ciudades_finales   = '';
                                        $ciudades_finaless  = '';
                                        $ciudades_finalesss = '';
                                        $lineas_finales    = '';
                                        $ciudades_finalesss = '';
                                        $codigos_funcionarios_final = '';
                                        $codigos_funcionarios = '';
                                        $ciuddd = '';
                                        $var = $row_lineas[2];
                                        $ultimo_valor_max = $var[strlen($var) - 1];
                                        if ($row_lineas[1] == 'ONC') {
                                            $row_lineas[1] = 'ONCO';
                                        }
										
                                        $sql1 = mysql_query("SELECT DISTINCT a.codigo_mm from asignacion_ma_excel_detalle ad, asignacion_ma_excel a where a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.codigo_ma = '$codigo_prod' and a.id = ad.id_asignacion_ma and ad.espe_linea = '$row_lineas[1] $row_lineas[2]'");
                                        while ($row_re = mysql_fetch_array($sql1)) {
                                            $re .= "'".$row_re[0]."',";
                                        }
                                        $re = substr($re, 0, -1);
                                        $sql_ciudades = mysql_query("SELECT DISTINCT ap.ciudad FROM asignacion_productos_excel a, asignacion_productos_excel_detalle ap WHERE a.id = ap.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ap.especialidad = '$row_lineas[1]' and ap.linea = '$row_lineas[2]' and ap.producto = '$codigo_prod'");

                                        while ($row_ciudades = mysql_fetch_array($sql_ciudades)) {
                                            $ciud   = $row_ciudades[0];
                                            $ciudd  = $row_ciudades[0];
                                            $ciuddd = $row_ciudades[0];
                                            $lin   = '1021';
                                            
                                            if($ciud == 1009){
                                                $ciud   = '114,122,104,119,120,121,116,124,109,118,117';
                                                $ciudd  = '114*1009*122*1009*104*1009*119*1009*120*1009*121*1009*116*1009*124*1009*109*1009*118*1009*117';
                                                $ciuddd = '114*122*104*119*120*121*116*124*109*118*117';
                                                $lin   = '1009';
                                            }
                                            if($ciud == 1022){
                                                $ciud   = '122,120,121,116,124,118';
                                                $ciudd  = '122*1022*120*1022*121*1022*116*1022*124*1022*118';
                                                $ciuddd = '122*120*121*116*124*118';
                                                $lin   = '1022';
                                            }
                                            if($ciud == 1023){
                                                $ciud   = '122,120,121,116,118,117';
                                                $ciudd  = '122*1023*120*1023*121*1023*116*1023*118*1023*117';
                                                $ciuddd = '122*120*121*116*118*117';
                                                $lin   = '1023';
                                            }
											if($ciud == 1021){
                                                $ciud   = '114,122,104,119,120,121,116,124,109,118,117';
                                                $ciudd  = '114*1021*122*1021*104*1021*119*1021*120*1021*121*1021*116*1021*124*1021*109*1021*118*1021*117';
                                                $ciuddd = '114*122*104*119*120*121*116*124*109*118*117';
                                                $lin   = '1021';
                                            }
                                            $ciudades_finales   .= $ciud   . ",";
                                            $ciudades_finaless  .= $ciudd  . "*" . $lin . "*";
                                            $ciudades_finalesss .= $ciuddd . "*";
                                            $lineas_finales    .= $lin   . ",";
                                        }
                                        $ciudades_finales  = substr($ciudades_finales, 0, -1);
                                        $lineas_finales    = substr($lineas_finales, 0, -1);

                                        ?>
                                        <input type="hidden" class="ciudades_plan_lineas" value="<?php echo $ciudades_finalesss ?>">
                                        <input type="hidden" class="as" value="<?php echo $ciudades_finalesss ?>">
										<input type="hidden" class="linea_mkt" value="<?php echo $row_lineas[3] ?>">
										
                                    </h3>
                                    <input type="hidden" value="<?php echo $row_ciudades2[0] . "@" . $row_lineas[1]; ?>" class="cabeceras" />
                                    <div class="caja_apoyo" id="" >
                                        <!-- Aqui va la parte en la que se puede editar -->

                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div id="totaless"></div>
        </div>
    </div>
    <a style="color:#fff; font-weight:bold; font-size:13px;" href="javascript:void(0)" id="filtro_especialidad" class="trigger">Filtrar Especialidad</a>
    <div class="panell">
        <?php $ca_sub = substr($ca, 0, -1); ?>
        <?php 
		/*$txtEspeFiltro="SELECT DISTINCT ad.especialidad from asignacion_productos_excel a, 
		asignacion_productos_excel_detalle ad where ad.id = a.id and a.ciclo = $ciclo_final and 
		a.gestion = $gestion_final and ad.producto in ($ca_sub) order by 1";*/
		$txtEspeFiltro="SELECT distinct cl.cod_especialidad from parrilla_personalizada p, 
								lineas l, categorias_lineas cl where p.cod_gestion='$gestion_final' and p.cod_ciclo='$ciclo_final' and 
								p.cod_mm in ($ca_sub) and p.cod_linea=l.codigo_linea and l.codigo_linea=cl.codigo_linea and 
								p.cod_med=cl.cod_med and cl.cod_especialidad<>'' order by 1";
		//echo $txtEspeFiltro;
		$sql_espe_filtro = mysql_query($txtEspeFiltro) ?>
        
		<?php while ($row_espe_filtro = mysql_fetch_array($sql_espe_filtro)) { ?>
        <div class="espe2">
            <p><?php echo $row_espe_filtro[0] ?></p>
        </div>
        <?php } ?>
    </div>
    <a style="color:#fff; font-weight:bold; font-size:13px;" href="javascript:void(0)" id="filtro_especialidad_linea" class="trigger2">
	Filtrar Agencias</a>
    <div class="panell_lineas">
        <?php $ca_sub = substr($ca, 0, -1); ?>
        <?php $sql_espe_filtro_linea = mysql_query("SELECT cod_ciudad, descripcion from ciudades c where c.cod_ciudad<>115 order by 2") ?>
        <?php while ($row_espe_filtro_linea = mysql_fetch_array($sql_espe_filtro_linea)) { ?>
        <div class="espe3">
            <p><?php echo $row_espe_filtro_linea[1] ?></p>
        </div>
        <?php } ?>
    </div>

    <div class="modal"></div>
</body>
</html>