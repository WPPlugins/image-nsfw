jQuery('#MODERATECONTENT__PLUGIN_test_api_key').click(function(){
    var api_key = jQuery(this).data("api_key");
    var image_url = "https://www.moderatecontent.com/img/sample_faces.jpg";
    var url = "https://www.moderatecontent.com/api/v2?key="+api_key+"&url="+image_url;
    jQuery.ajax({
      url: url,
      dataType: "json",
      success: function(response){
        var html = "<div style=\"background-color:#e0e0e0;padding:10px;margin-top:10px;\">";
        if (response.error_code == "0"){
            var code = JSON.stringify(response, null, "\t");
            
            html += "<h4 style=\"margin: 0px;\">Success</h4>";
            html += "<pre>"+code+"</pre>";
        } else {
            html += "<h4 style=\"margin: 0px;\">Error</h4>";
            html += "<p>There seems to be a problem with the request, please contact support and we\'ll be happy to help you. <a href=\"mailto:info@moderatecontent.com\" target=\"_top\">info@moderatecontent.com</a></p>";
        }
        html += "</div>";
        jQuery('#MODERATECONTENT__PLUGIN_test_api_key_test_result').html(html);
      }
    });
});

jQuery('#MODERATECONTENT__PLUGIN_get_api_key').click(function(){
    jQuery.ajax({
      url: ajaxurl,
      data: {action: 'MODERATECONTENT__PLUGIN_register_key'},
      success: function(response){
        console.log(response);
        if (response.length > 20){
            location.reload();
        } else {
            var html = "";
            html += "<p>There seems to be a problem with the request, please contact support and we\'ll be happy to help you. <a href=\"mailto:info@moderatecontent.com\" target=\"_top\">info@moderatecontent.com</a></p>";
            jQuery('#MODERATECONTENT__PLUGIN_get_api_key_error').html(html);
        }
        // var html = "<div style=\"background-color:#e0e0e0;padding:10px;margin-top:10px;\">";
        // if (response.error_code == "0"){
        //     var code = JSON.stringify(response, null, "\t");
            
        //     html += "<h4 style=\"margin: 0px;\">Success</h4>";
        //     html += "<pre>"+code+"</pre>";
        // } else {
        //     html += "<h4 style=\"margin: 0px;\">Error</h4>";
        //     html += "<p>There seems to be a problem with the request, please contact support and we\'ll be happy to help you. <a href=\"mailto:info@moderatecontent.com\" target=\"_top\">info@moderatecontent.com</a></p>";
        // }
        // html += "</div>";
        // jQuery('#MODERATECONTENT__PLUGIN_test_api_key_test_result').html(html);
      }
    });
});
