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
  $response = [];
  $fileUploaded = false;
  $fileTmpPath = '';
  $fileName = '';
  $fileType = '';
  $tempFilePath = '';
  $target_dir = "uploads/";

  file_put_contents('debug.log', "\nFILES Data:\n" . print_r($_POST, true), FILE_APPEND);

  $target_file = $target_dir . basename($_FILES['cf_attachment']['name']);
  $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $fileTmpPath = $_FILES['cf_attachment']['tmp_name'];
  $fileName = $_FILES['cf_attachment']['name'];

  $imageArr = explode('.', $fileName); 
  $rand = rand(10000, 99999);
  $newImageName = $imageArr[0] . '.' . $imageArr[1];
  // $newImageName = $imageArr[0] . $rand . '.' . $imageArr[1];
  $uploadPath = "uploads/" . $newImageName;
  $isUploaded = move_uploaded_file($_FILES["cf_attachment"]["tmp_name"], $uploadPath);
  $createResponse = $zohoInventory->uploadDocument("uploads/" . $newImageName);

  if (isset($createResponse['documents']) && is_array($createResponse['documents'])) {
    $documentId = $createResponse['documents']['document_id'] ?? 'Document ID not found';
  } else {
    echo "Error: 'documents' key is missing or null in the response.";
  }

  function getCurrentDateTime()
  {
    date_default_timezone_set('Asia/Kolkata');
    return date('Y-m-d H:i:s');
  }

  $tableData = [];
  $currentDateTime =  getCurrentDateTime();

  $moduleFields = [
    'cf_proforma_invoice_number' => $_POST['cf_proforma_invoice_number'] ?? null,
    'cf_date' =>  $_POST['cf_date'] ?? null,
    'cf_amount_to_be_paid' => $_POST['cf_amount_to_be_paid'] ?? 0.00,
    'cf_purchase_order' => $_POST['cf_purchase_order'] ?? null,
    'cf_po_num' => $_POST['cf_po_num'] ?? null,
    'cf_payment_amount'=>$_POST['cf_amount_to_be_paid'] ,
    'cf_payment_type'=>$_POST['PaymentType'] ,
    'cf_payment_percentage'=>$_POST['Percent'] ,
    'cf_payment_days'=>10 ,
    'cf_payment_due_date' => $_POST['DueDate'],
    'cf_attachment' => $documentId ?? null,
    'cf_created_by'=>$user_name ?? null,
    'cf_vendor'=>$_POST['vendor_id'] ?? null
  ];

  if (isset($_POST['cf_scm_head_approval_status'])) { 
    $moduleFields['cf_scm_head_approval_status'] = $_POST['cf_scm_head_approval_status'];

}

if (isset($_POST['cf_finance_head_approval_statu'])) {
    $moduleFields['cf_finance_head_approval_statu'] = $_POST['cf_finance_head_approval_statu'];
}
$tableValues = json_decode($_POST['table_fields'] ?? '{}', true);

if (count($tableValues)!=0) {
    $cf_scm_head_status = $tableValues[0]['cf_scm_head_status'];
    $cf_finance_head_status = $tableValues[0]['cf_finance_head_status'];
    $cf_comments = $tableValues[0]['cf_comments'];

    $moduleFields['cf_cm_approve_status_history'] = [
        'table_values' => [
            [
                'cf_date' => date('Y-m-d H:i:s'), 
                'cf_scm_head_status' => $cf_scm_head_status,
                'cf_finance_head_status' => $cf_finance_head_status,
                'cf_comments' => $cf_comments
            ]
        ]
    ];
}

  file_put_contents('debug.log', "\nFILES Data:\n" . print_r($moduleFields, true), FILE_APPEND);

  $createResponse = $zohoInventory->create_TransferRequest($moduleFields); 
  file_put_contents('debug.log', "\nError: Failed to create record " . print_r($createResponse, true), FILE_APPEND);

  if (isset($createResponse['module_record']['module_record_id'])) {
    $recordId = $createResponse['module_record']['module_record_id'];
    $response['createRecord'] = $createResponse;


  } else {
    $response['error'] = 'Failed to create record in Zoho Inventory';
    file_put_contents('debug.log', "\nError: Failed to create record " . print_r($createResponse, true), FILE_APPEND);
  }

  echo json_encode($response);
}
