function sendData(cod){
    var _cod_final=cod;
	
    // $.getJSON("ajax/navegador_lineas_distribucion/send.php",{
    $.getJSON("ajax/navegador_lineas_distribucion/send-depuracionBK.php",{
        "global_linea_distribucion":_cod_final
    },response);

    return false;
    //	alert("jorge"+_cod_final)
}

function response(datos) {
    location.href='registro_distribucion_lineasterritorios1.php?global_linea='+datos;
    $("#loaderr").attr("display", "none")
}
function response2(datos) {
    location.href='registro_distribucion_lineasterritorios11.php?global_linea='+1031;
    $("#loaderr").attr("display", "none")
}