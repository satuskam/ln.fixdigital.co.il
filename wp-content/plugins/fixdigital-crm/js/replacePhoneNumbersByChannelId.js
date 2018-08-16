jQuery(function(){
    
var $ = jQuery;
var data = dataForPhoneReplacingScriptFromPhp;

    
var PhoneNumbersReplacer = {
    channelId : null,
    phoneUrl : data.urlToGetPhone,
    phoneElementsClass : 'fix_smartphone',
    phoneLinksClass : 'fix_smartphone_href',
    elementorButtonWidgetClass: 'fixSmartphoneBtn',  // need to find Elementor button with telephone for replacing
    
    init: function() {
        this.insertClassIntoElementorButtonWidgets();
        this.channelId = this.findChannelId();
        this.askPhone();
    },
    
    
    insertClassIntoElementorButtonWidgets: function() {
        var $widgets = $('.' + this.elementorButtonWidgetClass);
      $('.elementor-button-link', $widgets).addClass( this.phoneLinksClass );
    },
    
    
    findChannelId: function() {
        var channelId = WebgroupUtils.getOriginalChannelIdFromUrl();
        
        if (!channelId) {
            channelId = $.trim( WebgroupUtils.readCookie('fixdigital-origin_channelid') );
        }
        
        return channelId;
    },
    
    
    createUrlToCrm: function() {
        var urlLastChar = this.phoneUrl.substr( this.phoneUrl.length - 1);
        
        if (urlLastChar !== '/') {
            this.phoneUrl += '/';
        }
        
        return this.phoneUrl + this.channelId;
    },
    
    
    askPhone: function() {
        var me = this;
        var $els = $('.' + this.phoneElementsClass);
        var $links = $('.' + this.phoneLinksClass);

        if (!me.channelId) return;
        if (!($els.length || $links.length)) return;
  	
        $.get( me.createUrlToCrm(), function(response) {
            if (response && response.phone) {
console.log('response phone: ' + response.phone);
                $els.html(response.phone);
                $links.attr('href', 'tel:' + response.phone);
            }
        });
    }
   
};
    
PhoneNumbersReplacer.init();

});