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
  <title>PI Table</title>
  <link rel="icon" href="statiq_logo.jpg" type="image/icon type">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.css">
  <style>
    /* Optional: Adjust the width of the filter dropdowns */
    #toolbar select.form-control {
      width: auto;
      display: inline-block;
    }
  </style>
</head>
<body>
  <div class="container mt-4">
    <h1 class="text-center mb-3">PI Records</h1>
    
    <!-- Toolbar for Bootstrap Table (includes search bar) -->
    <div id="toolbar" class="d-flex align-items-center mb-3">
      <div class="me-3">
        <label for="scmFilter" class="me-1"><strong>SCM Status:</strong></label>
        <select id="scmFilter" class="form-control">
          <!-- An empty value means All -->
          <option value="">All</option>
          <option value="select status">Select Status</option>
          <option value="Approve">Approve</option>
          <option value="Reject">Reject</option>
          <option value="Pending">Pending</option>
        </select>
      </div>
      <div>
        <label for="financeFilter" class="me-1"><strong>Finance Status:</strong></label>
        <select id="financeFilter" class="form-control">
          <option value="">All</option>
          <option value="select status">Select Status</option>
          <option value="Approve">Approve</option>
          <option value="Reject">Reject</option>
          <option value="Pending">Pending</option>
        </select>
      </div>
    </div>

    <!-- Table (built-in filter control removed for SCM and Finance columns) -->
    <table id="table" class="table table-striped" data-toolbar="#toolbar" data-search="true"
           data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-pagination="true"
           data-page-list="[10, 25, 50, 100, all]" data-show-export="true" data-click-to-select="true"
           data-side-pagination="client" data-filter-control="true" data-show-search-clear-button="true">
      <thead>
        <tr>
          <th data-field="index" data-sortable="true">#</th>
          <th data-field="pi_number" data-sortable="true" data-filter-control="input">Proforma Invoice Number</th>
          <th data-field="amount" data-sortable="true" data-filter-control="input">Amount</th>
          <th data-field="due_date" data-sortable="true" data-filter-control="input">Due Date</th>
          <th data-field="purchase_order" data-sortable="true" data-filter-control="input">Purchase Order</th>
          <th data-field="cf_vendor_formatted" data-sortable="true" data-filter-control="input">Vendor</th>
          <!-- We remove built-in filtering for these two columns -->
          <th data-field="scm-status" data-sortable="true">SCM Status</th>
          <th data-field="finance-status" data-sortable="true">Finance Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($AllPI as $index => $item): 

            $scm = trim($item['cf_scm_head_approval_status']);
            if($scm === 'Approve'){
              $scmOptionVal = "Approve";
              $scmDisplay = "Approve";
            } else {
              if(empty($scm)){
                $scmOptionVal = "select status";
                $scmDisplay = "Select Status";
              } else {
                $scmOptionVal = strtolower($scm);
                $scmDisplay = htmlspecialchars($scm);
              }
            }
            // Prepare Finance status value and display text:
            $finance = trim($item['cf_finance_head_approval_statu']);
            if($finance === 'Approve'){
              $financeOptionVal = "Approve";
              $financeDisplay = "Approve";
            } else {
              if(empty($finance)){
                $financeOptionVal = "select status";
                $financeDisplay = "Select Status";
              } else {
                $financeOptionVal = strtolower($finance);
                $financeDisplay = htmlspecialchars($finance);
              }
            }
        ?>
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
          <td><?= !empty($item['cf_po_num']) ? $item['cf_po_num'] : 'N/A' ?></td>
          <td><?= !empty($item['cf_vendor_formatted']) ? $item['cf_vendor_formatted'] : 'N/A' ?></td>
          <!-- SCM Status column -->
          <td>
            <?php if($scm === 'Approve'): ?>
              <select class="form-select scm-status" data-record-id="<?= htmlspecialchars($item['module_record_id']) ?>" name="scm_head_status">
                <option value="Approve" selected>Approve</option>
              </select>
            <?php else: ?>
              <select class="form-select scm-status" data-record-id="<?= htmlspecialchars($item['module_record_id']) ?>" name="scm_head_status">
                <option value="<?= $scmOptionVal ?>" selected disabled><?= $scmDisplay ?></option>
                <option value="Approve">Approve</option>
              </select>
            <?php endif; ?>
          </td>
          <!-- Finance Status column -->
          <td>
            <?php if($finance === 'Approve'): ?>
              <select class="form-select finance-status" data-record-id="<?= htmlspecialchars($item['module_record_id']) ?>" name="finance_head_status">
                <option value="Approve" selected>Approve</option>
              </select>
            <?php else: ?>
              <select class="form-select finance-status" data-record-id="<?= htmlspecialchars($item['module_record_id']) ?>" name="finance_head_status">
                <option value="<?= $financeOptionVal ?>" selected disabled><?= $financeDisplay ?></option>
                <option value="Approve">Approve</option>
              </select>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>


  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
       aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Are you sure you want to approve this?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="Neglect" data-dismiss="modal">No</button>
          <button type="button" class="btn btn-primary" id="confirmApprove">Yes</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts: Bootstrap, jQuery, Bootstrap-Table, and Custom Filtering & Update Logic -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/bootstrap-table.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.24.0/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
  
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
          console.log("param",param);
          
          const po_Id = param.get("cf_purchase_order");
     console.log("po_Id",po_Id);
          window.location.href = `https://inventory.zoho.in/app/60006170914#/purchaseorders/${po_Id}`;
        }
      });

        let selectedField, approvalType, pi_Id, newStatus;
        let scmForm, financeForm;

        $(document).on('change', '.scm-status', function() {
            handleApproval($(this), 'SCM');
        });

        $(document).on('change', '.finance-status', function() {
            handleApproval($(this), 'Finance');
        });

        function handleApproval(element, type) {
            selectedField = element;
            approvalType = type;
            newStatus = element.val();
            pi_Id = element.data('record-id');
            console.log("-------------------->",newStatus);

            if (newStatus === "Approve" || newStatus === "approve") {
            console.log("SCM or Finance status changed ");
            
                $('#exampleModal').modal('show');
            } else {
                console.log(`${type} status changed to ${newStatus}, no confirmation required.`);
            }
        }

        $('#Neglect').click(function() {
            $('#exampleModal').modal('hide');

        });

        $('#confirmApprove').click(function() {
            $('#exampleModal').modal('hide');

            if (pi_Id) {
                if (approvalType === 'SCM') {
                    scmForm = new FormData();
                    scmForm.append("cf_scm_head_approval_status", newStatus);
                    scmForm.append("pi_Id", pi_Id);
                    processApproval(scmForm, 'SCM');
                } else if (approvalType === 'Finance') {
                    financeForm = new FormData();
                    financeForm.append("cf_finance_head_approval_statu", newStatus);
                    financeForm.append("pi_Id", pi_Id);
                    processApproval(financeForm, 'Finance');
                }
            }
        });

        function processApproval(formData, type) {
            $.ajax({
                url: 'fetchPiDetails.php',
                type: 'GET',
                data: {
                    module_record_id: pi_Id
                },
                success: function(response) {
                    const piData = JSON.parse(response);
                    const moduleRecord = JSON.parse(piData).module_record.module_fields;
                    const result = moduleRecord.find(item => item.api_name ===
                        "cf_cm_approve_status_history");

                    let statusTableData = [];

                    if (result && Array.isArray(result.table_values)) {
                        result.table_values.forEach(innerArray => {
                            let recordMap = {};
                            innerArray.forEach(field => {
                                switch (field.api_name) {
                                    case 'cf_date':
                                        recordMap['cf_date'] = field.value || "N/A";
                                        break;
                                    case 'cf_scm_head_status':
                                        recordMap['cf_scm_head_status'] = field
                                            .value || "N/A";
                                        break;
                                    case 'cf_finance_head_status':
                                        recordMap['cf_finance_head_status'] = field
                                            .value || "N/A";
                                        break;
                                    case 'cf_comments':
                                        recordMap['cf_comments'] = field.value ||
                                            "N/A";
                                        break;
                                    default:
                                        break;
                                }
                            });
                            statusTableData.push(recordMap);
                        });
                    }

                    let current_D_T = new Date().toISOString(); // Current Date and Time
                    let newHistory = {
                        'cf_date': current_D_T,
                        'cf_scm_head_status': type === 'SCM' ? "SCM Head" : "N/A",
                        'cf_finance_head_status': type === 'Finance' ? "Finance Head" : "N/A"
                    };

                    statusTableData.push(newHistory);
                    formData.append('cf_cm_approve_status_history', JSON.stringify(
                    statusTableData));

                    $.ajax({
                        url: 'updatePi.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            const alertHTML = `
              <div style="position: fixed; top: 20px; right: 20px; z-index: 9999;" 
                  class="alert alert-success alert-dismissible fade show" 
                  role="alert">
                  <div class="d-flex align-items-center">
                      <strong>${type} Status Updated successfully!</strong>
                      <button type="button" class="close ml-2" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
              </div>
            `;
                            $('body').prepend(alertHTML);
                            setTimeout(function() {
                                $('.alert').alert('close');
                            }, 2000);
                        },
                        error: function(xhr, status, error) {
                            alert(
                            `Failed to update ${type} details. Please try again.`);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    alert(`Failed to fetch ${type} details. Please try again.`);
                }
            });
        }






      // Initialize Bootstrap Table for other columns
      $('#table').bootstrapTable({
          filterControl: true,
          pagination: true,
          search: true,
          sortable: true
      });

   


      // --- Update Functionality with Modal Confirmation ---
    //   $(document).on('change', '.scm-status, .finance-status', function() {
    //       let selectedField = $(this);
    //       let newStatus = selectedField.val(); // expected to be "approve"
    //       let pi_Id = selectedField.closest('tr').data('record-id');
    //       let type = selectedField.hasClass('scm-status') ? 'SCM' : 'Finance';
          
    //       if(newStatus === "approve") {
    //           $('#exampleModal').modal('show');
    //           $('#confirmApprove').off().on('click', function() {
    //               updateStatus(pi_Id, newStatus, type);
    //               $('#exampleModal').modal('hide');
    //           });
    //       } else {
    //           updateStatus(pi_Id, newStatus, type);
    //       }
    //   });
      
      // --- Custom Filtering Functionality for SCM and Finance Columns ---
      function applyFilters() {
          // Get the filter values (empty string means "All")
          let scmFilterValue = $('#scmFilter').val().trim().toLowerCase();
          let financeFilterValue = $('#financeFilter').val().trim().toLowerCase();
          
          $('#table tbody tr').each(function() {
              // Retrieve the value attribute of the selected option from each dropdown.
              let rowSCMStatus = $(this).find('.scm-status option:selected').attr('value') || "";
              let rowFinanceStatus = $(this).find('.finance-status option:selected').attr('value') || "";
              rowSCMStatus = rowSCMStatus.trim().toLowerCase();
              rowFinanceStatus = rowFinanceStatus.trim().toLowerCase();
              
              // Check if the row matches the filter:
              let scmMatch = (scmFilterValue === "" || rowSCMStatus === scmFilterValue);
              let financeMatch = (financeFilterValue === "" || rowFinanceStatus === financeFilterValue);
              
              // Show the row only if both filters match.
              $(this).toggle(scmMatch && financeMatch);
          });
      }
      
      // Bind the filtering function to changes on the filter dropdowns.
      $('#scmFilter, #financeFilter').on('change', applyFilters);
  });
  
  // --- AJAX Function to Update Status ---
  function updateStatus(pi_Id, status, type) {
      $.ajax({
          url: 'updatePi.php',
          type: 'POST',
          data: { pi_Id, status, type },
          success: function() {
              alert(type + " Status Updated Successfully");
              // Optionally, refresh table data if needed.
          },
          error: function() {
              alert("Failed to update " + type + " status.");
          }
      });
  }
  </script>
</body>
</html>
