$(document).ready(function() {
    
    $('.cambiar_pestana').click(function(){
        $('.cambiar_pestana').each(function(){
            $('.cambiar_pestana').removeClass('pest_activa');
        });
        
        $(this).addClass('pest_activa');
        $('.barra_pest').css('background', 'url(/images/'+ $(this).data('img') +') no-repeat');
    });
    
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
    
    $('#btn_reg').click(function(e) {
        e.preventDefault();
        
        if($('#formularioRegistro').validationEngine('validate') === true){
           
            $('#formularioRegistro').submit();
                
        }
        
    }); 
    
    $('#btn_entrar').click(function(e) {
        e.preventDefault();
        
        if($('#formularioLogin').validationEngine('validate') === true){
           
            $('#formularioLogin').submit();
                
        }
        
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

