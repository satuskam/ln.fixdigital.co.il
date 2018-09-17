// Object of params which should be passed by PHP wp_localize_script()
var pageInfoDataFromPhp;

jQuery(function(){
    var $ = jQuery;
    var utils = WebgroupUtils;
    
    
    function guid() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
        }
        return s4() + s4() +'-' + s4() + '-' + s4() + '-' + s4() + s4();
    }
    
    function getFormattedDate() {
        function prev0 (val) {
            return val < 10 ? '0' + val : '' + val;
        }
        
        var date = new Date();
        
        var str = [
            date.getFullYear(),
            prev0( date.getMonth() + 1 ),
            prev0( date.getDate() ),
            prev0( date.getHours() ),
            prev0( date.getMinutes() ),
            prev0( date.getSeconds() )
        ].join('-');

        return str;
    }
    
    function isReferrerFromCurrentSite()
    {
        var currDomain = location.hostname;
       
        var regex = new RegExp('^http[s]{0,1}://' + currDomain + '[^/]*');
        var result = document.referrer.search(regex);
       
        return result > -1;
    }
    
    function generateViewId()
    {
        var params = [
            pageInfoDataFromPhp.ClientID ? pageInfoDataFromPhp.ClientID : 0,
            pageInfoDataFromPhp.TenantID ? pageInfoDataFromPhp.TenantID : 0,
            pageInfoDataFromPhp.propertyId,
            pageInfoDataFromPhp.propertyTypeId,
            pageInfoDataFromPhp.system,
            getFormattedDate(),
            clientUniq
        ];
  
        var viewId = params.join('_');
         
        return viewId;
    }
    
    
    var originalQuery = location.search;
    var originalHash = location.hash;
    var originalChannelId = utils.getOriginalChannelIdFromUrl();
    var originalReferrer = document.referrer;
    var cookieOriginChannelId = utils.readCookie('cookie_origin_channelid');

    var clientUniq = utils.readCookie('ucoClientUniqId');
    if (!clientUniq) {
        clientUniq = guid();
        utils.createCookie('ucoClientUniqId', clientUniq, 100 * 365);
    }
    
    var visitorId = 'fix_' + clientUniq;
    utils.createCookie('fixdigital-origin_visitorid', visitorId, 100 * 365);
    
    var viewId = generateViewId();
 
    var tempReferer = utils.readCookie('ucoTempReferer');
    if (!tempReferer) {
        tempReferer = pageInfoDataFromPhp.currReferer;
        utils.createCookie('ucoTempReferer', tempReferer , 30);
    }

    if (originalChannelId === null) {
        if (originalReferrer !== null && !isReferrerFromCurrentSite()) {
            utils.createCookie('fixdigital-origin_hashparams', originalHash, 30);
            utils.createCookie('fixdigital-origin_queryparams', originalQuery, 30);
            utils.createCookie('fixdigital-origin_referrer', originalReferrer, 30);
            utils.createCookie('fixdigital-origin_viewid', viewId, 30);
        }
        
    } else {
        if ( cookieOriginChannelId === null || (cookieOriginChannelId !== null && cookieOriginChannelId !== originalChannelId) ) {
            utils.createCookie('fixdigital-origin_hashparams', originalHash, 30);
            utils.createCookie('fixdigital-origin_queryparams', originalQuery, 30);
            utils.createCookie('fixdigital-origin_referrer', originalReferrer, 30);
            utils.createCookie('fixdigital-origin_viewid', viewId, 30);
            utils.createCookie('fixdigital-origin_channelid', originalChannelId, 30);
            
        } else if (cookieOriginChannelId !== null && cookieOriginChannelId === originalChannelId) {
            utils.createCookie('fixdigital-origin_hashparams', originalHash, 30);
            utils.createCookie('fixdigital-origin_queryparams', originalQuery, 30);
            utils.createCookie('fixdigital-origin_referrer', originalReferrer, 30);
            utils.createCookie('fixdigital-origin_viewid', viewId, 30);
        }
    }
    
    // it will be used to send form data on server side
    utils.createCookie('fixdigital-origin_query_string_for_crm', utils.createQueryStringForCrm(), 30);

    var data = $.extend(
        {
            guid: clientUniq,
            event: 'pageOpened',
            tempReferer: tempReferer,
            referrer : location.referrer,
            origin_referrer : utils.readCookie('fixdigital-origin_referrer'),
            channelID : utils.readCookie('fixdigital-origin_channelid'),
            viewID : utils.readCookie('fixdigital-origin_viewid'),
            visitorID : utils.readCookie('fixdigital-origin_visitorid'),
            propertyId :  pageInfoDataFromPhp.propertyId,
            propertyTypeId : pageInfoDataFromPhp.propertyTypeId,
            system : pageInfoDataFromPhp.system,
        },
        pageInfoDataFromPhp
    );
    
    delete data.requestUrlToCrm;

	var url = pageInfoDataFromPhp.requestUrlToCrm + utils.createQueryStringForCrm();

	$.ajax({
        url: url,
        type: 'POST',
        data: data,
        dataType: 'json',
        success: function() {
            // console.log('success')
        },
        error: function() {
            // console.log('error')
        }
    });

});

