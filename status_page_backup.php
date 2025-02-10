<?php
require 'zoho/ZohoInventory.php';
$moduleRecordId = isset($_GET['module_record_id']) ? htmlspecialchars($_GET['module_record_id']) : '';

$date = isset($_GET['cf_date']) ? htmlspecialchars($_GET['cf_date']) : '';
$amount = isset($_GET['cf_amount_to_be_paid']) ? htmlspecialchars($_GET['cf_amount_to_be_paid']) : '';
$approvalStatus = isset($_GET['cf_approval_status']) ? htmlspecialchars($_GET['cf_approval_status']) : '';

$zohoinvetory_obj = new ZohoInventory;
$jsonData = $zohoinvetory_obj->getLoggedInUser();
$data = json_decode($jsonData, true);
$user_id = $data['user']['user_id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="index.css">
  <link rel="icon" href="statiq_logo.jpg" type="image/icon type">
  <title>PI Status</title>
  <script src="./index.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <style>
    select.form-select {
      position: absolute;
      appearance: none;
      padding: 10px;
      border: 2px solid #007BFF;
      border-radius: 8px;
      background-color: #f9f9f9;
      font-size: 16px;
      color: #333;
      width: 100%;
      outline: none;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .form-select {
      max-width: 100%;

      border-radius: 5px;
    }

    select.form-select:focus {
      border-color: #0056b3;
      box-shadow: 0 0 5px rgba(0, 91, 187, 0.5);
    }

    select.form-select option {
      background: #ffffff;
      color: #333;
      font-size: 16px;
      padding: 10px;
    }

    select.form-select:hover {
      background-color: #e6f0ff;
    }

    .status-pending {
      background-color: #5c3a07;
      /* Background color for Pending */
      color: #ffb300;
      /* Text color for Pending */
      padding: 5px 10px;
      border-radius: 12px;
      font-weight: bold;
      display: inline-block;
    }

    .status-approve {
      background-color: #0a5c07;
      /* Background color for Approve */
      color: #00ff00;
      /* Text color for Approve */
      padding: 5px 10px;
      border-radius: 12px;
      font-weight: bold;
      display: inline-block;
    }

    .status-reject {
      background-color: #5c0707;
      /* Background color for Reject */
      color: #ff4c4c;
      /* Text color for Reject */
      padding: 5px 10px;
      border-radius: 12px;
      font-weight: bold;
      display: inline-block;
    }
  </style>

</head>

<body>
  <div class="container">
    <input type="hidden" id="loggedInUserId" value="<?= htmlspecialchars($user_id) ?>">
    <div class="form-container">
      <div id="alert-container"></div>
      <form action="" method="post" id="formID">
        <div class="form-row">
          <!-- <div class="form-group col-md-6"> -->
          <label for="attachPi">Attach PI:</label>
          <div class="upload-btn-wrapper">
            <button class="upload-btn" id="uploadButton">Document Upload &#8682;</button>
            <input type="file" id="cf_attachment" name="cf_attachment">
          </div>
          <!-- </div> -->

        </div>

        <div class="form-row">
          <div class="form-group col-md-6 ">
            <label for="piNumber" data-toggle="tooltip" data-placement="right" title="Tooltip on right">PI Record Number: </label>
            <input type="text" class="form-control div_styling" id="piNumber" placeholder="Enter PI Number" required style="height: 55px;">
          </div>

          <div class="form-group col-md-6 ">
            <label for="Purchase Order" data-toggle="tooltip" data-placement="right" title="Tooltip on right">Purchase Order: </label>
            <input type="text" class="form-control" id="purchaseOrder" placeholder="Enter PI Number" required style="height: 55px;">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="piNumber" data-toggle="tooltip" data-placement="right">Proforma Invoice Number: </label>
            <input type="text" class="form-control" id="cf_proforma_invoice_number" required>
          </div>
          <div class="form-group col-md-6">
            <label for="piDate">PI Date:</label>
            <input type="date" class="form-control" id="piDate" placeholder="mm/dd/yyyy" value="<?php echo $date; ?>"
              required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="piAmount">PI Amount:</label>
            <input type="text" class="form-control" id="piAmount" placeholder="Enter PI Amount"
              value="<?php echo $amount; ?>" required>
          </div>
          <div class="form-group col-md-6">
            <label for="paymentType">Payment Type:</label>
            <input type="text" class="form-control" id="paymentType" name="paymentType" placeholder="" required>
          </div>
        </div>


        <div class="form-row">
          <div class=" form-group col-md-6">
            <label for="paymentTerms">SCM Head Status:</label>
            <select class="form-control" id="cf_scm_head_approval_status" name="cf_scm_head_approval_status">
              <option value="Pending">Pending</option>
              <option value="Approve">Approve</option>
              <option value="Reject">Reject</option>
            </select>
          </div>

          <div class=" form-group col-md-6">
            <label for="paymentTerms"> Finance Head Status:</label>
            <select class="form-control" id="cf_finance_head_approval_statu" name="cf_finance_head_approval_statu">
              <option value="Pending">Pending</option>
              <option value="Approve">Approve</option>
              <option value="Reject">Reject</option>
            </select>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="paymentTerms">SCM Head Comment:</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="2"></textarea>

          </div>
          <div class="form-group col-md-6">
            <label for="paymentTerms">Finance Head Comment:</label>
            <textarea class="form-control" id="exampleFormControlTextare  a2" rows="2"></textarea>
          </div>
        </div>

        <div class="payment-Terms">
          <table class="table table-bordered align-middle text-center" id="paymentTermsTable">
            <thead class="table-light">
              <tr>
                <th style="width: 70px;">#</th>
                <th style="width: 100px;">Payment Type</th>
                <th style="width: 90px;">%</th>
                <th style="width: 60px;">CURRENCY</th>
                <th style="width: 100px;">Amount</th>
                <th style="width: 40px;">Days</th>
                <th style="width: 90px;">Due Date</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <!-- <button type="button" class="btn btn-secondary px-4 py-2 mx-3 my-2" id="updatePaymentTerms" data-toggle="modal"
          data-target="#exampleModalCenter">Update Payment Terms</button> -->

        <div class="d-flex justify-content-center align-items-center">
          <button type="submit" id="submitButton" class="btn btn-primary  w-40">Submit</button>
        </div>
      </form>

    </div>
    <h3 class="text-center mb-4">Approve History</h1>
      <div style="margin-bottom: 100px;">
        <table class="table table-bordered align-middle text-center" id="statusHistory">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>Profile</th>
              <th>Status</th>
              <th>Comments</th>
            </tr>
          </thead>
          <tbody id="statusHistoryBody">
          </tbody>
        </table>
      </div>
  </div>
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">PO Payment Terms</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <table class="table" id="paymentTermsTable">
            <thead class="thead-light">
              <tr>
                <th scope="col">Payment Type</th>
                <th scope="col">Amount</th>
                <th scope="col">Percentage</th>
                <th scope="col">Days</th>
                <th scope="col">Due Date</th>
              </tr>
            </thead>
            <tbody id="paymentTableBody">
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let po_module_record_id;
    let fetched_scm_head_approval_value;
    let fetched_finance_approval_value;
    // let vendor_id;
    // let vendor_name;
    // let vendor_payment_form = new FormData();
    
    let vendor_payment_form_array = [];
    $(document).ready(function() {

  
    //   $('#updatePaymentTerms').on('click', function() {
        
    //     if (po_module_record_id) {
    //       $.ajax({
    //         url: 'fetch_PO_details.php',
    //         type: 'GET',
    //         data: {
    //           module_record_id: po_module_record_id
    //         },
    //         success: function(response) {
    //           const poData = JSON.parse(response);
    //           const recordFieldHash = JSON.parse(poData).purchaseorder.custom_field_hash;
    //           grand_total = JSON.parse(poData).purchaseorder.total;
    

    //           for (const key in recordFieldHash) {
    //             if (key.endsWith('_unformatted')) {
    //               eval(`var ${key} = recordFieldHash[key];`); // Creates separate variables
    //             }
    //           }

    //           const tableBody = $("#paymentTableBody");
    //           tableBody.empty();
    //           const newRow = `
    //           <tr>
    //             <td>Advance</td>
    //             <td contenteditable="true" class="amount" >${cf_advance_payment_amount_unformatted}</td>
    //             <td contenteditable="true" class="percentage" >${cf_advance_payment_percent_unformatted}</td>
    //             <td contenteditable="true" class="days" >${cf_advance_payment_days_unformatted}</td>
    //             <td contenteditable="true"class="due-date">${cf_advance_payment_due_date_unformatted}</td>
    //           </tr>
    //           <tr>
    //             <td>Pre Delivery</td>
    //             <td contenteditable="true" class="amount" >${cf_pre_delivery_amount_unformatted}</td>
    //             <td contenteditable="true" class="percentage" >${cf_pre_delivery_percentage_unformatted}</td>
    //             <td contenteditable="true"class="days" >${cf_pre_delivery_days_unformatted}</td>
    //             <td contenteditable="true"class="due-date">${cf_pre_delivery_due_date_unformatted}</td>
    //           </tr>

    //           <tr>
    //             <td>Post Delivery</td>
    //             <td contenteditable="true" class="amount" >${cf_post_delivery_amount_unformatted}</td>
    //             <td contenteditable="true" class="percentage" >${cf_post_delivery_percentage_unformatted}</td>
    //             <td contenteditable="true"class="days" >${cf_post_delivery_dates_unformatted}</td>
    //             <td contenteditable="true"class="due-date">${cf_post_delivery_due_date_unformatted}</td>
    //           </tr>
    // `;
    //           tableBody.append(newRow);
    //         },
    //         error: function(xhr, status, error) {
    //           console.error("Error fetching warehouse stock:", error);
    //           alert("Failed to fetch details. Please try again.");
    //         }
    //       });
    //     }

    //   });


      $('#paymentTableBody').on('input', '.amount', function() {
        let amount = parseFloat($(this).text()) || 0;
        let percentage = (amount / grand_total) * 100;
        $(this).closest('tr').find('.percentage').text(percentage.toFixed(2));
      });

      $('#paymentTableBody').on('input', '.percentage', function() {
        let percentage = parseFloat($(this).text()) || 0;
        let amount = (percentage / 100) * grand_total;
        $(this).closest('tr').find('.amount').text(amount.toFixed(2));
      });

      // Track the original Days value
      $('#paymentTableBody').on('focus', '.days', function() {
        // Store the original Days value in a data attribute
        $(this).data('originalDays', parseInt($(this).text()) || 0);
      });

      // Update Due Date when Days field changes
      $('#paymentTableBody').on('input', '.days', function() {
        let newDays = parseInt($(this).text()) || 0;
        let originalDays = $(this).data('originalDays') || 0;

        // Calculate the delta (difference in days)
        let deltaDays = newDays - originalDays;

        // Get the current Due Date from the cell
        let dueDateCell = $(this).closest('tr').find('.due-date');
        let baseDateStr = dueDateCell.text().trim();
        let baseDate = baseDateStr ? new Date(baseDateStr) : new Date(); // Default to today if empty

        // Add the delta to the base date
        baseDate.setDate(baseDate.getDate() + deltaDays);

        // Format the new Due Date as YYYY-MM-DD
        let newDueDate = baseDate.toISOString().split('T')[0];
        dueDateCell.text(newDueDate); // Update the Due Date cell

        // Update the original Days value to the new one for subsequent edits
        $(this).data('originalDays', newDays);
      });

      $('#paymentTableBody').on('focus', '.due-date', function() {
        // Store the original date in a data attribute
        const originalDate = new Date($(this).text().trim());
        if (!isNaN(originalDate)) {
          $(this).data('originalDate', originalDate);
        }
      });

      // Update the "Days" column when the date is changed
      $('#paymentTableBody').on('input', '.due-date', function() {

        const originalDate = $(this).data('originalDate');
        const newDate = new Date($(this).text().trim());
        if (isNaN(originalDate) || isNaN(newDate)) {
          return; // Exit if either date is invalid
        }

        const timeDiff = newDate - originalDate; // Difference in milliseconds
        const daysDiff = Math.round(timeDiff / (1000 * 60 * 60 * 24)); // Convert to days

        const daysCell = $(this).closest('tr').find('.days');
        const originalDays = $(this).data('originalDays') || parseInt(daysCell.text()) || 0;
        const newDaysValue = originalDays + daysDiff;
        daysCell.text(newDaysValue);

        // Update the stored original date and days for the next interaction
        $(this).data('originalDate', newDate);
        $(this).data('originalDays', newDaysValue);
      });

    });

    $('#saveChanges').on('click', function() {
      const tableData = [];
      let totalPercentage = 0;
      $("#paymentTableBody tr").each(function() {
        const row = {
          paymentType: $(this).find("td:eq(0)").text().trim(), // Payment Type
          amount: $(this).find("td:eq(1)").text().trim(), // Amount
          percentage: $(this).find("td:eq(2)").text().trim(), // Percentage
          days: $(this).find("td:eq(3)").text().trim(), // Days
          dueDate: $(this).find("td:eq(4)").text().trim(), // Due Date
        };
        tableData.push(row);

        totalPercentage += parseFloat(row.percentage) || 0;
      });

      if (totalPercentage !== 100) {
        const alertHTML = `
                  <div style="margin-top:15px;" class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>All payment percentages must add up to 100%. Please adjust the values!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  `;
        $('.modal-body').prepend(alertHTML);
        setTimeout(function() {
          $('.alert').alert('close');
        }, 4000);
        // alert('All payment percentages must add up to 100%. Please adjust the values.');
        return; // Stop execution if validation fails
      }

      let formData = new FormData();

      const keyPrefixMapping = {
        Advance: 'cf_advance_payment',
        'Pre Delivery': 'cf_pre_delivery',
        'Post Delivery': 'cf_post_delivery'
      };

      // const urlParams = new URLSearchParams(window.location.search);
      // module_record_id = urlParams.get("record_id");
      // Populate formData based on the payment data
      tableData.forEach(entry => {
        const prefix = keyPrefixMapping[entry.paymentType];

        if (prefix) {
          formData.append(`${prefix}_amount_unformatted`, entry.amount);
          formData.append(`${prefix}_percentage_unformatted`, entry.percentage);
          formData.append(`${prefix}_days_unformatted`, entry.days);
          formData.append(`${prefix}_due_date_unformatted`, entry.dueDate);
        }
        formData.append('module_record_id', po_module_record_id);
      });

      $.ajax({
        url: 'update_PO.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {

          const alertHTML = `
                  <div style="margin-top:15px;" class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Payment Terms Updated!</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  `;
          $('.modal-body').prepend(alertHTML);
          setTimeout(function() {
            $('.alert').alert('close');
          }, 4000);
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
        }
      });

    });

    document.getElementById('saveChanges').addEventListener('click', function() {

      const targetTableBody = document.querySelector('.payment-Terms tbody');
      const targetRow = targetTableBody.querySelector('tr');
      const paymentTypeInTarget = targetRow ? targetRow.cells[1].textContent.trim() : null;

      if (!paymentTypeInTarget) {
        console.error('No payment type found in the target table.');
        return;
      }

      // Reference the modal table body and its rows
      const modalTableBody = document.getElementById('paymentTableBody');
      const modalRows = modalTableBody.querySelectorAll('tr');

      // Determine the corresponding row in the modal table based on the payment type
      let modalRow;
      if (paymentTypeInTarget === 'Advance') {
        modalRow = modalRows[0]; // First row in the modal table
      } else if (paymentTypeInTarget === 'Pre Delivery') {
        modalRow = modalRows[1]; // Second row in the modal table
      } else if (paymentTypeInTarget === 'Post Delivery') {
        modalRow = modalRows[2]; // Third row in the modal table
      } else {
        console.error(`Payment Type "${paymentTypeInTarget}" not found in modal table.`);
        return;
      }

      // Extract data from the selected modal row
      const paymentType = modalRow.cells[0].textContent.trim();
      const amount = modalRow.cells[1].textContent.trim();
      const percentage = modalRow.cells[2].textContent.trim();
      const days = modalRow.cells[3].textContent.trim();
      const dueDate = modalRow.cells[4].textContent.trim();

      // Update the target table's row with visible data and hidden data attributes
      targetRow.innerHTML = `
        <td>1</td>
        <td data-payment-type="${paymentType}">${paymentType}</td>
        <td data-percentage="${percentage}">${percentage}</td>
        <td>₹</td> <!-- Assuming currency is ₹ -->
        <td data-amount="${amount}">${amount}</td>
        <td data-days="${days}">${days}</td>
        <td data-due-date="${dueDate}">${dueDate}</td>
      `;

      // Close the modal
      $('#exampleModalCenter').modal('hide');
    });


    let before_scm_head_approval_status;
    let before_finance_head_approval_status;

    document.addEventListener("DOMContentLoaded", () => {
      const loggedInUserId = document.getElementById('loggedInUserId').value;

      before_scm_head_approval_status = $('#cf_scm_head_approval_status').val();
      before_finance_head_approval_status = $('#cf_finance_head_approval_statu').val();
      console.log(before_finance_head_approval_status, "<----before_scm_");

      const urlParams = new URLSearchParams(window.location.search);
      console.log(urlParams.get('cf_payment_type'));
      var paymentType = "";
      let percent = "";
      let amount = "";
      let days = "";
      let dueDate = "";
      let currency = "";


      paymentType = urlParams.get("cf_payment_type");
      percent = urlParams.get("cf_payment_percentage_formatted");
      console.log(paymentType);
      amount = urlParams.get("cf_payment_amount");
      days = urlParams.get("cf_payment_days_formatted");
      dueDate = urlParams.get("cf_payment_due_date_formatted");
      const currencyMatch = amount.match(/[^\d.]/g);
      currency = currencyMatch ? currencyMatch.join("") : "USD";

      if (paymentType && amount) {
        const tableBody = document.querySelector("tbody");
        const tr = document.createElement("tr");
        tr.innerHTML = `
        <td>1</td>
        <td class="">${paymentType}</td>
        <td class="">${percent}</td>
        <td class="">${currency}</td>
        <td class="">${amount}</td>
        <td class="">${days}</td>
        <td class="">${dueDate}</td>
  `;
        tableBody.appendChild(tr);
      }

      document.querySelector("tbody").addEventListener("click", function(e) {
        if (e.target.classList.contains("editable")) {
          const currentText = e.target.textContent;

          // Handle Payment Type dropdown
          if (e.target.classList.contains("dropdown")) {

            const cellWidth = e.target.offsetWidth;

            // Create dropdown dynamically
            const select = document.createElement("select");
            select.classList.add("form-select");
            select.style.width = `${cellWidth}px`;


            const options = ["Pre Delivery", "Advance", "Post Delivery"];
            options.forEach(option => {
              const opt = document.createElement("option");
              opt.value = option;
              opt.textContent = option;
              if (option === currentText) {
                opt.selected = true;
              }
              select.appendChild(opt);
            });

            e.target.innerHTML = "";
            e.target.appendChild(select);

            // Open dropdown immediately
            select.focus();
            select.size = options.length; // Shows all dropdown options visible

            // Save value on blur or change
            select.addEventListener("blur", function() {
              e.target.textContent = select.value; // Save selected value
              select.size = 0; // Collapse dropdown
            });

            select.addEventListener("change", function() {
              e.target.textContent = select.value; // Save selected value on change
              select.size = 0; // Collapse dropdown
            });

          } else {
            // Handle other editable fields
            const inputType = e.target.cellIndex === 6 ? "date" : (isNaN(currentText) ? "text" : "number");
            e.target.innerHTML = `<input type="${inputType}" class="form-control" value="${currentText}" />`;

            const input = e.target.querySelector("input");
            input.focus();

            input.addEventListener("blur", function() {
              e.target.textContent = input.value; // Save the value back to the cell
            });

            input.addEventListener("keydown", function(event) {
              if (event.key === "Enter") {
                e.target.textContent = input.value; // Save the value back to the cell on Enter
              }
            });
          }
        }
      });

      module_record_id = urlParams.get("module_record_id");
      if (module_record_id) {
        $.ajax({
          url: 'fetchPiDetails.php',
          type: 'GET',
          data: {
            module_record_id: module_record_id
          },
          success: function(response) {
            const piData = JSON.parse(response);

            const moduleRecordNumber = JSON.parse(piData).module_record.module_fields[0].value;
            $('#piNumber').val(moduleRecordNumber);

            const moduleRecordMaps = JSON.parse(piData).module_record.module_fields;
            console.log("moduleRecordMaps", moduleRecordMaps);

            fetched_scm_head_approval_value = moduleRecordMaps.find(map => map.api_name === "cf_scm_head_approval_status").value;
            fetched_finance_approval_value = moduleRecordMaps.find(map => map.api_name === "cf_finance_head_approval_statu").value;
            cf_proforma_invoice_number_value = moduleRecordMaps.find(map => map.api_name === "cf_proforma_invoice_number").value;

            const cf_date_value = moduleRecordMaps.find(map => map.api_name === "cf_date").value;
            const cf_payment_type = moduleRecordMaps.find(map => map.api_name === "cf_payment_type").value;
            const cf_amount_to_be_paid = moduleRecordMaps.find(map => map.api_name === "cf_amount_to_be_paid").value;
            const cf_purchase_order_data = moduleRecordMaps.find(map => map.api_name === "cf_purchase_order");

            if (cf_purchase_order_data) {
              $('#purchaseOrder').val(cf_purchase_order_data.value_formatted);
              $('#purchaseOrder').data('id', cf_purchase_order_data.value);
            }

            $('#cf_scm_head_approval_status').val(fetched_scm_head_approval_value);
            $('#cf_finance_head_approval_statu').val(fetched_finance_approval_value);
            $('#piDate').val(cf_date_value);
            $('#paymentType').val(cf_payment_type);
            $('#piAmount').val(cf_amount_to_be_paid);
            $('#cf_proforma_invoice_number').val(cf_proforma_invoice_number_value);


            const moduleRecordID = JSON.parse(piData).module_record.module_record_id;
            const moduleRecordHash = JSON.parse(piData).module_record_hash.cf_attachment_formatted;
            console.log("moduleRecordHash", moduleRecordHash);

            $('#uploadButton').text(moduleRecordHash);
            po_module_record_id = JSON.parse(piData).module_record_hash.cf_purchase_order;

            $('#cf_attachment').on('change', function() {
              if (this.files && this.files[0]) {
                let cf_attachment = this.files[0].name;
                $('#uploadButton').text(cf_attachment); // Change button text to the file name
              }
            });

            $('#uploadButton').on('click', function() {
              $('#cf_attachment').click();
            });

            $('#piNumber').on('click', function() {
              const baseURL = "https://inventory.zoho.in/app/60006170914#/module/cm_vendor_payment_approval/";
              const queryParams = "?filter_by=Status.All&per_page=200&sort_column=created_time&sort_order=D";
              const redirectURL = `${baseURL}${moduleRecordID}${queryParams}`;
              window.open(redirectURL, '_blank');
            });

            $('#purchaseOrder').on('click', function() {
              const storedId = $('#purchaseOrder').data('id');

              const baseURL = "https://inventory.zoho.in/app/60006170914#/purchaseorders/";
              const redirectURL = `${baseURL}${storedId}`;
              window.open(redirectURL, '_blank');
            });

            const moduleRecord = JSON.parse(piData).module_record.module_fields
            const moduleRecordAmount = JSON.parse(piData).module_record.module_fields;
            const targetField = JSON.parse(piData).module_record.module_fields.find(field => field.api_name === "cf_scm_head_approval_status");
            const value = targetField ? targetField.value : null;

            const result = moduleRecord.find(item => item.api_name === "cf_cm_approve_status_history").table_values;
            const tableBody = document.getElementById("statusHistoryBody");

            result.forEach((rowValues, rowIndex) => {
              const row = document.createElement("tr");
              const indexCell = document.createElement("td");
              indexCell.textContent = rowIndex + 1;
              row.appendChild(indexCell);

              let cfDate = "";
              let cfSCMHeadStatus = "";
              let cfFinanceHeadStatus = "";
              let cfComment = "";

              rowValues.forEach((column) => {
                if (column.api_name === "cf_date") {
                  cfDate = column.value_formatted;
                } else if (column.api_name === "cf_scm_head_status") {
                  cfSCMHeadStatus = column.value_formatted;
                } else if (column.api_name === "cf_finance_head_status") {
                  cfFinanceHeadStatus = column.value_formatted;

                  if (cfFinanceHeadStatus === 'Pending') {
                    $(this).addClass('status-pending');
                  } else if (cfFinanceHeadStatus === 'Approve') {
                    $(this).addClass('status-approve');
                  } else if (cfFinanceHeadStatus === 'Reject') {
                    $(this).addClass('status-reject');
                  }

                } else if (column.api_name === "cf_comments") {
                  comment = column.value_formatted;
                }
              });

              const dateCell = document.createElement("td");
              dateCell.textContent = cfDate;

              const scmStatusCell = document.createElement("td");
              scmStatusCell.textContent = cfSCMHeadStatus;
              const financeStatusCell = document.createElement("td");
              financeStatusCell.textContent = cfFinanceHeadStatus;
              const Comment = document.createElement("td");
              Comment.textContent = comment;

              row.appendChild(dateCell);
              row.appendChild(scmStatusCell);
              row.appendChild(financeStatusCell);
              row.appendChild(Comment);

              tableBody.appendChild(row);
            });

          },
          error: function(xhr, status, error) {
            console.error("Error fetching warehouse stock:", error);
            alert("Failed to fetch details. Please try again.");
          }
        });
      }

      const checkfinanceStatus = document.getElementById("cf_finance_head_approval_statu");
      const checksmStatus = document.getElementById("cf_scm_head_approval_status");
      const checformFields = document.querySelectorAll("#formID input, #formID select, #formID textarea");
      const checksubmitButton = document.getElementById("submitButton");
      const financeValue = checkfinanceStatus.value;
      const scmValue = checksmStatus.value;


      if (financeValue === "Pending" || financeValue === "Payment Options" ||
        scmValue === "Pending" || scmValue === "Payment Options") {

        document.getElementById("exampleFormControlTextarea2").disabled = false;
        document.getElementById("exampleFormControlTextarea1").disabled = false;
        document.getElementById("cf_finance_head_approval_statu").disabled = false;
        document.getElementById("cf_scm_head_approval_status").disabled = false;
        document.getElementById("piAmount").disabled = false;
        document.getElementById("piDate").disabled = false;
        document.getElementById("piNumber").disabled = false;
        document.getElementById("cf_attachment").disabled = false;
        const submitButton = document.getElementById("submitButton");
        // submitButton.style.display = "block";
      }
      document.getElementById('formID').addEventListener('submit', function(event) {
        const piNumber = document.getElementById('piNumber').value.trim();
        const piDate = document.getElementById('piDate').value.trim();
        const piAmount = document.getElementById('piAmount').value.trim();

        if (!piNumber || !piDate || !piAmount) {
          event.preventDefault(); // Prevent form submission
          alert('All fields are mandatory. Please fill in all fields.');
        }
      });

      $('#submitButton').on('click', function(event) {
        event.preventDefault();

        console.log("------------------->");
        
        // if ($('#cf_scm_head_approval_status').val() == "Approve" && $('#cf_finance_head_approval_statu').val() == "Approve") {
        //   window.location.href = 'https://inventory.zoho.in/app/60006170914#/paymentsmade/new?transaction_type=vendor_advance';
        // }

        let piNumber = $('#piNumber').val();
        let piDate = $('#piDate').val();
        let piAmount = parseFloat($('#piAmount').val());
        let financeHeadStatus = $('#cf_finance_head_approval_statu').val();
        let scmHeadStatus = $('#cf_scm_head_approval_status').val();
        let comment1 = $('#exampleFormControlTextarea1').val();
        let comment2 = $('#exampleFormControlTextarea2').val();
        let moduleRecordId = new URLSearchParams(window.location.search).get('module_record_id');

        const now = new Date();
        const currentDateTime = now.toISOString().split('T')[0];
        const statusTableData = [];
        const table = document.querySelector("#statusHistory");
        const rows = document.querySelectorAll("#statusHistory tbody tr");

        rows.forEach((row, index) => {
          const cells = row.querySelectorAll("td");
          console.log(`Row ${index + 1} cells:`, cells);
          statusTableData.push({
            cf_date: cells[1]?.textContent.trim() || null,
            cf_scm_head_status: cells[2]?.textContent.trim() || null,
            cf_finance_head_status: cells[3]?.textContent.trim() || null,
            cf_comments: cells[4]?.textContent.trim() || null,
          });
        });

        const currentDate_Time = new Date();
        const year = currentDate_Time.getFullYear();
        const month = String(currentDate_Time.getMonth() + 1).padStart(2, '0'); // Months are 0-based
        const day = String(currentDate_Time.getDate()).padStart(2, '0');
        const hours = String(currentDate_Time.getHours()).padStart(2, '0');
        const minutes = String(currentDate_Time.getMinutes()).padStart(2, '0');
        const seconds = String(currentDate_Time.getSeconds()).padStart(2, '0');
        const current_D_T = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;

        let formData = new FormData();
        if (fetched_scm_head_approval_value === null && scmHeadStatus === null) {
          console.log("Both are null");
        }


        const normalizedFetchedValue = (fetched_scm_head_approval_value === null || fetched_scm_head_approval_value === "") ? "null" : fetched_scm_head_approval_value;


        const normalizedScmHeadStatus = scmHeadStatus ?? "null";
        console.log(normalizedFetchedValue, normalizedScmHeadStatus);

        if (normalizedFetchedValue !== normalizedScmHeadStatus) {
          console.log("Both are null");

          formData.append('cf_scm_head_approval_status', scmHeadStatus);
          statusTableData.push({
            'cf_date': current_D_T,
            'cf_scm_head_status': "SCM Head",
            'cf_finance_head_status': scmHeadStatus,
            'cf_comments': $('#exampleFormControlTextarea1').val()

          });
          formData.append('cf_cm_approve_status_history', JSON.stringify(statusTableData));
        }

        const normalizedFetchedValueFinance = (fetched_finance_approval_value === null || fetched_finance_approval_value === "") ? "null" : fetched_finance_approval_value;

        const normalizedFinanceHeadStatus = financeHeadStatus ?? "null";
        console.log("normalizedFinanceHeadStatus------", normalizedFetchedValueFinance, "normalizedFinanceHeadStatus------", normalizedFinanceHeadStatus);

        if (normalizedFetchedValueFinance !== normalizedFinanceHeadStatus) {
          formData.append('cf_finance_head_approval_statu', financeHeadStatus);

          statusTableData.push({
            'cf_date': current_D_T,
            'cf_scm_head_status': "Finance Head",
            'cf_finance_head_status': financeHeadStatus,
            'cf_comments': $('#exampleFormControlTextarea2').val()
          });

          formData.append('cf_cm_approve_status_history', JSON.stringify(statusTableData));
        }

        formData.append('module_record_id', moduleRecordId);
        formData.append('cf_date', piDate);
        formData.append('cf_amount_to_be_paid', piAmount);
        formData.append('cf_date_time', currentDateTime);

        let fileInput = $('#cf_attachment')[0];
        if (fileInput.files.length > 0) {
          formData.append('cf_attachment', fileInput.files[0]);
        }

        const tableRow = document.querySelector("#paymentTermsTable tbody tr");

        if (tableRow) {
          const paymentType = tableRow.cells[1].textContent.trim();
          const percent = tableRow.cells[2].textContent.trim();
          const currency = tableRow.cells[3].textContent.trim();
          const amount = tableRow.cells[4].textContent.trim();
          const days = tableRow.cells[5].textContent.trim();
          const dueDate = tableRow.cells[6].textContent.trim();

          formData.append("PaymentType", paymentType);
          formData.append("Percent", percent.replace("%", "").trim());
          formData.append("Amount", amount);
          formData.append("Days", days);
          formData.append("DueDate", dueDate);
        } else {
          console.log("No rows found in the table.");
        }

        $.ajax({
          url: 'updateForm.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {

            console.log("Success!-------------------------");
            alert("Record Updated Successfully!");

            const alertHTML = `
            <div style="margin-top:15px;" class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Record Updated Successfully!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
        `;
            $('body').prepend(alertHTML);

            setTimeout(function() {
              $('.alert').alert('close');
            }, 2000);

          },
          error: function(xhr, status, error) {
            console.error('Error:', error);
            $('#alert-container').html(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                Failed to submit data. Please try again.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
            setTimeout(() => {
              $('.alert').alert('close');
            }, 5000);
          }
        });

          var vendor_id=0;
          var vendor_name="";
    let vendor_payment_form = new FormData();
      //     if ($('#cf_scm_head_approval_status').val() == "Approve" && $('#cf_finance_head_approval_statu').val() == "Approve") {

      //       const urlParams = new URLSearchParams(window.location.search);
      // const po_record_id = urlParams.get("cf_purchase_order"); 

      //     if (po_record_id) {
      //       $.ajax({
      //         url: 'fetch_PO_details.php',
      //         type: 'GET',
      //         data: {
      //           module_record_id: po_record_id
      //         },
      //         success: function(response) {                

      //           const poData = JSON.parse(response);
      //           const recordFieldHash = JSON.parse(poData).purchaseorder.custom_field_hash;
      //           vendor_id = JSON.parse(poData).purchaseorder.vendor_id;
      //           vendor_name = JSON.parse(poData).purchaseorder.vendor_name;
                      

      //           vendor_payment_form.append("vendor_id", vendor_id);          
      //           vendor_payment_form.append("vendor_name", vendor_name);           
      //           // handleVendorDetails(vendor_id, vendor_name);
                
      //           vendor_payment_form.append("amount", $('#piAmount').val());
                    
      //               $.ajax({
      //                 url: 'create_vendor_payment.php',
      //                 type: 'POST',
      //                 data: vendor_payment_form,
      //                 processData: false,
      //                 contentType: false,
      //                 success: function(response) {
      //                   console.log("Success!", response);  
      //                  const  created_record_Id = response.record_id;
      //                  window.location.href = `https://inventory.zoho.in/app/60006170914#/paymentsmade/${created_record_Id}/edit`;

      //                 }, 
      //                 error: function(xhr, status, error) {
      //                   console.error('Error:', error);
      //                 }
      //               });



      //         },
      //         error: function(xhr, status, error) {
      //           alert("Failed to fetch details. Please try again.");
      //         }

      //       });
      //     }
     
      //     // window.location.href = 'https://inventory.zoho.in/app/60006170914#/paymentsmade/new?transaction_type=vendor_advance';

      //   }

      });
    });
  </script>
</body>

</html>