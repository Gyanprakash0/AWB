<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>
<?php
session_start();
include('connection.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$userId = $_SESSION['user_id'];
$queryRequests = "SELECT r.id, u.fullname, r.adhar_no, r.pan_no, r.contact_no, r.status, r.created_date 
                  FROM tbl_requests r 
                  JOIN tbl_users u ON r.requested_user_id = u.id 
                  WHERE r.user_id = '$userId'";
$requests = mysqli_query($conn, $queryRequests);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include('includes/header.php'); ?>

<div class="container mt-5">
    <h2>Your Requests</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Adhar No</th>
                <th>PAN No</th>
                <th>Contact No</th>
                <th>Status</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($request = mysqli_fetch_assoc($requests)) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($request['adhar_no']); ?></td>
                    <td><?php echo htmlspecialchars($request['pan_no']); ?></td>
                    <td><?php echo htmlspecialchars($request['contact_no']); ?></td>
                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                    <td><?php echo htmlspecialchars($request['created_date']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include('includes/footer.php'); ?>
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