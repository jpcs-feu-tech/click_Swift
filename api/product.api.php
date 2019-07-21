<?php
require_once('config.php');

if (isset($_GET['token']) && isset($_GET['serial']) && isset($_GET['model']) && isset($_GET['dop'])) {
	
	$dec = base64_decode($_GET['token']);
	
	$tok = explode(":", $dec);
	
	$stmt = $db->prepare("INSERT INTO `lg_registered_products` (`RegistrationID`, `UserID`, `SerialNumber`, `ModelNumber`, `PurchaseDate`) VALUES (NULL, ?, ?, ?, ?)");
	
	$stmt->bind_param('ssss', $tok[0], $_GET['serial'], $_GET['model'], $_GET['dop']);
			
	$stmt->execute();
	$stmt->close();
	
	echo "1";
} else {
	echo "0";
}

?>