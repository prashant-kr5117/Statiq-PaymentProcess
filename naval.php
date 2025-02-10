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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            padding: 5px;
            margin-bottom: 5px;
        }
        select {
            width: 100%;
            padding: 5px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            margin: 15% auto;
            width: 30%;
            border-radius: 5px;
        }
        .modal-footer {
            display: flex;
            justify-content: space-between;
        }
        .btn {
            padding: 8px 12px;
            cursor: pointer;
        }
        .btn-primary {
            background-color: blue;
            color: white;
            border: none;
        }
        .btn-secondary {
            background-color: gray;
            color: white;
            border: none;
        }
    </style>
</head>

<body>
    <h1 style="text-align:center;">PI Records</h1>
    <table id="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Proforma Invoice Number <input type="text" class="search" data-column="1"></th>
                <th>Amount <input type="text" class="search" data-column="2"></th>
                <th>Due Date <input type="text" class="search" data-column="3"></th>
                <th>Purchase Order <input type="text" class="search" data-column="4"></th>
                <th>Vendor <input type="text" class="search" data-column="5"></th>
                <th>SCM Status 
    <input type="text" class="search" data-column="6">
    <select class="form-select scm-status-filter">
        <option value="">Select Status</option>
        <option value="Approve">Approve</option>
        <option value="Reject">Reject</option>
        <option value="Pending">Pending</option>
    </select>
</th>
                <th>Finance Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($AllPI as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $item['cf_proforma_invoice_number'] ?? 'N/A' ?></td>
                    <td><?= $item['cf_payment_amount'] ?? 'N/A' ?></td>
                    <td><?= $item['cf_payment_due_date'] ?? 'N/A' ?></td>
                    <td><?= $item['cf_po_num'] ?? 'N/A' ?></td>
                    <td><?= $item['cf_vendor_formatted'] ?? 'N/A' ?></td>
                    <td>
                        <select class="form-select scm-status"
                            data-record-id="<?= htmlspecialchars($item['module_record_id']) ?>" name="scm_head_status">
                            <?php if (trim($item['cf_scm_head_approval_status']) === 'Approve'): ?>
                            <option value="Approve" selected>Approve</option>
                            <?php else: ?>
                            <option value="" selected disabled>
                                <?= !empty($item['cf_scm_head_approval_status']) ? htmlspecialchars($item['cf_scm_head_approval_status']) : 'Select Status' ?>
                            </option>
                            <?php 
                                if (trim($item['cf_scm_head_approval_status']) == 'Reject' || trim($item['cf_scm_head_approval_status']) == 'Pending') { ?>
                                    <option value="Approve">Approve</option>
                            <?php    }
                            ?>
                           <?php endif; ?>
                        </select>
                </td> 
                    <td>
                        <select class="finance-status">
                            <option value="Approve" <?= ($item['cf_finance_head_approval_statu'] === 'Approve') ? 'selected' : '' ?>>Approve</option>
                            <option value="Reject">Reject</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div id="exampleModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to approve this?</p>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="Neglect">No</button>
                <button class="btn btn-primary" id="confirmApprove">Yes</button>
            </div>
        </div>
    </div>

    <script>
      $(document).ready(function() {
    // Search functionality for table columns
    $(".search").on("keyup", function() {
        let column = $(this).data("column");
        let value = $(this).val().toLowerCase();
        $("#table tbody tr").filter(function() {
            $(this).toggle($(this).find("td").eq(column).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Filter table rows based on SCM Status dropdown selection
    $(".scm-status-filter").on("change", function() {
        let selectedStatus = $(this).val().toLowerCase();

        $("#table tbody tr").each(function() {
            let rowStatus = $(this).find("td select.scm-status").val().toLowerCase();
            
            if (selectedStatus === "" || rowStatus === selectedStatus) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Handle status change and modal functionality
    $(document).on("change", ".scm-status, .finance-status", function() {
        let selectedField = $(this);
        let newStatus = selectedField.val();
        
        if (newStatus === "Approve") {
            $("#exampleModal").fadeIn();
            $("#confirmApprove").off().on("click", function() {
                $("#exampleModal").fadeOut();
                alert("Status Updated Successfully");
            });
        }
    });

    // Close modal on 'Neglect' button click
    $("#Neglect").on("click", function() {
        $("#exampleModal").fadeOut();
    });
});

    </script>
</body>
</html>
