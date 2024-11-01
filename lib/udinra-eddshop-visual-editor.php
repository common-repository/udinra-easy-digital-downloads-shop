<?php
function udinra_eddshop_button() {
	$udinra_eddshop_cap = apply_filters( 'udinra_eddshop_button_cap', 'edit_posts' );
	if ( current_user_can( $udinra_eddshop_cap ) ) {
		add_filter( 'mce_external_plugins', 'udinra_edd_shop_plugin' );
		add_filter( 'mce_buttons', 'udinra_eddshop_register_button' );
	}
}
function udinra_edd_shop_plugin( $plugin_array ) {
	$plugin_array['udinra_eddshop_subscribe'] = plugins_url( 'js/udinra_eddshop_button.js',dirname( __FILE__ ));
	return $plugin_array;
}
function udinra_eddshop_register_button( $buttons ) {
	array_push( $buttons, "udinra_eddshop_subscribe" );
	return $buttons;
}
?>