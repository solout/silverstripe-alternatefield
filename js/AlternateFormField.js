if (!so) {
    so = {};
}

so.alternatefield = {
    init: function () {
        this.alternatefieldsetup();
    },
    alternatefieldsetup: function () {

        $('.selected-field').each(function () {
            $(this).on('change', function() {
                so.alternatefield.showHideAlternateValue(this);
            });
            so.alternatefield.showHideAlternateValue(this);
        });

    },
    showHideAlternateValue: function (el) {
        var alternatename = $(el).data('for');
        // console.log($(el).val());
        var alternate = $('[data-fieldid='+alternatename+']');
        var alternatediv = $(alternate).closest('.AlternateFormFieldAlternateValue');

        var val = $(el).val();
        if (val == '') {
            $('[data-for='+alternatename+']'+' :checked').each(function () {
                val += $(this).val();
            });
        }

        if (val && val.indexOf('Other') !== -1) {
            $(alternate).show();
            $(alternatediv).show();
        } else {
            $(alternate).hide();
            $(alternatediv).hide();
        }

    }
};

$(document).ready(function () {
    so.alternatefield.init();
});


