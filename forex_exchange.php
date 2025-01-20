<?php
session_start();
include('Connection.php');
include('includes/header.php'); 
$exchangeRate = null;
$withdrawn = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fromCurrency = $_POST['from_currency'];
    $toCurrency = $_POST['to_currency'];
    $amount = $_POST['amount'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $idType = $_POST['id_type'];
    $idNumber = $_POST['id_number'];

    $uploadDir = 'confidential/';
    $fileName = $_FILES['id_file']['name'];
    $fileTmp = $_FILES['id_file']['tmp_name'];
    $fileType = $_FILES['id_file']['type'];
    $fileError = $_FILES['id_file']['error'];

    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    if (in_array($fileType, $allowedTypes) && $fileError === 0) {
        $filePath = $uploadDir . basename($fileName);
        move_uploaded_file($fileTmp, $filePath);

       
        $response = file_get_contents("https://api.exchangerate-api.com/v4/latest/$fromCurrency");
        $data = json_decode($response, true);

        if (isset($data['rates'][$toCurrency])) {
            $exchangeRate = $data['rates'][$toCurrency];
            $convertedAmount = $amount * $exchangeRate;

            
            $stmt = $conn->prepare("INSERT INTO forex_requests (user_id, from_currency, to_currency, amount, converted_amount, email, mobile, id_type, id_number, id_file, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->bind_param("issdisssss", $_SESSION['user_id'], $fromCurrency, $toCurrency, $amount, $convertedAmount, $email, $mobile, $idType, $idNumber, $filePath);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        echo "Invalid file type or error uploading file.";
    }
}


if (isset($_GET['withdraw_id'])) {
    $withdrawId = $_GET['withdraw_id'];
    $stmt = $conn->prepare("UPDATE forex_requests SET status = 'withdrawn' WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $withdrawId, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    $withdrawn = true; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forex Exchange</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="display-4">Forex Exchange</h1>
        <p class="lead text-primary font-weight-bold"><?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="from_currency">From Currency</label>
                    <input type="text" class="form-control" name="from_currency" required placeholder="e.g., USD">
                </div>
                <div class="form-group col-md-4">
                    <label for="to_currency">To Currency</label>
                    <input type="text" class="form-control" name="to_currency" required placeholder="e.g., EUR">
                </div>
                <div class="form-group col-md-4">
                    <label for="amount">Amount</label>
                    <input type="number" class="form-control" name="amount" required placeholder="e.g., 100" step="0.01">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="mobile">Mobile</label>
                    <input type="text" class="form-control" name="mobile" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="id_type">Select ID Type</label>
                    <select class="form-control" name="id_type" required>
                        <option value="">Choose...</option>
                        <option value="Passport">Passport</option>
                        <option value="Aadhar">Aadhar</option>
                        <option value="Pancard">Pancard</option>
                        <option value="Govt ID">Govt Validated ID</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="id_number">ID Number</label>
                    <input type="text" class="form-control" name="id_number" required placeholder="Enter ID Number">
                </div>
                <div class="form-group col-md-4">
                    <label for="id_file">Upload ID File</label>
                    <input type="file" class="form-control" name="id_file" accept=".jpg,.jpeg,.png,.pdf" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>

        <?php if ($exchangeRate): ?>
            <div class="alert alert-success mt-4">
                <h5>Conversion Result:</h5>
                <p><?php echo htmlspecialchars($amount) . ' ' . htmlspecialchars($fromCurrency) . ' = ' . htmlspecialchars(number_format($convertedAmount, 2)) . ' ' . htmlspecialchars($toCurrency); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($withdrawn): ?>
            <div class="alert alert-warning mt-4">
                <h5>Withdrawal Successful!</h5>
                <p>Your request has been marked as withdrawn.</p>
            </div>
        <?php endif; ?>

        <h2 class="mt-5">Requested Currency Conversions</h2>
        <div class="table-responsive">
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>From Currency</th>
                        <th>To Currency</th>
                        <th>Amount</th>
                        <th>Converted Amount</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>ID Type</th>
                        <th>ID Number</th>
                        <th>ID File</th>
                        <th>Status</th>
                        <th>Request Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM forex_requests WHERE user_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $_SESSION['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['from_currency']}</td>
                                <td>{$row['to_currency']}</td>
                                <td>{$row['amount']}</td>
                                <td>{$row['converted_amount']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['mobile']}</td>
                                <td>{$row['id_type']}</td>
                                <td>{$row['id_number']}</td>
                                <td><a href='{$row['id_file']}' target='_blank'>View</a></td>
                                <td>{$row['status']}</td>
                                <td>{$row['created_at']}</td>
                                <td>";
                        if ($row['status'] === 'pending') {
                            echo "<button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#withdrawModal' data-id='{$row['id']}'>Withdraw</button>";
                        } else {
                            echo "Withdrawn";
                        }
                        echo "</td></tr>";
                    }

                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withdrawModalLabel">Confirm Withdrawal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to withdraw this request?
                </div>
                <div class="modal-footer">
                    <form method="GET" action="">
                        <input type="hidden" name="withdraw_id" id="withdrawIdInput">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Withdraw</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <?php include('includes/footer.php');  ?>
    
    <script>
        
        $('#withdrawModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); 
            var withdrawId = button.data('id'); 
            var modal = $(this);
            modal.find('#withdrawIdInput').val(withdrawId);
        });
    </script>
</body>
</html>
<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>