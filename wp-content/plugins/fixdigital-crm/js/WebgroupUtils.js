/*
 *  This script contains functions which are used by other js-scripts of this plugin
 */
var WebgroupUtils = {
    
    readCookie: function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    },
    
    
    createCookie: function(name, value, days) {
        value = value ? value : '';
        
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        } else {
            var expires = "";
        }
        document.cookie = name+"="+value+expires+"; path=/";
    },
    
    
    eraseCookie: function(name) {
        this.createCookie(name,"",-1);
    },
    
    
    createQueryStringForCrm: function() {
        var hash = this.readCookie('fixdigital-origin_hashparams');
        var query = this.readCookie('fixdigital-origin_queryparams');
        var resultQuery = [];
        
        if (hash) {
            hash = hash.substr(1);
        }
        
        if (query) {
            query = query.substr(1);
        }
        
        [hash, query].forEach(function(str){
            if (str) {
                str.split('&').forEach(function(item) {
                    if (item) {
                        resultQuery.push( encodeURI(item) );
                    }
                });
            }
        });

        resultQuery = resultQuery.join('&');
        if (resultQuery) {
            resultQuery = '?' + resultQuery;
        }
        
        return resultQuery;
    },
    
    
    getOriginalChannelIdFromUrl: function() {
        var $ = jQuery;
        var channelId = null;
        var queryRegex = /[?&]{1}channelID=([^&]+)/;
        var hashRegex = /[#&]{1}channelID=([^&]+)/;
        var result;
        
        result = (location.search).match(queryRegex);
        if ($.isArray(result) && result.length > 1 && result[1]) {
            channelId = result[1];
        }
        
        if (!channelId) {
            result = (location.hash).match(hashRegex);
            if ($.isArray(result) && result.length > 1 && result[1]) {
                channelId = result[1];
            }
        }

        return channelId;
    }
    
};

