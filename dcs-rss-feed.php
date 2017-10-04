<?php
/*
Plugin Name: DCS RSS Feed
Plugin URI: http://douglasconsulting.net/
Description: RSS Feed reader pluing
Version: 1.0
Author: Jason Douglas
Author URI: http://douglasconsulting.net
License: GPL
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Nonce's for our AJAX calls. Scripts and style sheets.
 */
function dcs_rss_feed_load_scripts()
{
	//Stylesheets
	wp_register_style( 'dcs-rss-feed-style', plugins_url('dcs-rss-feed.css', __FILE__), array(), "0.6" );

	wp_enqueue_style( 'dcs-rss-feed-style' );
	
	//Scripts
	//wp_enqueue_script('jquery');
	//wp_enqueue_script('jquery-alerts', (WP_PLUGIN_URL.'/dcs_rss_feed_quote_machine/js/jquery.alerts.js'), array('jquery'), "1.12", true);

	//wp_enqueue_script('dcs_rss_feed_contact_page_script', (WP_PLUGIN_URL.'/dcs_rss_feed_contact_page/dcs_rss_feed_contact_page.js'), 
	//                  array('jquery', 'jquery-alerts'), "0.6", true);

    //Register nonce values we can use to verify our ajax calls from the editor.
    //wp_localize_script( "dcs_rss_feed_contact_page_script", "dcs_rss_feed_contact_page_script_vars",
     //                   array(
	//							"ajaxurl" => admin_url('admin-ajax.php'),
    //                            "dcs_rss_feed_contact_page_submit_nonce"=>wp_create_nonce("dcs_rss_feed_contact_page_submit"),
    //                        )
    //                  );
}
add_action('wp_enqueue_scripts', 'dcs_rss_feed_load_scripts');


/**
* RSS Feed shortcode.
*/
function dcs_rss_feed_shortcode($atts, $content=null)
{
	extract( shortcode_atts( array(
							), $atts ) );
	
	$retval = "";
	
	$feed_url = "http://www.huntingreport.com/rss/rss.cfm";

	$contents = file_get_contents( $feed_url );

	$x = new SimpleXmlElement($contents);

	$retval .= "<ul>";
 
	foreach($x->channel->item as $entry) 
	{
		$retval .= "<li><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
	}
	$retval .= "</ul>";
	
	return $retval;
}
add_shortcode( 'dcs_rss_feed', 'dcs_rss_feed_shortcode' );

/**
* Error Logger
*/
if( !function_exists('dcs_error_log') )
{
	function dcs_error_log($text)
	{
		$logfile = "dcs_log_".date_format(date_create(),"Y-m-d").".log";
		error_log($text.PHP_EOL,3,dirname(__FILE__)."/logs/".$logfile);
	}
}
