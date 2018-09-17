/*
 *  Add edit button in elementor mode to uneditable elements: menu, logo, widgets, footer.
 *  To go into admin to edit them.
 */

var UneditablePojoElementsLinks = {
    $frame: null,
    
    init: function() {
        var me = this;
        var $ = jQuery;
        
        this.$frame = $('iframe#elementor-preview-iframe');
        
        this.$frame.bind('load',function(){
            me.appendStyles();
            me.initEditBtnForHeaderMenu();
            me.initEditBtnForHeaderLogo();
            me.initEditBtnForStickyHeaderLogo();
            me.initEditBtnForWidgets();
            me.initEditBtnForFooter();
            me.initEditBtnsForCopyrightBar();
            
            me.$frame.contents().find('.uneditableElementLink').click(function(e){
                var linkToView = $(this).attr('data-href');
                window.open(linkToView, '_blank');
            });
        });
        
            
    },
    
    
    appendStyles: function() {
        var $ = jQuery;
        
        var styleHtml = [
            '<style>',
                '.uneditableElement:hover, .parentUneditableElement:hover { border: 1px solid yellow !important; position: relative;}',
                '.uneditableElement .uneditableElementLink, .parentUneditableElement > .uneditableElementLink {',
                    'background: #dd9933;',
                    'border: 1px solid white;',
                    'line-height: 26px;',
                    'padding: 0px 4px;',
                    'color: white;',
                    'display: none;',
                    'position: absolute;',
                    'top: 0px;',
                '}',
                '.uneditableElement:hover .uneditableElementLink, .parentUneditableElement:hover > .uneditableElementLink {',
                    'display: inline-block;',
                    'z-index: 100;',
                '}',
                '.uneditableElement .uneditableElementLink:hover, .parentUneditableElement > .uneditableElementLink:hover {',
                    'color: wheat;',
                    'border-color: wheat;',
                    'cursor: pointer;',
                '}',
            '</style>'

        ].join('');

        this.$frame.contents().find('body').append(styleHtml);
    },
    
    
    initEditBtnForHeaderMenu: function() {
        var $ = jQuery;
        var $menu = this.$frame.contents().find('.nav-main');
        
        if (!$menu.length) {
            $menu = this.$frame.contents().find('#nav-main');  // example: 'superstar' theme
        }
        
        $menu.addClass('uneditableElement');
        
        var editBtnHtml = this.getEditBtnMarkup('/wp-admin/nav-menus.php');
        
        $menu.prepend(editBtnHtml);
    },
    
    
    getEditBtnMarkup: function(link, title, iconClass, offset) {
        title = title || 'עריכה';
        iconClass = iconClass || 'fa-pencil';
        offset = offset || 'right: 0px';
        
        var editBtnHtml = [
            '<span data-href="' + link + '" class="uneditableElementLink" title="' + title + '" role="link" style="' + offset +  '">',
                '<span class="elementor-screen-only">עריכה</span>',
                '<i class="fa ' + iconClass + '"></i>',
            '</span>'
        ].join('');
        
        return editBtnHtml;
    },
    
    
    initEditBtnForHeaderLogo: function() {
        var $ = jQuery;
        var $header = this.$frame.contents().find('#header');
        var $logoImg = $('.logo-img', $header);
        var $logoText = $('.logo-text', $header);
        
        var editImgBtnHtml = this.getEditBtnMarkup('/wp-admin/customize.php?return=%2Fwp-admin%2Fedit.php%3Fpost_type%3Dpage&clickOn=accordion-section-logo');
        $logoImg.prepend(editImgBtnHtml);
        $logoImg.addClass('uneditableElement');
        
        
        var editTextBtnHtml = this.getEditBtnMarkup('/wp-admin/options-general.php');
        $logoText.prepend(editTextBtnHtml);
        $logoText.addClass('uneditableElement');
    },
    
    
    initEditBtnForStickyHeaderLogo: function() {
        var $ = jQuery;
        var $header = this.$frame.contents().find('.sticky-header');
        var $logoImg = $('.logo-img', $header);
        var $logoText = $('.logo-text', $header);
        
        var editImgBtnHtml = this.getEditBtnMarkup('/wp-admin/customize.php?return=%2Fwp-admin%2Fedit.php%3Fpost_type%3Dpage&clickOn=accordion-section-logo');
        $logoImg.prepend(editImgBtnHtml);
        $logoImg.addClass('uneditableElement');
        
        
        var editTextBtnHtml = this.getEditBtnMarkup('/wp-admin/options-general.php');
        $logoText.prepend(editTextBtnHtml);
        $logoText.addClass('uneditableElement');
    },
    
    
    initEditBtnForWidgets: function() {
        var $ = jQuery;
        var me = this;
        var $widgets = this.$frame.contents().find('section.widget .widget-inner');

        $widgets.each(function(i, el){
            var $widget = $(el);
            var editBtnHtml = me.getEditBtnMarkup('/wp-admin/widgets.php', "ערוך את הווידג'ט");
            
            $widget.prepend(editBtnHtml);
            $widget.addClass('uneditableElement');
        });
    },
    
    
    initEditBtnForFooter: function() {
        var $ = jQuery;
        var me = this;
        var $footer = this.$frame.contents().find('#footer, #footer-widgets');
       
        var editFooterBtnHtml = me.getEditBtnMarkup(
            '/wp-admin/customize.php?return=%2Fwp-admin%2Fedit.php%3Fpost_type%3Dpage&clickOn=accordion-section-footer',
            'ערוך סגנונות',
            'fa-paint-brush'
        );
        $footer.prepend(editFooterBtnHtml);
        $footer.addClass('parentUneditableElement');
    },
    
    
    initEditBtnsForCopyrightBar: function() {
        var $ = jQuery;
        var me = this;
        var $copyrightBar = this.$frame.contents().find('#copyright, #footer-copyright');
        
        var editStylesBtnHtml = this.getEditBtnMarkup(
            '/wp-admin/customize.php?return=%2Fwp-admin%2Fedit.php%3Fpost_type%3Dpage&clickOn=accordion-section-copyright',
            'ערוך סגנונות',
            'fa-paint-brush'
        );

        var editTextBtnHtml = this.getEditBtnMarkup(
            '/wp-admin/admin.php?page=pojo-general',
            'ערוך טקסט',
            'fa-pencil',
            'right: 28px;'
        );
        
        $copyrightBar.prepend(editStylesBtnHtml);
        $copyrightBar.prepend(editTextBtnHtml);
        $copyrightBar.addClass('uneditableElement');
    }
    
};


jQuery(function(){
    UneditablePojoElementsLinks.init();
});
