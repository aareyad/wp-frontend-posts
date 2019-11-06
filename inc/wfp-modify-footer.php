<?php

/***
* Filter dashboard footer left content
* @return string: theme name with version
***/

add_filter('admin_footer_text', 'wfp_show_theme_info_in_footer', 100);

function wfp_show_theme_info_in_footer($content) {
	$theme = wp_get_theme();
	$parent = $theme->get('Template');
	$parent_version = wp_get_theme($parent);
	$content = '<span id="footer-thankyou">'.$theme->get('Name').' | v'.$theme->get('Version').'</span>';
	if(!empty($parent)){
		$content .= ' (child theme of '.$parent.' v'.$parent_version->get('Version').')';
	}
	return $content;
}

/***
* Filter dashboard footer right content
* @return string: WordPress version
***/

add_filter('update_footer', 'wfp_modify_wp_info_in_footer', 100);

function wfp_modify_wp_info_in_footer($content) {
	$content = '<span>WordPress | v'.get_bloginfo('version').'</span>';
	return $content;
}