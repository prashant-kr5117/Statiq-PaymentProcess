<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'zoho/ZohoInventory.php';
$zohoInventory = new ZohoInventory();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $response = [];
  $fileUploaded = false;
  $fileTmpPath = '';
  $fileName = '';
  $fileType = '';
  $tempFilePath = '';

  $target_dir = "uploads/";

  file_put_contents('debug.log', "\nFILES Data:\n" . print_r($_POST, true), FILE_APPEND);


  $paymentData = [];

// Define the expected keys and prefixes for each payment type
$paymentTypes = [
    'advance' => 'cf_advance_payment',
    'pre_delivery' => 'cf_pre_delivery',
    'post_delivery' => 'cf_post_delivery'
];

// Loop through each payment type and append data to the array
foreach ($paymentTypes as $type => $prefix) {
    $paymentData[$prefix . '_amount_unformatted'] = $_POST[$prefix . '_amount_unformatted'] ?? null;
    $paymentData[$prefix . '_percentage_unformatted'] = $_POST[$prefix . '_percentage_unformatted'] ?? null;
    $paymentData[$prefix . '_days_unformatted'] = $_POST[$prefix . '_days_unformatted'] ?? null;
    $paymentData[$prefix . '_due_date_unformatted'] = $_POST[$prefix . '_due_date_unformatted'] ?? null;
}

$paymentDa = $paymentData;


$customFields = [
    "custom_fields" => [
        [
            "api_name" => "cf_advance_payment_amount",
            "value" => $paymentData['cf_advance_payment_amount_unformatted']
        ],
        [
            "api_name" => "cf_advance_payment_percent",
            "value" => $paymentData['cf_advance_payment_percentage_unformatted']
        ],
        [
            "api_name" => "cf_advance_payment_days",
            "value" => $paymentData['cf_advance_payment_days_unformatted']
        ],
        [
            "api_name" => "cf_advance_payment_due_date",
            "value" => $paymentData['cf_advance_payment_due_date_unformatted']
        ],
        [
            "api_name" => "cf_pre_delivery_amount",
            "value" => $paymentData['cf_pre_delivery_amount_unformatted']
        ],
        [
            "api_name" => "cf_pre_delivery_percentage",
            "value" => $paymentData['cf_pre_delivery_percentage_unformatted']
        ],
        [
            "api_name" => "cf_pre_delivery_days",
            "value" => $paymentData['cf_pre_delivery_days_unformatted']
        ],
        [
            "api_name" => "cf_pre_delivery_due_date",
            "value" => $paymentData['cf_pre_delivery_due_date_unformatted']
        ],
        [
            "api_name" => "cf_post_delivery_amount",
            "value" => $paymentData['cf_post_delivery_amount_unformatted']
        ],
        [
            "api_name" => "cf_post_delivery_percentage",
            "value" => $paymentData['cf_post_delivery_percentage_unformatted']
        ],
        [
            "api_name" => "cf_post_delivery_dates",
            "value" => $paymentData['cf_post_delivery_days_unformatted']
        ],  
        [
            "api_name" => "cf_post_delivery_due_date",
            "value" => $paymentData['cf_post_delivery_due_date_unformatted']
        ],

    ]
];



$moduleRecordId = $_POST['module_record_id'] ?? null;
  file_put_contents('debug.log', "\n Data----------------------------:\n" . print_r($paymentDa, true), FILE_APPEND);
  $updateResponse = $zohoInventory->update_PO_Record($moduleRecordId,$customFields);
 
  file_put_contents('debug.log', "\nFILES Data:\n" . print_r($updateResponse, true), FILE_APPEND);


  echo json_encode($updateResponse);    
}
  



