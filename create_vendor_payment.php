<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'zoho/ZohoInventory.php';
$zohoInventory = new ZohoInventory();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $moduleFields = [
        'vendor_id' => $_POST['vendor_id'] ?? null,
        'vendor_name' => $_POST['vendor_name'] ?? null,
        'amount' => $_POST['amount'] ?? null
    ];

    file_put_contents('debug5.log', "\moduleFields Data:\n" . print_r($moduleFields, true), FILE_APPEND);

    try {
        $createResponse = $zohoInventory->create_vendor_payment($moduleFields);

        if (isset($createResponse['vendorpayment']['payment_id'])) {
            $recordId = $createResponse['vendorpayment']['payment_id'];

            $response = [
                'success' => true,
                'message' => 'Record created successfully.',
                'record_id' => $recordId
            ];
        } else {
            // Handle case where payment ID is missing
            $response = [
                'success' => false,
                'message' => 'Failed to create record. Invalid API response.',
                'error' => $createResponse
            ];
        }
    } catch (Exception $e) {
        // Handle any exceptions
        $response = [
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ];
    }

    // Clear any extra output
    ob_clean();

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
