<?php

require_once('config.php');

if (isset($_GET['token']) && isset($_GET['m'])) {
	$token = $db->real_escape_string($_GET['token']);
	switch ($_GET['m']) {
		case 'changeUser':
			if (isset($_GET['newUser'])) {
				$user = $db->real_escape_string($_GET['newUser']);
				$q = $db->query("SELECT * FROM lg_users WHERE Email = '{$user}'");
				if ($q->num_rows <= 0) {
					$db->query("UPDATE lg_users SET Email = '{$user}' WHERE Token = '$token'");
					echo "1";
				} else {
					echo "0";
				}
			} else {
				echo "0";
			}
		break;
		case 'changePass':
			
			if (isset($_GET['newPass'])) {
				$pass = hash('sha256', $_GET['newPass']);
				
				$db->query("UPDATE lg_users SET Password = '{$pass}' WHERE Token = '$token'");
				echo "1";
			} else {
				echo "0";
			}
		break;
		case 'deleteAccount':
			$db->query("DELETE FROM lg_users WHERE Token = '$token'");
			echo "1";
		break;
		
	}
} else {
	echo "0";
}


?>