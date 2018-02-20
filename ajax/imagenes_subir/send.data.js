function sendData(){
    
    var nombre_pic = $("#nombre_pic").val();
    var codigo_material = $("#codigo_material").val();
    $.getJSON("ajax/imagenes_subir/send.php",{
        "nombre_pic": nombre_pic,
        "codigo_material": codigo_material
    },response);

    return false;
}

function response(datos)
{
    if(datos == 'good'){
        alert("Datos ingresados satisfactoriamente.")
        var from = document.referrer;
        //        window.location = from;
        //        window.history.back();
        parent.parent.$.fancybox.close();
    }else{
        alert("Los datos no fueron guardados, intentelo de nuevo.")
        //        window.location.reload(true);
        parent.parent.$.fancybox.close();
    }
    $("#loader").css('display', 'none')
    $("#manto").css('display', 'none')
}