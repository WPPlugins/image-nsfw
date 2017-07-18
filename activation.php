<?php
function MODERATECONTENT__PLUGIN_activate() {
    
    
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'moderate_content_requests';

    $sql = "CREATE TABLE `$table_name` ( "
            . "`id` INT NOT NULL AUTO_INCREMENT , "
            . "`file` BLOB , "
            . "`url` BLOB , "
            . "`status` VARCHAR(32) , "
            . "`rating` VARCHAR(32) , "
            . "`score_everyone` DOUBLE , "
            . "`score_teen` DOUBLE , "
            . "`score_adult` DOUBLE , "
            . "`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , "
            . "UNIQUE KEY id (id)) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    // $api_key = get_option('MODERATECONTENT__PLUGIN_unique_key', "");
    // if ($api_key == "" || strlen($api_key) < 20){
    //     MODERATECONTENT__PLUGIN_v2_key();
    // }
    add_option( 'MODERATECONTENT__PLUGIN_debug', "false", '', 'yes' );
}

// function MODERATECONTENT__PLUGIN_v2_key() {
//     $url = 'https://www.moderatecontent.com/documentation/api';
//     $post_data = array( 'email' => get_option('admin_email'), 'wp_source_flag' => 'true' );
//     $result = wp_remote_post( $url, array( 'body' => $post_data ) );
//     $result = json_decode($result["body"]);

//     add_option( 'MODERATECONTENT__PLUGIN_unique_key', $result->api_key, '', 'yes' );
//     add_option( 'MODERATECONTENT__PLUGIN_debug', "false", '', 'yes' );
// }

function MODERATECONTENT__PLUGIN_deactivate() {
    // delete_option( 'MODERATECONTENT__PLUGIN_unique_key' );
    // delete_option( 'MODERATECONTENT__PLUGIN_debug' );
}

function MODERATECONTENT__PLUGIN_uninstall(){
    delete_option( 'MODERATECONTENT__PLUGIN_unique_key' );
    delete_option( 'MODERATECONTENT__PLUGIN_debug' );
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'moderate_content_requests';
    $sql = "DROP TABLE IF EXISTS $table_name;";

    $wpdb->query( $sql );
}

// function MODERATECONTENT__PLUGIN_updated() {
//     $api_key = get_option('MODERATECONTENT__PLUGIN_unique_key', "");
//     if (strlen($api_key) < 20){
//         delete_option( 'MODERATECONTENT__PLUGIN_unique_key' );
//         MODERATECONTENT__PLUGIN_v2_key();
//     }
// }
