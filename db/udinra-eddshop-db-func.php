<?php

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

function udinra_edd_shop_create() {
   global $wpdb;
   $UdinraEddShop = $wpdb->prefix . 'udeddshop';
   $udinra_charset_collate = $wpdb->get_charset_collate();
   update_option( "udinra_eddshop_db_version", '1.0.0' );	
   $udinra_edd_sql = "CREATE TABLE IF NOT EXISTS $UdinraEddShop (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			download_id bigint(20) NOT NULL,
			price decimal(5,2),
			earning decimal(11,2),
			sales int(11),
			PRIMARY KEY  (id)
			) $udinra_charset_collate;";
	dbDelta( $udinra_edd_sql );
}

function udinra_edd_shop_delete() {
	global $wpdb;
   $UdinraEddShop = $wpdb->prefix . 'udeddshop';
   delete_option( "udinra_eddshop_db_version");	
	delete_option('udinra_eddshop_filter_show');
	delete_option('udinra_eddshop_filter_image');
   $udinra_edd_sql = "DROP TABLE IF EXISTS $UdinraEddShop;";
   $wpdb->query($udinra_edd_sql);
}

function udinra_eddshop_populate_db() {
	global $wpdb;
   $udinra_edd_sql = "SELECT id FROM $wpdb->posts 
						WHERE post_type = 'download'
						AND post_status = 'publish'";
   $udinra_eddshop_id_lists = $wpdb->get_results($udinra_edd_sql);
   foreach ($udinra_eddshop_id_lists as $udinra_eddshop_id_list) { 
		$udinra_eddshop_id = $udinra_eddshop_id_list->id;
		$udinra_eddshop_price = udinra_eddshop_price_func($udinra_eddshop_id);
		$udinra_eddshop_earning = udinra_eddshop_earn_func($udinra_eddshop_id);
		$udinra_eddshop_sales = udinra_eddshop_sale_func($udinra_eddshop_id);
		udinra_edd_shop_insert($udinra_eddshop_id,$udinra_eddshop_price,$udinra_eddshop_earning,$udinra_eddshop_sales);
   }
}

function udinra_eddshop_earn_func($udinra_eddshop_id) {
	global $wpdb;
	$udinra_edd_sql = "SELECT COALESCE(pm.meta_value,0)
						FROM $wpdb->posts p 
						INNER JOIN $wpdb->postmeta pm 
						ON p.id = pm.post_id 
						WHERE p.post_type = 'download'
						AND meta_key = '_edd_download_earnings'
						AND p.id = $udinra_eddshop_id
						LIMIT 1";
	return $wpdb->get_var($udinra_edd_sql);
}

function udinra_eddshop_sale_func($udinra_eddshop_id) {
	global $wpdb;
	$udinra_edd_sql = "SELECT COALESCE(pm.meta_value,0)
						FROM $wpdb->posts p 
						INNER JOIN $wpdb->postmeta pm 
						ON p.id = pm.post_id 
						WHERE p.post_type = 'download'
						AND meta_key = '_edd_download_sales'
						AND p.id = $udinra_eddshop_id
						LIMIT 1";
	return $wpdb->get_var($udinra_edd_sql);
}

function udinra_eddshop_price_func($udinra_eddshop_id) {
	global $wpdb;
	$udinra_edd_sql = "SELECT COALESCE(pm.meta_value,0)
						FROM $wpdb->posts p 
						INNER JOIN $wpdb->postmeta pm 
						ON p.id = pm.post_id 
						WHERE p.post_type = 'download'
						AND meta_key = 'edd_price'
						AND p.id = $udinra_eddshop_id
						LIMIT 1";
	return $wpdb->get_var($udinra_edd_sql);
}

function udinra_edd_shop_insert($download_id,$price,$earning,$sales) {
	global $wpdb;
   $UdinraEddShop = $wpdb->prefix . 'udeddshop';
   $wpdb->insert( 
		$UdinraEddShop, 
		array( 
			'download_id' => $download_id, 
			'price' => $price, 
			'earning' => $earning, 			
			'sales' => $sales
		) 
	);
}

function udinra_eddshop_check_db() {
	global $wpdb;
   $udinra_edd_sql = "SELECT id FROM $wpdb->posts 
						WHERE post_type = 'download' 
						AND post_status = 'publish'";
   $udinra_eddshop_id_lists = $wpdb->get_results($udinra_edd_sql);
   foreach ($udinra_eddshop_id_lists as $udinra_eddshop_id_list) { 
		$udinra_eddshop_id = $udinra_eddshop_id_list->id;
		udinra_eddshop_check_id($udinra_eddshop_id);
   }
}

function udinra_eddshop_check_id($udinra_eddshop_id) {
	global $wpdb;
	$UdinraEddShop = $wpdb->prefix . 'udeddshop';
	$udinra_edd_sql = "SELECT download_id
						FROM $UdinraEddShop eshop 
						WHERE eshop.download_id = $udinra_eddshop_id
						LIMIT 1";
	$udinra_eddshop_download_id = $wpdb->get_var($udinra_edd_sql);

	if ($udinra_eddshop_download_id > 0) {
		$udinra_eddshop_price = udinra_eddshop_price_func($udinra_eddshop_id);
		$udinra_eddshop_earning = udinra_eddshop_earn_func($udinra_eddshop_id);
		$udinra_eddshop_sales = udinra_eddshop_sale_func($udinra_eddshop_id);
		udinra_edd_shop_update($udinra_eddshop_id,$udinra_eddshop_price,$udinra_eddshop_earning,$udinra_eddshop_sales);
	}
	else {
		$udinra_eddshop_price = udinra_eddshop_price_func($udinra_eddshop_id);
		$udinra_eddshop_earning = udinra_eddshop_earn_func($udinra_eddshop_id);
		$udinra_eddshop_sales = udinra_eddshop_sale_func($udinra_eddshop_id);
		udinra_edd_shop_insert($udinra_eddshop_id,$udinra_eddshop_price,$udinra_eddshop_earning,$udinra_eddshop_sales);
	}
}

function udinra_edd_shop_update($download_id,$price,$earning,$sales) {
	global $wpdb;
   $UdinraEddShop = $wpdb->prefix . 'udeddshop';
   $wpdb->update( 
		$UdinraEddShop, 
		array( 
			'price' => $price, 
			'earning' => $earning, 			
			'sales' => $sales
		),
		array(
		'download_id' => $download_id	
		)	
	);
}

function udinra_eddshop_delete_id() {
	global $wpdb;
	$UdinraEddShop = $wpdb->prefix . 'udeddshop';
    $udinra_edd_sql = "SELECT download_id FROM $UdinraEddShop
						WHERE NOT EXISTS (
							SELECT 1 FROM $wpdb->posts
							WHERE id = download_id 
							AND post_type = 'download'
							AND post_status = 'publish'
							)";
   $udinra_eddshop_id_lists = $wpdb->get_results($udinra_edd_sql);
   foreach ($udinra_eddshop_id_lists as $udinra_eddshop_id_list) { 
		$udinra_eddshop_id = $udinra_eddshop_id_list->download_id;
		$wpdb->delete($UdinraEddShop,array('download_id' => $udinra_eddshop_id));
   }
}

function udinra_eddshop_fetch_downloads($udinra_edd_shop_sql) {
	global $wpdb;
   $udinra_final_html = '';
   $udinra_download_html = '';
   $udinra_eddshop_filter_show = get_option('udinra_eddshop_filter_show');
   $udinra_eddshop_filter_image = get_option('udinra_eddshop_filter_image');
   $UdinraEddShop = $wpdb->prefix . 'udeddshop';
   $udinra_eddshop_download_lists = $wpdb->get_results($udinra_edd_shop_sql);
   $udinra_update_query = '';
   $udinra_img_container = '<div class="w3-card-4">';
   $udinra_other_container = '<div class="w3-container">';
   $udinra_row_container = '<div class="w3-row w3-padding">';
   $udinra_row_counter = 1;

   if($udinra_eddshop_filter_image == 'medium'){
		$udinra_div_html = '<div class="w3-third w3-container">';	   
		$udinra_eddshop_per_row = 3;
   }
   else {
		$udinra_div_html = '<div class="w3-quarter w3-container">';
		$udinra_eddshop_per_row = 4;
   }

		
   foreach ($udinra_eddshop_download_lists as $udinra_eddshop_download_list) { 
		
		if ( has_post_thumbnail($udinra_eddshop_download_list->download_id)) {
			if($udinra_eddshop_filter_image == 'medium'){
				$udinra_download_html = $udinra_img_container . '<a href="' . get_permalink($udinra_eddshop_download_list->download_id) . '" title="' . 
										get_the_title($udinra_eddshop_download_list->download_id) . '">' .
										get_the_post_thumbnail($udinra_eddshop_download_list->download_id)  .
										'</a>' . $udinra_other_container;
			}
			else{
				$udinra_download_html = $udinra_img_container . '<a href="' . get_permalink($udinra_eddshop_download_list->download_id) . '" title="' . 
										get_the_title($udinra_eddshop_download_list->download_id) . '">' .
										get_the_post_thumbnail($udinra_eddshop_download_list->download_id)  .
										'</a>' . $udinra_other_container;			}
		}
		else {
			if($udinra_eddshop_filter_image == 'medium'){
				$udinra_download_html = udinra_img_container . '<a href="' . get_permalink($udinra_eddshop_download_list->download_id) . '" title="' . 
										get_the_title($udinra_eddshop_download_list->download_id) . '">' .
										'<img src="' . plugins_url( 'image/udimage.png', dirname(__FILE__) ) . '" > '  .
										'</a>' . $udinra_other_container;				
			}
			else{
				$udinra_download_html = udinra_img_container . '<a href="' . get_permalink($udinra_eddshop_download_list->download_id) . '" title="' . 
										get_the_title($udinra_eddshop_download_list->download_id) . '">' .
										'<img src="' . plugins_url( 'image/udimage.png', dirname(__FILE__) ) . '" > '  .
										'</a>' . $udinra_other_container;				
			}
		}
		
		if($udinra_eddshop_filter_show == 'true'){
			$udinra_download_html .=	'<a href="' . get_permalink($udinra_eddshop_download_list->download_id) . '" title="' . 
										get_the_title($udinra_eddshop_download_list->download_id) . '">' . 
										'<b>' . get_the_title($udinra_eddshop_download_list->download_id) . '</b></a>' . '</div></div>';
		}
		else {
			$udinra_download_html .= '</div></div>';
		}
		
		if($udinra_row_counter == 1){
				$udinra_final_html .= $udinra_row_container . $udinra_div_html . $udinra_download_html . '</div>';
		}
		else {
			if($udinra_row_counter > 0 && ($udinra_row_counter % $udinra_eddshop_per_row == 0)) {
				$udinra_final_html .= $udinra_div_html . $udinra_download_html . '</div></div>' . $udinra_row_container;
			}
			if($udinra_row_counter > 0 && ($udinra_row_counter % $udinra_eddshop_per_row != 0)){
				$udinra_final_html .= $udinra_div_html . $udinra_download_html . '</div>';
			}	
		}
		$udinra_row_counter = $udinra_row_counter + 1;
		$udinra_download_html = '';
   }
    return $udinra_final_html;
}

?>