<?php
function MODERATECONTENT__PLUGIN_post_published_notification($data , $postarr){
    $post_content = $data["post_content"];
    $post_content = str_replace("\\","",$post_content);
    preg_match_all('/<img[^>]+>/i',$post_content, $images); 
    foreach($images[0] as $key => $image){
        $origional_img = $image;
        preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $image, $sources);
        $origional_src = $sources[1];
        $json = MODERATECONTENT__PLUGIN_review_file($origional_src, "-- url --");
        if ($json->error_code == "0"){
            if ($json->rating_letter == "a") {
                $upload_dir = wp_upload_dir();
                $adult_file = $upload_dir["basedir"] . "/rating_adult_box.png";
                if (!file_exists($adult_file)){
                    copy(MODERATECONTENT__PLUGIN_DIR . "img/rating_adult_box.png", $adult_file);
                }
                $new_source = $upload_dir["baseurl"] . "/rating_adult_box.png";
                $new_image = str_replace($origional_src, $new_source, $origional_img);
                $post_content = str_replace($origional_img,$new_image,$post_content);
            }
        }
    }
    $data["post_content"] = $post_content;
    return $data;
}

function MODERATECONTENT__PLUGIN_handle_upload( $fileinfo ){
    MODERATECONTENT__PLUGIN_l("Event: wp_handle_upload");
    MODERATECONTENT__PLUGIN_l("File: " . $fileinfo["file"]);
    MODERATECONTENT__PLUGIN_l("Url: " . $fileinfo["url"]);
    MODERATECONTENT__PLUGIN_l("Type: " . $fileinfo["type"]);
    $file = $fileinfo["file"];
    $url = $fileinfo["url"];
    $type = $fileinfo["type"];
    if ($type == "image/jpeg" || $type == "image/jpg" || $type == "image/gif" || $type == "image/png"){
        MODERATECONTENT__PLUGIN_l("Review File Start: " . $url);
        $json = MODERATECONTENT__PLUGIN_review_file($url, $file);
        if ($json->error_code == "0"){
            if ($json->rating_letter == "a") {
                $upload_dir = wp_upload_dir();
                $adult_file = $upload_dir["basedir"] . "/rating_adult_box.png";
                if (!file_exists($adult_file)){
                    copy(MODERATECONTENT__PLUGIN_DIR . "img/rating_adult_box.png", $adult_file);
                }
                $fileinfo["file"] = $adult_file;
                $fileinfo["url"] = $upload_dir["baseurl"] . "/rating_adult_box.png";
                $fileinfo["type"] = "image/png";        
            } else {
                MODERATECONTENT__PLUGIN_l("Approved File:" . $file);
            }
        }
        MODERATECONTENT__PLUGIN_l("Review File End: " . $url);
    } else {
        MODERATECONTENT__PLUGIN_l("Not file type: image/jpeg, image/jpg, image/gif, image/png");
    }
    return $fileinfo;
}

function MODERATECONTENT__PLUGIN_review_file($url, $file){
    if (strpos($url, "://localhost/") > 0){
        $url = "https://www.moderatecontent.com/img/logo.png";
        // $url = "http://femjoybabes.com/wp-content/uploads/2016/07/nudity-shaved-pussy-femjoy_008.jpeg";
        MODERATECONTENT__PLUGIN_l('<span style="color:red;">Could not moderate your image because your using localhost, the ModerateContent.com API can\'t access localhost images because they are on a private network for just your computer. We have substituted this URL to help with testing - ' . $url . ".</span>");
    }
    
    $moderate_url = "https://www.moderatecontent.com/api/v2?key=".get_option('MODERATECONTENT__PLUGIN_unique_key',"")."&url=".$url;
    MODERATECONTENT__PLUGIN_l('Moderate Url: ' . $moderate_url);
    $ch = curl_init($moderate_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);

    if (curl_error($ch) != ""){
        MODERATECONTENT__PLUGIN_l("Curl Error: " . curl_error($ch));
    } else {
        MODERATECONTENT__PLUGIN_l("Curl Success");
    }

    
    curl_close($ch);
    $json = json_decode($result);
    MODERATECONTENT__PLUGIN_create_eval_record($file, $url, $json);
    MODERATECONTENT__PLUGIN_l("Moderate Response:" . $result, true);
    
    return $json;
}

function MODERATECONTENT__PLUGIN_l($msg, $force=false){
    if ($force || get_option('MODERATECONTENT__PLUGIN_debug') == "true"){
        $file = MODERATECONTENT__PLUGIN_DIR . "logs/" . get_option('MODERATECONTENT__PLUGIN_unique_key',"").'_log.txt';
        $msg = date(DATE_ATOM) . ": " .$msg . chr(13).chr(10);
        file_put_contents($file, $msg, FILE_APPEND);
    }
}

function MODERATECONTENT__PLUGIN_create_eval_record($file, $url, $score_json){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'moderate_content_requests';
    
    $everyone = strval( $score_json->predictions->everyone );
    $teen = strval( $score_json->predictions->teen );
    $adult = strval( $score_json->predictions->adult );
    
    $file = str_replace("\\","/",$file);
    
    $sql = "INSERT INTO $table_name SET "
            . "file = '$file', "
            . "url = '$url', "
            . "rating = '$score_json->rating_label', "
            . "score_everyone = '$everyone', "
            . "score_teen = '$teen', "
            . "score_adult = '$adult', "
            . "status = 'Evaluated' ;";
    
    $wpdb->query( $sql );
}