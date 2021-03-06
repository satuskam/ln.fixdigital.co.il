jQuery (function ($) {
  function getParameterByName (name, url) {
    if (!url) {
      url = window.location.href;
    }
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return "";
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

  var javascript_debugging = typeof ai_debugging !== 'undefined';
  var ai_data_id = "AI_NONCE";
  var site_url = "AI_SITE_URL";
  var page = site_url+"/wp-admin/admin-ajax.php?action=ai_ajax&ip-data=ip-address-country&ai_check=" + ai_data_id;

  var debug_ip_address = getParameterByName ("ai-debug-ip-address");
  if (debug_ip_address != null) page += "&ai-debug-ip-address=" + debug_ip_address;
  var debug_ip_address = getParameterByName ("ai-debug-country");
  if (debug_ip_address != null) page += "&ai-debug-country=" + debug_ip_address;

  var enable_block = false;

  var ai_ip_data_blocks = $("div.ai-ip-data");
  if (ai_ip_data_blocks.length)

    $.get (page, function (ip_data) {

      if (javascript_debugging) console.log ("AI IP DATA: " + ip_data);

      ai_ip_data_blocks.each (function () {
        var ip_data_array = ip_data.split (",");
        var ip_address  = ip_data_array [0];
        var country     = ip_data_array [1];

        enable_block = true;
        var found = false;

        var ip_addresses_list = $(this).attr ("ip-addresses");
        if (typeof ip_addresses_list != "undefined") {
          var ip_address_array      = ip_addresses_list.split (",");
          var ip_address_list_type  = $(this).attr ("ip-address-list");

          $.each (ip_address_array, function (index, list_ip_address) {
            if (list_ip_address.charAt (0) == "*") {
              if (list_ip_address.charAt (list_ip_address.length - 1) == "*") {
                list_ip_address = list_ip_address.substr (1, list_ip_address.length - 2);
                if (ip_address.indexOf (list_ip_address) != - 1) {
                  found = true;
                  return false;
                }
              } else {
                  list_ip_address = list_ip_address.substr (1);
                  if (ip_address.substr (- list_ip_address.length) == list_ip_address) {
                    found = true;
                    return false;
                  }
                }
            }
            else if (list_ip_address.charAt (list_ip_address.length - 1) == "*") {
              list_ip_address = list_ip_address.substr (0, list_ip_address.length - 1);
              if (ip_address.indexOf (list_ip_address) == 0) {
                found = true;
                return false;
              }
            }
            else if (list_ip_address == "#" && ip_address == "") {
              found = true;
              return false;
            }

            else if (list_ip_address == ip_address) {
              found = true;
              return false;
            }
          });

          switch (ip_address_list_type) {
            case "B":
              if (found) enable_block = false;
              break;
            case "W":
              if (!found) enable_block = false;
              break;
          }
        }

        if (enable_block) {
          var countries_list = $(this).attr ("countries");
          if (typeof countries_list != "undefined") {
            var country_array         = countries_list.split (",");
            var country_list_type     = $(this).attr ("country-list");

            var found = false;

            $.each (country_array, function (index, list_country) {
              if (list_country == country) {
                found = true;
                return false;
              }
            });
            switch (country_list_type) {
              case "B":
                if (found) enable_block = false;
                break;
              case "W":
                if (!found) enable_block = false;
                break;
            }
          }
        }

        var block_wrapping_div = $(this).closest ('div.ai-ip-data-block');
        $(this).css ({"visibility": "", "position": "", "width": "", "height": "", "z-index": ""}).removeClass ('ai-ip-data');
        block_wrapping_div.css ({"visibility": "", "position": "", "z-index": ""}).removeClass ('ai-ip-data-block');
        block_wrapping_div.find ('.ai-debug-name.ai-ip-country').text (ip_data);
        block_wrapping_div.find ('.ai-debug-name.ai-ip-status').text (enable_block ? 'VISIBLE' : 'HIDDEN');
        if (!enable_block) {
          $(this).hide ();
//          block_wrapping_div.addClass ('ai-hidden');
//          block_wrapping_div.hide ();  // to prevent hiding block debug info
        }
      });
    }).fail (function(jqXHR, status, err) {
      if (javascript_debugging) console.log ("Ajax call failed, Status: " + status + ", Error: " + err);
      $("div.ai-ip-data").each (function () {
        $(this).css ({"display": "none", "visibility": "", "position": "", "width": "", "height": "", "z-index": ""}).removeClass ('ai-ip-data').hide ();
      });
    });
});
