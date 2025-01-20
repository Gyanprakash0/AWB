<?php 
session_start(); 
include('Connection.php'); 
include('includes/header.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}


function isFutureDate($date) {
    return strtotime($date) > time();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_status'])) {
    $user_id = $_SESSION['user_id'];
    $name = trim($_POST['name']);
    $mobile_number = trim($_POST['mobile_number']);
    $trip_start_date = trim($_POST['trip_start_date']);
    $trip_duration = trim($_POST['trip_duration']);
    $gender = trim($_POST['gender']);
    $preference = trim($_POST['preference']);
    $status = trim($_POST['status']);
    $other_status = isset($_POST['other_status']) ? trim($_POST['other_status']) : '';
    $your_location = trim($_POST['your_location']);
    $trip_location = trim($_POST['trip_location']);
    $mode_of_transport = trim($_POST['mode_of_transport']);

    
    if (!validateDate($trip_start_date) || !isFutureDate($trip_start_date)) {
        $_SESSION['error_message'] = "Invalid date. Please enter a valid date in the format YYYY-MM-DD and ensure it is in the future.";
    } else {
        
        $stmt = $conn->prepare("INSERT INTO user_status (user_id, name, mobile_number, trip_start_date, trip_duration, gender, preference, status, other_status, your_location, trip_location, mode_of_transport) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ississssssss", $user_id, $name, $mobile_number, $trip_start_date, $trip_duration, $gender, $preference, $status, $other_status, $your_location, $trip_location, $mode_of_transport);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Status submitted successfully.";
            } else {
                $_SESSION['error_message'] = "Error: " . $stmt->error;
            }
        } else {
            $_SESSION['error_message'] = "Failed to prepare statement: " . $conn->error;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_status'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $mobile_number = trim($_POST['mobile_number']);
    $trip_start_date = trim($_POST['trip_start_date']);
    $trip_duration = trim($_POST['trip_duration']);
    $gender = trim($_POST['gender']);
    $preference = trim($_POST['preference']);
    $status = trim($_POST['status']);
    $other_status = isset($_POST['other_status']) ? trim($_POST['other_status']) : '';
    $your_location = trim($_POST['your_location']);
    $trip_location = trim($_POST['trip_location']);
    $mode_of_transport = trim($_POST['mode_of_transport']);

   
    if (!validateDate($trip_start_date) || !isFutureDate($trip_start_date)) {
        $_SESSION['error_message'] = "Invalid date. Please enter a valid date in the format YYYY-MM-DD and ensure it is in the future.";
    } else {
       
        $stmt = $conn->prepare("UPDATE user_status SET name=?, mobile_number=?, trip_start_date=?, trip_duration=?, gender=?, preference=?, status=?, other_status=?, your_location=?, trip_location=?, mode_of_transport=? WHERE id=?");
        if ($stmt) {
            $stmt->bind_param("ssissssssssi", $name, $mobile_number, $trip_start_date, $trip_duration, $gender, $preference, $status, $other_status, $your_location, $trip_location, $mode_of_transport, $id);
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Status updated successfully.";
            } else {
                $_SESSION['error_message'] = "Error: " . $stmt->error;
            }
        } else {
            $_SESSION['error_message'] = "Failed to prepare statement: " . $conn->error;
        }
    }
}


$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM user_status WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

include('includes/header.php'); 
?>
 <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<div class="jumbotron jumbotron-fluid bg-light">
    <div class="container text-center">
        <h1 class="display-4 text-primary">User Status</h1>
        <p class="lead text-secondary"><?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
        <hr class="my-4">

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success_message']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error_message']; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="" method="POST" class="mt-4">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="mobile_number">Mobile Number:</label>
                    <input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="trip_start_date">Trip Start Date:</label>
                    <input type="text" class="form-control" id="trip_start_date" name="trip_start_date" placeholder="YYYY-MM-DD" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="trip_duration">Trip Duration (Days):</label>
                    <input type="number" class="form-control" id="trip_duration" name="trip_duration" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="gender">Gender:</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="preference">Preference:</label>
                    <select class="form-control" id="preference" name="preference" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="1">Open to trip</option>
                        <option value="2">On Trip</option>
                        <option value="3">Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="other_status">If Other, please specify:</label>
                    <input type="text" class="form-control" id="other_status" name="other_status">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="your_location">Your Location:</label>
                    <input type="text" class="form-control" id="your_location" name="your_location" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="trip_location">Trip Location:</label>
                    <input type="text" class="form-control" id="trip_location" name="trip_location" required>
                </div>
            </div>
            <div class="form-group">
                <label for="mode_of_transport">Mode of Transport:</label>
                <select class="form-control" id="mode_of_transport" name="mode_of_transport" required>
                    <option value="Air">Air</option>
                    <option value="Train">Train</option>
                    <option value="Roadways">Roadways</option>
                </select>
            </div>
            <button type="submit" name="submit_status" class="btn btn-primary">Submit Status</button>
        </form>

        <hr class="my-4">

        <h2 class="mt-4">Current Statuses</h2>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Name</th>
                <th>Mobile</th>
                <th>Start Date</th>
                <th>Duration</th>
                <th>Gender</th>
                <th>Preference</th>
                <th>Status</th>
                <th>Your Location</th>
                <th>Trip Location</th>
                <th>Mode of Transport</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['trip_start_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['trip_duration']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td><?php echo htmlspecialchars($row['preference']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td><?php echo htmlspecialchars($row['your_location']); ?></td>
                        <td><?php echo htmlspecialchars($row['trip_location']); ?></td>
                        <td><?php echo htmlspecialchars($row['mode_of_transport']); ?></td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" 
                                    data-id="<?php echo $row['id']; ?>" 
                                    data-name="<?php echo htmlspecialchars($row['name']); ?>" 
                                    data-mobile="<?php echo htmlspecialchars($row['mobile_number']); ?>" 
                                    data-startdate="<?php echo htmlspecialchars($row['trip_start_date']); ?>" 
                                    data-duration="<?php echo htmlspecialchars($row['trip_duration']); ?>" 
                                    data-gender="<?php echo htmlspecialchars($row['gender']); ?>" 
                                    data-preference="<?php echo htmlspecialchars($row['preference']); ?>" 
                                    data-status="<?php echo htmlspecialchars($row['status']); ?>" 
                                    data-otherstatus="<?php echo htmlspecialchars($row['other_status']); ?>" 
                                    data-yourlocation="<?php echo htmlspecialchars($row['your_location']); ?>" 
                                    data-triplocation="<?php echo htmlspecialchars($row['trip_location']); ?>" 
                                    data-transport="<?php echo htmlspecialchars($row['mode_of_transport']); ?>">Edit
                            </button>
                            <a href="delete_status.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="11" class="text-center">No statuses found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="" method="POST">
                    <input type="hidden" id="editId" name="id">
                    <div class="form-group">
                        <label for="editName">Name:</label>
                        <input type="text" class="form-control" id="editName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="editMobile">Mobile Number:</label>
                        <input type="text" class="form-control" id="editMobile" name="mobile_number" required>
                    </div>
                    <div class="form-group">
                        <label for="editStartDate">Trip Start Date:</label>
                        <input type="text" class="form-control" id="editStartDate" name="trip_start_date" placeholder="YYYY-MM-DD" required>
                    </div>
                    <div class="form-group">
                        <label for="editDuration">Trip Duration (Days):</label>
                        <input type="number" class="form-control" id="editDuration" name="trip_duration" required>
                    </div>
                    <div class="form-group">
                        <label for="editGender">Gender:</label>
                        <select class="form-control" id="editGender" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editPreference">Preference:</label>
                        <select class="form-control" id="editPreference" name="preference" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editStatus">Status:</label>
                        <select class="form-control" id="editStatus" name="status" required>
                            <option value="1">Open to trip</option>
                            <option value="2">On Trip</option>
                            <option value="3">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editOtherStatus">If Other, please specify:</label>
                        <input type="text" class="form-control" id="editOtherStatus" name="other_status">
                    </div>
                    <div class="form-group">
                        <label for="editYourLocation">Your Location:</label>
                        <input type="text" class="form-control" id="editYourLocation" name="your_location" required>
                    </div>
                    <div class="form-group">
                        <label for="editTripLocation">Trip Location:</label>
                        <input type="text" class="form-control" id="editTripLocation" name="trip_location" required>
                    </div>
                    <div class="form-group">
                        <label for="editModeOfTransport">Mode of Transport:</label>
                        <select class="form-control" id="editModeOfTransport" name="mode_of_transport" required>
                            <option value="Air">Air</option>
                            <option value="Train">Train</option>
                            <option value="Roadways">Roadways</option>
                        </select>
                    </div>
                    <button type="submit" name="edit_status" class="btn btn-primary">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var name = button.data('name');
        var mobile = button.data('mobile');
        var startdate = button.data('startdate');
        var duration = button.data('duration');
        var gender = button.data('gender');
        var preference = button.data('preference');
        var status = button.data('status');
        var otherstatus = button.data('otherstatus');
        var yourlocation = button.data('yourlocation');
        var triplocation = button.data('triplocation');
        var transport = button.data('transport');

        var modal = $(this);
        modal.find('#editId').val(id);
        modal.find('#editName').val(name);
        modal.find('#editMobile').val(mobile);
        modal.find('#editStartDate').val(startdate);
        modal.find('#editDuration').val(duration);
        modal.find('#editGender').val(gender);
        modal.find('#editPreference').val(preference);
        modal.find('#editStatus').val(status);
        modal.find('#editOtherStatus').val(otherstatus);
        modal.find('#editYourLocation').val(yourlocation);
        modal.find('#editTripLocation').val(triplocation);
        modal.find('#editModeOfTransport').val(transport);
    });
</script>


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

<?php include('includes/footer.php'); ?>
<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>