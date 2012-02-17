<?php
/*
Plugin Name: SoundCloud Wordpress
Plugin URI: n/a
Description: A simple Sound Cloud plugin for Wordpress
Version: 1.0
Author: Richard Caceres
Author URI: http://rchrd.net
License: GPL
*/
echo basename(dirname(__FILE__));exit;

function scwp_handle_wp_footer() {
	
	$curr_dir_name = basename(dirname(__FILE__));
	$framesource = plugins_url($curr_dir_name . '/scwp-content.php');
	$img_url     = plugins_url($curr_dir_name . '/80x50_orange.png');
	$loader_url  = plugins_url($curr_dir_name . '/loader.gif');

	?>
	
	<div id="scwp_container">
		<div id="scwp_loading_message">
			<!--<img style="height:16px" src="<?=$img_url;?>" alt="Sound Plugin"/>-->
			<!--<img src="<?=$loader_url;?>" alt="Loading data..." />-->
		</div>
		<div>
			<iframe height="400" width="100%" frameborder="0" src="<?=$framesource;?>"></iframe>
		</div>
	</div>
	<script>
		document.scwp_handle_done_loading = function() {
			//$("#scwp_loading_message").remove();
		}
	</script>
	
	<?
	
}

add_action('dbx_post_sidebar', 'scwp_handle_wp_footer');

