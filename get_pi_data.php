<?php
require 'zoho/ZohoInventory.php';

header('Content-Type: application/json');

$zohoInventory = new ZohoInventory();
$AllPI = $zohoInventory->getAllPI();

echo json_encode($AllPI);
?>
