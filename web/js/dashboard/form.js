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

    return {
        //main function to initiate the module
        init: function () {
            $('.select2').select2({
                allowClear: true
            });

            chooseLanguage();
        }
    };

}();
