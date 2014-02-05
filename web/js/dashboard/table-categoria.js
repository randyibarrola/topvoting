var TableCategoria = function() {
    var initTable = function() {
        $('#data-table-categoria').dataTable({
            "fnDrawCallback": function(oSettings) {
            },
            "bRetrieve": true
        }).columnFilter({
            sPlaceHolder: "head:after",
            aoColumns: [
                {type: "text"}, {type: "text"}, {type: "text"}, {type: "text"}
            ]
        });
    };

    return {
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            initTable();
        }
    };
}();