<?php

/***
* Exclude BuddyPress admin member lists in members page
***/

add_filter('bp_ajax_querystring', 'wfp_exclude_roles_admin_members', 20, 2);

function wfp_exclude_roles_admin_members( $ps = false, $object = false ){
    
    $users=array();
	
	$users = get_users( array( 'role__in' => array('administrator'), 'fields' => 'ID' ) );

	if($users){

	    if($object!='members')
	        return $ps;
	        
	    $excluded_user = implode(',',$users);
	  
	    $args=wp_parse_args($ps);
	    
	    if(!empty($args['user_id']))
	        return $ps;
	    
	    if(!empty($args['exclude']))
	        $args['exclude']=$args['exclude'].','.$excluded_user;
	    else 
	        $args['exclude']=$excluded_user;
	      
	    $ps=build_query($args);
	   
    }
    return $ps;
}