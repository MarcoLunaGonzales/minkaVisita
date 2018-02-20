
$(document).ready(function(){
    verlistado()
})
function verlistado(){
    var randomnumber=Math.random()*11;
    $.post("lib/carga_medicos2.php", {
        randomnumber:randomnumber
    }, function(data){
        $("#contenido").html(data);
    });



}