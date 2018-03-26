<?php

/*
Plugin Name: WxPress Export
Plugin URI: http://www.imbvo.com/
Description: Plugin is forked from Export All Urls by Atlas Gondal. Extracts all URLs then uploads them to S3 bucket for serverless WordPress Experience.
Version: 1.0
Author: Bee Vo
Author URI: http://www.imbvo.com/
*/


function extract_all_urls_nav(){

    add_options_page( 'WxPress Export', 'WxPress Exports', 'manage_options', 'extract-all-urls-settings', 'include_settings_page' );

}


add_action( 'admin_menu', 'extract_all_urls_nav' );



function include_settings_page(){

    include(plugin_dir_path(__FILE__) . 'extract-all-urls-settings.php');

}
