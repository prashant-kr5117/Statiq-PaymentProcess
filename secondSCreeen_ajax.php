<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proforma Invoice Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.3/dist/bootstrap-table.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.3/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.21.3/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Proforma Invoice Details</h2>
        <table id="piTable" class="table table-bordered"
               data-toggle="table"
               data-search="true"
               data-filter-control="true"
               data-show-search-clear-button="true"
               data-pagination="true">
            <thead class="table-gray">
                <tr>
                    <th>#</th>
                    <th data-field="invoice" data-filter-control="input">Proforma Invoice Number</th>
                    <th data-field="amount" data-filter-control="input">Payment Amount</th>
                    <th data-field="due_date" data-filter-control="input">Payment Due Date</th>
                    <th data-field="po_number" data-filter-control="input">PO Number</th>
                    <th data-field="vendor" data-filter-control="input">Vendor</th>
                    <th data-field="scm_status" data-filter-control="select">SCM Head Approval Status</th>
                    <th data-field="finance_status" data-filter-control="select">Finance Head Approval Status</th>
                </tr>
            </thead>
            <tbody id="piTableBody"></tbody>
        </table>
    </div>

    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Status Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to update the status?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmUpdate">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            fetchPIData();

            let selectedRecordId = "";
            let selectedField = "";
            let selectedStatus = "";

            function fetchPIData() {
                $.ajax({
                    url: 'get_pi_data.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        populateTable(response);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            }

            function populateTable(data) {
                let tableBody = $('#piTableBody');
                tableBody.empty();

                data.forEach((item, index) => {
                    let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.cf_proforma_invoice_number || 'N/A'}</td>
                            <td>${item.cf_payment_amount || 'N/A'}</td>
                            <td>${item.cf_payment_due_date || 'N/A'}</td>
                            <td>${item.cf_po_num || 'N/A'}</td> 
                            <td>${item.cf_vendor_formatted || 'N/A'}</td>
                            <td>
                                <select class="form-select status-dropdown scm-status" data-record-id="${item.module_record_id}">
                                    ${item.cf_scm_head_approval_status 
                                        ? `<option value="${item.cf_scm_head_approval_status}" selected>${item.cf_scm_head_approval_status}</option>` 
                                        : '<option value="" disabled selected>Select Status</option>'
                                    }
                                    ${item.cf_scm_head_approval_status !== 'Approve' ? '<option value="Approve">Approve</option>' : ''}
                                </select>
                            </td>
                            <td>
                                <select class="form-select status-dropdown finance-status" data-record-id="${item.module_record_id}">
                                    ${item.cf_finance_head_approval_statu 
                                        ? `<option value="${item.cf_finance_head_approval_statu}" selected>${item.cf_finance_head_approval_statu}</option>` 
                                        : '<option value="" disabled selected>Select Status</option>'
                                    }
                                    ${item.cf_finance_head_approval_statu !== 'Approve' ? '<option value="Approve">Approve</option>' : ''}
                                </select>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });

                $('#piTable').bootstrapTable();
                $('#piTable').bootstrapTable('refreshOptions', { filterControl: true });

                // Attach event listener
                $('.status-dropdown').change(function () {
                    selectedRecordId = $(this).data('record-id');
                    selectedStatus = $(this).val();
                    selectedField = $(this).hasClass('scm-status') ? 'cf_scm_head_approval_status' : 'cf_finance_head_approval_statu';
                    $('#confirmModal').modal('show');
                });
            }

            $('#confirmUpdate').click(function () {
                $('#confirmModal').modal('hide');
                updateStatus(selectedRecordId, selectedField, selectedStatus);
            });

            function updateStatus(recordId, field, status) {
                $.ajax({
                    url: 'update_status.php',
                    type: 'POST',
                    data: { record_id: recordId, field: field, status: status },
                    success: function (response) {
                        console.log("Status updated successfully", response);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error updating status:", error);
                    }
                });
            }
        });
    </script>
</body>
</html>
