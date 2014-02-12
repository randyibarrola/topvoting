var View = function () {
    var chooseLanguage = function() {
        $('.select_translate').off('change').change(function(){
            var tg = $(this);
            var dfor = tg.attr('data-for');

            if(dfor && dfor.length > 0) {
                var split = dfor.split(',');
                for(var s in split) {
                    $('.'+ split[s].trim()).hide();
                    $('.'+ split[s].trim()+'[data-locale='+tg.val()+']').show();
                }
            }
        });
    };

    var initView = function() {
        $('.view-container').each(function () {
            var view = $(this);
            var temp_holder = [];

            var dialog_delete = $('.confirm_delete[data-for=' + view.attr('id') + ']');
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
                                    defaultDeleteAction(btn);
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

            var btnDeleteAction = function() {
                var btns = view.find('.view-btn-delete-object');
                btns.each(function() {
                    var btn = $(this);
                    if(!btn.attr('onClick')) {
                        btn.unbind('click').click(function() {
                            if(dialog_delete) {
                                temp_holder['delete_btn'] = btn;
                                dialog_delete.dialog("open");
                            }
                            else {
                                if($(btn).hasClass('ajax-btn'))
                                    defaultDeleteAction(btn);
                                else
                                    window.location.href = getBtnHref(btn);
                            }

                            return false;
                        });
                    }
                });
            };

            var defaultDeleteAction = function(btn) {
                if(btn != null) {
                    App.blockUI(btn.parents('.portlet').eq(0));
                    $.ajax({
                        type: "POST",
                        url: btn.attr('href'),
                        dataType: "json",
                        success: function(result) {
                            var unique_id = $.gritter.add({
                                position: 'top-right',
                                title: '',
                                text: result.message,
                                time: 3000,
                                class_name: 'gritter-' + result.message_type
                            });
                            App.scrollTo($('body'), -100);
                            if(result.http_code == 200)
                            {
                                view.remove();
                                window.location.href = btn.attr('data-redirect') ? btn.attr('data-redirect') : result.data_holder.route;
                            }
                            else {
                                App.unblockUI(btn.parents('.portlet').eq(0));
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            App.unblockUI(btn.parents('.portlet').eq(0));
                        }
                    });
                }
            };

            btnDeleteAction();
        });
    };

    return {
        init: function () {
            if(jQuery().select2)
                $('.select2').select2({
                    allowClear: true
                });

            chooseLanguage();
            initView();
        }
    };

}();
