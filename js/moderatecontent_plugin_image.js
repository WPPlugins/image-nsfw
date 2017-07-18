(function($) {
    function init(page){
        $('.MODERATECONTENT__PLUGIN_row').remove();
        $('#MODERATECONTENT__PLUGIN_pagination').remove();
        // $('#MODERATECONTENT__PLUGIN_content').html( "" );
        get_images(page);
    }
    
    function get_images(page){
        $.post(ajaxurl, {action: 'get_images', page: page}, function(response) {
//            console.log(response);
            if ( response.images.length > 0 ){
                for(var key in response.images){
                    var image = response.images[key];
                    render_row( image );
                }
                update_pagination(response.image_count, response.page, response.index);
                update_events();
                
            } else {
                var html = '';
                html += '<tr class="MODERATECONTENT__PLUGIN_row">';
                html += '    <td colspan="3" style="padding:10px;background-color:#f0f0f0;">';
                html += '       No images processed yet, upload an image to see a record in this table.';
                html += '    </td>';
                html += '</tr>';
                $('#MODERATECONTENT__PLUGIN_content').append( html );
            }
	},'json');
    }
    
    function update_pagination(image_count, page, index){
        var html = '';
        var page_count = image_count / 10;
        if (page_count >= 1){
            html += '<div style="width:95%;" id="MODERATECONTENT__PLUGIN_pagination">';
            html += '   <div style="float:left;margin:3px;">Page: ';
            html += '   </div>';
            for(var i=0; i < page_count; i++){
                var el_class = "MODERATECONTENT__PLUGIN_link";
                var el_style = "cursor:pointer;text-decoration:underline;color: blue;";
                if (page == i) {
                    el_class = "";
                    el_style = "";
                }
                html += '<div style="float:left;margin:3px;">';
                html += '   <div class="'+el_class+'" style="'+el_style+'">';
                html +=         (i+1);
                html += '   </div>';
                html += '</div>';
            }
            html += '</div>';
            $('#MODERATECONTENT__PLUGIN_content').append( html );
        }
    }
    
    function render_row( image ){
        var style_transparent_image = "opacity: 0.2;filter: alpha(opacity=20);";
        var style_everyone = style_transparent_image;
        var style_teen = style_transparent_image;
        var style_adult = style_transparent_image;
        if (image.rating == "teen") style_teen = "";
        else if (image.rating == "adult") style_adult = "";
        else style_everyone = "";
        
        var html = "";
        html += '<tr class="MODERATECONTENT__PLUGIN_row">';
        html += '    <td style="padding:10px;background-color:#f0f0f0;width: 200px;"><image src="'+image.url+'" style="width:200px;" /></td>';
        html += '    <td style="padding:10px;background-color:#f0f0f0;width: 200px;">';
        html += '       <div style="float:left;font-size:9px;margin:12px;margin-left:2px;margin-right:2px;">';
        html += '           <image id="rating_everyone_' + image.id + '" src="'+MODERATECONTENT__PLUGIN_URL+'img/rating_everyone.png" style="height:100px;'+style_everyone+'" />';
        html += '       </div>';
        html += '       <div style="float:left;font-size:9px;margin:12px;margin-left:2px;margin-right:2px;">';
        html += '           <image id="rating_teen_' + image.id + '" src="'+MODERATECONTENT__PLUGIN_URL+'img/rating_teen.png" style="height:100px;'+style_teen+'" />';
        html += '       </div>';
        html += '       <div style="float:left;font-size:9px;margin:12px;margin-left:2px;margin-right:2px;">';
        html += '           <image id="rating_adult_' + image.id + '" src="'+MODERATECONTENT__PLUGIN_URL+'img/rating_adult.png" style="height:100px;'+style_adult+'" />';
        html += '       </div>';
        html += '       <div style="clear:both;"></div>';
        html += '    </td>';
        html += '    <td style="padding:10px;background-color:#f0f0f0;"">';
        html += '       Timestamp ' + image.timestamp + '<br />';
        // html += '       Url ' + image.url + '<br />';
        // html += '       File ' + image.file + '<br />';
        html += '       Everyone Score ' + image.score_everyone + '<br />';
        html += '       Teen Score ' + image.score_teen + '<br />';
        html += '       Adult Score ' + image.score_adult + '<br />';
        html += '    </td>';
        html += '</tr>';
        $('#MODERATECONTENT__PLUGIN_content').append( html );
    }
    
    function update_events(){
        $('.flag_rating').click(function(){
            var rating = "everyone";
            var el_text = $(this).text();
            if (el_text == "Flag Teen") rating = "teen";
            if (el_text == "Flag Adult") rating = "adult";

            var id = $(this).data('id');
            
            $.post(ajaxurl, {action: 'change_rating', id: id, rating: rating}, function(response) {
                if (response[0].rating != 'everyone'){
                    $('#rating_everyone_'+response[0].id).css('opacity','0.2');
                    $('#rating_everyone_'+response[0].id).css('filter','alpha(opacity=20)');
                } else {
                    $('#rating_everyone_'+response[0].id).css('opacity','1.0');
                    $('#rating_everyone_'+response[0].id).css('filter','alpha(opacity=100)');
                }
                if (response[0].rating != 'teen'){
                    $('#rating_teen_'+response[0].id).css('opacity','0.2');
                    $('#rating_teen_'+response[0].id).css('filter','alpha(opacity=20)');
                } else {
                    $('#rating_teen_'+response[0].id).css('opacity','1.0');
                    $('#rating_teen_'+response[0].id).css('filter','alpha(opacity=100)');
                }
                if (response[0].rating != 'adult'){
                    $('#rating_adult_'+response[0].id).css('opacity','0.2');
                    $('#rating_adult_'+response[0].id).css('filter','alpha(opacity=20)');
                } else {
                    $('#rating_adult_'+response[0].id).css('opacity','1.0');
                    $('#rating_adult_'+response[0].id).css('filter','alpha(opacity=100)');
                }
//                console.log(response[0]);
            },'json');
        });
        
        $('.MODERATECONTENT__PLUGIN_link').click(function(){
            var page = $(this).text();
            page = parseInt($(this).text());
            page = page - 1;
//            console.log($(this).text(),page);
            init(page);
        });

    }
    
    init(0);
})( jQuery );
