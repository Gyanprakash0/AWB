<?php
session_start(); 
include('connection.php'); 
include('includes/header.php'); 
?>

<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="display-4">Welcome!</h1>
        <p class="lead text-primary font-weight-bold"><?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
        <hr class="my-4">
        <h1>Connect +</h1>
    </div>
</div>

<div class="container">
    <h2>Profile Matching</h2>

    <div class="row">
        <div class="col-md-6">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="your_location">Enter Your Location</label>
                    <input type="text" class="form-control" id="your_location" name="your_location" required>
                </div>
                <div class="form-group">
                    <label for="trip_duration">Trip Days</label>
                    <input type="number" class="form-control" id="trip_duration" name="trip_duration" required>
                </div>
                <div class="form-group">
                    <label for="trip_location">Trip Location</label>
                    <input type="text" class="form-control" id="trip_location" name="trip_location" required>
                </div>
                <div class="form-group">
                    <label for="mode_of_transport">Mode of Transport</label>
                    <select class="form-control" id="mode_of_transport" name="mode_of_transport" required>
                        <option value="Roadways">Roadways</option>
                        <option value="Air">Air</option>
                        <option value="Train">Train</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Find Matches</button>
            </form>
        </div>

        <div class="col-md-6">
           
            <div class="signal-wave mt-4">
                <?php
                $matches = [];
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Get user inputs
                    $your_location = $_POST['your_location'];
                    $trip_duration = $_POST['trip_duration'];
                    $trip_location = $_POST['trip_location'];
                    $mode_of_transport = $_POST['mode_of_transport'];

                    
                    $query = "SELECT us.*, u.email FROM user_status us
                              JOIN tbl_users u ON us.user_id = u.id
                              WHERE us.your_location LIKE ? 
                              AND us.trip_duration = ? 
                              AND us.trip_location LIKE ? 
                              AND us.mode_of_transport = ?";

                    // Prepare and execute the query
                    $stmt = mysqli_prepare($conn, $query);
                    $your_location = "%$your_location%"; // Using LIKE
                    $trip_location = "%$trip_location%"; // Using LIKE
                    mysqli_stmt_bind_param($stmt, "siss", $your_location, $trip_duration, $trip_location, $mode_of_transport);
                    
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    while ($match = mysqli_fetch_assoc($result)) {
                        $matches[] = $match;
                    }
                    
                    mysqli_stmt_close($stmt);
                }

                if ($matches): ?>
                    <ul class="list-group">
                        <?php foreach ($matches as $match): ?>
                            <li class="list-group-item">
                                <a href="mailto:<?php echo htmlspecialchars($match['email']); ?>">
                                    <i class="fa fa-user"></i> 
                                    <?php echo htmlspecialchars($match['name'] . ' (' . $match['your_location'] . ')'); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No matches found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.signal-wave {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.list-group-item {
    display: flex;
    align-items: center;
}

.list-group-item i {
    margin-right: 10px; 
}


@media (max-width: 768px) {
    .row {
        flex-direction: column; 
    }
}
</style>
<br><br>

<?php include('includes/footer.php'); ?>

<script>
   
    if ('webkitSpeechRecognition' in window) {
        let recognition = new webkitSpeechRecognition();
        recognition.continuous = true; 
        recognition.interimResults = false; // No interim results

        recognition.onresult = function(event) {
            let transcript = event.results[event.resultIndex][0].transcript.toLowerCase().trim();
            console.log("Recognized text: " + transcript);

            // Handle specific commands
            if (transcript.includes("open awb mic")) {
                recognition.start(); 
            } else if (transcript.includes("enter my location")) {
                recognition.start(); 
            } else if (transcript.includes("trip days")) {
                recognition.start(); 
            } else if (transcript.includes("trip location")) {
                recognition.start(); 
            } else if (transcript.includes("mode of transport")) {
                recognition.start(); 
            } else {
               
                if (document.getElementById('your_location').value === '') {
                    document.getElementById('your_location').value = transcript;
                } else if (document.getElementById('trip_duration').value === '') {
                    document.getElementById('trip_duration').value = transcript;
                } else if (document.getElementById('trip_location').value === '') {
                    document.getElementById('trip_location').value = transcript;
                } else if (document.getElementById('mode_of_transport').value === '') {
                    document.getElementById('mode_of_transport').value = transcript;
                }
            }
        };

        recognition.onerror = function(event) {
            console.error("Speech recognition error: " + event.error);
        };

        recognition.start(); 
    } else {
        alert("Your browser does not support speech recognition. Please use Google Chrome.");
    }
</script>

<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/ 
?>
