<?php
session_start();
include('connection.php');
include('includes/header.php');


$user_id = $_SESSION['user_id'];


$query = "SELECT * FROM loan_requests WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);


$loan_count = mysqli_num_rows($result);
?>

<style>
  body {
    font-family: 'Arial', sans-serif;
    background-color: #f7f7f7;
    color: #333;
    margin: 0;
    padding: 0;
  }

  .container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }

  .loan-request-form {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin-bottom: 40px;
  }

  .loan-request-form h2 {
    text-align: center;
    color: #007bff;
    font-size: 28px;
    margin-bottom: 20px;
  }

  .loan-request-form p {
    text-align: center;
    font-size: 16px;
    margin-bottom: 30px;
  }

  .loan-request-form section {
    margin-bottom: 30px;
  }

  .loan-request-form label {
    font-size: 16px;
    font-weight: bold;
    color: #555;
    margin-bottom: 5px;
    display: block;
  }

  .loan-request-form input,
  .loan-request-form select,
  .loan-request-form textarea {
    width: 100%;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    box-sizing: border-box;
    font-size: 16px;
    margin-bottom: 20px;
    transition: border-color 0.3s;
  }

  .loan-request-form input:focus,
  .loan-request-form select:focus,
  .loan-request-form textarea:focus {
    border-color: #007bff;
    outline: none;
  }

  .loan-request-form textarea {
    resize: vertical;
  }

  .loan-request-form .submit-section {
    text-align: center;
  }

  .loan-request-form button {
    background-color: #007bff;
    color: #fff;
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
  }

  .loan-request-form button:hover {
    background-color: #0056b3;
  }

  .loan-request-form .form-footer {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 14px;
    color: #555;
  }

  .loan-request-form .form-footer a {
    color: #007bff;
    text-decoration: none;
    margin-left: 5px;
  }

  .loan-request-form .form-footer a:hover {
    text-decoration: underline;
  }

  .loan-request-form .submit-btn {
    width: auto;
    max-width: 300px;
    display: inline-block;
  }

  .loan-request-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 40px;
  }

  .loan-request-table th,
  .loan-request-table td {
    padding: 12px;
    text-align: center;
    border: 1px solid #ddd;
  }

  .loan-request-table th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
  }

  .loan-request-table td {
    background-color: #f9f9f9;
  }

  .loan-request-table td:nth-child(even) {
    background-color: #f1f1f1;
  }

  .loan-request-table tbody tr:hover {
    background-color: #f1f1f1;
  }

  .loan-request-table .status-pending {
    color: #ffc107;
    font-weight: bold;
  }

  .loan-request-table .status-approved {
    color: #28a745;
    font-weight: bold;
  }

  .loan-request-table .status-rejected {
    color: #dc3545;
    font-weight: bold;
  }

  @media screen and (max-width: 768px) {
    .loan-request-form {
      padding: 20px;
    }

    .loan-request-table th,
    .loan-request-table td {
      padding: 8px;
      font-size: 14px;
    }

    .loan-request-form button {
      width: 100%;
      padding: 14px;
    }
  }
</style>

<div class="container">
  <!-- Check loan request limit -->
  <?php if ($loan_count >= 2): ?>
    <p style="margin-top:100px;"class="alert alert-warning">You have already made 2 loan requests. You cannot make a third request.</p>
  <?php else: ?>
    <div style="margin-top:100px;" class="loan-request-form">
      <h2>AWB - Tour and Travels Loan Request</h2>
      <p>We support you during your trip. Fill out the form below to request a loan.</p>

      <form action="submit_loan.php" method="POST" id="loanForm">
        <!-- User Information -->
        <section>
          <h3>User Information</h3>
          <label for="name">Full Name:</label>
          <input type="text" id="name" name="name" required>
          <label for="email">Email Address:</label>
          <input type="email" id="email" name="email" required>
          <label for="contact">Contact Number:</label>
          <input type="text" id="contact" name="contact" required>
          <label for="bank_name">Bank Name & ACC No (XXXXXXX9867): </label>
          <input type="text" id="bank_name" name="bank_name" required>
          <label for="ifsc">Bank IFSC Code:</label>
          <input type="text" id="ifsc" name="ifsc" required>
        </section>

        <!-- Loan Information -->
        <section>
          <h3>Loan Details</h3>
          <label for="loan_amount">Loan Amount (in INR):</label>
          <input type="number" id="loan_amount" name="loan_amount" min="1000" required>

          <label for="loan_type">Loan Type:</label>
          <select id="loan_type" name="loan_type" required>
            <option value="start_trip">Start Trip</option>
            <option value="complete_trip">Complete Your Journey</option>
          </select>

          <label for="repayment_type">Repayment Type:</label>
          <select id="repayment_type" name="repayment_type" required>
            <option value="full_payment">Full Payment</option>
            <option value="emi">EMI</option>
          </select>

          <label for="emi_duration" id="emi_duration_label" style="display:none;">EMI Duration (Months):</label>
          <select id="emi_duration" name="emi_duration" style="display:none;">
            <option value="3">3 Months</option>
            <option value="6">6 Months</option>
            <option value="9">9 Months</option>
            <option value="12">12 Months</option>
          </select>

          <div id="additional-charges">
            <p>Additional charges: <span id="extra-charges">0</span> INR</p>
            <p>Total Payable Amount: <span id="total-amount">0</span> INR</p>
          </div>
        </section>

        <!-- Additional Information -->
        <section>
          <h3>Additional Information</h3>
          <label for="reason">Reason for Loan Request:</label>
          <textarea id="reason" name="reason" rows="4" required></textarea>
        </section>

        <!-- Submit Button -->
        <div class="submit-section">
          <button type="submit" class="submit-btn">Submit Loan Request</button>
        </div>
      </form>
    </div>
  <?php endif; ?>

 
  <h3>Your Previous Loan Requests</h3>
  <?php if ($loan_count > 0): ?>
    <table class="loan-request-table">
      <thead>
        <tr>
          <th>Loan Amount</th>
          <th>Loan Type</th>
          <th>Repayment Type</th>
          <th>EMI Duration</th>
          <th>Reason</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?php echo $row['loan_amount']; ?> INR</td>
            <td><?php echo ucfirst(str_replace('_', ' ', $row['loan_type'])); ?></td>
            <td><?php echo ucfirst(str_replace('_', ' ', $row['repayment_type'])); ?></td>
            <td><?php echo $row['emi_duration'] ? $row['emi_duration'] . ' Months' : 'N/A'; ?></td>
            <td><?php echo $row['reason']; ?></td>
            <td class="status-<?php echo strtolower($row['status']); ?>"><?php echo ucfirst($row['status']); ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No previous loan requests found.</p>
  <?php endif; ?>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>
