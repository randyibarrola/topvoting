if(typeof $.fn.dataTable != "undefined") {
    $.extend( true, $.fn.dataTable.defaults, {
        "oLanguage": {
            "oAria": {
                "sSortAscending": ": activate to sort column ascending",
                "sSortDescending": ": activate to sort column descending"
            },
            "oPaginate": {
                "sFirst": "First",
                "sLast": "Last",
                "sNext": "Next",
                "sPrevious": "Previous"
            },
            "sEmptyTable": "No data available in table",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
            "sInfoEmpty": "Showing 0 to 0 of 0 entries",
            "sInfoFiltered": "(filtered from _MAX_ total entries)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Show _MENU_ entries",
            "sLoadingRecords": "Loading...",
            "sProcessing": "Processing...",
            "sSearch": "Search:",
            "sUrl": "",
            "sZeroRecords": "No matching records found"
        }
    } );
}

if(typeof $.fn.DataTable.TableTools != "undefined") {
    $.extend( true, $.fn.DataTable.TableTools.BUTTONS, {
        "print": {
            "sButtonText": 'Print',
            "sInfo": "<h6>Print view</h6><p>Please use your browser's print function to print this table. Press escape when finished.",
            "sToolTip": "View print view"
        },
        "xls": {
            "sButtonText": 'Excel',
            "sToolTip": "Save for excel"
        }
    });
}
