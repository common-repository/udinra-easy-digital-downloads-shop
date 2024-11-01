function udeddajax(){
	jQuery('#loadingmessage').show();
	jQuery('#udedd_response').hide();
	jQuery.post(udinra_eddshop_script.ajaxurl, jQuery("#udEddForm").serialize()
		,
		function(response_from_udinra_eddshop_function){
			jQuery("#udedd_response").html(response_from_udinra_eddshop_function);
			jQuery('#loadingmessage').hide();
			jQuery('#udedd_response').show();
		}
	);
}
