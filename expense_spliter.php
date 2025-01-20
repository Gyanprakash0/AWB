<?php
session_start();
include('connection.php'); 


$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<h2>Please log in to manage expenses.</h2>";
    exit; 
}


function fetchUserInfo($conn, $user_id) {
    $stmt = $conn->prepare("SELECT username FROM tbl_users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


function fetchUserGroups($conn, $user_id) {
    $groups = [];
    $stmt = $conn->prepare("
        SELECT DISTINCT g.group_id, g.group_name 
        FROM tbl_group g
        JOIN tbl_group_members gm ON g.group_id = gm.group_id
        WHERE gm.user_id = ? OR g.created_by = ?
    ");
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $groups[] = $row;
    }
    return $groups;
}


function fetchGroupMembers($conn, $group_id) {
    $members = [];
    $stmt = $conn->prepare("SELECT user_id FROM tbl_group_members WHERE group_id = ?");
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $members[] = $row['user_id'];
    }
    return $members;
}


function fetchGroupExpenses($conn, $group_id) {
    $expenses = [];
    $stmt = $conn->prepare("SELECT e.type, e.payment_method, e.proof, e.amount, u.username AS payer_name FROM tbl_expenses e JOIN tbl_users u ON e.user_id = u.id WHERE e.group_id = ?");
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    return $stmt->get_result();
}


function insertExpense($conn, $user_id, $type, $payment_method, $file_path, $group_id, $amount, $description) {
    $stmt = $conn->prepare("INSERT INTO tbl_expenses (user_id, type, payment_method, proof, group_id, amount, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssis", $user_id, $type, $payment_method, $file_path, $group_id, $amount, $description);
    return $stmt->execute();
}


$group_id = $_GET['group_id'] ?? null;


$user_info = fetchUserInfo($conn, $user_id);
$groups = fetchUserGroups($conn, $user_id);
$members = [];
$total_expense = 0;

if ($group_id) {
    $members = fetchGroupMembers($conn, $group_id);
    $expense_result = fetchGroupExpenses($conn, $group_id);
    while ($row = $expense_result->fetch_assoc()) {
        $total_expense += $row['amount'];
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

   
    $file_path = '';
    if ($payment_method == 'cash' && isset($_FILES['receipt']) && $_FILES['receipt']['error'] == 0) {
        $file_path = 'uploads/' . basename($_FILES['receipt']['name']);
        move_uploaded_file($_FILES['receipt']['tmp_name'], $file_path);
    } elseif ($payment_method == 'upi' && isset($_FILES['upi_ss']) && $_FILES['upi_ss']['error'] == 0) {
        $file_path = 'uploads/' . basename($_FILES['upi_ss']['name']);
        move_uploaded_file($_FILES['upi_ss']['tmp_name'], $file_path);
    }

    if (insertExpense($conn, $user_id, $type, $payment_method, $file_path, $group_id, $amount, $description)) {
        echo "<script>alert('Expense record added successfully!');</script>";
    }
}


include('includes/header.php');
?>

<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="boxes">
                <h2>Select a Group</h2>
                <form method="GET" action="">
                    <select class="form-select" name="group_id" onchange="this.form.submit()">
                        <option value="">-- Select a group --</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?php echo $group['group_id']; ?>" <?php echo ($group['group_id'] == $group_id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($group['group_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <?php if ($group_id): ?>
                <div class="boxes mt-4">
                    <h2>Add Expense</h2>
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type of Expense:</label>
                            <select id="type" name="type" class="form-select" required>
                                <option value="food">Food</option>
                                <option value="travel">Travel</option>
                                <option value="fare">Fare</option>
                                <option value="cloth">Cloth</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount:</label>
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description:</label>
                            <input type="text" class="form-control" name="description" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method:</label>
                            <select id="payment_method" name="payment_method" class="form-select" required>
                                <option value="cash">Cash</option>
                                <option value="upi">UPI</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3" id="receipt-container" style="display:none;">
                            <label for="receipt" class="form-label">Upload Receipt:</label>
                            <input type="file" class="form-control" id="receipt" name="receipt">
                        </div>
                        <div class="mb-3" id="upi-container" style="display:none;">
                            <label for="upi_ss" class="form-label">Upload UPI Screenshot:</label>
                            <input type="file" class="form-control" id="upi_ss" name="upi_ss">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Expense</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6" style="border: 1px solid #ddd; padding: 20px;">
            <h3>Expenses: - <span style="color:lightgreen;"><?php echo $total_expense; ?></span></h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Payer</th>
                        <th>Type</th>
                        <th>Method</th>
                        <th>Proof</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($group_id) {
                        $expense_result = fetchGroupExpenses($conn, $group_id);
                        while ($row = $expense_result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['payer_name']}</td>
                                <td>{$row['type']}</td>
                                <td>{$row['payment_method']}</td>
                                <td><a href='{$row['proof']}' target='_blank'>".($row['proof'] ? 'View Proof' : 'N/A')."</a></td>
                                <td>{$row['amount']}</td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        const receiptContainer = document.getElementById('receipt-container');
        const upiContainer = document.getElementById('upi-container');
        receiptContainer.style.display = this.value === 'cash' ? 'block' : 'none';
        upiContainer.style.display = this.value === 'upi' ? 'block' : 'none';
    });
</script>

<style>
    body {
        min-height: 100vh;
    }
    .boxes {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        background-color: #f9f9f9;
    }
    h2, h3 {
        color: #333;
    }
</style>

<?php include('includes/footer.php'); ?>
