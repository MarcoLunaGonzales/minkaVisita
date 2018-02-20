
$(document).ready(function(){
   $('#tabla_listado_materiales').dataTable( {
        "sPaginationType": "full_numbers" ,
        "bStateSave": true,
        "iDisplayLength": 100,
        "aaSorting": [[ 5, "desc" ]]
    } );
})
