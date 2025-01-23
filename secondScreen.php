<?php
require 'zoho/ZohoInventory.php';
$zohoinvetory_obj = new ZohoInventory;
$AllPI = $zohoinvetory_obj->getAllPI();

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Advanced Table</title>
  <link rel="icon" href="statiq_logo.jpg" type="image/icon type">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.css">
</head>

<body>
  <div class="container mt-4">
    <h1 class="text-center mb-4">PI Records</h1>
    <table id="table"
      class="table table-striped"
      data-toolbar="#toolbar"
      data-search="true"
      data-show-refresh="true"
      data-show-toggle="true"
      data-show-columns="true"
      data-pagination="true"
      data-page-list="[10, 25, 50, 100, all]"
      data-show-export="true"
      data-click-to-select="true"
      data-side-pagination="client">
      <thead>
        <tr>
          <th data-field="index">#</th>
          <th data-field="pi_number" data-sortable="true">Proforma Invoice Number</th>
          <th data-field="amount" data-sortable="true">Amount</th>
          <th data-field="due_date" data-sortable="true">Due Date</th>
          <th data-field="purchase_order" data-sortable="true">Purchase Order</th>
          <th data-field="cf_vendor_formatted" data-sortable="true">Vendor</th>
          <th data-field="scm-status" data-sortable="false">SCM Status</th> <!-- New Column -->
          <th data-field="finance-status" data-sortable="false">Finance Status</th> <!-- New Column -->

        </tr>
      </thead>
      <tbody>
        <?php foreach ($AllPI as $index => $item): ?>
          <tr data-record='<?php echo json_encode([
                              'module_record_id' => $item['module_record_id'],
                              'cf_payment_type' => $item['cf_payment_type'],
                              'record_name' => $item['record_name'],
                              'cf_payment_due_date' => $item['cf_payment_due_date'],
                              'cf_payment_amount' => $item['cf_payment_amount'],
                              'cf_po_num' => $item['cf_po_num'],
                              'cf_payment_type' => $item['cf_payment_type'],
                              'cf_payment_amount' => $item['cf_payment_amount'],
                              'cf_purchase_order' => $item['cf_purchase_order'],
                              'cf_payment_percentage_formatted' =>  $item['cf_payment_percentage_formatted'],
                              'cf_payment_days_formatted' =>  $item['cf_payment_days_formatted'],
                              'cf_payment_due_date_formatted' =>  $item['cf_payment_due_date_formatted'],
                              'cf_scm_head_approval_status' => $item['cf_scm_head_approval_status'],
                              'cf_finance_head_approval_statu' => $item['cf_finance_head_approval_statu'],
                              'cf_proforma_invoice_number' => $item['cf_proforma_invoice_number'],
                              'cf_vendor_formatted' => $item['cf_vendor_formatted'],

                            ], JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>
            <td><?= $index + 1 ?></td>
            <td><?= !empty($item['cf_proforma_invoice_number']) ? $item['cf_proforma_invoice_number'] : 'N/A' ?></td>
            <td><?= !empty($item['cf_payment_amount']) ? $item['cf_payment_amount'] : 'N/A' ?></td>
            <td><?= !empty($item['cf_payment_due_date']) ? $item['cf_payment_due_date'] : 'N/A' ?></td>
            <td data-value="<?= !empty($item['module_record_id']) ? $item['module_record_id'] : '' ?>"><?= !empty($item['cf_po_num']) ? $item['cf_po_num'] : 'N/A' ?></td>
            <td><?= !empty($item['cf_vendor_formatted']) ? $item['cf_vendor_formatted'] : 'N/A' ?></td>
            <td>
            <select class="form-select scm-status" data-record-id="<?= $item['module_record_id'] ?>" name="scm_head_status">
              <option value="" <?= empty($item['cf_scm_head_approval_status']) ? 'selected' : '' ?> disabled>Select Status</option>
              <option value="Approve" <?= $item['cf_scm_head_approval_status'] == 'Approve' ? 'selected' : '' ?>>Approve</option>
              <option value="Reject" <?= $item['cf_scm_head_approval_status'] == 'Reject' ? 'selected' : '' ?>>Reject</option>
            </select>
            </td>

            <!-- Finance Head Approval Status Dropdown -->
            <td>
              <select class="form-select finance-status" data-record-id="<?= $item['module_record_id'] ?>" name="finance_head_status">
              <option value="" <?= empty($item['cf_finance_head_approval_statu']) ? 'selected' : '' ?> disabled>Select Status</option>
                <option value="Approve" <?= $item['cf_finance_head_approval_statu'] == 'Approve' ? 'selected' : '' ?>>Approve</option>
                <option value="Reject" <?= $item['cf_finance_head_approval_statu'] == 'Reject' ? 'selected' : '' ?>>Reject</option>
              </select>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <style>
      tr:hover {
        cursor: pointer;
      }
    </style>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>

  <script>
    $(document).ready(function() {

      const currentDate_Time = new Date();
      const year = currentDate_Time.getFullYear();
      const month = String(currentDate_Time.getMonth() + 1).padStart(2, '0'); // Months are 0-based
      const day = String(currentDate_Time.getDate()).padStart(2, '0');
      const hours = String(currentDate_Time.getHours()).padStart(2, '0');
      const minutes = String(currentDate_Time.getMinutes()).padStart(2, '0');
      const seconds = String(currentDate_Time.getSeconds()).padStart(2, '0');
      const current_D_T = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

      $('#table tbody').on('click', 'td', function(event) {
        const $row = $(this).closest('tr');
        const columnIndex = $(this).index();
        const record = $row.data('record');
        console.log("Clicked record:", JSON.stringify(record));

        const params = new URLSearchParams(record).toString();
        if (columnIndex < 4) {
                  
          window.location.href = `statusScreen.php?${params}`;
        } else if (columnIndex === 4) {
          const param = new URLSearchParams(record);
          const po_Id = param.get("cf_purchase_order");
     console.log("po_Id",po_Id);
          window.location.href = `https://inventory.zoho.in/app/60006170914#/purchaseorders/${po_Id}`;
        }
      });

      // let scmStatusTableData = [];

      $(document).on('change', '.scm-status', function() {
        let pi_Id = $(this).data('record-id');
        let newStatus = $(this).val();
        scmForm = new FormData();
        scmForm.append("cf_scm_head_approval_status", newStatus);
        scmForm.append("pi_Id", pi_Id);

        if (pi_Id) {
          $.ajax({
            url: 'fetchPiDetails.php',
            type: 'GET',
            data: {
              module_record_id: pi_Id
            },
            success: function(response) {
              const piData = JSON.parse(response);
              const moduleRecord = JSON.parse(piData).module_record.module_fields;
              const result = moduleRecord.find(item => item.api_name === "cf_cm_approve_status_history").table_values;

              scmStatusTableData = [];
              result.forEach(innerArray => {
                let recordMap = {};
                innerArray.forEach(field => {
                  switch (field.api_name) {
                    case 'cf_date':
                      recordMap['cf_date'] = field.value || "N/A";
                      break;
                    case 'cf_scm_head_status':
                      recordMap['cf_scm_head_status'] = field.value || "N/A";
                      break;
                    case 'cf_finance_head_status':
                      recordMap['cf_finance_head_status'] = field.value || "N/A";
                      break;
                    case 'cf_comments':
                      recordMap['cf_comments'] = field.value || "N/A";
                      break;
                    default:
                      break;
                  }
                });

                scmStatusTableData.push(recordMap);
              });
              console.log(current_D_T);

              // Add the new history record
              let newHistory = {
                'cf_date': current_D_T,
                'cf_scm_head_status': "SCM Head",
                'cf_finance_head_status': newStatus
              };

              scmStatusTableData.push(newHistory);
              console.log("scmStatusTableData--------------->", scmStatusTableData);

              // Append the updated scmStatusTableData to the FormData
              scmForm.append('cf_cm_approve_status_history', JSON.stringify(scmStatusTableData));

              // Call the AJAX POST request to update the data in the backend
              $.ajax({
                url: 'updatePi.php',
                type: 'POST',
                data: scmForm,
                processData: false,
                contentType: false,
                success: function(response) {
                  console.log("Update response: ", response);
                },
                error: function(xhr, status, error) {
                  alert("Failed to update details. Please try again.");
                }
              });
            },
            error: function(xhr, status, error) {
              alert("Failed to fetch details. Please try again.");
            }
          });
        }
      });




      // let scmStatusTableData = [];
      // $(document).on('change', '.scm-status', function() {
      //   let pi_Id = $(this).data('record-id');

      //   scmForm = new FormData();

      //   if (pi_Id) {
      //     $.ajax({
      //       url: 'fetchPiDetails.php',
      //       type: 'GET',
      //       data: {
      //         module_record_id: pi_Id
      //       },
      //       success: function(response) {
      //         const piData = JSON.parse(response);
      //         const moduleRecord = JSON.parse(piData).module_record.module_fields;
      //         const result = moduleRecord.find(item => item.api_name === "cf_cm_approve_status_history").table_values;

      //         result.forEach(innerArray => {
      //           let recordMap = {};
      //           innerArray.forEach(field => {
      //             switch (field.api_name) {
      //               case 'cf_date':
      //                 recordMap['cf_date'] = field.value || "N/A";
      //                 break;
      //               case 'cf_scm_head_status':
      //                 recordMap['cf_scm_head_status'] = field.value || "N/A";
      //                 break;
      //               case 'cf_finance_head_status':
      //                 recordMap['cf_finance_head_status'] = field.value || "N/A";
      //                 break;
      //               case 'cf_comments':
      //                 recordMap['cf_comments'] = field.value || "N/A";
      //                 break;
      //               default:
      //                 break;
      //             }
      //           });

      //           scmStatusTableData.push(recordMap);
      //         });

      //       },
      //       error: function(xhr, status, error) {
      //         alert("Failed to fetch details. Please try again.");
      //       }
      //     });
      //   }

      //   let newStatus = $(this).val();

      //   // scmForm.append("cf_scm_head_approval_status", newStatus);
      //   scmForm.append("pi_Id", pi_Id);

      //   let newHistory = {
      //     'cf_date': current_D_T,
      //     'cf_scm_head_status': "SCM Head",
      //     'cf_finance_head_status': newStatus
      //   };

      //   scmStatusTableData.push(newHistory);
      //   scmForm.append('cf_cm_approve_status_history', JSON.stringify(scmStatusTableData));

      //   $.ajax({
      //     url: 'updatePi.php',
      //     type: 'POST',
      //     data: scmForm,
      //     processData: false,
      //     contentType: false,
      //     success: function(response) {

      //       const alertHTML = `
      //             <div style="margin-top:35px;" class="alert alert-success alert-dismissible fade show" role="alert">
      //               <strong>PI Status Updated!</strong>
      //               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      //                   <span aria-hidden="true">&times;</span>
      //               </button>
      //             </div>
      //             `;
      //       $('body').prepend(alertHTML);
      //       setTimeout(function() {
      //         $('.alert').alert('close');
      //       }, 4000);
      //     },
      //     error: function(xhr, status, error) {
      //       console.error('Error:', error);
      //     }
      //   });
      // });
      // let fcmStatusTableData = [];
      $(document).on('change', '.finance-status', function() {

        let pi_Id = $(this).data('record-id');
        let newStatus = $(this).val();

        financeForm = new FormData();
        financeForm.append("cf_finance_head_approval_statu", newStatus);
        financeForm.append("pi_Id", pi_Id);

     
        if (pi_Id) {
          $.ajax({
            url: 'fetchPiDetails.php',
            type: 'GET',
            data: {
              module_record_id: pi_Id
            },
            success: function(response) {
              const piData = JSON.parse(response);
              const moduleRecord = JSON.parse(piData).module_record.module_fields;
              const result = moduleRecord.find(item => item.api_name === "cf_cm_approve_status_history").table_values;

              financeStatusTableData = [];
              result.forEach(innerArray => {
                let recordMap = {};
                innerArray.forEach(field => {
                  switch (field.api_name) {
                    case 'cf_date':
                      recordMap['cf_date'] = field.value || "N/A";
                      break;
                    case 'cf_scm_head_status':
                      recordMap['cf_scm_head_status'] = field.value || "N/A";
                      break;
                    case 'cf_finance_head_status':
                      recordMap['cf_finance_head_status'] = field.value || "N/A";
                      break;
                    case 'cf_comments':
                      recordMap['cf_comments'] = field.value || "N/A";
                      break;
                    default:
                      break;
                  }
                });

                financeStatusTableData.push(recordMap);
              });
              console.log(current_D_T);

              // Add the new history record
              let newHistory = {
                'cf_date': current_D_T,
                'cf_scm_head_status': "Finance Head",
                'cf_finance_head_status': newStatus
              };

              financeStatusTableData.push(newHistory);
              console.log("scmStatusTableData--------------->", financeStatusTableData);

              // Append the updated scmStatusTableData to the FormData
              financeForm.append('cf_cm_approve_status_history', JSON.stringify(financeStatusTableData));

              // Call the AJAX POST request to update the data in the backend
              $.ajax({
                url: 'updatePi.php',
                type: 'POST',
                data: financeForm,
                processData: false,
                contentType: false,
                success: function(response) {
                  console.log("Update response: ", response);
                },
                error: function(xhr, status, error) {
                  alert("Failed to update details. Please try again.");
                }
              });
            },
            error: function(xhr, status, error) {
              alert("Failed to fetch details. Please try again.");
            }
          });
        }



      });

    });

    $(function() {
      $('#table').bootstrapTable({
        onCheck: function() {
          $('#remove').prop('disabled', false);
        },
        onUncheck: function() {
          if (!$('#table').bootstrapTable('getSelections').length) {
            $('#remove').prop('disabled', true);
          }
        }
      });
    });
  </script>
</body>

</html>