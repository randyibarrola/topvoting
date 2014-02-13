$(document).ready(function() {
    
    $('.cambiar_estado').click(function() {
        estado = $('#estado').data('estado');
        url = $(this).data('url');
        $.ajax({
            type: "GET",
            url:  url,
            async: true,
            dataType: 'json',          
            success: function( data ) {
                  if(data.resultado === 'ok'){
                      $('#estado').data('estado', data.estado);
                      $('#estado').text(data.texto);
                  }
              },
              error: function(error){

              }
          });        
    });     
    
});



    $('#clearTitulo').click(function() {
        $('#buscarcliente').hide();  
        $(this).css('display', 'none');
        $('#buscador').val('');
    });    
function tiempo() {
       $.ajax({
      type: "GET",
      url:  urlTiempo,
      async: true,
      dataType: 'json',          
      success: function( data ) {
            redirigir();
        },
        error: function(error){

        }
    }); 
}