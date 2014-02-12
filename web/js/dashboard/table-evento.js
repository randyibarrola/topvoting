var TableEvento = function() {
    var initTable = function() {
        $('#data-table-evento').dataTable().columnFilter({
            sPlaceHolder: "head:after",
            aoColumns: [
                {type: "text"}, {type: "text"}, {type: "text"}, {type: "number"}, {type: "date"}, {type: "select", values: list_traducciones}
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