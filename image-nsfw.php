<?php
/**
 * @package ModerateContent
 */
/*
Plugin Name: Image (NSFW)
Plugin URI: https://www.moderatecontent.com
Description: Stops the upload of NSFW images. Using the FREE api at moderatecontent.com to rate content and block it if it's adult.
Version: 1.0.6
Author: ModerateContent.com
Author URI: https://www.moderatecontent.com
License: GPLv2 or later
Text Domain: moderatecontent
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

global $wpdb;

define( 'MODERATECONTENT__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MODERATECONTENT__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once(MODERATECONTENT__PLUGIN_DIR.'activation.php');
register_activation_hook( __FILE__, 'MODERATECONTENT__PLUGIN_activate' );
register_deactivation_hook( __FILE__, 'MODERATECONTENT__PLUGIN_deactivate' );
register_uninstall_hook( __FILE__, 'MODERATECONTENT__PLUGIN_uninstall' );
// add_action( 'upgrader_process_complete', 'MODERATECONTENT__PLUGIN_updated', 10, 2 );

require_once(MODERATECONTENT__PLUGIN_DIR.'admin.php');
require_once(MODERATECONTENT__PLUGIN_DIR.'evaluate.php');
add_filter('wp_handle_upload', 'MODERATECONTENT__PLUGIN_handle_upload');
add_filter ( 'wp_insert_post_data', 'MODERATECONTENT__PLUGIN_post_published_notification' , 10, 2);

wp_enqueue_script( 'admin_3', MODERATECONTENT__PLUGIN_URL . 'js/moderatecontent_plugin_admin.js', array( 'jquery' ), '1.0.1', true );

// MODERATECONTENT__PLUGIN_updated();
