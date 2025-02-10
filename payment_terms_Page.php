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
    body {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
    }

    h1 {
      text-align: center;
      margin-top: 45px;
      margin-bottom: 20px;
    }

    .modal-body {
      width: 80%;
    }

    table {
      margin: auto;
      border-collapse: collapse;
      width: 100%;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
      /* Center-align text in the table */
    }

    th {
      background-color: #f2f2f2;
    }

    .save-changes-container {
      margin-top: 20px;

      text-align: center;
    }

    #saveChanges {
      padding: 10px 20px;
      font-size: 16px;
    }
  </style>

</head>

<body>

  <div>
    <h1>Payment Terms</h1>
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
        <!-- Table rows here -->
      </tbody>
    </table>
    <div class="save-changes-container">
      <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
    </div>

  </div>

</body>

<script>
  const urlParams = new URLSearchParams(window.location.search);
  console.log(urlParams);
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
            eval(`var ${key} = recordFieldHash[key];`);
          }
        }

        const tableBody = $("#paymentTableBody");
        tableBody.empty();
        let newRow = "";
        if (
          typeof cf_advance_payment_amount_unformatted !== "undefined" ||
          typeof cf_advance_payment_percent_unformatted !== "undefined" ||
          typeof cf_advance_payment_days_unformatted !== "undefined" ||
          typeof cf_advance_payment_due_date_unformatted !== "undefined"
        ) {
          newRow += `
    <tr>
      <td>Advance</td>
      <td contenteditable="true" class="amount">${typeof cf_advance_payment_amount_unformatted !== "undefined" ? cf_advance_payment_amount_unformatted : ""}</td>
      <td contenteditable="true" class="percentage">${typeof cf_advance_payment_percent_unformatted !== "undefined" ? cf_advance_payment_percent_unformatted : ""}</td>
      <td contenteditable="true" class="days">${typeof cf_advance_payment_days_unformatted !== "undefined" ? cf_advance_payment_days_unformatted : ""}</td>
      <td contenteditable="true" class="due-date">${typeof cf_advance_payment_due_date_unformatted !== "undefined" ? cf_advance_payment_due_date_unformatted : ""}</td>
    </tr>`;
        }

        if (
          typeof cf_pre_delivery_amount_unformatted !== "undefined" ||
          typeof cf_pre_delivery_percentage_unformatted !== "undefined" ||
          typeof cf_pre_delivery_days_unformatted !== "undefined" ||
          typeof cf_pre_delivery_due_date_unformatted !== "undefined"
        ) {
          newRow += `
          <tr>
            <td>Pre Delivery</td>
            <td contenteditable="true" class="amount">${typeof cf_pre_delivery_amount_unformatted !== "undefined" ? cf_pre_delivery_amount_unformatted : ""}</td>
            <td contenteditable="true" class="percentage">${typeof cf_pre_delivery_percentage_unformatted !== "undefined" ? cf_pre_delivery_percentage_unformatted : ""}</td>
            <td contenteditable="true" class="days">${typeof cf_pre_delivery_days_unformatted !== "undefined" ? cf_pre_delivery_days_unformatted : ""}</td>
            <td contenteditable="true" class="due-date">${typeof cf_pre_delivery_due_date_unformatted !== "undefined" ? cf_pre_delivery_due_date_unformatted : ""}</td>
          </tr>`;
        }

        if (
          typeof cf_post_delivery_amount_unformatted !== "undefined" ||
          typeof cf_post_delivery_percentage_unformatted !== "undefined" ||
          typeof cf_post_delivery_dates_unformatted !== "undefined" ||
          typeof cf_post_delivery_due_date_unformatted !== "undefined"
        )
        {
          newRow += `
          <tr>
            <td>Post Delivery</td>
            <td contenteditable="true" class="amount">${typeof cf_post_delivery_amount_unformatted !== "undefined" ? cf_post_delivery_amount_unformatted : ""}</td>
            <td contenteditable="true" class="percentage">${typeof cf_post_delivery_percentage_unformatted !== "undefined" ? cf_post_delivery_percentage_unformatted : ""}</td>
          
          </tr>`;
        }

        tableBody.append(newRow);
      },
      error: function(xhr, status, error) {
        console.error("Error fetching warehouse stock:", error);
        alert("Failed to fetch details. Please try again.");
      }
    });
  }

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
  // });

  // Track the original Days value
  // $('#paymentTableBody').on('focus', '.days', function() {
  //   // Store the original Days value in a data attribute
  //   $(this).data('originalDays', parseInt($(this).text()) || 0);
  // });

  // $('#paymentTableBody').on('input', '.days', function() {
  //   let newDays = parseInt($(this).text()) || 0;

  //   if (newDays < 0) {
  //       alert("Days value cannot be negative!");
  //       if ($(this).is('input')) {
  //           $(this).val(originalDays);
  //       } else {
  //           $(this).text(originalDays);
  //       }
  //       return;
  //   }

  //   let originalDays = $(this).data('originalDays') || 0;
  //   let deltaDays = newDays - originalDays;
  //   let dueDateCell = $(this).closest('tr').find('.due-date');
  //   let baseDateStr = dueDateCell.text().trim();
  //   let baseDate = baseDateStr ? new Date(baseDateStr) : new Date(); // Default to today if empty
  //   baseDate.setDate(baseDate.getDate() + deltaDays);
  //   let newDueDate = baseDate.toISOString().split('T')[0];
  //   dueDateCell.text(newDueDate); // Update the Due Date cell
  //   $(this).data('originalDays', newDays);
  // });

  $('#paymentTableBody').on('focus', '.days', function() {
    // Store the original Days value correctly based on element type
    let $element = $(this);
    let originalDays;
    if ($element.is('input')) {
        originalDays = parseInt($element.val()) || 0;
    } else {
        originalDays = parseInt($element.text()) || 0;
    }
    $element.data('originalDays', originalDays);
});

$('#paymentTableBody').on('input', '.days', function() {
    let $element = $(this);
    let isInput = $element.is('input');
    let originalDays = $element.data('originalDays') || 0;

    // Get current value based on element type
    let currentValue = isInput ? $element.val() : $element.text();
    let newDays = parseInt(currentValue) || 0;

    // Prevent negative values
    if (newDays < 0) {
        alert("Days value cannot be negative!");
        if (isInput) {
            $element.val(originalDays);
        } else {
            $element.text(originalDays);
        }
        return; 
    }

    // Update due date calculations
    let deltaDays = newDays - originalDays;
    let dueDateCell = $element.closest('tr').find('.due-date');
    let baseDateStr = dueDateCell.text().trim();
    let baseDate = baseDateStr ? new Date(baseDateStr) : new Date();
    baseDate.setDate(baseDate.getDate() + deltaDays);
    dueDateCell.text(baseDate.toISOString().split('T')[0]);

    // Store the new valid value as original
    $element.data('originalDays', newDays);
});

  $('#paymentTableBody').on('focus', '.due-date', function() {
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
      return; 
    }

    const timeDiff = newDate - originalDate; // Difference in milliseconds
    const daysDiff = Math.round(timeDiff / (1000 * 60 * 60 * 24)); // Convert to days
    const daysCell = $(this).closest('tr').find('.days');
    const originalDays = $(this).data('originalDays') || parseInt(daysCell.text()) || 0;
    const newDaysValue = originalDays + daysDiff;
    daysCell.text(newDaysValue);
    $(this).data('originalDate', newDate);
    $(this).data('originalDays', newDaysValue);
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
    if (totalPercentage > 100) {

      const alertHTML = `
        <div style="margin-top:15px;" class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>All payment percentages must add up to 100%. Please adjust the values.!</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      `;
      $('body').prepend(alertHTML);
      setTimeout(function() {
        $('.alert').alert('close');
      }, 4000);

      return; // Stop execution if validation fails
    }

    let formData = new FormData();
    const keyPrefixMapping = {
      'Advance': 'cf_advance_payment',
      'Pre Delivery': 'cf_pre_delivery',
      'Post Delivery': 'cf_post_delivery',
    };

    module_record_id = urlParams.get("record_id");

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
    console.log(tableData);

    $.ajax({
      url: 'update_PO.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        console.log('Success:', response);
        const alertHTML = `
        <div style="margin-top:15px;" class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>Payment Terms Updated!</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      `;
        $('body').prepend(alertHTML);

        setTimeout(function() {
          $('.alert').alert('close');
          window.location.href = "https://inventory.zoho.in/app/60006170914#/purchaseorders/" + module_record_id + "?filter_by=Status.All&per_page=200&search_criteria=%7B%22search_text%22%3A%22crm%22%7D&sort_column=created_time&sort_order=D";
        }, 500);
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
      }
    });

    console.log(tableData[0].paymentType);
    let advance_form = new FormData();

    advance_form.append('cf_payment_type', "Advance");
    advance_form.append('cf_purchase_order', module_record_id);
    advance_form.append('cf_days', tableData[0].days);
    advance_form.append('cf_due_date', tableData[0].dueDate);
    advance_form.append('cf_due_amount', tableData[0].amount);
    advance_form.append('cf_percentage', tableData[0].percentage);

    $.ajax({
      url: 'create_running_payment_term.php',
      type: 'POST',
      data: advance_form,
      processData: false,
      contentType: false,
      success: function(response) {
        console.log('response', response);
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
      }
    });

    let pre_form = new FormData();
    pre_form.append('cf_payment_type', "Pre Delivery");
    pre_form.append('cf_purchase_order', module_record_id);
    pre_form.append('cf_days', tableData[1].days);
    pre_form.append('cf_due_date', tableData[1].dueDate);
    pre_form.append('cf_due_amount', tableData[1].amount);
    pre_form.append('cf_percentage', tableData[1].percentage);

    $.ajax({
      url: 'create_running_payment_term.php',
      type: 'POST',
      data: pre_form,
      processData: false,
      contentType: false,
      success: function(response) {
        console.log('response', response);
      },
      error: function(xhr, status, error) {
        console.error('Error:', error);
      }
    });
    window.location.href = "https://inventory.zoho.in/app/60006170914#/purchaseorders/" + module_record_id + "?filter_by=Status.All&per_page=200&search_criteria=%7B%22search_text%22%3A%22crm%22%7D&sort_column=created_time&sort_order=D";

  });
</script>

</html>