<?php

/***
* class form creating shortcode
* and maintain form data
***/

class WfpFrontend {
	
	// Return single instance
	
	protected static $instance = null;
	
	public static function get_instance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/***
	* Create shortcode for frontend form
	***/
	
	public function __construct(){
		add_shortcode( 'wfp_frontend_form', array($this,'generate_shortcode'));
		add_action( 'admin_post_wfp_post_save', array($this, 'wfp_save_if_submitted'));
	}
	
	/***
	* Check user logged in
	* HTML content for form
	***/
	
	public function generate_shortcode() {
		if (!is_user_logged_in()) {
			return sprintf(
				__('You need to %s to see this page.', 'wp-frontend-posts'),
				'<a href="' . wp_login_url(get_permalink()) . '" title="Login">Login</a>'
			);
		}
		ob_start();
			wpf_fronend_show_response();
			require WFP_PATH . 'views/wfp-post-form.php';
		return ob_get_clean();
		
	}
	
	/***
	* Check form submitted or not
	* Validate form data
	***/
	
	public function wfp_save_if_submitted() {
		$url = $_SERVER['HTTP_REFERER'];
		$response = ['status'=>'error','msg' => ''];
		
		// Verify nonce
		
		if( !wp_verify_nonce($_POST['_wpnonce'], 'wfp-frontend-post') ) {
			$response['msg'] = 'Did not save because your form seemed to be invalid. Sorry';
			wp_fronend_redirect($url,$response);
		}
	
		// Stop running function if form wasn't submitted
		
		if ( !isset($_POST['submit']) ) {
			return;
		}
		
		$options = get_option( 'wfp_frontend_options' );
		
		$post_title = sanitize_text_field($_POST['title']);
		$post_desc = $_POST['content'];
		
		
		$min_title = isset($options['wfp_frontend_min_title'])? intval($options['wfp_frontend_min_title']) : 0;
		$min_desc = isset($options['wfp_frontend_min_content'])? intval($options['wfp_frontend_min_content']) : 0;
		
		$category = array();
		
		if(isset($_POST['post_category'])){
			$category = $_POST['post_category'];
		}

		// Form validation to make sure there is content
		
		if(strlen($post_title) < $min_title) {
			$response['msg'] = sprintf('Please enter a title. Title must be at least %d characters long.', $min_title);
			wfp_fronend_redirect($url, $response);
		}
		
		if(strlen($_POST['content']) < $min_desc) {
			$response['msg'] = sprintf('Content must be at least %d characters long.', $min_desc);
			wfp_fronend_redirect($url, $response);
		}
		
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		
		$post = array(
			'post_title'    => sanitize_text_field($_POST['title']),
			'post_content'  => $_POST['content'],
			'post_author'    => $user_id,
			'post_category' => $category,
			'tags_input'    => sanitize_text_field($_POST['post_tags']),
			'post_status'   => 'publish',
			'post_type' 	=> 'post'
		);
		
		// Save form data
		
		$insert = wp_insert_post($post);
		
		if($insert){
			if(!empty($_POST['wfp_feature_image'])){
				set_post_thumbnail( $insert, intval($_POST['wfp_feature_image']) );
			}
			$response['msg'] = 'Post Publish successfully';
			$response['status'] = 'success';
			wfp_fronend_redirect(get_permalink( $insert ), []);
		}else{
			$response['msg'] = 'Post Insert Failed';
			wfp_fronend_redirect($url,$response);
		}
	}
	
}

WfpFrontend::get_instance();