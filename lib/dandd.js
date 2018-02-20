//$(document).ready(function(){
//    //Counter
//    counter = 0;
//    //Make element draggable
//    $(".drag").draggable({
//        helper:'clone',
//        containment: 'frame',
//        //When first dragged
//        stop:function(ev, ui) {
//            var pos=$(ui.helper).offset();
//            var acc = []
//            $.each(pos, function(index, value) {
//                acc.push(index + ': ' + value);
//            });
//            alert(JSON.stringify(acc));
//            objName = "#clonediv"+counter
//            $(objName).css({
//                "left":pos.left,
//                "top":(pos.top-200)
//            });
//            $(objName).removeClass("drag");
//
//
//            //When an existiung object is dragged
//            $(objName).draggable({
//                containment: 'parent',
//                stop:function(ev, ui) {
//                    var pos=$(ui.helper).offset();
//                    alert($(this).attr("id"))
//                    console.log($(this).attr("id"));
//                    console.log(pos.left)
//                    console.log(pos.top)
//                }
//            });
//        }
//    });
//    //Make element droppable
//    $("#frame").droppable({
//        drop: function(ev, ui) {
//            counter++;
//            var element=$(ui.draggable).clone();
//            element.addClass("tempclass");
//            $(this).append(element);
//            $(".tempclass").attr("id","clonediv"+counter);
//            $("#clonediv"+counter).removeClass("tempclass");
//
//            //Get the dynamically item id
//            draggedNumber = ui.helper.attr('id').search(/drag([UTI])/)
//            itemDragged = "dragged" + RegExp.$1
//            itemDragged = "dragged"
//            console.log(itemDragged)
//
//            $("#clonediv"+counter).addClass(itemDragged);
//        }
//    });
//Aqui realizamos la funcionalidad para cada ciudad y especialidad
//});

jQuery.fn.appendEach = function( arrayOfWrappers ){
 
    // Map the array of jQuery objects to an array of
    // raw DOM nodes.
    var rawArray = jQuery.map(
        arrayOfWrappers,
        function( value, index ){
 
            // Return the unwrapped version. This will return
            // the underlying DOM nodes contained within each
            // jQuery value.
            return( value.get() );
 
        }
        );
 
    // Add the raw DOM array to the current collection.
    this.append( rawArray );
 
    // Return this reference to maintain method chaining.
    return( this );
 
};
var grilla = function() {
    var ciudad = $("#frame .cajita .cajita_left input:checked").val()
    var especialidades = []
    $("#sticky_navigation input:checked").each(function(){
        especialidades.push($(this).val())
    })
    //    alert("ciudad: "+ciudad+" especialidades: "+especialidades)
    //    $("#ciudad_"+ciudad+" .cajita_right").append(especialidades)
    $.each(especialidades, function(idx, val) {
        $("#ciudad_"+ciudad+" .cajita_right").append('<span class="espe">'+val+' <img src="imagenes/no.png" alt="Cerrar" class="eliminar_espe" /> </span>');
        $(".eliminar_ciudad").click(function(){
            $(this).parent().parent().remove()
    })
    });
    $("#sticky_navigation input[type='checkbox']").attr('checked', false)  
    $(".eliminar_espe").click(function(){
        $(this).parent().remove()
    })
    $(".eliminar_ciudad").click(function(){
        $(this).parent().parent().remove()
    })
}
var armarGrilla = function(cadena) {
    window.location.href = "plan_de_lineas2.php?cadena="+cadena;
}
var armarGrilla2 = function(cadena,id) {
    window.location.href = "plan_de_lineas2_editar.php?cadena="+cadena+"&id_cab="+id;
}