<?php 

require_once("config.php");


if (isset($_GET['e']) && isset($_GET['fname']) && isset($_GET['lname']) && isset($_GET['address']) && isset($_GET['city']) && isset($_GET['contact']) && isset($_GET['postal']) && isset($_GET['pass'])) {
	$email = $db->real_escape_string($_GET['e']);
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$pass = hash('sha256', $_GET['pass']);
		$test_query = $db->query("SELECT * FROM lg_users WHERE Email = '$email'");
		if ($test_query->num_rows <= 0) {
			$stmt = $db->prepare("INSERT INTO `lg_users` (`UserID`, `Email`, `Password`, `Token`, `LoginTime`, `FirstName`, `LastName`, `Address`, `City`, `ZipCode`, `ContactNumber`) VALUES (NULL, ?, ?, '', '', ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param('ssssssss', $_GET['e'], $pass, $_GET['fname'], $_GET['lname'], $_GET['address'], $_GET['city'], $_GET['postal'], $_GET['contact']);
			
			$stmt->execute();
			
			copy("default.png", "images/{$stmt->insert_id}.jpg");
			
			$stmt->close();
			
			echo "1|";
		} else {
			echo "0|Account already exists!";
		}
	} else {
		echo "0|Invalid email!";
	}
} else {
	echo '0|Invalid request!';
}
?>