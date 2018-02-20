function sendData(){
    
    var codigo = $("#codigo_hermes").val();
    var codigo_baco = $("#baco").val();
    $.getJSON("ajax/baco_cod_hermes/send.php",{
        "codigo_baco": codigo_baco,
        "codigo_hermes": codigo
    },response);

    return false;
}

function response(datos)
{
    if(datos == 'good'){
        alert("Datos ingresados satisfactoriamente.")
        var from = document.referrer;
        window.location = from;
    //        window.history.back();
    }else{
        alert("Los datos no fueron guardados, intentelo de nuevo.")
        window.location.reload(true);
    }
//    $("#loader").css('display', 'none')
//    $("#manto").css('display', 'none')
}