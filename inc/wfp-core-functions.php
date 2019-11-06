<?php

/***
* Redirect to single post
* Reload the file if error
***/

function wfp_fronend_redirect($url, $response = array()){
	update_option('wfp_frontend_message', $response);
	wp_redirect( $url );
	exit;
}

/***
* Show error message
***/

function wpf_fronend_show_response(){
	$resppnse = get_option('wfp_frontend_message');
	if(isset($resppnse['msg'])){
		printf('<p class="wp-message %s"> %s </p>', $resppnse['status'], $resppnse['msg']);
		update_option('wfp_frontend_message',[]);
	}
}


/***
* Check option fields
***/

function wfp_get_option_status($option, $key){
	$options = get_option( $option );
	$status = false;
	if(!empty($options[$key])){
		$status = true;
	}
	return $status;
}