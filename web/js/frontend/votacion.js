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
    
    $('.votar').click(function(e) {
        e.preventDefault();
        formulario = $('.formulario');
        url = $(this).data('url');
        $.ajax({
            type: "POST",
            url:  formulario.attr('action'),
            async: true,
            dataType: 'json',   
            data: formulario.serialize(),
            success: function( data ) {
                  if(data.resultado === 'ok'){
                     formulario.html('Su Voto se efectuo satisfactoriamente');               
                  }
              },
              error: function(error){

              }
          });        
    });      
    
});

