
$(document).ready(function(){
    $("body").addClass("loading"); 
    verlistado()
})
function verlistado(){
    var randomnumber=Math.random()*11;
    $.post("lib/carga_demanda_mm.php", {
        randomnumber:randomnumber
    }, function(data){
        $("#contenido").html(data);
        $("body").removeClass("loading"); 
    });



}