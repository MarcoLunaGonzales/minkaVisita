
$(document).ready(function(){
	verlistado()
})
function verlistado(){
	$("body").on({
		ajaxStart: function() { 
			$(this).addClass("loading"); 
		},
		ajaxStop: function() { 
			$(this).removeClass("loading"); 
		}    
	});
	var randomnumber=Math.random()*11;
	$.post("lib/carga_medicos_adicionar.php", {
		randomnumber:randomnumber
	}, function(data){
		$("#contenido").html(data);
	});



}