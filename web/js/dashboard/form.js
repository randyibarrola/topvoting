var Form = function () {
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

    var optionsValidate = {
        focusInvalid: false,
        errorElement: 'span',
        errorClass: 'input-error tooltips',
        focusCleanup: true,
        ignore: ".ignore",
        submitHandler: function(form) {
            if($(form).hasClass('ajax-form'))
                initAjaxSubmit(form);
            else {
                App.blockUI($(form).parents('.portlet').eq(0));
                form.submit();
            }
            return false;
        },
        errorPlacement: function (error, element) { // render error placement for each input type
            if($(element).hasClass('validate-icon')) {
                $(element).removeClass('input-error tooltips');
                var text = $(error).text();
                $(error).attr('data-original-title', text).html($('<i class="icon-exclamation-sign"></i>'));
                $(element).parents('.controls').eq(0).append($(error));
            }
            else {
                $(element, error).removeClass('input-error tooltips');
                $(error).addClass('help-inline no-left-padding');
                $(element).parents('.controls').eq(0).append($(error));
            }

            _eventsError(error, element);
        },
        invalidHandler: function (event, validator) { //display error alert on form submit
            _showAlertForm(validator.currentForm, false, 'error');
            App.scrollTo($(validator.currentForm).find('.alert-validation').eq(0), -100);
        },
        highlight: function (element) { // hightlight error inputs
            _highlight(element);
        },
        unhighlight: function (element) { // revert the change dony by hightlight
            _unhighlight(element);
        }
    };

    var initValidateForm = function(form) {
        var selector = form ? form : $('.form-validate');
        Form.defaultFormErrorText = selector.find('.alert-validation .alert-message').eq(0).text();
        Form.validator = selector.validate(optionsValidate);
    };

    var _errorToolTips = function(error) {
        if($(error).hasClass('tooltips'))
            $(error).off().tooltip();
    };

    var _eventsError = function(error, element) {
        _errorToolTips(error);
        $(element).unbind('focus').focus(function() {
            _unhighlight(element);
            $(error).remove();
            var alert = $(element).parents('form').eq(0).find('.alert-validation').eq(0);
            if(!alert.hasClass('hide'))
                alert.addClass('hide');
        });
    };

    var _showAlertForm = function(form, message, type) {
        var alert = $(form).find('.alert-validation').eq(0)
            .removeClass('alert-success')
            .removeClass('alert-warning')
            .removeClass('alert-info')
            .removeClass('alert-error')
            .addClass('alert-' + type);
        if(message != false && message != null && message.trim().length > 0)
            alert.find('.alert-message').html(message);

        alert.removeClass('hide');

        window.setTimeout(function() {
            if(!alert.hasClass('hide'))
                alert.addClass('hide').find('.alert-message').eq(0).html(Form.defaultFormErrorText);
        }, 5000);
    };

    var _highlight = function(element) {
        $(element).siblings('span.input-error').remove();
        $(element).parents('.control-group').eq(0).addClass('error');
        if($(element).hasClass('validate-icon'))
            $(element).parents('.controls').eq(0).addClass('input-icon')
    };

    var _unhighlight = function(element) {
        $(element).parents('.control-group').eq(0).removeClass('error');
        $(element).parents('.controls').eq(0).removeClass('input-icon')
    };

    var initAjaxForm = function(form, submit) {
        var options = {
            clearForm: $(form).hasClass('form_new') ? true : false,
            resetForm: $(form).hasClass('form_new') ? true : false,
            dataType: 'json',
            iframe: form && $(form).attr('enctype') && $(form).attr('enctype') == 'multipart/form-data'  ? true : false,
            type: form ? $(form).attr('method') : 'POST',
            beforeSubmit: function(arr, $form, options) {
                App.blockUI($(form).parents('.portlet').eq(0));
            },
            error: function(context, xhr, e, status) {
                App.unblockUI($(form).parent('.portlet').eq(0));
            },
            success: function(data, status, xhr, $form) {
                if(data.http_code == 500) {
                    if(data.errors_form) {
                        for(var i in data.errors_form.widget_errors) {
                            var element = $(form).find('input[name="'+i+'"]').eq(0);
                            var error = $('<span></span>');
                            if($(element).hasClass('validate-icon')) {
                                $(element).removeClass('input-error tooltips');
                                error.addClass('input-error tooltips').attr('data-original-title', data.errors_form.widget_errors[i][0]).append($('<i class="icon-exclamation-sign"></i>'));
                            }
                            else {
                                error.addClass('help-inline no-left-padding').html(data.errors_form.widget_errors[0]);
                            }

                            _highlight(element);
                            $(element).parents('.controls').eq(0).append(error);
                            _eventsError(error, element);
                        }

                        if(data.errors_form.global_errors && data.errors_form.global_errors.length > 0) {
                            data.message = data.errors_form.global_errors[0];
                        }
                    }
                    _showAlertForm(form, data.message, data.message_type);
                    App.scrollTo($(form).find('.alert-validation').eq(0), -100);
                }
                else {
                    _showAlertForm(form, data.message, data.message_type);
                    App.scrollTo($(form).find('.alert-validation').eq(0), -100);
                }

                App.unblockUI($(form).parents('.portlet').eq(0));
            }
        };

        if(submit) {
            if($(form).valid())
                $(form).ajaxSubmit(options);
        }
        else
            $(form).ajaxForm(options);
    };

    var initAjaxSubmit = function(form) {
        if(form) {
            initAjaxForm(form, true);
        }
        else {
            $('.ajax-form').each(function() {
                var iform = $(this);
                initAjaxForm(iform, false);
            });
        }
    };

    return {
        validator: {},
        //main function to initiate the module
        init: function () {
            $('.select2').select2({
                allowClear: true
            });

            chooseLanguage();

            if($.validator)
                initValidateForm();
            else
                initAjaxSubmit();
        },
        defaultFormErrorText: ""
    };

}();