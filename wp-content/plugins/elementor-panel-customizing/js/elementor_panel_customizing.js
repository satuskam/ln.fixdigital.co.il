/*
 *  Customize the Elementor editor panel to meet the requirements of UCO WebGroup
 */

var ElementorPanelCustomizing = {
    
    init: function(){
        this.initPanelContentChanging();
        // this.createOnEditorPanelVisibleEvent();
    	this.watchIsPanelReady();
    },
    
    
    /**
     *  Try to define when the elementor's editor is visible and fire 'elementor_editor_panel_visible' event.
     *  I know It's not good enough code. But it's only way which I found
     *  to know the event when Elementor editor panel has been rendered
     *  
     *  This function doesn't work with elementor-1.7.3
     */
/*    createOnEditorPanelVisibleEvent() {
        var me = this;
        var $ = jQuery;
        var i = 0;
        
        elementorFrontend.hooks.addAction( 'init', function() {
            if (elementor.elements && elementor.elements && elementor.elements.length > 0) {
                elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function() {
                    // this hook can be fired many times but we need only first one
                    // to understand that the editor's panel is visible
                    if (i++) return;
                    
                    setTimeout(function(){
                        $(window).trigger('elementor_editor_panel_visible');
                    }, 500);
                    
                } );
                
            } else {
                var intervalId = setInterval(function(){
                    i++;
                    
                    if ($('#elementor-panel-header-wrapper').length && $('#elementor-panel-footer').length) {
                        $(window).trigger('elementor_editor_panel_visible');
                        
                        clearInterval(intervalId);
                    }
                    
                    if (i > 100) {
                        clearInterval(intervalId);
                    }
                }, 100);
            }
        });
    }, */
    
    
    watchIsPanelReady: function() {
        var i = 0;
        var $ = jQuery;
        
        $(function(){
            var intervalId = setInterval(function(){
                i++;

                if ($('#elementor-panel-header-wrapper').length && $('#elementor-panel-footer').length) {
                    $(window).trigger('elementor_editor_panel_visible');

                    clearInterval(intervalId);
                }

                if (i > 500) {
                    clearInterval(intervalId);
                }
            }, 100);
        });
        
    },
    
    
    initPanelContentChanging: function() {
        var me = this;
        var $ = jQuery;
        
        $(window).on('elementor_editor_panel_visible', function(){
            me.changePanelHeader();
            me.changePanelFooter();
        });
    },
    
    
    changePanelFooter: function() {
        var $ = jQuery;
        
        // change text on button
        var newText = 'שמור';
        var $btnWrapper = $('#elementor-panel-footer-save');
        var $btn = $('.elementor-button', $btnWrapper);
        var $btnIndicator = $('.elementor-state-icon', $btn).detach();
        
        $btn.empty().append($btnIndicator).append(newText);
        $btnWrapper.attr('title', newText);
       
       /*
        // change icon for 'X' button and add redirecting on clicking
        var $exitBtn = $('#elementor-panel-footer-exit');
        var $subMenu = $('.elementor-panel-footer-sub-menu-wrapper', $exitBtn);
        var linkToView = $('#elementor-panel-footer-view-page', $subMenu).attr('href');
        
        $subMenu.detach();
        
        // change icon 
        $('.fa', $exitBtn).attr('class', 'fa fa-eye');
        // change button hint
        $exitBtn.attr('title', 'הצג דף');
        
        $exitBtn.click(function(){
            window.open(linkToView, '_blank');  // <- This is what makes it open in a new window.
        });
    	*/
    },
    
    /*
     *  Change logo on the Elementor's panel header
     */
    changePanelHeader: function() {
        var $ = jQuery;
        
        var currUrl = new URL(location.href);
console.log(location.href);
        var urlToBackToFixdigital = currUrl.searchParams.get('urlToBackToFixdigital');
console.log(urlToBackToFixdigital);
        if (!urlToBackToFixdigital) {
            urlToBackToFixdigital = 'http://www.fixdigital.co.il';
        }
       
        var panelTitle = '<a href="' + urlToBackToFixdigital + '" class="elementorPanelHeaderLogo"></a>';
        var $title = $('#elementor-panel-header-title');

        // we need to replace title element by its clone
        // to provide the new title showing after clicking on header's buttons
        var $titleClone = $title.clone(); 

        $title.remove();
        $titleClone.empty().html(panelTitle);
        $titleClone.insertAfter('#elementor-panel-header-menu-button');
    }
    
};


ElementorPanelCustomizing.init();


        


