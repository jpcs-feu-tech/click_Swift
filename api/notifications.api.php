<?php

require_once("config.php");

function humanTiming ($time)
{

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}

$result['s'] = 0;
if (isset($_GET['m'])) {
    switch($_GET['m']) {
        case 'retrieve':
            if (isset($_GET['t'])) {
                $token = $db->real_escape_string($_GET['t']);
                
                $q = $db->query("SELECT * FROM lg_users WHERE Token = '$token'");
                if ($q->num_rows == 1) {
                    
                    $uObj = $q->fetch_object();
                    
                    $q = $db->query("SELECT * FROM lg_notifications WHERE UserID = {$uObj->UserID} or UserID = 0");
                    $arr = [];
                    while ($notifData = $q->fetch_assoc()) {
                        $notifData['ReadableElapsed'] = humanTiming(strtotime($notifData['DatePosted'])) . ' ago';
                        $q2 = $db->query("SELECT * FROM lg_notifications_read WHERE NotifID = {$notifData['NotifID']} and UserID = {$uObj->UserID} LIMIT 1");
                        $notifData['IsRead'] = $q2->num_rows;
                        $arr[] = $notifData;
                        
                    }
                    
                    $result['s'] = 1;
                    $result['notifications'] = $arr;
                    
                }
            }
        break;
        case 'markread':
            if (isset($_GET['t'])) {
                $token = $db->real_escape_string($_GET['t']);
                
                $q = $db->query("SELECT * FROM lg_users WHERE Token = '$token'");
                if ($q->num_rows == 1) {
                    
                    $uObj = $q->fetch_object();
                    
                    $q = $db->query("SELECT * FROM lg_notifications WHERE UserID = {$uObj->UserID} or UserID = 0");
                    $arr = [];
                    while ($notifData = $q->fetch_assoc()) {
                        $q2 = $db->query("SELECT * FROM lg_notifications_read WHERE NotifID = {$notifData['NotifID']} and UserID = {$notifData['UserID']} LIMIT 1");
                        if ($q2->num_rows == 0) {
                            $db->query("INSERT INTO lg_notifications_read (NotifID, UserID, Status) VALUES ({$notifData['NotifID']}, {$uObj->UserID}, 1)");
                        }
                    }
                    
                    $result['s'] = 1;
                    
                }
            }
            break;
    }
}
echo json_encode($result);
?>