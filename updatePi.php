<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'zoho/ZohoInventory.php';
$zohoInventory = new ZohoInventory();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    file_put_contents('debug.log', "\nPOST Data:\n" . print_r($_POST, true), FILE_APPEND);
    $moduleFields = [];
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

                ];
            }
        }
        $moduleFields['cf_cm_approve_status_history'] = $formattedData;
    }

    $moduleRecordId = $_POST['pi_Id'] ?? null;
        $recordResponse = $zohoInventory->updateZohoInventoryRecord($moduleRecordId, $moduleFields);
    echo json_encode(['status' => 'success', 'message' => 'Data received']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
