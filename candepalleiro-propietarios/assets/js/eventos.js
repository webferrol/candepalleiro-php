jQuery(document).ready(function ($) {
    //$(".propietarios_page_nuevo-propietario #publish").on("click", function(){   

    $(".toplevel_page_propietarios #nif, .propietarios_page_nuevo-propietario #nif").on("blur", function(){
        var value = $("#nif").val();
        var tipoDocumento = $("#tipo_documento").val();
        
        jQuery.ajax({
            type: "post",
            url: ajax_var.url,
            data: {'action': ajax_var.action, 'nonce': ajax_var.nonce, 'tipo': tipoDocumento, 'value': value},
            success: function(result){
                if(!result['status']) {
                    alert(result['message']);
                }
            }
        });
    });
});