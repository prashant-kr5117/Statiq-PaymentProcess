<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'zoho/ZohoInventory.php';
$zohoInventory = new ZohoInventory();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


 


$moduleRecordId = $_POST['module_record_id'] ?? null;
  file_put_contents('debug.log', "\n Data----------------------------:\n" . print_r($paymentDa, true), FILE_APPEND);
  $updateResponse = $zohoInventory->update_PO_Record($moduleRecordId,$customFields);
 
  file_put_contents('debug.log', "\nFILES Data:\n" . print_r($updateResponse, true), FILE_APPEND);


  echo json_encode($updateResponse);    
}
  



