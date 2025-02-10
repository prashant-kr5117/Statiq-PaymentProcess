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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <h1 class="text-center mb-3">PI Records</h1>
        <table id="table" class="table table-striped" data-toolbar="#toolbar" data-search="true"
            data-show-refresh="true" data-show-toggle="true" data-show-columns="true" data-pagination="true"
            data-page-list="[10, 25, 50, 100, all]" data-show-export="true" data-click-to-select="true"
            data-side-pagination="client">
            <thead>
    <tr>
        <th data-field="index" data-sortable="true">#</th>
        <th data-field="pi_number" data-sortable="true" data-filter-control="input">Proforma Invoice Number</th>
        <th data-field="amount" data-sortable="true" data-filter-control="input">Amount</th>
        <th data-field="due_date" data-sortable="true" data-filter-control="input">Due Date</th>
        <th data-field="purchase_order" data-sortable="true" data-filter-control="input">Purchase Order</th>
        <th data-field="cf_vendor_formatted" data-sortable="true" data-filter-control="input">Vendor</th>
        <th data-field="scm-status" data-sortable="false" data-filter-control="select">SCM Status</th>
        <th data-field="finance-status" data-sortable="false" data-filter-control="select">Finance Status</th>
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

                              'cf_payment_due_date_formatted' =>  $item['cf_payment_due_date_formatted'],
                              'cf_scm_head_approval_status' => $item['cf_scm_head_approval_status'],
                              'cf_finance_head_approval_statu' => $item['cf_finance_head_approval_statu'],
                              'cf_proforma_invoice_number' => $item['cf_proforma_invoice_number'],
                              'cf_vendor_formatted' => $item['cf_vendor_formatted'],

                            ], JSON_HEX_APOS | JSON_HEX_QUOT); ?>'>

                            <td><?= $index + 1 ?></td>
                            <td><?= !empty($item['cf_proforma_invoice_number']) ? $item['cf_proforma_invoice_number'] : 'N/A' ?>
                            </td>
                            <td><?= !empty($item['cf_payment_amount']) ? $item['cf_payment_amount'] : 'N/A' ?></td>
                            <td><?= !empty($item['cf_payment_due_date']) ? $item['cf_payment_due_date'] : 'N/A' ?></td>
                            <td data-value="<?= !empty($item['module_record_id']) ? $item['module_record_id'] : '' ?>">
                                <?= !empty($item['cf_po_num']) ? $item['cf_po_num'] : 'N/A' ?></td>
                            <td><?= !empty($item['cf_vendor_formatted']) ? $item['cf_vendor_formatted'] : 'N/A' ?></td>

                    <td>
                        <select class="form-select scm-status" data-record-id="<?= $item['module_record_id'] ?>"
                            name="scm_head_status">
                            <option value="" selected disabled>
                                <?= !empty($item['cf_scm_head_approval_status']) && $item['cf_scm_head_approval_status'] !== 'Approve' 
                ? htmlspecialchars($item['cf_scm_head_approval_status']) 
                : 'Select Status' ?>
                            </option>
                            <option value="Approve"
                                <?= $item['cf_scm_head_approval_status'] === 'Approve' ? 'selected' : '' ?>>Approve
                            </option>
                        </select>
                    </td>
                    <td>
                        <select class="form-select finance-status" data-record-id="<?= $item['module_record_id'] ?>"
                            name="finance_head_status">
                            <option value="" selected disabled>
                                <?= !empty($item['cf_finance_head_approval_statu']) && $item['cf_finance_head_approval_statu'] !== 'Approve' 
                ? htmlspecialchars($item['cf_finance_head_approval_statu']) 
                : 'Select Status' ?>
                            </option>
                            <option value="Approve"
                                <?= $item['cf_finance_head_approval_statu'] === 'Approve' ? 'selected' : '' ?>>Approve
                            </option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>


                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">


                            </div>
                            <div class="modal-body">
                                Are you sure you want to approve this?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" id="Neglect"
                                    data-dismiss="modal">No</button>
                                <button type="button" class="btn btn-primary" id="confirmApprove">Yes</button>
                            </div>
                        </div>
                    </div>
                </div>
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
                window.location.href =
                    `https://inventory.zoho.in/app/60006170914#/purchaseorders/${po_Id}`;
            }
        });

        let selectedField, approvalType, pi_Id, newStatus;
        let scmForm, financeForm;

        // SCM Status Change Event
        $(document).on('change', '.scm-status', function() {
            handleApproval($(this), 'SCM');
        });

        // Finance Status Change Event
        $(document).on('change', '.finance-status', function() {
            handleApproval($(this), 'Finance');
        });

        function handleApproval(element, type) {
            selectedField = element;
            approvalType = type;
            newStatus = element.val();
            pi_Id = element.data('record-id');

            if (newStatus === "Approve") {
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

    });

    $(function() {
        $('#table').bootstrapTable({x
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