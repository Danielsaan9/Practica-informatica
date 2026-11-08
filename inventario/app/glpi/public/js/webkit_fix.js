if (navigator.userAgent.indexOf('AppleWebKit') !== -1 && navigator.vendor.indexOf('Apple') !== -1) {
    // Workaround for select2 dropdownAutowidth not applying until the second time the dropdown is opened
    // See: https://github.com/glpi-project/glpi/issues/13433 and https://github.com/select2/select2/issues/4678
    const original_select2_fn = $.fn.select2;
    $.fn.select2 = function (options) {
        const result = original_select2_fn.apply(this, arguments);
        if (typeof options === 'object') {
            // open and close the dropdown after initialization
            result.on('select2:open', function () {
                const el = $(this);
                if (el.data('opened-before') === undefined) {
                    el.data('opened-before', true);
                    el.select2('close');
                    el.select2('open');
                }
            });
        }
        return result;
    };
    $.fn.select2.defaults = original_select2_fn.defaults;
}
