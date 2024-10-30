<?php
/*
Plugin Name: JGC AdBlocker Detector
Description: JGC AdBlocker Detector allows you to display a notice when an ad blocker is detected. This notice can be displayed in a modal box or as text containers in the places where your ads usually appear.
Version: 1.0.1
Author: GalussoThemes
Author URI: https://galussothemes.com
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: jgc-adblocker-detector
Domain Path: /languages

JGC AdBlocker Detector is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

JGC AdBlocker Detector is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with JGC AdBlocker Detector. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if (! defined( 'ABSPATH')) {
	exit;
}

define('JGCABD_PLUGIN_NAME', 'JGC AdBlocker Detector');
define('JGCABD_PLUGIN_VERSION', '1.0.1');
define('JGCABD_PLUGIN_DIR', plugin_dir_path( __FILE__ ));

function jgcabd_set_default_options(){

	$options = array(
		'jgcadb_plugin_version'       => JGCABD_PLUGIN_VERSION,
		'enable_adblocker_detector'   => '',
		'mode'                        => 'modal-box',
		'disable_for_administrators'  => '',
		'modal_box_heading'           => '',
		'modal_box_content'           => '',
		'display_close_button'        => 'display',
		'ad_containers_css_selectors' => '',
		'text_containers_content'     => '',
		'enable_g_analytics_event'    => '',
	);

	update_option( 'jgcabd_options', $options );

}

function jgcabd_add_new_options($options){

	$options['jgcadb_plugin_version'] = JGCABD_PLUGIN_VERSION;
	// Nuevas opciones, en su caso.
	//$options['nueva_opcion'] = isset( $options['nueva_opcion'] ) ? $options['nueva_opcion'] : 'valor_nueva_opcion';
	update_option('jgcabd_options', $options);
}

register_activation_hook( __FILE__, 'jgcabd_activation');
function jgcabd_activation(){

	if (get_option('jgcabd_options') == false) {
		jgcabd_set_default_options();
	}else{
		$options              = get_option('jgcabd_options');
		$plugin_version_in_db = $options['jgcadb_plugin_version'];

		if ($plugin_version_in_db != JGCABD_PLUGIN_VERSION){
			jgcabd_add_new_options($options);
		}
	}

}

require_once( plugin_dir_path( __FILE__ ) . '/inc/jgcabd-functions.php' );

if (is_admin()){
	require_once( plugin_dir_path( __FILE__ ) . '/inc/jgcabd-options.php' );
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'jgcabd_add_plugin_action_links');
function jgcabd_add_plugin_action_links( $links ) {

    $url = admin_url('options-general.php?page=jgc-adblocker-detector');

	$custom_links = array('<a href="'. $url .'">' . __('Settings', 'jgc-adblocker-detector') . '</a>');
	$links = array_merge($custom_links, $links);

    return $links;

}

add_action('init', 'jgcabd_init');
function jgcabd_init() {

	load_plugin_textdomain( 'jgc-adblocker-detector', false, basename( dirname( __FILE__ ) ) . '/languages' );

	jgcabd_run();

}
