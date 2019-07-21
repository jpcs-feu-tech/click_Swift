<?php


require_once("config.php");


$q = $db->query("SELECT * FROM lg_products");
$arr = [];
while ($data = $q->fetch_assoc()) {
    $product = $data;
    $img_file = WORKING_URL . $data['ImageFile'];
    $product['ImageFile'] = $img_file;
    $tips = [];
    $q2 = $db->query("SELECT * FROM lg_product_tips WHERE ProductID = {$data['ProductID']}");
    while ($tips_data = $q2->fetch_assoc()) {
        unset($tips_data['ProductID']);
        $tips[] = $tips_data;
        
    }
    $product['Tips'] = $tips;
    
    $arr[] = $product;
}

die(json_encode($arr));

?>