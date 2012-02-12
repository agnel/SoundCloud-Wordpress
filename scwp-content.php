<?php
/**
 * @author Richard Caceres <rchrd.net>
 */ 

/*-----------------------------------------------------------------------------*/
session_start();
date_default_timezone_set('GMT');

require_once __DIR__.'/class.soundcloudwordpress.php';

$scwp = new SoundcloudWordpress();

/*
 * This receives a request code from the centralized redirector.
 * 
 * If it gets it, it sets a session and redirects to remove get param.
 */ 
if(isset($_GET['code'])) {

	try {
		
		$scwp->logIn($_GET['code']);

		/*
		 * We redirect to get rid of the get parameters 
		 */ 
		Header('Location: ' . $scwp->redirect_url);
		
	} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
	    exit($e->getMessage());
	}

	exit;
} 

/*-----------------------------------------------------------------------------*/
?>

<html>
<head>
<link rel='stylesheet' href='scwp-styles.css' type='text/css' media='all' />
</head>
<body>
	
<?

/*
 * Show some content since we're logged in
 */  
if($scwp->isLoggedIn()) {

	try {		
	    $me        = json_decode($scwp->soundcloud->get('me'), true);
		$me_tracks = json_decode($scwp->soundcloud->get('me/tracks', 
			array("order"=>"created_at", "format" => "json")), true);
		
		//print_r($me);
		//print_r($me_tracks);
		
		?>
		<div>
			<img class="avatar" src="80x50_orange.png" />
			<img class="avatar" src="<?=$me['avatar_url'];?>" />
			Logged in as <a target="_blank" href="<?=$me['permalink_url'];?>"><?=$me['username'];?></a>
		</div>
		
		<br>
		
		<ul class="tracklist">
		
		<?
	
		
		foreach($me_tracks as $track) {
			//print_r($track);
			
			$json_embed = $scwp->soundcloud->get("http://soundcloud.com/oembed", 
				array(
					"url"    => $track['secret_uri'],
					"format" => "json",
					"iframe" => true
				));
			$oembed_data = json_decode($json_embed);
			
			?>
				<li>
				<div><?=$track['title'];?> &ndash; Date <?= date("M. d 'y", strtotime($track['created_at']));?> &ndash; Length <?= gmdate("i:s", .01 * $track['duration']);?></div>
				<input style='width:100%%;' value='<?=htmlentities($oembed_data->html,ENT_COMPAT);?>'></input>
				</li>
			<?
		
		}
		
		?>
		</ul>
		<?

	} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
	    exit($e->getMessage());
	}
} 
/*
 * The login form
 */ 
else {

	$authorizeUrl = $scwp->soundcloud->getAuthorizeUrl(array("state" => $scwp->redirect_url));
	echo $constant_redirect_url;
	?>
	<a href="<?php echo $authorizeUrl; ?>"><img src="btn-connect-sc-l.png" alt="Connect with Sound Cloud"/></a>
	
	<?

}
	
?>

<script>
if(parent.document.scwp_handle_done_loading) {
	parent.document.scwp_handle_done_loading();
}
</script>
</body>
</html>






