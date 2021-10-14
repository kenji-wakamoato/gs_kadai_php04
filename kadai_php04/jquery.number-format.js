/*!
 jquery.number-format.js

 This software is released under the MIT License
 http://opensource.org/licenses/mit-license.php
 */
 (function ($) {

    $.fn.numberformat = function (options) {

        // option
        var setting = $.extend({
            align: 'right',
            separator: ','
        }, options);

        // make the string formatted on changed
        this.change(function () {
            var val = $(this).val().toString()
                    .replace(setting.separator, '');
            var formattedVal = val.replace(/(\d)(?=(?:\d{3}){2,}(?:\.|$))|(\d)(\d{3}(?:\.\d*)?$)/g
                    , '$1$2' + setting.separator + '$3');
            $(this).val(formattedVal)
                    .css({
                        textAlign: setting.align
                    })
                    .attr('data-value', val);
        }).change();

        // Move caret to end of string on focused
        this.focus(function () {
            var val = $(this).val();
            $(this).val('').val(val);
        });

        // Remove comma on submit
        this.parents('form').submit(function () {
            var input = $(this).find('input');
            input.each(function () {
                var val = $(this).val().replace(setting.separator, '');
                $(this).val(val);
            });
        });

        return this;
    };
})(jQuery);