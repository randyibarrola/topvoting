$(document).ready(function() {
    
    $('.cambiar_pestana').click(function(){
        $('.cambiar_pestana').each(function(){
            $('.cambiar_pestana').removeClass('pest_activa');
        });
        
        $(this).addClass('pest_activa');
        $('.barra_pest').css('background', 'url('+ $(this).data('img') +') no-repeat');

        fin_cajas = false;
        pagina_cajas = 0;
        load_cajas = false;
        $('#cuerpo_cont').find('.cajas').eq(0).empty();
        cargarCajasIndex();
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

    cargarCajasIndex();
});

$(window).scroll(function(){
    if ($(window).scrollTop() + 190 >= $(document).height() - $(window).height() && !fin_cajas && !load_cajas){
        cargarCajasIndex();
    }
});

function cargarCajasIndex() {
    var contenedor_pestannas = $('#pestannas');
    if(!load_cajas && !fin_cajas && contenedor_pestannas.children('.cambiar_pestana').length > 0) {
        var contenedor = $('#cuerpo_cont');
        contenedor.find('#load_cajas').show();

        var pestanna = contenedor_pestannas.children('.pest_activa').eq(0);

        pagina_cajas++;
        load_cajas = true;

        $.ajax({
            type: "POST",
            url:  url_cajas,
            data: 'contenido='+pestanna.attr('data-content')+'&pagina='+pagina_cajas,
            success: function( data ) {
                var cajas = contenedor.children('.cajas').eq(0).find('.caja').length;
                contenedor.find('#load_cajas').hide();
                contenedor.children('.cajas').eq(0).append(data);
                load_cajas = false;
                if(cajas + 8 > contenedor.children('.cajas').eq(0).find('.caja').length)
                    fin_cajas = true;
            },
            error: function(error){
                contenedor.find('#load_cajas').hide();
                load_cajas = false;
                pagina_cajas--;
            }
        });
    }
}

