(function($){
    $(document).ready(function() {
        $('#contenido').children().each(function() {
            $(this).freetile({
                animate: true,
                elementDelay: 0
            });
        });
    });
})(jQuery)
				
