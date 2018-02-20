function sendData(cod)
{
    
    var cuantos = $("#cuantos").val();
    var nombres = [];
    var direcciones = [];
    for(var i =1; i <= cuantos; i++){
        nombres +=$("#name_farm_"+i).val() + ",";
        direcciones +=$("#dir_farm_"+i).val() + ",";
    }
    var sexo = $("#sexo").val();
    var edad = $("#edad").val();
    var paci = $("#paci").val();
    var prescriptiva = $("#prescriptiva").val();
    var nivel = $("#nivel").val();
    var consulta = $("#consulta").val();
    $.getJSON("ajax/categorizacion_medicos/send.php",{
        "nombres": nombres,
        "direcciones": direcciones,
        "sexo": sexo,
        "edad": edad,
        "paci": paci,
        "prescriptiva": prescriptiva,
        "nivel": nivel,
        "consulta": consulta,
        "cod_medico":  cod
    },response);

    return false;
}

function response(datos)
{
//        alert(datos)
    if(datos == 'good'){
        alert("Datos ingresados satisfactoriamente.")
        var from = document.referrer;
        window.location = from;
    //        window.history.back();
    }else{
        alert("Los datos no fueron guardados, intentelo de nuevo.")
        window.location.reload(true);
    }
    $("#loader").css('display', 'none')
    $("#manto").css('display', 'none')
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