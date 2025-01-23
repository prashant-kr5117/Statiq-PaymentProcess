<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'zoho/ZohoInventory.php';
$zohoInventory = new ZohoInventory();

$jsonData = $zohoInventory->getLoggedInUser();
$data = json_decode($jsonData, true);
$user_name = $data['user']['name'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  

   
}
