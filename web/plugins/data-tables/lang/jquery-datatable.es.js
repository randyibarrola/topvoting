if(typeof $.fn.dataTable != "undefined") {
    $.extend( true, $.fn.dataTable.defaults, {
        "oLanguage": {
            "oAria": {
                "sSortAscending": ": activar para ordenar la columna ascendentemente",
                "sSortDescending": ": activar para ordenar la columna descendentemente"
            },
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sEmptyTable": "No existen datos disponibles en la tabla",
            "sInfo": "Mostrando del _START_ a _END_ de _TOTAL_ registros",
            "sInfoEmpty": "No hay registros para mostrar",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sLoadingRecords": "Cargando...",
            "sProcessing": "Procesando...",
            "sSearch": "Filtrar:",
            "sUrl": "",
            "sZeroRecords": "No se en contraton registros"
        }
    } );
}

if(typeof $.fn.DataTable.TableTools != "undefined") {
    $.extend( true, $.fn.DataTable.TableTools.BUTTONS, {
        "print": {
            "sButtonText": 'Imprimir',
            "sInfo": "<h6>Vista de impresión</h6><p>Por favor, use la función imprimir de su navegador para imprimir esta tabla. Presione escape cuando finalice.",
            "sToolTip": "Ver vista de impresión"
        },
        "xls": {
            "sButtonText": 'Excel',
            "sToolTip": "Salvar como documento excel"
        }
    });
}
