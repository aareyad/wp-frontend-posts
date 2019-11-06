<?php

/***
* class for plugin settings page
***/

class WP_Frontend_Setting_Page {
	
	public function __construct(){
		add_action( 'admin_menu', array( $this, 'custom_menu_page') );
		add_action( 'admin_init',array($this, 'wfp_settings_init') );
	}
	
	// Add page menu
	
	function custom_menu_page(){
		add_menu_page( 
			__( 'WP Frontend', 'wp-frontend-posts' ),
			'WP Frontend',
			'manage_options',
			'wfp-settings',
			array( $this, 'options_page_html')
		); 
	}
	
	// Show HTML content in settings pages
	
	function options_page_html() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
	 
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error( 'wfp_frontend_messages', 'wfp_message', __( 'Settings Saved', 'wfp-frontend-posts' ), 'updated' );
		}
	 
		settings_errors( 'wfp_frontend_messages' );
		?>
		<div class="wrap wfp-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
			<?php
				settings_fields( 'wfp_frontend' );
				do_settings_sections( 'wfp_frontend' );
				submit_button( __('Save Settings', 'wp-frontend-posts'), 'wfp-admin-button primary' );
			?>
			</form>
		</div>
		<?php
	}
	
	// Add section and fields
	
	function wfp_settings_init() {
		register_setting( 'wfp_frontend', 'wfp_frontend_options' );
		 
		add_settings_section(
			'wfp_frontend_setting_section',
			__( 'Main Settings', 'wp-frontend-posts' ),
			array($this,'wfp_frontend_section_cb'),
			'wfp_frontend'
		);
		 
		add_settings_field(
			'wfp_frontend_min_title',
			__( 'Minimum Title Length', 'wp-frontend-posts' ),
			array($this,'wfp_admin_field'),
			'wfp_frontend',
			'wfp_frontend_setting_section',
			[
				'label_for' => 'wfp_frontend_min_title',
				'class' => 'wfp_front_end_row',
				'wfp_frontend_custom_data' => 'custom',
				'description'	=> 'Type minimum length for title'
			]
		);
		
		add_settings_field(
			'wfp_frontend_min_content',
			__( 'Minimum Content Length', 'wp-frontend-posts' ),
			array($this, 'wfp_admin_field'),
			'wfp_frontend',
			'wfp_frontend_setting_section',
			[
				'label_for' => 'wfp_frontend_min_content',
				'class' => 'wfp_front_end_row',
				'wfp_frontend_custom_data' => 'custom',
				'description'	=> 'Type minimum length for description'
			]
		);
		
		add_settings_field(
			'wfp_hide_bp_admin',
			__( 'BuddyPress Admin', 'wp-frontend-posts' ),
			array($this, 'wfp_admin_field_checkbox'),
			'wfp_frontend',
			'wfp_frontend_setting_section',
			[
				'label_for' => 'wfp_hide_bp_admin',
				'class' => 'wfp_front_end_row',
				'wfp_frontend_custom_data' => 'custom',
				'description'	=> 'Hide admin members from BuddyPress member pages'
			]
		);
		
		add_settings_field(
			'show_theme_version',
			__( 'Theme Version', 'wp-frontend-posts' ),
			array($this, 'wfp_admin_field_checkbox'),
			'wfp_frontend',
			'wfp_frontend_setting_section',
			[
				'label_for' => 'show_theme_version',
				'class' => 'wfp_front_end_row',
				'wfp_frontend_custom_data' => 'custom',
				'description'	=> 'Show Theme name and version in dashboard footer'
			]
		);
	}
		 
	function wfp_frontend_section_cb( $args ) {
		?>
		<div class="form-shortcodes">
			<h4>[wfp_frontend_form]</h4>
		</div>
		<?php
	}
	
	// number field for title and content length
	
	function wfp_admin_field( $args ) {
		
		$options = get_option( 'wfp_frontend_options' );
		?>
			<input type="number" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo sanitize_text_field($options[$args['label_for']]); ?>" name="wfp_frontend_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
			<p class="description">
				<?php echo esc_html( $args['description'] ); ?>
			</p>

		<?php
	}
	
	// checkbox field for controling theme version and BuddyPress
	
	function wfp_admin_field_checkbox( $args ){
		$options = get_option( 'wfp_frontend_options' );
		$value = isset($options[$args['label_for']])?sanitize_text_field($options[$args['label_for']]):'';
		?>
			<input type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="1" <?php checked( $value, 1 ); ?> name="wfp_frontend_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
			<?php echo esc_html_e('Show/Hide', 'wfp-frontend-posts'); ?>
			<p class="description">
				<?php echo esc_html( $args['description'] ); ?>
			</p>

		<?php
	}
}

new WP_Frontend_Setting_Page();