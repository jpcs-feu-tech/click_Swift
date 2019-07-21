<?php

require_once('config.php');

$result = ['s' => 0];
if (isset($_GET['m'])) {
	switch ($_GET['m']) {
		case 'login':
			
			$email = $db->real_escape_string($_GET['u']);
			$password = $db->real_escape_string(hash('sha256', $_GET['p']));
			$query = $db->query("SELECT * FROM lg_users WHERE Email = '{$email}' AND Password = '{$password}'");  
			
			if ($query->num_rows == 1) {
				$now = microtime(true);
				$obj = $query->fetch_object();
				$result['s'] = 1;
				
				//todo implement a secure cypher
				$result['token'] = base64_encode($obj->UserID . ":" . $now . ":"  . rand(1, 1000000000));
				$result['User']['uid'] = $obj->UserID;
				$result['User']['email'] = $obj->Email;
				$result['User']['firstName'] = $obj->FirstName;
				$result['User']['lastName'] = $obj->LastName;
				$result['User']['contactNumber'] = $obj->ContactNumber;
				$result['User']['address'] = $obj->Address;


				$result['User']['profileImageUrl'] = $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . '/images/'. $obj->UserID . '.jpg';
				
				$q = $db->query("SELECT * FROM lg_registered_products WHERE UserID = {$obj->UserID}");
                $arr = [];
                while ($data = $q->fetch_assoc()) {
                    unset($data['UserID']);
                    $arr[] = $data;
                }
                        
                $result['User']['registered_products'] = $arr;
                        
						
				$query = $db->query("UPDATE lg_users SET Token = '{$result['token']}' WHERE UserID = {$obj->UserID}");
			} else  {
				$result['msg'] = "Account not found!";
			}
			
		break;
		case 'token':
			
			$token = $db->real_escape_string($_GET['t']);
			//todo implement a secure cypher
			$dec = base64_decode(($token));
			if (strpos($dec, ':') !== false) {
				$split = explode(':', $dec);
				
				$query = $db->query("SELECT * FROM lg_users WHERE UserID = {$split[0]} AND Token = '{$token}'");
				if ($query->num_rows == 1) {
					$later = $split[1];
					$now = microtime(true);
					$diff = $now - $later;
					$days = date('d', $diff);
					if ($days < 7) {
						$now = microtime(true);
						$obj = $query->fetch_object();
						$result['s'] = 1;
						
						//todo implement a secure cypher
						$result['token'] = base64_encode($obj->UserID . ":" . $now . ":" . rand(1, 1000000000));
						$result['User']['uid'] = $obj->UserID;
						$result['User']['email'] = $obj->Email;
						$result['User']['firstName'] = $obj->FirstName;
						$result['User']['lastName'] = $obj->LastName;
						$result['User']['profileImageUrl'] = $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . '/images/'. $obj->UserID . '.jpg';
				        $result['User']['contactNumber'] = $obj->ContactNumber;
				        $result['User']['address'] = $obj->Address;
						
						
						$q = $db->query("SELECT * FROM lg_registered_products WHERE UserID = {$obj->UserID}");
                        $arr = [];
                        while ($data = $q->fetch_assoc()) {
                            unset($data['UserID']);
                            $arr[] = $data;
                        }
                        
                        $result['User']['registered_products'] = $arr;
                        
						
						
						$query = $db->query("UPDATE lg_users SET Token = '{$result['token']}' WHERE UserID = {$obj->UserID}");
					} else {
						
					}
				} else {
					$result['s'] = 0;
				}
			}
			
		break;
		default:
			header('Location: login.api.php');
		break;
	}
}
die(json_encode($result));
?>