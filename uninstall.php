<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

udinra_uninstall_eddshop_plugin();

function udinra_uninstall_eddshop_plugin () {
	udinra_delete_eddshop_options();
}

function udinra_delete_eddshop_options () {
	udinra_edd_shop_uninstall();
}

include 'db/udinra-eddshop-call-func.php';
include 'db/udinra-eddshop-db-func.php';

?>