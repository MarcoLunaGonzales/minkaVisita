function sendData()
{
    var territorio = $("#territorio").val();
    
    $.getJSON("ajax/reportecategorizacion/send.php",{
        "territorio": territorio
    },response);

    return false;
}

function response(datos){
    alert (datos)
}

function gruposEspeciales(){
    var linea = $("#linea").val();
    var territorio = $("#territorio").val();
    
    $.getJSON("ajax/gruposespeciales/getgrupos.php",{
        "linea" : linea,
        "territorio": territorio
    }, responsegruposEspeciales);
    
    return false
}
function responsegruposEspeciales(datos){
    $("#gruposespeciales").html(datos)
}




function datosMedico(){
    var territorio = $("#territorio").val();
    var gestion = $("#gestion").val();
    var linea = $("#linea").val();
    var especialidad = $("#especialidad").val();
    var categoria = $("#categoria").val();
    $.getJSON("ajax/reportecategorizacion/getmedico.php",{
        "territorio": territorio,
        "gestion": gestion,
        "linea": linea,
        "especialidad": especialidad,
        "categoria": categoria
    },responseMedico)
    
//    var dataString = 'territorio='+ territorio + '&gestion=' + gestion + '&linea=' + linea  + '&especialidad=' + especialidad  + '&categoria=' + categoria ;
//    
//    $.ajax({
//        type: "POST",
//        url: "ajax/reportecategorizacion/getmedico.php",
//        data: dataString,
//        success: function(datos) {
//            alert(datos)
//        }
//    });
//    return false;
}


function responseMedico(datos){
//    alert(datos)
    $("#medicos").html(datos)
}


function datosVisitador(){
    var territorio = $("#territorio").val();
    $.getJSON("ajax/reportecategorizacion/getvisitador.php", {
        "territorio": territorio
    }, responsedatosVisitador)
}
function responsedatosVisitador(datos){
    $("#visitador").html(datos)
}


function datosVisitador2(){
    var territorio = $("#territorio").val();
    $.getJSON("ajax/reportecategorizacion/getvisitador2.php", {
        "territorio": territorio
    }, responsedatosVisitador2)
}
function responsedatosVisitador2(datos){
    $("#visitador").html(datos)
}