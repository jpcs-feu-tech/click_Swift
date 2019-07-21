<?php

require_once("config.php");


if (isset($_GET['m'])) {
    switch ($_GET['m']) {
        case 'udata':
            if (isset($_GET['token'])) {
                $token = $db->real_escape_string($_GET['token']);
                
                $q = $db->query("SELECT * FROM lg_users WHERE Token = '$token'");
                
                if ($q->num_rows == 1) {
                    $userObj = $q->fetch_object();
                    
                    $q = $db->query("SELECT * FROM lg_registered_products WHERE UserID = {$userObj->UserID}");
                    $arr = [];
                    while ($data = $q->fetch_assoc()) {
                        unset($data['UserID']);
                        $arr[] = $data;
                    }
                    
                    $user_data = [];
                    $user_data['uid'] = $userObj->UserID;
    				$user_data['email'] = $userObj->Email;
    				$user_data['firstName'] = $userObj->FirstName;
    				$user_data['lastName'] = $userObj->LastName;
    				$user_data['profileImageUrl'] = $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']) . '/images/'. $userObj->UserID . '.jpg';
                    
                    echo json_encode(['s' => 1, 'User' => $user_data, 'registered_products' => $arr]);
                    
                    
                } else {
                    echo '0';
                }
                
            } else {
                echo '0';
            }
        break;
    }
} else {
    echo '0';
}

?>