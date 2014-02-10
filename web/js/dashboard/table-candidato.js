var TableCandidato = function() {
    var initTable = function() {
        $('#data-table-candidato').dataTable().columnFilter({
            sPlaceHolder: "head:after",
            aoColumns: [
                {type: "text"}, {type: "text"}, {type: "select", values: list_categoria}, {type: "select", values: list_traducciones}
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