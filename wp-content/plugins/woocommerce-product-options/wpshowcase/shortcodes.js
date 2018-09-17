jQuery(document).ready(function ($) {

    $(document).on('click', '.wpshowcase-accordion-header', function () {
        var currentStatus = $(this).find('.wpshowcase-accordion-header-status').val();
        $(this).closest('.wpshowcase-accordion').find('.wpshowcase-accordion-content').hide();
        $(this).closest('.wpshowcase-accordion').find('.wpshowcase-accordion-header-expander').html(wpshowcase_shortcodes_settings.plus);
        if (currentStatus == 'closed') {
            $(this).closest('.wpshowcase-accordion-element').find('.wpshowcase-accordion-content').show();
            $(this).closest('.wpshowcase-accordion-element').find('.wpshowcase-accordion-header-expander').html(wpshowcase_shortcodes_settings.minus);
            $(this).find('.wpshowcase-accordion-header-status').val('open');
        } else {
            $(this).find('.wpshowcase-accordion-header-status').val('closed');
        }
    });

});