<?php

require 'zoho/ZohoInventory.php';
$zohoinvetory_obj = new ZohoInventory;

if (isset($_GET['module_record_id'])) {
    $module_record_id = isset($_GET['module_record_id'])?$_GET['module_record_id']:0;
    $poData = $zohoinvetory_obj->getPurchase_Order_Details($module_record_id);
    echo json_encode($poData);
    
}