//jQuery(function(){
var $ = jQuery;
 
IngoApp = {
    init: function() {
        this.initWishlistIconDisplaying();
        
        $(function(){
            console.log('ready');
        });
    },
    
    
    initWishlistIconDisplaying: function() {
        var $wishlistMenuLink = jQuery('nav a[href="/wishlist"]');
        var whishlistIcon = '<img src="data:image/svg+xml;utf8;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pgo8IS0tIEdlbmVyYXRvcjogQWRvYmUgSWxsdXN0cmF0b3IgMTkuMC4wLCBTVkcgRXhwb3J0IFBsdWctSW4gLiBTVkcgVmVyc2lvbjogNi4wMCBCdWlsZCAwKSAgLS0+CjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iQ2FwYV8xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDYwIDYwIiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA2MCA2MDsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+CjxnPgoJPGc+CgkJPHBhdGggZD0iTTU0LjUsNDZMNTQuNSw0NlYxYzAtMC42LTAuNC0xLTEtMWgtNDdjLTAuNiwwLTEsMC40LTEsMXY1OGMwLDAuNiwwLjQsMSwxLDFoMzRjMC4zLDAsMC41LTAuMSwwLjctMC4zbDEzLTEzICAgIGMwLjEtMC4xLDAuMS0wLjIsMC4yLTAuM3YtMC4xQzU0LjUsNDYuMiw1NC41LDQ2LjEsNTQuNSw0NnogTTcuNSwyaDQ1djQzaC0xMmMtMC42LDAtMSwwLjQtMSwxdjEyaC0zMlYyeiBNNDEuNSw1Ni42VjQ3aDkuNiAgICBsLTQuOCw0LjhMNDEuNSw1Ni42eiIgZmlsbD0iIzkxREM1QSIvPgoJCTxwYXRoIGQ9Ik0xNS41LDE0YzAuMywwLDAuNS0wLjEsMC43LTAuM2w2LTZsLTEuNC0xLjRsLTUuMyw1LjNsLTIuMy0yLjNsLTEuNCwxLjRsMywzQzE1LDEzLjksMTUuMiwxNCwxNS41LDE0eiIgZmlsbD0iIzkxREM1QSIvPgoJCTxyZWN0IHg9IjI1LjUiIHk9IjciIHdpZHRoPSIxMCIgaGVpZ2h0PSIyIiBmaWxsPSIjOTFEQzVBIi8+CgkJPHJlY3QgeD0iMjUuNSIgeT0iMTIiIHdpZHRoPSIyMiIgaGVpZ2h0PSIyIiBmaWxsPSIjOTFEQzVBIi8+CgkJPHBhdGggZD0iTTE1LjUsMjdjMC4zLDAsMC41LTAuMSwwLjctMC4zbDYtNmwtMS40LTEuNGwtNS4zLDUuM2wtMi4zLTIuM2wtMS40LDEuNGwzLDNDMTUsMjYuOSwxNS4yLDI3LDE1LjUsMjd6IiBmaWxsPSIjOTFEQzVBIi8+CgkJPHJlY3QgeD0iMjUuNSIgeT0iMjAiIHdpZHRoPSIxMCIgaGVpZ2h0PSIyIiBmaWxsPSIjOTFEQzVBIi8+CgkJPHJlY3QgeD0iMjUuNSIgeT0iMjUiIHdpZHRoPSIyMiIgaGVpZ2h0PSIyIiBmaWxsPSIjOTFEQzVBIi8+CgkJPHBhdGggZD0iTTE1LjUsNDBjMC4zLDAsMC41LTAuMSwwLjctMC4zbDYtNmwtMS40LTEuNGwtNS4zLDUuM2wtMi4zLTIuM2wtMS40LDEuNGwzLDNDMTUsMzkuOSwxNS4yLDQwLDE1LjUsNDB6IiBmaWxsPSIjOTFEQzVBIi8+CgkJPHJlY3QgeD0iMjUuNSIgeT0iMzMiIHdpZHRoPSIxMCIgaGVpZ2h0PSIyIiBmaWxsPSIjOTFEQzVBIi8+CgkJPHJlY3QgeD0iMjUuNSIgeT0iMzgiIHdpZHRoPSIyMiIgaGVpZ2h0PSIyIiBmaWxsPSIjOTFEQzVBIi8+CgkJPHJlY3QgeD0iMTIuNSIgeT0iNDYiIHdpZHRoPSIxMSIgaGVpZ2h0PSIyIiBmaWxsPSIjOTFEQzVBIi8+CgkJPHJlY3QgeD0iMTIuNSIgeT0iNTEiIHdpZHRoPSIyMCIgaGVpZ2h0PSIyIiBmaWxsPSIjOTFEQzVBIi8+Cgk8L2c+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPGc+CjwvZz4KPC9zdmc+Cg==" />';
        
        $wishlistMenuLink.html('<span class="wishlistIcon">' + whishlistIcon + '</span>');
        $wishlistMenuLink.attr('title', 'רשימת משאלות');
        $wishlistMenuLink.show();
    }
}; 
 
//})();
//
//
//jQuery(function(){
    IngoApp.init(); 
//});


/**
 * Valeria's code
 * add click handler to accordion header if there is a link
 */
jQuery( document ).ready( function(){
    jQuery( '.title>a' ).on( 'click', function(){
        window.location = jQuery( this ).attr( 'href' );
    } );
} );