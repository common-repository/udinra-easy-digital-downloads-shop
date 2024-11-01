<?php


function udinra_eddshop_shortcode( $udinra_eddshop_atts ) {

    $udinra_eddshop_parameters = shortcode_atts( array(
									'sort' => 'newest', 
									'image' => 'medium',
									'show' => 'false'
										), $udinra_eddshop_atts );

	$udinra_edd_shop_html = '';
	$udinra_eddshop_filter_sort = 0;
	
	if($udinra_eddshop_parameters["sort"] == 'newest'){
		$udinra_eddshop_filter_sort = 1;
	}
	if($udinra_eddshop_parameters["sort"] == 'oldest'){
		$udinra_eddshop_filter_sort = 2;
	}
	if($udinra_eddshop_parameters["sort"] == 'lowprice'){
		$udinra_eddshop_filter_sort = 3;
	}
	if($udinra_eddshop_parameters["sort"] == 'highprice'){
		$udinra_eddshop_filter_sort = 4;
	}
	if($udinra_eddshop_parameters["sort"] == 'earning'){
		$udinra_eddshop_filter_sort = 5;
	}
	if($udinra_eddshop_parameters["sort"] == 'sales'){
		$udinra_eddshop_filter_sort = 6;
	}
	update_option('udinra_eddshop_filter_show',$udinra_eddshop_parameters["show"]);
	update_option('udinra_eddshop_filter_image',$udinra_eddshop_parameters["image"]);
	
	$udinra_edd_shop_call = 0;
	
	$udinra_edd_shop_html = udinra_eddshop_get_downloads($udinra_edd_shop_call,$udinra_eddshop_filter_sort);
	
	udinra_eddshop_script();
	udinra_eddshop_css();
	return $udinra_edd_shop_html;
	
}

function udinra_eddshop_script() {
	wp_enqueue_script( 'udinra-eddshop-handle', plugins_url( 'js/udinra_eddshop_ajax.js',dirname( __FILE__ )), array( 'jquery' ) );
	wp_localize_script( 'udinra-eddshop-handle', 'udinra_eddshop_script', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

function udinra_eddshop_css() {
	wp_enqueue_style( 'udinra-eddshop-css', plugins_url( 'css/udstyle.css',dirname( __FILE__ )));
}
	
add_shortcode( 'udinra_eddshop', 'udinra_eddshop_shortcode' );

?>