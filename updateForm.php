<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'zoho/ZohoInventory.php';
$zohoInventory = new ZohoInventory();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    file_put_contents('debug.log', "\nFILES Data:\n" . print_r($_POST, true), FILE_APPEND);
    $moduleFields = [
        'cf_date' => $_POST['cf_date'] ?? '',
        'cf_amount_to_be_paid' => $_POST['piAmount'] ?? 0.00,
        'cf_payment_type' => $_POST['PaymentType'],
        'cf_payment_percentage' => $_POST['Percent'],
        'cf_payment_amount' => $_POST['Amount'],
        'cf_payment_days' => $_POST['Days'],
        'cf_payment_due_date' => $_POST['DueDate'],

    ];

    if (isset($_POST['cf_scm_head_approval_status'])) {
        $moduleFields['cf_scm_head_approval_status'] = $_POST['cf_scm_head_approval_status'];
    }

    if (isset($_POST['cf_finance_head_approval_statu'])) {
        $moduleFields['cf_finance_head_approval_statu'] = $_POST['cf_finance_head_approval_statu'];
    }

    if (isset($_POST['cf_cm_approve_status_history'])) {
        $cf_cm_approve_status_history = $_POST['cf_cm_approve_status_history'] ?? null;

        $dataArray = json_decode($cf_cm_approve_status_history, true);
        $formattedData = ['table_values' => []];
        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($dataArray as $row) {
                $formattedData['table_values'][] = [
                    'cf_date' => $row['cf_date'] ?? "",
                    'cf_scm_head_status' => $row['cf_scm_head_status'] ?? "",
                    'cf_finance_head_status' => $row['cf_finance_head_status'] ?? "",
                    'cf_comments' => $row['cf_comments'] ?? "",
                ];
            }
        }
        $moduleFields['cf_cm_approve_status_history'] = $formattedData;
    }

    if (isset($_FILES['cf_attachment']) && $_FILES['cf_attachment']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $fileTmpPath = $_FILES['cf_attachment']['tmp_name'];
        $fileName = basename($_FILES['cf_attachment']['name']);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newImageName = pathinfo($fileName, PATHINFO_FILENAME) . rand(10000, 99999) . '.' . $fileType;
        $uploadPath = $target_dir . $newImageName;

        if (move_uploaded_file($fileTmpPath, $uploadPath)) {
            $createResponse = $zohoInventory->uploadDocument($uploadPath);

            if (isset($createResponse['documents']) && is_array($createResponse['documents'])) {
                $documentId = $createResponse['documents']['document_id'] ?? null;
                if ($documentId) {
                    $moduleFields['cf_attachment'] = $documentId; // Add document ID to fields
                }
            } else {
                file_put_contents('debug.log', "\nError: 'documents' key is missing or null in the response.\n", FILE_APPEND);
            }
        } else {
            file_put_contents('debug.log', "\nError: File upload failed.\n", FILE_APPEND);
        }
    } else {
        file_put_contents('debug.log', "\nNo file uploaded or file error detected.\n", FILE_APPEND);
    }

    
    $moduleRecordId = $_POST['module_record_id'] ?? null;
    $recordResponse = $zohoInventory->updateZohoInventoryRecord($moduleRecordId, $moduleFields);

    file_put_contents('debug5.log', "\nFILES Data:\n" . print_r($recordResponse, true), FILE_APPEND);


    echo json_encode(['status' => 'success', 'message' => 'Data received']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
