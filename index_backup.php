<?php
require 'zoho/ZohoInventory.php';
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
  <title>Submit form</title>
  <link rel="icon" href="statiq_logo.jpg" type="image/icon type">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="./index.js"></script>
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
      /* Ensures dropdown doesn't exceed table cell width */
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
  </style>
</head>

<body>
  <div class="container">
    <div class="form-container">
      <form action="" id="formID" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="attachPi">Attach PI:</label>
          <div class="upload-btn-wrapper">
            <button type="button" class="upload-btn" id="uploadButton">Document Upload &#8682;</button>
            <input type="file" id="cf_attachment" name="cf_attachment" style="display:none;" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="piNumber">PI Number:</label>
            <input type="text" class="form-control" id="piNumber" name="piNumber" placeholder="Fill Invoice Number" required>
          </div>

          <div class="form-group col-md-6">
            <label for="piDate">PI Date:</label>
            <input type="date" class="form-control" id="piDate" name="piDate" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="piAmount">PI Amount:</label>
            <input type="text" class="form-control" id="piAmount" name="piAmount" placeholder="Enter PI Amount" required>
          </div>
          <div class="form-group col-md-6">
            <label for="paymentType">Payment Type:</label>
            <input type="text" class="form-control" id="paymentType" name="paymentType:" placeholder="Payment Type">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="paymentTerms">SCM Head Status:</label>
            <select class="form-control" id="cf_scm_head_approval_status" name="cf_scm_head_approval_status">
              <option selected value="Select an Option">Select an Option</option>
              <option value="Pending">Pending</option>
              <option value="Approve">Approve</option>
              <option value="Reject">Reject</option>
            </select>
          </div>

          <div class=" form-group col-md-6">
            <label for="paymentTerms">Finance Head Status:</label>
            <select class="form-control" id="cf_finance_head_approval_statu" name="cf_finance_head_approval_statu">
            <option selected value="Select an Option">Select an Option</option>
              <option value="Pending">Pending</option>
              <option value="Approve">Approve</option>
              <option value="Reject">Reject</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="paymentTerms">SCM Head Comment :</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="2"></textarea>
          </div>
          <div class="form-group col-md-6">
            <label for="paymentTerms">Finance Head Comment :</label>
            <textarea class="form-control" id="exampleFormControlTextarea2" rows="2"></textarea>
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
                <th style="width: 50px;">Days</th>
                <th style="width: 90px;">Due Date</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <button type="button" class="btn btn-secondary px-4 py-2 mx-3 my-2" id="updatePaymentTerms" data-toggle="modal" data-target="#exampleModalCenter">Update Payment Terms</button>
        <button type="submit" id="submitButton" class="btn btn-primary submit-btn">Submit</button>
      </form>
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
      document.getElementById("uploadButton").addEventListener("click", function() {
        // Trigger the hidden file input click
        document.getElementById("cf_attachment").click();
      });

      document.getElementById("cf_attachment").addEventListener("change", function() {
        // Get the selected file

        console.log("Attachment---------------------------------------------");

        if (this.files && this.files[0]) {
          let cf_attachment = this.files[0].name;
          $('#uploadButton').text(cf_attachment); // Change button text to the file name
        }

      });


      document.addEventListener("DOMContentLoaded", () => {

        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0'); // Add 1 to month (0-based)
        const date = String(now.getDate()).padStart(2, '0');
        const cuurenDate = `${year}-${month}-${date}`;
        $('#piDate').val(cuurenDate);

        const tableBody = document.querySelector("#paymentTermsTable tbody");
        const piAmountField = document.getElementById("piAmount");

        // Function to update the piAmount field whenever an Amount field is changed
        function syncAmountField(e) {
          if (!e.target.classList.contains("amount-input")) return;

          const updatedAmount = parseFloat(e.target.value) || 0; // Get the new value from the field
          piAmountField.value = updatedAmount.toFixed(2); // Update the piAmount field with the new value
          console.log(`Amount synchronized: ${updatedAmount}`);
        }

        // Event listeners for Enter key and blur events
        tableBody.addEventListener("keydown", function(e) {
          if (e.key === "Enter" && e.target.classList.contains("amount-input")) {
            e.preventDefault();
            e.target.blur(); // Trigger blur to finalize the value
          }
        });

        tableBody.addEventListener("blur", syncAmountField, true);


        function parseDate(input) {
          if (!input) return null;
          const parts = input.split("-");
          if (parts.length !== 3) return null;
          const [day, month, year] = parts.map(Number);
          return new Date(year, month - 1, day); // JavaScript Date: month is zero-based
        }


        function formatDate(date) {
          if (!(date instanceof Date) || isNaN(date)) return "";
          const day = String(date.getDate()).padStart(2, "0");
          const month = String(date.getMonth() + 1).padStart(2, "0");
          const year = date.getFullYear();
          return `${day}-${month}-${year}`;
        }

        // Function to update the Due Date when Days value changes
        function updateDueDate(inputElement) {
          const row = inputElement.closest("tr");
          const dueDateCell = row.querySelector(".due-date");

          const originalDays = parseInt(inputElement.getAttribute("data-original-days")) || 0;
          const newDays = parseInt(inputElement.value) || 0;

          if (isNaN(originalDays) || isNaN(newDays)) {
            console.error("Invalid Days value.");
            return;
          }

          const daysDifference = newDays - originalDays;

          if (daysDifference !== 0) {
            const originalDateString = dueDateCell.getAttribute("data-original-date");
            const originalDate = parseDate(originalDateString);

            if (!originalDate) {
              console.error("Invalid original date format:", originalDateString);
              return;
            }

            // Calculate new Due Date
            const updatedDate = new Date(originalDate);
            updatedDate.setDate(updatedDate.getDate() + daysDifference);

            // Update the Due Date cell and attributes
            const updatedDateString = formatDate(updatedDate);
            dueDateCell.textContent = updatedDateString;
            dueDateCell.setAttribute("data-original-date", updatedDateString);

            // Update the Days input attribute
            inputElement.setAttribute("data-original-days", newDays);
            $('.days-input').val(newDays);


            console.log(`Due Date updated to: ${updatedDateString}`);
          }
        }

        // Event listener for input blur (focus out) and Enter key
        tableBody.addEventListener("blur", function(event) {
          if (event.target.classList.contains("days-input")) {
            updateDueDate(event.target);
          }
        }, true);

        tableBody.addEventListener("keydown", function(event) {
          if (event.key === "Enter" && event.target.classList.contains("days-input")) {
            event.preventDefault(); // Prevent default Enter behavior
            event.target.blur(); // Trigger blur to save changes
          }
        });

      });
      $(document).ready(function() {
        const currentUserID = "<?php echo $user_id; ?>";

        // if (currentUserID !== 10) {
        //   document.getElementById("cf_finance_head_approval_statu").disabled = true;
        //   document.getElementById("exampleFormControlTextarea2").disabled = true;
        // }


        let grand_total = 0;

        $('#updatePaymentTerms').on('click', function() {

          const urlParams = new URLSearchParams(window.location.search);
          module_record_id = urlParams.get("record_id");

          if (module_record_id) {
            $.ajax({
              url: 'fetch_PO_details.php',
              type: 'GET',
              data: {
                module_record_id: module_record_id
              },
              success: function(response) {
                const poData = JSON.parse(response);
                const recordFieldHash = JSON.parse(poData).purchaseorder.custom_field_hash;
                grand_total = JSON.parse(poData).purchaseorder.total;


                for (const key in recordFieldHash) {
                  if (key.endsWith('_unformatted')) {
                    eval(`var ${key} = recordFieldHash[key];`); // Creates separate variables
                  }
                }

                const tableBody = $("#paymentTableBody");
                tableBody.empty();
                const newRow = `
                <tr>
                  <td>Advance</td>
                  <td contenteditable="true" class="amount" >${cf_advance_payment_amount_unformatted}</td>
                  <td contenteditable="true" class="percentage" >${cf_advance_payment_percent_unformatted}</td>
                  <td contenteditable="true" class="days" >${cf_advance_payment_days_unformatted}</td>
                  <td contenteditable="true"class="due-date">${cf_advance_payment_due_date_unformatted}</td>
                </tr>
              <tr>
                <td>Pre Delivery</td>
                <td contenteditable="true" class="amount" >${cf_pre_delivery_amount_unformatted}</td>
                <td contenteditable="true" class="percentage" >${cf_pre_delivery_percentage_unformatted}</td>
                <td contenteditable="true"class="days" >${cf_pre_delivery_days_unformatted}</td>
                <td contenteditable="true"class="due-date">${cf_pre_delivery_due_date_unformatted}</td>
              </tr>

              <tr>
                <td>Post Delivery</td>
                <td contenteditable="true" class="amount" >${cf_post_delivery_amount_unformatted}</td>
                <td contenteditable="true" class="percentage" >${cf_post_delivery_percentage_unformatted}</td>
                <td contenteditable="true"class="days" >${cf_post_delivery_dates_unformatted}</td>
                <td contenteditable="true"class="due-date">${cf_post_delivery_due_date_unformatted}</td>
              </tr>
              `;
                tableBody.append(newRow);
              },
              error: function(xhr, status, error) {
                console.error("Error fetching warehouse stock:", error);
                alert("Failed to fetch details. Please try again.");
              }
            });
          }

        });


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
      });


      $('#saveChanges').on('click', function() {
        const tableData = [];
        $("#paymentTableBody tr").each(function() {
          const row = {
            paymentType: $(this).find("td:eq(0)").text().trim(), // Payment Type
            amount: $(this).find("td:eq(1)").text().trim(), // Amount
            percentage: $(this).find("td:eq(2)").text().trim(), // Percentage
            days: $(this).find("td:eq(3)").text().trim(), // Days
            dueDate: $(this).find("td:eq(4)").text().trim(), // Due Date
          };
          tableData.push(row); // Add row data to array
        });

        let formData = new FormData();

        // Mapping for payment type to key prefixes
        const keyPrefixMapping = {
          Advance: 'cf_advance_payment',
          'Pre Delivery': 'cf_pre_delivery',
          'Post Delivery': 'cf_post_delivery'
        };


        // const urlParams = new URLSearchParams(window.location.search);
        module_record_id = urlParams.get("record_id");
        // Populate formData based on the payment data
        tableData.forEach(entry => {
          const prefix = keyPrefixMapping[entry.paymentType];

          if (prefix) {
            formData.append(`${prefix}_amount_unformatted`, entry.amount);
            formData.append(`${prefix}_percentage_unformatted`, entry.percentage);
            formData.append(`${prefix}_days_unformatted`, entry.days);
            formData.append(`${prefix}_due_date_unformatted`, entry.dueDate);
          }
          formData.append('module_record_id', module_record_id);
        });

        $.ajax({
          url: 'update_PO.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            console.log('Success:', response);

          },
          error: function(xhr, status, error) {
            console.error('Error:', error);

          }
        });

        // Log formData entries for debugging
        // for (let [key, value] of formData.entries()) {
        //   console.log(`${key}: ${value}`);
        // }

      });




      const urlParams = new URLSearchParams(window.location.search);
      let paymentType = "";
      let percent = "";
      let amount = "";
      let days = "";
      let dueDate = "";
      let currency = "";

      // Check URL parameters and assign values
      if (urlParams.has("pre_delivery_percentage")) {
        paymentType = "Pre-Delivery";
        percent = urlParams.get("pre_delivery_percentage");
        amount = urlParams.get("pre_delivery_amount");
        days = urlParams.get("pre_delivery_days");
        dueDate = urlParams.get("pre_delivery_due_date");
      }
      if (urlParams.has("post_delivery_percentage")) {
        paymentType = "Post-Delivery";
        percent = urlParams.get("post_delivery_percentage");
        amount = urlParams.get("post_delivery_amount");
        days = urlParams.get("post_delivery_dates");
        dueDate = urlParams.get("post_delivery_due_date");
      }
      if (urlParams.has("advance_payment_percent")) {
        paymentType = "Advance";
        percent = urlParams.get("advance_payment_percent");
        amount = urlParams.get("advance_payment_amount");
        days = urlParams.get("advance_payment_days");
        dueDate = urlParams.get("advance_payment_due_date");
      }

      $('#paymentType').val(paymentType);
      $('#piAmount').val(amount);

      // Extract currency from amount
      const currencyMatch = amount.match(/[^0-9.]/g);
      currency = currencyMatch ? currencyMatch.join("") : "";

      // Append non-editable row to table
      function cleanNumber(str) {
        // Remove commas and any spaces first
        return str.replace(/,/g, '');
      }

      console.log(typeof amount);
      console.log("---amount--", cleanNumber(amount).slice(1));
      console.log("---amount--", amount);

      if (paymentType && amount) {
        const tableBody = document.querySelector("tbody");
        const tr = document.createElement("tr");
        tr.innerHTML = `
        <td>1</td>
        <td class="editable dropdown">${paymentType}</td>
        <td class="editable">${percent}</td>
        <td class="editable">${currency}</td>
     <td>
  <input type="number" 
         class="form-control amount-input" 
         data-original-amount="${cleanNumber(amount).slice(1)}" 
         value="${cleanNumber(amount).slice(1)}" />
</td>
          <td>
          <input type="number" 
               class="form-control days-input" 
               data-original-days="${days}" 
               value="${days}" />
          </td>
      <td class="due-date" 
          data-original-date="${dueDate}">
        ${dueDate}
      </td>
  `;
        tableBody.appendChild(tr);
      }

      document.querySelector("tbody").addEventListener("click", function(e) {
        if (e.target.classList.contains("editable")) {
          const currentText = e.target.textContent;

          if (e.target.classList.contains("dropdown")) {
            const cellWidth = e.target.offsetWidth;
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



      document.getElementById('formID').addEventListener('submit', function(event) {

        const piNumber = document.getElementById('piNumber').value.trim();
        const piDate = document.getElementById('piDate').value.trim();
        const piAmount = document.getElementById('piAmount').value;

        if (!piNumber || !piDate || !piAmount) {
          event.preventDefault(); // Prevent form submission
          alert('All fields are mandatory. Please fill in all fields.');
        }
      });
      let scmHeadStatus = "";
      let financeHeadStatus = "";
      $('#cf_scm_head_approval_status, #cf_finance_head_approval_statu').on('change', function() {
        const scmHeadStatus = $('#cf_scm_head_approval_status').val();
        console.log("scmHeadStatus==================", scmHeadStatus);

        const financeHeadStatus = $('#cf_finance_head_approval_statu').val();
        if ($(this).attr('id') === 'cf_scm_head_approval_status' && scmHeadStatus !== 'Select an Option') {
          $('#cf_finance_head_approval_statu').val('Select an Option');
          console.log("=Inside IF========", scmHeadStatus);
          const exampleFormControlTextarea1 = $('#exampleFormControlTextarea1').val();

        } else if ($(this).attr('id') === 'cf_finance_head_approval_statu' && financeHeadStatus !== 'Select an Option') {
          $('#cf_scm_head_approval_status').val('Select an Option');
          const exampleFormControlTextarea2 = $('#exampleFormControlTextarea2').val();
        }
      });

      $('#submitButton').on('click', function(event) {
        event.preventDefault();

        function getUrlParameter(name) {
          const urlParams = new URLSearchParams(window.location.search);
          return urlParams.get(name);
        }

        let poRecordId = getUrlParameter('record_id');
        let purchaseOrderNumber = getUrlParameter('purchaseorder_number');
        let piNumber = $('#piNumber').val();
        let piDate = $('#piDate').val();
        let piAmount = parseFloat($('#piAmount').val());

        let cf_scm_head_approval_status = $('#cf_scm_head_approval_status').val();

        let cf_payment_amount = $('cf_payment_amount').val();
        let cf_payment_percentage = $('cf_payment_percentage').val();


        console.log('cf_payment_amount', cf_payment_amount);
        console.log('cf_payment_percentage', cf_payment_percentage);

        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0'); // Add 1 to month (0-based)
        const date = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const cuurenDateTime = `${year}-${month}-${date} ${hours}:${minutes}:${seconds}`;


        let cf_approve_status_by_scm_head = $('#cf_scm_head_approval_status').val();
        let formData = new FormData();
        if (piDate) {
          let formattedDate = new Date(piDate).toISOString().split('T')[0];
          formData.append('cf_date', formattedDate);
        } else {
          formData.append('cf_date', '');
        }
        const urlParams = new URLSearchParams(window.location.search);


        if (urlParams.has("pre_delivery_percentage")) {
          paymentType = "Pre-Delivery";
        } else if (urlParams.has("post_delivery_percentage")) {
          paymentType = "Post-Delivery";
        } else if (urlParams.has("advance_payment_percent")) {
          paymentType = "Advance Payment";
        }
        console.log("paymentType", paymentType);

        formData.append('cf_proforma_invoice_number', piNumber);
        formData.append('cf_date', piDate);
        formData.append('cf_amount_to_be_paid', $('#piAmount').val().replace(/[^0-9.,]/g, '').replace(/,/g, ''));
        formData.append('cf_payment_amount', $('#piAmount').val());
        formData.append('cf_purchase_order', poRecordId);
        formData.append('cf_po_num', purchaseOrderNumber);
        formData.append('cf_payment_amount', amount);
        formData.append('cf_payment_percentage', percent.replace('%', ''));
        formData.append('cf_payment_due_date', dueDate);

        const cf_scm_head_approval_value =  $('#cf_scm_head_approval_status').val();
        if (cf_scm_head_approval_value !== 'Select an Option') {
          formData.append('cf_scm_head_approval_status', cf_scm_head_approval_value);
        }
  
        let financeHeadStatus = $('#cf_finance_head_approval_statu').val();
        if (financeHeadStatus !== 'Select an Option') {

          formData.append('cf_finance_head_approval_statu', financeHeadStatus);
        }

        let cf_attachment = $('#cf_attachment')[0].files[0];
        if (cf_attachment) {
          console.log("file present-------------", );
          formData.append('cf_attachment', cf_attachment);
        }
        console.log('formData', formData);

        const tableData = [];
        console.log("------->1",cf_scm_head_approval_value ,"<------------->2", financeHeadStatus);

        if (cf_scm_head_approval_value !== 'Select an Option' || financeHeadStatus !== 'Select an Option') {
      
          tableData.push({
            'cf_date': cuurenDateTime,
            'cf_scm_head_status': cf_scm_head_approval_value !== 'Select an Option' ? "SCM Head" : "Finance Head",
            'cf_finance_head_status': financeHeadStatus !== 'Select an Option' ? financeHeadStatus : $('#cf_scm_head_approval_status').val(),
            'cf_comments': financeHeadStatus !== 'Select an Option' ? $('#exampleFormControlTextarea2').val() : $('#exampleFormControlTextarea1').val()
          })
        }

        const tableRow = document.querySelector("#paymentTermsTable tbody tr");
        if (tableRow) {
          const paymentType = tableRow.cells[1].textContent.trim();
          const percent = tableRow.cells[2].textContent.trim();
          const currency = tableRow.cells[3].textContent.trim();
          const amount = tableRow.cells[4].textContent.trim();
          const days = tableRow.cells[5].textContent.trim();
          console.log("days", days);

          const dueDate = tableRow.cells[6].textContent.trim();

          formData.append("PaymentType", paymentType);
          formData.append("Percent", percent.replace("%", "").trim());
          formData.append("Amount", amount);
          formData.append("Days", 10);
          formData.append("DueDate", dueDate);
        } else {
          console.log("No rows found in the table.");
        }
        formData.append('table_fields', JSON.stringify(tableData));
        console.log(formData);

        $.ajax({
          url: 'submit_form.php',
          type: 'POST',
          processData: false,
          contentType: false,
          data: formData,
          success: function(response) {
            console.log(response);
            // window.location.href = './secondScreen.php';
          },
          error: function() {
            alert('Failed to submit the form');
          }
        });
      });
    </script>

</body>

</html>