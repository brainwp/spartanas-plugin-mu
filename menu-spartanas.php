<?php
/**
 * Plugin Name: Spartanas Plugin MU
 * Plugin URI: https://github.com/brasadesign/spartanas-plugin-mu
 * Description: A brief description of the plugin.
 * Version: 0.1
 * Author: brasadesign
 * Author URI: http://brasa.art.br
 * Network: true
 * License: GPLv2
 */
function _brasa_network_site_option(){
	$screen = get_current_screen();
	if($screen->base != 'site-info-network')
		return;

	switch_to_blog($_GET['id']);
	echo '<script>';
	$select_true = '';
	$select_false = '';
	if ( get_option('brasa_menu_mu') == 'true' ) $select_true = 'selected=true';
    if ( get_option('brasa_menu_mu') == 'false' ) $select_false = 'selected=false';
	echo 'jQuery("form .form-table").append("<tr><th>Exibir no menu de sites?</th><td><select name=brasa_menu_mu><option '.$select_true.' value=true>Sim</option><option '.$select_false.' value=false>Não</option></td></tr>");';
	echo '</script>';
	restore_current_blog();
}
add_action('admin_footer','_brasa_network_site_option',99999);


function _brasa_network_site_option_save(){
	if(!isset($_POST['brasa_menu_mu']))
		return;

	switch_to_blog($_POST['id']);
	update_option('brasa_menu_mu',$_POST['brasa_menu_mu']);
	restore_current_blog();
}
add_action('admin_init','_brasa_network_site_option_save',99999);

function brasa_get_menu_sites(){
	if(!is_multisite())
		return array(home_url('/') => get_bloginfo('name'));

	$sites = wp_get_sites();
	$_sites = array();

	foreach ($sites as $site){
		if(get_blog_option($site['blog_id'], 'brasa_menu_mu', false) == 'true'){
			$details = get_blog_details($site['blog_id'], true);
			$_sites[$details->siteurl] = $details->blogname;  
		}
	}
	return $_sites;
}