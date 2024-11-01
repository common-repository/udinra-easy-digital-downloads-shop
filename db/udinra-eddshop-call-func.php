<?php

function udinra_edd_shop_install(){
	udinra_edd_shop_create();
	udinra_eddshop_populate_db();
}

function udinra_edd_shop_refresh(){
	udinra_eddshop_delete_id();
	udinra_eddshop_check_db();
}

function udinra_edd_shop_uninstall(){
	udinra_edd_shop_delete();
}
function udinra_eddshop_call_init($udinra_eddshop_filter_sort){
	global $wpdb;
	$UdinraEddShop = $wpdb->prefix . 'udeddshop';
	$udinra_edd_shop_begin     = '<form id="udEddForm" class="w3-container">' . '<div class="w3-row w3-padding">';
	$udinra_div_html 			= '<div class="w3-third w3-container">';
	$udinra_edd_shop_end       = '<input name="udinradec" id="udinradec" type="hidden" class="w3-container" />' .
						         '<input name="action" type="hidden" value="udinra_eddshop_hook" />&nbsp;' . '</form>';	
	$udinra_edd_shop_sort      = '<select class="UdinraSelect" id="udeddsort" name="udeddsort" onchange="udeddajax();" style="width:100%">' . 
								  udinra_eddshop_get_sort() . '</select>';		
	$udinra_div_loading_html = '<div id="loadingmessage" class="w3-container" style="display:none;">' . 
								'<img style="padding-left:40%;padding-top:5%;" src="' . plugins_url( 'image/loader.svg', dirname(__FILE__) ) . '" > ' .
								'</div>' ;

	$udinra_edd_shop_order = udinra_eddshop_set_order($udinra_eddshop_filter_sort);

	$udinra_edd_shop_sql = "SELECT  download_id , earning , sales , price 
							FROM $UdinraEddShop 
							$udinra_edd_shop_order";		
	$udinra_edd_shop_downloads = udinra_eddshop_fetch_downloads($udinra_edd_shop_sql);	
	$udinra_edd_shop_html = $udinra_edd_shop_begin . $udinra_div_html . $udinra_edd_shop_sort . '</div>' .						
							'</div>' . $udinra_edd_shop_end . $udinra_div_loading_html .'<div id="udedd_response" class="w3-container">' .
							$udinra_edd_shop_downloads . '</div></div>';
	return	$udinra_edd_shop_html;
}
function udinra_eddshop_call_refresh($udinra_eddshop_filter_sort){
	global $wpdb;
	$UdinraEddShop = $wpdb->prefix . 'udeddshop';
	$udinra_edd_shop_order = udinra_eddshop_set_order($udinra_eddshop_filter_sort);

	$udinra_edd_shop_sql = "SELECT  download_id , earning , sales , price 
							FROM $UdinraEddShop 
							$udinra_edd_shop_order";		
							
	$udinra_edd_shop_downloads = udinra_eddshop_fetch_downloads($udinra_edd_shop_sql);	
	$udinra_edd_shop_html = $udinra_edd_shop_downloads . '</div></div>';
	return	$udinra_edd_shop_html;
}
function udinra_eddshop_get_downloads($udinra_edd_shop_call,$udinra_eddshop_filter_sort){
	$udinra_edd_shop_html = '';
	if($udinra_edd_shop_call == 0){
		$udinra_edd_shop_html = udinra_eddshop_call_init($udinra_eddshop_filter_sort);
		return $udinra_edd_shop_html;
	}
	if($udinra_edd_shop_call == 1){
		$udinra_edd_shop_html = udinra_eddshop_call_refresh($udinra_eddshop_filter_sort);
		return $udinra_edd_shop_html;
	}
}

function udinra_eddshop_get_sort(){
	$udinra_eddshop_sort =	'<option value="default">Order By</option>' . 
							'<option value="earning">Best Sellers</option>' .
							'<option value="sales">Popularity</option>' .
							'<option value="highprice">High Price</option>' .
							'<option value="lowprice">Low Price</option>' .
							'<option value="newest">Newest</option>' .
							'<option value="oldest">Oldest</option>' ;
	return $udinra_eddshop_sort;
}

function udinra_eddshop_set_order($udinra_eddshop_filter_sort){
	$udinra_edd_sql_order = '';
	if($udinra_eddshop_filter_sort == 1){
		$udinra_edd_sql_order = ' ORDER BY id DESC';
	}
	if($udinra_eddshop_filter_sort == 2){
		$udinra_edd_sql_order = ' ORDER BY id ASC';
	}
	if($udinra_eddshop_filter_sort == 3){
		$udinra_edd_sql_order = ' ORDER BY price ASC';
	}
	if($udinra_eddshop_filter_sort == 4){
		$udinra_edd_sql_order = ' ORDER BY price DESC';
	}
	if($udinra_eddshop_filter_sort == 5){
		$udinra_edd_sql_order = ' ORDER BY earning DESC';
	}
	if($udinra_eddshop_filter_sort == 6){
		$udinra_edd_sql_order = ' ORDER BY sales DESC';
	}
	return $udinra_edd_sql_order;
}


?>