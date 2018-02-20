function sendData(codigo){
    $.getJSON("ajax/adicionar_medico/eliminar.php",{
        "codigo_medico": codigo
    },response);

    return false;
}

function response(datos)
{
    if(datos.mensaje == true){
        alert("Datos eliminados satisfactoriamente")
        window.location.reload(true);
    }else{
        alert("Ocurrio un error en el proceso, Intentelo de nuevo")
        window.location.reload(true);
    }
}
