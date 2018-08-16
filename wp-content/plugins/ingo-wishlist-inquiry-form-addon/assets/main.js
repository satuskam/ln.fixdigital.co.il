jQuery.noConflict();
(function ($) {
    Inquiry = {
        $productsInput : null,
        $inquiryWrapper : null,
        
        init: function(){
            var me = this;
            this.$productsInput = $('form .productsHidden input');
            this.$inquiryWrapper = $('.inquiryWrapper');

//            this.replaceShareIcons();

            this.updateProductsInput();
            
            $('body').on('wc_fragments_refreshed', function(){
                me.updateProductsInput();
            });
        },
        
        
 /*       replaceShareIcons: function() {
            var $container = $('.inquiryWrapper .shareWrapper');
            var $share = $('.yith-wcwl-share');
            var $clone = $share.clone();
            
            $container.append($clone);
        }, */
        
        
        updateProductsInput: function() {
            var productsValue = [];
            var $rows = $('.woocommerce-cart-form__contents tr.cart_item');
            var showInqueryBlock = false;
           
            $rows.each(function(i, el){
                var $row = $(el);
                var prodId = $row.find('[data-product_id]').attr('data-product_id');
      
                if (!productsInIngoWishlist[prodId]) return;
                
                showInqueryBlock = true;
                
                var prodData = productsInIngoWishlist[prodId];
                var itemsCount = $('.quantity input[type=number]', $row).val();
                productsValue.push(
                    "sku: '" + prodData.sku + "', name: '" + prodData.name + "', count: '" + itemsCount + "'"
                );
            });
            
            if (showInqueryBlock) {
                this.$inquiryWrapper.show();
            } else {
                this.$inquiryWrapper.hide();
            }
            

            this.$productsInput.val( productsValue.join('; ') );
        }
    };
    
    
    $(function(){
       Inquiry.init();
    });
    
})(jQuery);