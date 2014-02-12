var TableAdvanced = function () {

    var initTable = function() {
        $.extend( true, jQuery().dataTable.defaults, {
            "aaSorting": [[0, 'asc']],
            "aLengthMenu": [5, 10, 20, 50],
            "iDisplayLength": 10,
            "oLanguage": {
                "sLengthMenu": "_MENU_"
            },
            "sDom": "<'row-fluid'<'span5'T><'span5'f><'span2'l>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "bSortCellsTop": true,
            "fnCreatedRow": null,
            "fnDrawCallback": null
        } );

        if(jQuery().dataTable.TableTools) {
            $.extend( true, jQuery().dataTable.TableTools.DEFAULTS, {
                "sSwfPath": SWF_DT_TOOLS,
                "oTags": {
                    "collection": {
                        "container": "ul",
                        "button": "li",
                        "liner": "a"
                    }
                }
            } );

            jQuery().dataTable.TableTools.DEFAULTS.aButtons = [
                {
                    "sExtends":    "print",
                    "sButtonText": "<span class='icon-print' />",
                    "mColumns": "visible"
                },
                {
                    "sExtends":    "xls",
                    "sButtonText": "<span class='icon-download' /> Excel",
                    "mColumns": "visible",
                    "bFooter": false,
                    "fnCellRender": function ( mTypeData, i, tr, aDataIndex ) {
                        return getSaveDataCell(mTypeData);
                    },
                    "sTitle": typeof _nameSaveList == "undefined" ? "" : _nameSaveList
                }
            ];

            $.extend( true, $.fn.DataTable.TableTools.classes, {
                "container": "btn-group",
                "buttons": {
                    "normal": "btn",
                    "disabled": "btn disabled"
                },
                "collection": {
                    "container": "DTTT_dropdown dropdown-menu",
                    "buttons": {
                        "normal": "",
                        "disabled": "disabled"
                    }
                }
            } );
        }

        $('.table-advanced').each(function () {
            var tSettings = null;
            var table = $(this);
            var temp_holder = [];
            var indexThAction = table.find('th.table-actions-cell').eq(0).parents('tr').eq(0).find('th').index(table.find('th.table-actions-cell'));
            if(indexThAction == -1)
                indexThAction = false;

            table.find('.table-row .btn, .table-row .active-toggle-button').tooltip();

            var dialog_delete = $('.confirm_delete[data-for=' + table.attr('id') + ']');
            if(dialog_delete.length > 0) {
                dialog_delete = dialog_delete.first();
                dialog_delete.dialog({
                    dialogClass: 'ui-dialog-red',
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    buttons: [
                        {
                            'class' : 'btn red',
                            "text" : "Eliminar",
                            click: function() {
                                var btn = temp_holder['delete_btn'];
                                if($(btn).hasClass('ajax-btn'))
                                    defaultDeleteAction(temp_holder['delete_nrow'], btn);
                                else {
                                    window.location.href = getBtnHref(btn);
                                }

                                $(this).dialog( "close" );
                            }
                        },
                        {
                            'class' : 'btn',
                            "text" : "Cancelar",
                            click: function() {
                                $(this).dialog( "close" );
                            }
                        }
                    ],
                    beforeClose: function() {
                        temp_holder['delete_nrow'] = null;
                        temp_holder['delete_btn'] = null;
                    }
                });
            }
            else {
                dialog_delete = false;
            }

            var getBtnHref = function(btn) {
                var href = window.location;

                if($(btn.attr('href')))
                    href = $(btn).attr('href');
                else if($(btn).attr('data-href'))
                    href = $(btn).attr('data-href');

                return href;
            };

            var btnDeleteRowAction = function(nRow) {
                var btn = $(nRow).find('.table-btn-delete-row').eq(0);
                if(!btn.attr('onClick')) {
                    btn.unbind('click').click(function() {
                        if(dialog_delete) {
                            temp_holder['delete_nrow'] = nRow;
                            temp_holder['delete_btn'] = btn;
                            dialog_delete.dialog("open");
                        }
                        else {
                            if($(btn).hasClass('ajax-btn'))
                                defaultDeleteAction(nRow, btn);
                            else
                                window.location.href = getBtnHref(btn);
                        }

                        return false;
                    });
                }
            };

            var defaultDeleteAction = function(nRow, btn) {
                if(nRow != null && btn != null) {
                    $(nRow).hide('fast');
                    $.ajax({
                        type: "POST",
                        url: btn.attr('href'),
                        dataType: "json",
                        success: function(result) {
                            if(result.http_code == 200)
                            {
                                oTable.fnDeleteRow(nRow);
                            }
                            else {
                                $(nRow).show('fast');
                            }
                            $.gritter.add({
                                position: 'top-right',
                                title: '',
                                text: result.message,
                                time: 3000,
                                class_name: 'gritter-' + result.message_type
                            });
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $(nRow).show('fast');
                        }
                    });
                }
            };

            var btnActiveRowAction = function(nRow) {
                var btn = $(nRow).find('.active-toggle-button').eq(0);
                if(!btn.attr('onClick')) {
                    if(jQuery().toggleButtons) {
                        var error = false;
                        btn.click(function() {
                            return false;
                        });
                        $(btn).toggleButtons({
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
                            transitionspeed: 0.1,
                            onChange: function() {
                                if(!error) {
                                    var input = btn.find('input:checkbox:eq(0)');
                                    $.ajax({
                                        type: "POST",
                                        url: btn.attr('href'),
                                        dataType: "json",
                                        success: function(result) {
                                            if(result.http_code == 500)
                                            {
                                                error = true;
                                                btn.find('.labelLeft:eq(0)').trigger('click');
                                            }

                                            $.gritter.add({
                                                position: 'top-right',
                                                title: '',
                                                text: result.message,
                                                time: 3000,
                                                class_name: 'gritter-' + result.message_type
                                            });
                                        },
                                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                                            error = true;
                                            btn.find('.labelLeft:eq(0)').trigger('click');
                                        }
                                    });
                                }
                                else {
                                    error = false;
                                }
                            }
                        });
                    }
                }
            };

            var oTable = table.dataTable({
                "aoColumnDefs": indexThAction ? [{ "aTargets": [ 0 ] }, {"bSortable": false, "aTargets": [ indexThAction ] }] : [{ "aTargets": [ 0 ] }],
                "fnDrawCallback": function(oSettings) {
                    tSettings = oSettings;
                    if($.fnDTSpecialDrawCallback)
                        $.fnDTSpecialDrawCallback(oSettings);
                },
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    btnDeleteRowAction(nRow);
                    btnActiveRowAction(nRow);
                    if($.fnDTSpecialCreatedRow)
                        $.fnDTSpecialCreatedRow(nRow, aData, iDataIndex);
                }
            });

            jQuery('.dataTables_filter input').addClass("m-wrap small"); // modify table search input
            jQuery('.dataTables_length select').addClass("m-wrap small").select2(); // modify table per page dropdown and initialzie select2 dropdown

            table.parents('.portlet').eq(0).find('.table-column-toggler:eq(0) input[type="checkbox"]').change(function(){
                /* Get the DataTables object again - this is not a recreation, just a get of the object */
                var iCol = parseInt($(this).attr("data-column"));
                var bVis = oTable.fnSettings().aoColumns[iCol].bVisible;
                oTable.fnSetColumnVis(iCol, (bVis ? false : true));

                var element = $(this).parents('.checker').length > 0 ? $(this).parents('span').eq(0) : $(this);

                if(element.hasClass('checked'))
                    element.removeClass('checked');
                else
                    element.addClass('checked');
            });
        });
    };

    var getSaveDataCell = function(mTypeData) {
        var sLoopData = mTypeData;
        if(mTypeData.indexOf('hidden-print') != -1) {
            var data = $('<div>' + mTypeData + '</div>');
            data.find('.hidden-print').remove();
            sLoopData = data.text();
        }

        if( typeof sLoopData == "string" )
        {
            /* Strip newlines, replace img tags with alt attr. and finally strip html... */
            sLoopData = sLoopData.replace(/\n/g," ");
            sLoopData =
                sLoopData.replace(/<img.*?\s+alt\s*=\s*(?:"([^"]+)"|'([^']+)'|([^\s>]+)).*?>/gi,
                    '$1$2$3');
            sLoopData = sLoopData.replace( /<.*?>/g, "" );
        }
        else
        {
            sLoopData = sLoopData+"";
        }

        return sLoopData;
    };

    return {

        //main function to initiate the module
        init: function () {
            if (!jQuery().dataTable) {
                return;
            }

            initTable();
        }

    };

}();
