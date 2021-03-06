var TableCategoria = function() {
    var initTable = function() {
        $('#data-table-categoria').dataTable().columnFilter({
            sPlaceHolder: "head:after",
            aoColumns: [
                {type: "text"}, {type: "text"}, {type: "select", values: list_padre}, {type: "select", values: list_traducciones}
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