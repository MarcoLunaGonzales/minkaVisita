function sendData(cod) {
    
    var cuantos = $("#cuantos").val();
    var numero = $("#numero_visita").val();
    var nombres = [];
    var direcciones = [];
    var linea = $("#linea").val();
    var ciclo1 = $("#ciclo1").val();
    var ciclo2 = $("#ciclo2").val();
    for(var i =1; i <= cuantos; i++){
        nombres +=$("#name_farm_"+i).val() + ",";
        direcciones +=$("#dir_farm_"+i).val() + ",";
    }
    $.getJSON("ajax/banco_muestras/send.php",{
        "nombres": nombres,
        "direcciones": direcciones,
        "cod_medico":  cod,
        "linea": linea,
        "numero":numero,
        "ciclo1":ciclo1,
        "ciclo2":ciclo2
    },response);

    return false;
}

function sendData2(cod) {
    
    var cuantos = $("#cuantos").val();
    var numero = $("#numero_visita").val();
    var nombres = [];
    var direcciones = [];
    var linea = $("#linea").val();
    var ciclo1 = $("#ciclo1").val();
    var ciclo2 = $("#ciclo2").val();
    for(var i =1; i <= cuantos; i++){
        nombres +=$("#name_farm_"+i).val() + ",";
        direcciones +=$("#dir_farm_"+i).val() + ",";
    }
    $.getJSON("ajax/banco_muestras/editar.php",{
        "nombres": nombres,
        "direcciones": direcciones,
        "cod_medico":  cod,
        "linea": linea,
        "numero":numero,
        "ciclo1":ciclo1,
        "ciclo2":ciclo2
    },response2);

    return false;
}
function response(datos) {
    if(datos == 'good'){
        alert("Datos ingresados satisfactoriamente.")
        // var from = document.referrer;
        // window.location = from;
        // window.history.back();
        alert("Recuerde realizar el calculo del medico selecionado por visitador. Usted esta siendo redireccionado a la ventana para realizar el calculo.")
        window.location.href = "calculo_banco_muestras.php";
    }else{
        alert("Los datos no fueron guardados, intentelo de nuevo.")
        window.location.reload(true);
    }
    $("#loader").css('display', 'none')
    $("#manto").css('display', 'none')
}

function response2(datos) {
    if(datos.mensaje == 'good'){
        alert("Datos ingresados satisfactoriamente.")
        // var from = document.referrer;
        // window.location = from;
        // window.history.back();
        alert("Recuerde realizar el calculo del medico selecionado por visitador. Usted esta siendo redireccionado a la ventana para realizar el calculo.")
        window.location.href='envios_mails/cambio_bm.php?medico='+datos.medico+'&visitador='+datos.visitador;
    }else{
        alert("Los datos no fueron guardados, intentelo de nuevo.")
        window.location.reload(true);
    }
    $("#loader").css('display', 'none')
    $("#manto").css('display', 'none')
}
