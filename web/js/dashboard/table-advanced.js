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
            var indexThAction = table.find('th.table-actions-cell').eq(0).parents('tr').eq(0).find('th').index(table.find('th.table-actions-cell'));
            if(indexThAction == -1)
                indexThAction = false;

            var btnDeleteRowAction = function(nRow) {
                var btn = $(nRow).find('.table-btn-delete-row').eq(0);
                if(!btn.attr('onClick')) {
                    btn.off().click(function() {
                        $(nRow).hide();
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
                                    $(nRow).show();
                                }
//                            AppConsole.show(resultado.message, resultado.message_type, 5000);
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {
                                $(nRow).show();
                            }
                        });

                        return false;
                    });
                }
            };

            var oTable = table.dataTable({
                "aoColumnDefs": indexThAction ? [{ "aTargets": [ 0 ] }, {"bSortable": false, "aTargets": [ indexThAction ] }] : [{ "aTargets": [ 0 ] }],
                "fnDrawCallback": function(oSettings) {
                    tSettings = oSettings;
                },
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    btnDeleteRowAction(nRow);
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
