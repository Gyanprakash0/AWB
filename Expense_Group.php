<?php
session_start();
include('connection.php');

// Create Group Functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_group'])) {
    $group_name = $_POST['group_name'];
    $user_id = $_SESSION['user_id'];
    $unique_id = strtoupper(bin2hex(random_bytes(3))); // Generate a unique 6-digit ID

    $stmt = $conn->prepare("INSERT INTO tbl_groups_new (unique_id, group_name, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $unique_id, $group_name, $user_id);
    $stmt->execute();
}

// Join Group Functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_group'])) {
    $unique_id = $_POST['unique_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT group_id FROM tbl_groups_new WHERE unique_id = ?");
    $stmt->bind_param("s", $unique_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $group_id = $row['group_id'];
        $stmt = $conn->prepare("INSERT INTO tbl_group_members_new (user_id, group_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $group_id);
        $stmt->execute();
    } else {
        echo "<script>alert('Group not found.');</script>";
    }
}

// Fetch groups for the user
$user_id = $_SESSION['user_id'];
$groups = [];
$stmt = $conn->prepare("SELECT * FROM tbl_groups_new WHERE created_by = ? OR group_id IN (SELECT group_id FROM tbl_group_members_new WHERE user_id = ?)");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $groups[] = $row;
}

// Handle adding expenses
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    $group_id = $_POST['group_id'];
    $type = $_POST['type'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    
    // Handle file upload
    $proof_image = '';
    if (isset($_FILES['proof_image']) && $_FILES['proof_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $proof_image = $target_dir . basename($_FILES["proof_image"]["name"]);
        move_uploaded_file($_FILES["proof_image"]["tmp_name"], $proof_image);
    }

    if (in_array('all', $_POST['split_users'])) {
        // Fetch all members for the group
        $stmt = $conn->prepare("SELECT user_id FROM tbl_group_members_new WHERE group_id = ?");
        $stmt->bind_param("i", $group_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $split_users = [];
        while ($row = $result->fetch_assoc()) {
            $split_users[] = $row['user_id'];
        }
        $split_users = implode(',', $split_users);
    } else {
        $split_users = implode(',', $_POST['split_users']);
    }

    // Insert the expense record into the database
    $stmt = $conn->prepare("INSERT INTO tbl_expenses_total (user_id, group_id, type, payment_method, amount, description, split_users, proof) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iississs", $user_id, $group_id, $type, $payment_method, $amount, $description, $split_users, $proof_image);
    $stmt->execute();
}

// Fetch notifications
$notifications = [];
$stmt = $conn->prepare("
    SELECT e.*, u.username, g.group_name 
    FROM tbl_expenses_total e 
    JOIN tbl_users u ON e.user_id = u.id 
    JOIN tbl_groups_new g ON e.group_id = g.group_id 
    ORDER BY e.payment_method ASC
");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

// Fetch expenses for the selected group
$expenses = [];
if (isset($_POST['view_expenses'])) {
    $group_id = $_POST['group_id'];
    $stmt = $conn->prepare("SELECT * FROM tbl_expenses_total WHERE group_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $group_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $expenses[] = $row;
    }
}

// Fetch group members for user selection
$members = [];
if (isset($_POST['group_id'])) {
    $group_id = $_POST['group_id'];
    $stmt = $conn->prepare("SELECT u.id, u.username FROM tbl_group_members_new gm JOIN tbl_users u ON gm.user_id = u.id WHERE gm.group_id = ?");
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $members[] = $row;
    }
}

include('includes/header.php');
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 col-lg-3" style="border-right: 1px solid #ddd; padding: 20px;">
            <h2>Create Group</h2>
            <form method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" name="group_name" placeholder="Group Name" required>
                </div>
                <button type="submit" class="btn btn-primary" name="create_group">Create Group</button>
            </form>

            <h2>Join Group</h2>
            <form method="POST">
                <div class="form-group">
                    <input type="text" class="form-control" name="unique_id" placeholder="Enter 6-digit Group ID" required>
                </div>
                <button type="submit" class="btn btn-primary" name="join_group">Join Group</button>
            </form>

            <h2>Your Groups</h2>
            <ul class="list-group">
                <?php foreach ($groups as $group): ?>
                    <li class="list-group-item" ondblclick="showExpenseForm(<?php echo $group['group_id']; ?>)">
                        <?php echo htmlspecialchars($group['group_name']); ?> (ID: <?php echo htmlspecialchars($group['unique_id']); ?>)
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="col-md-8 col-lg-9" style="padding: 20px;">
            <div id="expense-form" style="display:none;">
                <h2>Add Expense</h2>
                <form method="POST" id="expenseForm" enctype="multipart/form-data">
                    <input type="hidden" name="group_id" id="group_id">
                    <div class="form-group">
                        <label>Type:</label>
                        <select class="form-control" name="type" required>
                            <option value="food">Food</option>
                            <option value="travel">Travel</option>
                            <option value="fare">Fare</option>
                            <option value="cloth">Cloth</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Payment Method:</label>
                        <select class="form-control" name="payment_method" required>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Amount:</label>
                        <input type="number" class="form-control" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <input type="text" class="form-control" name="description" required>
                    </div>
                    <div class="form-group">
                        <label>Select Users to Split:</label>
                        <select class="form-control" name="split_users[]" id="split_users" multiple required>
                            <option value="all">All</option>
                            <?php foreach ($members as $member): ?>
                                <option value="<?php echo $member['id']; ?>"><?php echo htmlspecialchars($member['username']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Proof of Payment:</label>
                        <input type="file" class="form-control" name="proof_image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-success" name="add_expense">Add Expense</button>
                    <button type="submit" class="btn btn-info" name="view_expenses">List Your Expenses</button>
                </form>
            </div>

            <?php if (!empty($expenses)): ?>
                <h2>Your Expenses</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Split Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expenses as $expense): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($expense['description']); ?></td>
                                <td>₹<?php echo htmlspecialchars($expense['amount']); ?></td>
                                <td><?php echo htmlspecialchars($expense['split_users']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <div id="notification-box" style="height: 300px; overflow-y: auto; display: none;">
                <h2>Notifications</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Group</th>
                            <th>Type</th>
                            <th>Payment Method</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Proof</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($notifications)): ?>
                            <tr>
                                <td colspan="7">No notifications available.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($notifications as $notification): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($notification['username']); ?></td>
                                    <td><?php echo htmlspecialchars($notification['group_name']); ?></td>
                                    <td><?php echo htmlspecialchars($notification['type']); ?></td>
                                    <td><?php echo htmlspecialchars($notification['payment_method']); ?></td>
                                    <td>₹<?php echo htmlspecialchars($notification['amount']); ?></td>
                                    <td><?php echo htmlspecialchars($notification['description']); ?></td>
                                    <td>
                                        <?php if ($notification['proof']): ?>
                                            <a href="<?php echo htmlspecialchars($notification['proof']); ?>" target="_blank">View Proof</a>
                                        <?php else: ?>
                                            No Proof
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function showExpenseForm(groupId) {
        document.getElementById('group_id').value = groupId;
        document.getElementById('expense-form').style.display = 'block';
        document.getElementById('notification-box').style.display = 'block'; // Show notifications
    }
</script>

<?php include('includes/footer.php'); ?>
