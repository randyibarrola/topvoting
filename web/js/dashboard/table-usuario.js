var TableUsuario = function() {
    var initTable = function() {
        $('#data-table-usuario').dataTable().columnFilter({
            sPlaceHolder: "head:after",
            aoColumns: [
                {type: "text"}, {type: "text"}, {type: "text"}, {type: "text"}, {type: "text"}
            ]
        });


        $('.active-toggle-button').toggleButtons({
            width: 50,
            height: 20,
            label: {
                enabled: "<i class='icon-ok-circle'></i>",
                disabled: "<i class='icon-remove-circle'></i>"
            },
            style: {
                enabled: "success",
                disabled: "warning"
            },
            transitionspeed: 0.1
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
