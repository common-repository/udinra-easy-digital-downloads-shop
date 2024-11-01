<?php
/*
Plugin Name: Udinra Easy Digital Downloads Shop
Plugin URI: https://udinra.com/downloads/easy-digital-downloads-shop-pro
Description: Shop for your Easy Digital Downloads store.
Author: Udinra
Version: 1.2
Author URI: https://udinra.com
*/

function Udinra_EDDShop() {
	$udinra_edd_shop = '';
	if(isset($_POST['save_option'])) {
		udinra_edd_shop_refresh();
		$udinra_edd_shop =  'The Shop got refreshed successfully.';
	}	
	include 'lib/udinra_html_eddshop.php';
}

function udinra_edd_shop_admin() {
	if (function_exists('add_options_page')) {
		add_options_page('Udinra EDD Shop', 'Udinra EDD Shop', 'manage_options', basename(__FILE__), 'Udinra_EDDShop');
	}
}

function udinra_eddshop_admin_notice() {
	global $current_user ;
	$user_id = $current_user->ID;
	if ( ! get_user_meta($user_id, 'udinra_eddshop_admin_notice') ) {
		echo '<div class="notice notice-info"><p>'; 
		printf(__('Increase conversion & Sales with EDD Shop Pro plugin <a href="%1$s"><b>Read More</b></a> | <a href="%2$s">Hide Notice</a>'),'https://udinra.com/downloads/easy-digital-downloads-shop-pro' ,'?udinra_eddshop_admin_ignore=0');
		echo "</p></div>";
	}
}

function udinra_eddshop_admin_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	if ( isset($_GET['udinra_eddshop_admin_ignore']) && '0' == $_GET['udinra_eddshop_admin_ignore'] ) {
		add_user_meta($user_id, 'udinra_eddshop_admin_notice', 'true', true);
	}
}

function udinra_eddshop_act() {
	udinra_edd_shop_install();
	wp_schedule_event( current_time( 'timestamp' ), 'daily', 'udinra_eddshop_event');
}

function udinra_eddshop_event() {
	udinra_edd_shop_refresh();
}
function udinra_eddshop_deact() {
	wp_clear_scheduled_hook('udinra_eddshop_event');
	remove_action('admin_menu','udinra_edd_shop_admin');	
	remove_action('admin_notices', 'udinra_eddshop_admin_notice');
	remove_action('admin_init', 'udinra_eddshop_admin_ignore');
	remove_action( 'wp_ajax_udinra_eddshop_hook', 'udinra_eddshop_function' );
	remove_action( 'wp_ajax_nopriv_udinra_eddshop_hook', 'udinra_eddshop_function' ); 
	remove_action( 'admin_enqueue_scripts', 'udinra_eddshop_admin_style' );
	remove_filter( 'plugin_action_links', 'udinra_eddshop_settings_plugin_link');

	udinra_edd_shop_uninstall();
}
function udinra_eddshop_function(){
	$udinra_edd_shop_call = 1;
	udinra_eddshop_common($udinra_edd_shop_call);	
}

function udinra_eddshop_common($udinra_edd_shop_call){
	$udinra_eddshop_filter_sort = '';
	$udinra_edd_shop_html = '';
	if(isset($_POST['udeddsort'])){
		$udinra_eddshop_filter_sort = 1;
		if($_POST['udeddsort'] == 'newest'){
			$udinra_eddshop_filter_sort = 1;
		}
		if($_POST['udeddsort'] == 'oldest'){
			$udinra_eddshop_filter_sort = 2;
		}
		if($_POST['udeddsort'] == 'lowprice'){
			$udinra_eddshop_filter_sort = 3;
		}
		if($_POST['udeddsort'] == 'highprice'){
			$udinra_eddshop_filter_sort = 4;
		}
		if($_POST['udeddsort'] == 'earning'){
			$udinra_eddshop_filter_sort = 5;
		}
		if($_POST['udeddsort'] == 'sales'){
			$udinra_eddshop_filter_sort = 6;
		}
	}
	else{
		$udinra_eddshop_filter_sort = 1;
	}
	$udinra_edd_shop_html = udinra_eddshop_get_downloads($udinra_edd_shop_call,$udinra_eddshop_filter_sort);
	echo $udinra_edd_shop_html;
	die();
}

function udinra_eddshop_update()
{
	udinra_eddshop_button();
}

function udinra_eddshop_admin_style($hook) {
	
	if($hook == 'settings_page_udinra-easy-digital-downloads-shop') {
		wp_enqueue_style( 'udinra_eddshop_pure_style', plugins_url('css/udstyle.css', __FILE__) );	
		wp_enqueue_script( 'udinra_eddshop_pure_js', plugins_url('js/udinra_slideshow.js', __FILE__),array(), '1.0.0', true );
    }
}

function udinra_eddshop_settings_plugin_link( $links, $file ) 
{
    if ( $file == plugin_basename(dirname(__FILE__) . '/udinra-easy-digital-downloads-shop.php') ) 
    {
        $in = '<a href="options-general.php?page=udinra-easy-digital-downloads-shop">' . __('Settings','udeddshop') . '</a>';
        array_unshift($links, $in);
   }
    return $links;
}

include 'init/udinra-init-eddshop.php';
include 'lib/udinra-eddshop-visual-editor.php';
include 'db/udinra-eddshop-call-func.php';
include 'db/udinra-eddshop-db-func.php';

global $wpdb;	

register_activation_hook(__FILE__, 'udinra_eddshop_act');
register_deactivation_hook(__FILE__, 'udinra_eddshop_deact');

add_action('admin_menu','udinra_edd_shop_admin');	
add_action('admin_notices', 'udinra_eddshop_admin_notice');
add_action('admin_init', 'udinra_eddshop_admin_ignore');
add_action( 'init', 'udinra_eddshop_update' );

add_action( 'wp_ajax_udinra_eddshop_hook', 'udinra_eddshop_function' );
add_action( 'wp_ajax_nopriv_udinra_eddshop_hook', 'udinra_eddshop_function' ); 
add_action( 'admin_enqueue_scripts', 'udinra_eddshop_admin_style' );
add_filter( 'plugin_action_links', 'udinra_eddshop_settings_plugin_link', 10, 2 );

?>
