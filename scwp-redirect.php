<?php
/**
 * This is the node that redirects to the dynamic sites.
 * It is currently hosted at http://dev.rchrd.net/scwp/scwp-redirect.php
 * @author Richard Caceres <rchrd.net>
 */ 

if(isset($_GET['state'])) {

	$redirect_url = $_GET['state'] . '?';

	unset($_GET['state']);

	$redirect_url .= http_build_query($_GET);

	Header('Location: ' . $redirect_url);
	
} else {
	
	echo "Error. No State was passed\n";
	
}
