function sendDataa(cod)
{
	var _cod_final=cod;
	$.getJSON("ajax/ejecutar_distribucion/send2BK.php",{
		"ejecutar_distribucion":_cod_final
	},responsee);

	return false;
}

function responsee(datos)
{
	var algo = datos;
//	alert(algo)
//		alert('Las salidas se efectuaron correctamente.');
		location.href='registro_distribucion_lineasterritorios1.php?global_linea='+algo;
		$("#loaderr").attr("display", "none")
}

function sendGruposEspeciales(cod_ciclo,cod_gestion){
    $.getJSON("ajax/ejecutar_distribucion/grupos_especiales.php",{
        "cod_ciclo":cod_ciclo,
        "cod_gestion": cod_gestion
    }, responsesendGruposEspeciales);
    
    return false;
}

function responsesendGruposEspeciales(datos){
//    alert("Datos Ingresados satisfactoriamente")
    location.href = 'navegadorDistribucionGruposCiclos.php';
    $("#loaderr").attr("display", "none")
}
function sendBanco(cod_ciclo,cod_gestion){
    $.getJSON("ajax/ejecutar_distribucion/banco.php",{
        "cod_ciclo":cod_ciclo,
        "cod_gestion": cod_gestion
    }, responsesendBanco);
    
    return false;
}

function responsesendBanco(datos){
//    alert("Datos Ingresados satisfactoriamente")
//    alert("dsa")
    location.href = 'navegadorDistribucionGruposBanco.php';
    $("#loaderr").attr("display", "none")
    
}