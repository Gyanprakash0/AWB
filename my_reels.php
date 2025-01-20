<?php
session_start();
include('connection.php'); 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$user_id = $_SESSION['user_id'];
$user_reels_query = "SELECT * FROM reels WHERE user_id = ?";
$user_reels_stmt = $conn->prepare($user_reels_query);
$user_reels_stmt->bind_param("i", $user_id);
$user_reels_stmt->execute();
$user_reels_result = $user_reels_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reels</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container">
        <h2>My Reels</h2>

        <?php
        if ($user_reels_result->num_rows > 0) {
            while ($row = $user_reels_result->fetch_assoc()) {
                echo '<div class="card video-card">';
                echo '<div class="card-body text-center">';
                echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                echo '<video class="video-frame" controls>';
                echo '<source src="travel_clips/' . htmlspecialchars($row['file_name']) . '" type="video/mp4">';
                echo 'Your browser does not support the video tag.';
                echo '</video>';
                echo '<div>';
                echo '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row['id'] . '">Delete</button>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No reels uploaded by you.</p>';
        }
        ?>
    </div>

    <script>
        $(document).ready(function() {
            $(".delete-btn").click(function() {
                const reelId = $(this).data("id");
                if (confirm("Are you sure you want to delete this reel?")) {
                    $.post("delete_reel.php", { id: reelId }, function(response) {
                        location.reload(); // Reload page after delete
                    });
                }
            });
        });
    </script>

    <?php include('includes/footer.php'); ?>
</body>
</html>
