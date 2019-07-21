<?php


require_once('config.php');

if (isset($_POST['token']) && isset($_POST['img'])) {
	$dec = base64_decode($_POST['token']);
	if (strpos($dec, ':') !== false) {
		$split = explode(':', $dec);
		$uid = $db->real_escape_string($split[0]);
		
		file_put_contents('images/'. $uid . '.jpg', base64_decode($_POST['img']));
	}
}

?>