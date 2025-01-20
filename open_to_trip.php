<?php
session_start();
include('connection.php');
 include('includes/header.php');
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch users open to trips
$queryOpenTrips = "SELECT u.id, u.fullname, us.trip_location, us.trip_start_date, us.trip_duration, us.mode_of_transport, us.mobile_number 
                   FROM tbl_users u 
                   JOIN user_status us ON u.id = us.user_id 
                   WHERE us.status = 'Open To Trip'";
$openTrips = mysqli_query($conn, $queryOpenTrips);

// Fetch requests received by the user (excluding rejected requests)
$userId = $_SESSION['user_id'];
$queryReceivedRequests = "
    SELECT r.id, u.fullname, us.mobile_number, r.status 
    FROM tbl_requests r 
    JOIN tbl_users u ON r.user_id = u.id 
    JOIN user_status us ON u.id = us.user_id 
    WHERE r.requested_user_id = '$userId' AND r.status != 'Rejected'
";
$receivedRequests = mysqli_query($conn, $queryReceivedRequests);

// Fetch all requests made by the user
$querySentRequests = "
    SELECT r.id, u.fullname, r.status 
    FROM tbl_requests r 
    JOIN tbl_users u ON r.requested_user_id = u.id 
    WHERE r.user_id = '$userId'
";
$sentRequests = mysqli_query($conn, $querySentRequests);

// Fetch rejected requests
$queryRejectedRequests = "
    SELECT r.id, u.fullname, r.status 
    FROM tbl_requests r 
    JOIN tbl_users u ON r.requested_user_id = u.id 
    WHERE r.user_id = '$userId' AND r.status = 'Rejected'
";
$rejectedRequests = mysqli_query($conn, $queryRejectedRequests);
?>
<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWB-Get Connected</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .jumbotron {
            background-color: #e9ecef;
            border-radius: 10px;
            padding: 2rem;
        }
        h3 {
            color: #007bff;
        }
        table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        table:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        th {
            background-color: #f1f1f1;
            font-weight: bold;
        }
        .btn {
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        .modal-footer {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>


<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <p class="lead text-primary font-weight-bold"><?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
        <hr class="my-4">
        
        <div class="row">
            <div class="col-md-6">
                <h3>Open to Trips</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Trip Location</th>
                            <th>Trip Start Date</th>
                            <th>Duration</th>
                            <th>Transport Mode</th>
                            <th>Mobile Number</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($trip = mysqli_fetch_assoc($openTrips)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($trip['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($trip['trip_location']); ?></td>
                                <td><?php echo htmlspecialchars($trip['trip_start_date']); ?></td>
                                <td><?php echo htmlspecialchars($trip['trip_duration']); ?></td>
                                <td><?php echo htmlspecialchars($trip['mode_of_transport']); ?></td>
                                <td><?php echo htmlspecialchars($trip['mobile_number']); ?></td>
                                <td>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#requestModal" data-user-id="<?php echo $trip['id']; ?>" data-user-name="<?php echo htmlspecialchars($trip['fullname']); ?>">Connect</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="col-md-6">
                <h3>Requests Received</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile Number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($request = mysqli_fetch_assoc($receivedRequests)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($request['mobile_number']); ?></td>
                                <td><?php echo htmlspecialchars($request['status']); ?></td>
                                <td>
                                    <form action="handle_request.php" method="POST">
                                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                        <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <h3>All Requested</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($sentRequest = mysqli_fetch_assoc($sentRequests)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($sentRequest['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($sentRequest['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Rejected Requests</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($rejectedRequest = mysqli_fetch_assoc($rejectedRequests)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($rejectedRequest['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($rejectedRequest['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Connect Request Modal -->
<div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="requestModalLabel" aria-hidden="true">
    <div class="modal-dialog request-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Connect with <span id="modalUserName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="send_request.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="requested_user_id" id="userId" value="">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <div class="form-group">
                        <label for="adhar_no">Adhar No</label>
                        <input type="text" class="form-control" name="adhar_no" required>
                    </div>
                    <div class="form-group">
                        <label for="pan_no">PAN No</label>
                        <input type="text" class="form-control" name="pan_no" required>
                    </div>
                    <div class="form-group">
                        <label for="contact_no">Contact No</label>
                        <input type="text" class="form-control" name="contact_no" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Set user ID and name for request modal
    $('#requestModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var userId = button.data('user-id');
        var userName = button.data('user-name');
        
        var modal = $(this);
        modal.find('#userId').val(userId);
        modal.find('#modalUserName').text(userName);
    });
});
</script>

<?php include('includes/footer.php');?>
</body>
</html>
