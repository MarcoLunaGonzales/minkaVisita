$(document).ready(function(){
    
    $(".tab_content").hide();
    $("ul.tabs li:first").addClass("active").show();
    $(".tab_content:first").show();

    $("ul.tabs li").click(function()
    {
        $("ul.tabs li").removeClass("active");
        $(this).addClass("active");
        $(".tab_content").hide();

        var activeTab = $(this).find("a").attr("href");
        $(activeTab).fadeIn();
        return false;
    });
    $('input[placeholder], textarea[placeholder]').placeholder(); 
    $("#exafinicial").mask("99/99");
    $("#telefono").mask("9999999");
    $("#celular").mask("99999999");
        
    // Delete a slide
    $('#table-principal tr td.cargar_categoria .name_farm').bind("change", ( change ));
    function change(){
        cambiarCategoria($(this).val(),$(this).attr('name'))
    }
    function cambiarCategoria(nombre,puesto){
        var nombre_farmacia = nombre;
        var puesto = puesto;
        $.getJSON("ajax/categorizacion_medicos/cambiar.php",{
            "nombre_farmacia":  nombre_farmacia,
            "puesto":  puesto
        },responseCambiar);
    
        return false;
    
    }
    function responseCambiar(datos){
        if(datos.nombre==null || datos.nombre == ""){
            $("input[name='dir_farm_"+datos.puesto+"']").attr('value','Sin categoria')
        }else{
            $("input[name='dir_farm_"+datos.puesto+"']").attr('value',datos.nombre)
        }
    }
    $(".boton").click(function(){
        var vacio
        if($("#name_farm_1").val() == 'vacio' || $("#name_farm_2").val() == 'vacio' || $("#name_farm_3").val() == 'vacio' ){
            vacio = 0;
        }else{
            vacio = 1;
        }
        if($("#paci").val().length <= 0 || $("#consulta").val().length <= 0 ||  vacio ==0 ){
//            alert("campos vacios")
        }else{
            $("#loader").css('display', 'block')
            $("#manto").css('display', 'block')
            $(".para_bloquear").attr('disabled', 'disabled');
            sendData();
        }
    })
});