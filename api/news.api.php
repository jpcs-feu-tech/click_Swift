<?php

require_once("config.php");

$working_url = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']) . '/';

$q = $db->query("SELECT * FROM lg_news ORDER BY DatePosted DESC");
$arr = [];
while ($data = $q->fetch_assoc()) {
    $img = $data['HeaderImage'];
    $data['HeaderImage'] = $working_url . $img;
    $arr[] = $data;
}
echo json_encode(['news' => $arr]);

?>