<?php
session_start();
include('Connection.php');

// Handle search term
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM posts WHERE title LIKE ? OR description LIKE ? ORDER BY date_time DESC";
$stmt = $conn->prepare($query);
$searchWildcard = '%' . $searchTerm . '%';
$stmt->bind_param("ss", $searchWildcard, $searchWildcard);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        displayPost($row);
    }
} else {
    echo '<p>No posts found.</p>';
}

$stmt->close();

function displayPost($row) {
    echo '<div class="card mb-3 p-3">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
    echo '<h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($row['date_time']) . ' - ' . htmlspecialchars($row['post_type']) . '</h6>';
    echo '<p class="card-text">' . htmlspecialchars($row['description']) . '</p>';
    echo '<p class="card-text"><small class="text-muted">' . htmlspecialchars($row['location']) . '</small></p>';

    // Display media if available
    if (!empty($row['media_url'])) {
        displayMedia($row);
    }

    echo '</div></div>';
}

function displayMedia($row) {
    if ($row['media_type'] == 'image') {
        echo '<img src="' . htmlspecialchars($row['media_url']) . '" class="img-fluid rounded" alt="Post Image">';
    } elseif ($row['media_type'] == 'video') {
        echo '<video controls class="img-fluid rounded"><source src="' . htmlspecialchars($row['media_url']) . '" type="video/mp4">Your browser does not support the video tag.</video>';
    } elseif ($row['media_type'] == 'audio') {
        echo '<audio controls><source src="' . htmlspecialchars($row['media_url']) . '" type="audio/mpeg">Your browser does not support the audio element.</audio>';
    } elseif ($row['media_type'] == 'pdf') {
        echo '<iframe src="' . htmlspecialchars($row['media_url']) . '" width="100%" height="400px" class="rounded"></iframe>';
    }
}
