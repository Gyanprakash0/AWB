<?php
session_start();
include('connection.php'); 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


if (!file_exists('travel_clips')) {
    mkdir('travel_clips', 0777, true);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['reel'])) {
    $user_id = $_SESSION['user_id'];
    $file_name = $_FILES['reel']['name'];
    $file_tmp = $_FILES['reel']['tmp_name'];
    $file_type = $_FILES['reel']['type'];

    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $location = isset($_POST['location']) ? $_POST['location'] : '';

    // Validate file type
    $allowed_types = ['video/mp4', 'video/avi', 'video/mkv'];
    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($file_tmp, "travel_clips/$file_name")) {
            $query = "INSERT INTO reels (user_id, file_name, title, description, location) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("issss", $user_id, $file_name, $title, $description, $location);
            $stmt->execute();

            $_SESSION['message'] = "Reel uploaded successfully!";
        } else {
            $_SESSION['message'] = "Failed to upload video. Please try again.";
        }
    } else {
        $_SESSION['message'] = "Invalid file type. Please upload a video.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Clips</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container">
        <h2>Upload Clips</h2>
        
        <?php
        if (isset($_SESSION['message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
            unset($_SESSION['message']);
        }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="reel">Select Video:</label>
                <input type="file" class="form-control" id="reel" name="reel" required>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" class="form-control" id="location" name="location">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
</html>
