<?php
/*
Plugin Name: My posts view link
Plugin URI: 
Description: Display a link to display only the logged in user posts in the edit posts view 
Version: 0.1
Author: Anna Johansson
Author URI: http://annathewebdesigner.com
Text Domain: mypostslink 
*/
add_filter('current_screen', 'my_posts_view_get_current_screen' );
function my_posts_view_get_current_screen( $screen ) {
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) 
    	return $screen;

	if ( $screen->base === 'edit' ) 
		add_filter('views_'.$screen->id, 'my_posts_view_link' );
	
    return $screen;
} 
function my_posts_view_link( $links ) {
   		
	global $current_user;
	global $wp_post_types;
		
	$current_screen = get_current_screen();	
	$current_post_type = $current_screen->post_type;	
	$post_type_plural_name = $wp_post_types[$current_post_type]->labels->name;
	
	$author = "author_name=$current_user->user_nicename";
	$link = ( $current_post_type === 'post' ) ? "$current_screen->parent_file?$author" : "$current_screen->parent_file&amp;$author";	
	
	$output = "<a href=\"$link\">" . sprintf(__('My %s', 'mypostslink'), strtolower($post_type_plural_name)) . "</a>";
		
   	$links['current-user-posts'] = $output;
   	
   	return $links;
}
function my_posts_view_init() {
  load_plugin_textdomain( 'mypostslink', false, dirname( plugin_basename( __FILE__ ) ) ); 
}
add_action('plugins_loaded', 'my_posts_view_init');