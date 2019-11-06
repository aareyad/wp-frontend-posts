<?php 
/*
Plugin Name: WP Frontend Posts
Plugin URI: 
Description: Publish post from frontend
Author: Ali Akbar Reyad
Author URI: https://github.com/aareyad
Tags: user post, frontend post, frontend blogging, post without dashboard
Text Domain: wp-frontend-posts
Domain Path: /languages/
License: GPL v2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Version: 1.0
*/

if(!defined('ABSPATH')) die();

define("PREFIX", "wfp_");
define('WFP_PATH', plugin_dir_path(__FILE__));

class WpFrontendPosts {
	protected static $instance = null;
	protected $plugin_path;
	protected $plugin_prefix;
	
	public static function instance() {
		if(self::$instance == null) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/***
	* Load Plugin required resources
	***/
	
	public function __construct(){
		$this->plugin_path = WFP_PATH;
		$this->plugin_prefix = PREFIX;
		
		add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
		add_action( 'admin_enqueue_scripts', array($this, 'load_admin_script') );
		add_action( 'wp_enqueue_scripts', array($this, 'load_frontend_script') );
		$this->file_include();
	}
	
	/***
	* Enqueue frontend stylesheet and script
	***/
	 
	public function load_frontend_script(){
		wp_enqueue_style('wfp-frontend-style', plugins_url('/assets/css/wfp-style.css',__FILE__));
		wp_enqueue_style('select2-style', plugins_url('/assets/css/select2.min.css',__FILE__));
		wp_enqueue_style('wfp-load-fontawesome', plugins_url('/assets/css/fontawesome-all.css',__FILE__));
		wp_enqueue_media();
		wp_enqueue_script( 'select2-js', plugins_url('/assets/js/select2.min.js',__FILE__),array('jquery'),false,false);
		wp_enqueue_script( 'wfp-frontend-script', plugins_url('/assets/js/wfp-script.js',__FILE__),array('jquery'),false,true);
	}
	
	/***
	* Enqueue admin stylesheet and script
	***/
	
	public function load_admin_script(){
		wp_enqueue_style('wfp-admin-style', plugins_url('/assets/css/wfp-admin-styles.css',__FILE__));
	}
	
	/***
	* Loads the plugin's text-domain for localization.
	***/
	
	function load_plugin_textdomain(){
		load_plugin_textdomain('wp-frontend-posts', false, plugin_basename(dirname(__FILE__)) . '/languages');
	}
	
	/***
	* Include modules
	***/
	
	public function file_include(){
		
		require 'inc/wfp-core-functions.php';
		
		$show_theme_version = wfp_get_option_status('wfp_frontend_options', 'show_theme_version');
		$wfp_hide_bp_admin = wfp_get_option_status('wfp_frontend_options', 'wfp_hide_bp_admin');
		
		if(is_admin()){
			require 'inc/admin/wfp-frontend-setting.php';
		}
		
		require 'inc/wfp-frontend.php';
		
		if($show_theme_version){
			require 'inc/wfp-modify-footer.php';
		}
		
		if($wfp_hide_bp_admin){
			require 'inc/wfp-bp-member-modify.php';
		}
	}
	
}
WpFrontendPosts::instance();