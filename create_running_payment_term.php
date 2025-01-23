<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'zoho/ZohoInventory.php';
$zohoInventory = new ZohoInventory();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    file_put_contents('debug1.log', "\nFILES Data:\n" . print_r($_POST, true), FILE_APPEND);
    $createResponse = $zohoInventory->create_running_payment_Terms($_POST);

    print_r($createResponse);
    file_put_contents('debug1.log', "\nFILES Data:\n" . print_r($createResponse, true), FILE_APPEND);

}
