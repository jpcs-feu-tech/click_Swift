<?php


require_once('config.php');



if (isset($_POST['token']) && isset($_POST['msg'])) {
    $token = $db->real_escape_string($_POST['token']);
    $image64 = $_POST['img'];
    $msg = $db->real_escape_string($_POST['msg']);
    
    $q = $db->query("SELECT * FROM lg_users WHERE Token = '$token' LIMIT 1");
    if ($q->num_rows == 1) {
        $uObj = $q->fetch_object();
        
        $stmt = $db->prepare("INSERT INTO lg_user_complaints (ComplaintID, UserID, Image64, Complaint) VALUES (NULL, ?, ?, ?)");
        $stmt->bind_param('iss', $uObj->UserID, $image64, $msg);
        $stmt->execute();
        die("1");
    }
}

echo "0";

?>