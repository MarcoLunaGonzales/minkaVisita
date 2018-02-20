/*Muestras Medicas*/
function sendData(valor,gestion){
    
    $.getJSON("ajax/cerrarLinea/send.php",{
        "ciclo": valor,
        "gestion":gestion
    },response);

    return false;
}

function response(datos)
{
    alert(datos)    
    window.location.href = "navegador_lineas_visita_ciclo.php";
}

