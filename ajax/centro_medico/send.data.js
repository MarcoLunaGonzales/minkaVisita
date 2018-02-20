function guardar(){
    var nombre = $("#name").val();
    var direccion = $("#direccion").val();
    var ciudad = $("#ciudad").val();
    $.getJSON("ajax/centro_medico/guarda.php",{
        "nombre": nombre,
        "direccion": direccion,
        "ciudad":  ciudad
    },responseGuardar);
    
    return false;
}
function responseGuardar(datos){
    if(datos == 'good'){
        alert("Datos ingresados satisfactoriamente")
        var from = document.referrer;
        window.location = from;
    }else{
        alert("Error en el proceso intentelo de nuevo")
        window.location.reload(true);
    }
}

function eliminar(codd){
    $.getJSON("ajax/centro_medico/elimina.php",{
        "codigo": codd
    },responseEliminar);
    
    return false;
}
function responseEliminar(datos){
    if(datos == 'good'){
        alert("Datos Eliminados satisfactoriamente")
        window.location.reload(true);
    }else{
        alert("Error en el proceso intentelo de nuevo")
        window.location.reload(true);
    }
}

function editar(codigo){
    var nombre = $("#name").val();
    var direccion = $("#direccion").val();
    var ciudad = $("#ciudad").val();
    $.getJSON("ajax/centro_medico/update.php",{
        "codigo": codigo,
        "nombre": nombre,
        "direccion": direccion,
        "ciudad":  ciudad
    },responseEditar);
    
    return false;
}
function responseEditar(datos){
    if(datos == 'good'){
        alert("Datos ingresados satisfactoriamente")
        var from = document.referrer;
        window.location = from;
    }else{
        alert("Error en el proceso intentelo de nuevo")
        window.location.reload(true);
    }
}