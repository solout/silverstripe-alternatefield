if (typeof (so) === 'undefined') {
    var so = {};
}

so.alternatefield = {
    init: function () {
        this.alternatefieldsetup();
    },
    alternatefieldsetup: function () {

        $('select.selected-field').each(function () {
            $(this).on('change', function () {
                so.alternatefield.showHideAlternateValue(this);
            });
            so.alternatefield.showHideAlternateValue(this);
        });
        $('.selected-field input').each(function () {
            $(this).on('change', function () {
                so.alternatefield.showHideAlternateValueCheckbox(this);
            });
            so.alternatefield.showHideAlternateValueCheckbox(this);
        });

    },
    showHideAlternateValue: function (el) {
        var alternatename = $(el).data('for');
        // console.log($(el).val());
        var alternate = $('[data-fieldid=' + alternatename + ']');
        var alternatediv = $(alternate).closest('.AlternateFormFieldAlternateValue');

        var val = $(el).val();
        if (val == '') {
            $('[data-for=' + alternatename + ']' + ' :checked').each(function () {
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

    },
    showHideAlternateValueCheckbox: function (el) {
        var alternate = $('[data-fieldid=' + alternatename + ']');

        var alternatename = $(el).data('for');
        var alternate = $(el).parents('.AlternateFormField').find('.AlternateFormFieldAlternateValue input');
        var alternatediv = $(alternate).closest('.AlternateFormFieldAlternateValue');
        var val = false;

        if ($(el).is(':checked')) {
            var val = $(el).val();
        }

        if (val == '') {
            $('[data-for=' + alternatename + ']' + ' :checked').each(function () {
                val += $(this).val();
            });
        }

        if (val && val === "Other" && $(el).is(':checked')) {
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


