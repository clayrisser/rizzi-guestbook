<?php
/*
Plugin Name: Rizzi Guestbook Pro
Description: Rizzi Guestbook Pro is the simplest guestbook to administrate, but still maintains the necessary functionality a guestbook needs.
Author: JamRizzi Technologies
Version: 3.0.0
Author URI: http://jamrizzi.com
Plugin URI: http://software.jamrizzi.com/store/products/rizzi-guestbook/
*/


/*
 * Copyright 2015 JamRizzi Technologies (email: info@jamrizzi.com)
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 51
 * Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

global $vgb_name, $vgb_homepage, $vgb_version;
$vgb_name               = "Rizzi Guestbook Pro";
$vgb_homepage           = "http://jamrizzi.com/wordpress/plugins/rizzi-guestbook";
$vgb_version            = "3.0.0";

//Our plugin options
global $opt_vgb_page, $opt_vgb_style, $opt_vgb_items_per_pg, $opt_vgb_reverse, $opt_rgb_homepage_field, $opt_rgb_captcha_key, $opt_rgb_show_powered_by, $opt_rgb_sign_page, $opt_rgb_show_page, $opt_rgb_use_captcha, $rgb_hide_value_section;
global $opt_vgb_allow_upload, $opt_vgb_allow_upload, $opt_vgb_max_upload_siz;
global $opt_vgb_no_anon_signers;
global $opt_vgb_show_browsers, $opt_vgb_show_flags, $opt_vgb_show_cred_link;
global $opt_vgb_hidesponsor, $opt_vgb_digg_pagination;
$opt_vgb_page           = 'vgb_page';
$opt_vgb_style          = 'vgb_style';
$opt_vgb_items_per_pg   = 'vgb_items_per_pg';
$opt_vgb_reverse        = 'vgb_reverse';
$opt_rgb_homepage_field = 'rgb_homepage_field';
$rgb_hide_value_section = 'hide_value_section';
$opt_rgb_use_captcha    = 'rgb_use_captcha';
$opt_rgb_sign_page      = 'rgb_sign_page';
$opt_rgb_show_page      = 'rgb_show_page';
$opt_rgb_show_powered_by = 'rgb_show_powered_by';
$opt_rgb_captcha_key    = 'rgb_captcha_key';
$opt_vgb_allow_upload   = 'vgb_allow_upload';
$opt_vgb_max_upload_siz = 'vgb_max_upload_siz';
$opt_vgb_no_anon_signers= 'vgb_no_anon_signers';
$opt_vgb_show_browsers  = 'vgb_show_browsers';
$opt_vgb_show_flags     = 'vgb_show_flags';
$opt_vgb_show_cred_link = 'vgb_show_cred_link';
$opt_vgb_hidesponsor    = 'vgb_hidesponsor';
$opt_vgb_digg_pagination= 'vgb_digg_pagination';

//Load the textdomain for localization
define('WPVGB_DOMAIN', 'wpvipergb');
load_plugin_textdomain(WPVGB_DOMAIN, false, dirname( plugin_basename(__FILE__) ) . '/lang');

//Include required implementation code
require_once('_output_guestbook.php');
require_once('_admin_menu.php');


//Hook into the user-selected Guestbook page and fill its contents with the Guestbook.
//NOTE: If you want to manually generate a guestbook in one of your templates, simply
//echo vgb_GetGuestbook(), passing the desired options (or nothing, for default).
add_filter('the_content', 'vgb_replace_content');
function vgb_replace_content($content)
{
    global $post, $opt_vgb_page;
    if( $post->ID != get_option($opt_vgb_page) ) return $content;
    
    global $opt_vgb_reverse, $opt_vgb_allow_upload, $opt_vgb_items_per_pg, $opt_vgb_max_upload_siz;
	global $opt_vgb_no_anon_signers, $opt_vgb_digg_pagination;
    global $opt_vgb_show_browsers, $opt_vgb_show_flags, $opt_vgb_show_cred_link;
    return vgb_GetGuestbook(array('entriesPerPg' => get_option($opt_vgb_items_per_pg),
                                  'reverseOrder' => get_option($opt_vgb_reverse),
                                  'allowUploads' => get_option($opt_vgb_allow_upload),
                                  'maxImgSizKb'  => get_option($opt_vgb_max_upload_siz),
                                  'showBrowsers' => get_option($opt_vgb_show_browsers),
                                  'showFlags'    => get_option($opt_vgb_show_flags),
                                  'showCredLink' => get_option($opt_vgb_show_cred_link),
								  'disallowAnon' => get_option($opt_vgb_no_anon_signers),
								  'diggPagination'=>get_option($opt_vgb_digg_pagination)));
                                        
}


//Since our guestbook is basically a glorified full-page comments template,
//suppress the regular one by replacing it with a blank file. 
add_filter('comments_template', 'suppress_comments');
function suppress_comments( $file )
{
    global $post, $opt_vgb_page;
    if( $post->ID == get_option($opt_vgb_page) ) return dirname(__FILE__) . '/_blank.php';
    else                                         return $file;  
}


//Add some styles (make sure they come after the ecu stylesheet, so we can override)
//REMOVED ECU as a prerequisite for the below two wp_enqueue_style calls
add_action('wp_enqueue_scripts', 'vgb_enqueue_styles');
function vgb_enqueue_styles()
{
    global $vgb_version, $opt_vgb_style;
    wp_enqueue_style('WP-ViperGB-Default', vgb_get_data_url().'', array(), $vgb_version );
    $currentStyle = get_option($opt_vgb_style);
    if( $currentStyle != 'Default' )
        wp_enqueue_style('WP-ViperGB-'.$currentStyle, vgb_get_data_url().'styles/'.$currentStyle.".css", array('WP-ViperGB-Default'), $vgb_version );
}


//Add support for [img] shortcode in comments (for user-uploaded images)
add_action('comment_text', 'comment_img_shortcode');
function comment_img_shortcode($content)
{
    return preg_replace('/\[img=?\]*(.*?)(\[\/img)?\]/e', '"<img src=\"$1\" class=\"ecu_images\" alt=\"" . basename("$1") . "\" />"', $content);
}




add_action( 'wp_enqueue_scripts', 'safely_add_stylesheet' );
function safely_add_stylesheet() {

    global $wpdb;
    $rgb_homepage_field_name = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'rgb_homepage_field'", ARRAY_A);
    $rgb_homepage_field_value = $rgb_homepage_field_name['option_value'];

    global $wpdb;
    $rgb_hide_value_section_name = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = 'hide_value_section'", ARRAY_A);
    $rgb_hide_value_section_value = $rgb_hide_value_section_name['option_value'];


    if( $rgb_homepage_field_value == 1 ) {
        wp_enqueue_style( 'prefix-style1', plugins_url('styles/options/homepage_field.css', __FILE__) );
    }
    if( $rgb_hide_value_section_value == 1 ) {
        wp_enqueue_style( 'prefix-style2', plugins_url('styles/options/hide_value_section.css', __FILE__) );
    }

    wp_enqueue_style( 'prefix-style3', plugins_url('styles/options/style.css', __FILE__) );
}

?>