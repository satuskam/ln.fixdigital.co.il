jQuery(document).ready(function ($) {

    $('.suboptions-hide-options').each(function () {
        var productOption = $(this).closest('.product-option');
        //var productOptions = $(this).closest('.product-options');
        var productOptionsPostId = $(this).closest('.product-options').find('.product-option-post-id').val();
        productOption.find('select, input[type="checkbox"], input[type="radio"]').on('update_option',function () {
            productOption.find('option:selected, input[type="radio"]:checked').each(function () {
                var value = $(this).val();
                productOption.find('.hide-when-suboption-selected-' + value).each(function () {
                    var showProductOptionId = $(this).val();
                    $('.product-option-post-id[value="' + productOptionsPostId + '"]').each(function () {
                        $(this).closest('.product-options').find('.product-option-' + showProductOptionId).hide();
                    });
                });
                productOption.find('.show-when-suboption-selected-' + value).each(function () {
                    var showProductOptionId = $(this).val();
                    $('.product-option-post-id[value="' + productOptionsPostId + '"]').each(function () {
                        $(this).closest('.product-options').find('.product-option-' + showProductOptionId).show();
                    });
                });
            });
            productOption.find('input[type="checkbox"]:checked').each(function () {
                var name = $(this).attr('name');
                var lastIndexOfOpeningBracket = name.lastIndexOf('[');
                var lastIndexOfClosingBracket = name.lastIndexOf(']');
                var value = name.substring(lastIndexOfOpeningBracket + 1, lastIndexOfClosingBracket);
                productOption.find('.hide-when-suboption-selected-' + value).each(function () {
                    var showProductOptionId = $(this).val();
                    $('.product-option-post-id[value="' + productOptionsPostId + '"]').each(function () {
                        $(this).closest('.product-options').find('.product-option-' + showProductOptionId).hide();
                    });
                });
                productOption.find('.show-when-suboption-selected-' + value).each(function () {
                    var showProductOptionId = $(this).val();
                    $('.product-option-post-id[value="' + productOptionsPostId + '"]').each(function () {
                        $(this).closest('.product-options').find('.product-option-' + showProductOptionId).show();
                    });
                });
            });
        });
        productOption.find('select, input[type="checkbox"], input[type="radio"]').first().trigger('update_option');
    });

});