/*Muestras Medicas*/
function sendData(valor){
    
    $.getJSON("ajax/veriDevolucion/send.php",{
        "valores": valor
    },response);

    return false;
}

function response(datos)
{
    if(datos.mensaje == 'good'){
        window.location.href = "exportar_devoluciones_salidas.php?valores="+datos.valores;
    }else{
        var n = noty({
            text: "¿Desea continuar?, hay devoluciones que todavia no fueron aprobadas",
            type: "error",
            dismissQueue: true,
            layout: "center",
            theme: 'defaultTheme',
            modal:true,
            buttons: [
            {
                addClass: 'btn btn-primary', 
                text: 'Ok', 
                onClick: function($noty) {
                    $noty.close();
                    window.location.href = "exportar_devoluciones_salidas.php?valores="+datos.valores;
                }
            },
            {
                addClass: 'btn btn-danger', 
                text: 'Cancel', 
                onClick: function($noty) {
                    $noty.close();
                    alert("No se realizo el envio de los productos devueltos")
                }
            }
            ]
        });
    }
}

/*Material de Apoyo*/

function sendData2(valor){
    
    $.getJSON("ajax/veriDevolucion/send2.php",{
        "valores": valor
    },response2);

    return false;
}

function response2(datos)
{
    if(datos.mensaje == 'good'){
        window.location.href = "exportar_devoluciones_salidas_ma.php?valores="+datos.valores;
    }else{
        var n = noty({
            text: "¿Desea continuar?, hay devoluciones que todavia no fueron aprobadas",
            type: "error",
            dismissQueue: true,
            layout: "center",
            theme: 'defaultTheme',
            modal:true,
            buttons: [
            {
                addClass: 'btn btn-primary', 
                text: 'Ok', 
                onClick: function($noty) {
                    $noty.close();
                    window.location.href = "exportar_devoluciones_salidas_ma.php?valores="+datos.valores;
                }
            },
            {
                addClass: 'btn btn-danger', 
                text: 'Cancel', 
                onClick: function($noty) {
                    $noty.close();
                    alert("No se realizo el envio de los productos devueltos")
                }
            }
            ]
        });
    }
}