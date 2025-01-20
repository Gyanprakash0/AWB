<?php
session_start();
include('Connection.php');

if (!isset($_SESSION['user_id'])) {
    exit("User not logged in.");
}

$userId = $_SESSION['user_id'];

$query = "
    SELECT u.id, u.fullname, us.your_location, us.mobile_number, 
           (SELECT COUNT(*) FROM messages 
            WHERE receiver_id = u.id AND sender_id = ? AND seen = 0) AS unseen_count
    FROM tbl_users u 
    JOIN user_status us ON u.id = us.user_id 
    WHERE us.status = 'Open to trip' AND u.id != ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No users open to trip.";
} else {
    while ($row = $result->fetch_assoc()) {
        
        $maskedPhone = substr($row['mobile_number'], 0, 3) . 'XXXXX';
        $unseenCount = $row['unseen_count'] > 0 ? " (" . $row['unseen_count'] . " unseen)" : "";
        
        echo '<li class="list-group-item user-item" data-id="' . $row['id'] . '" data-name="' . htmlspecialchars($row['fullname']) . '" data-location="' . htmlspecialchars($row['your_location']) . '">'
            . htmlspecialchars($row['fullname']) . $unseenCount . '<br>'
            . '<small>' . htmlspecialchars($row['your_location']) . '</small><br>'
            . '<small>' . htmlspecialchars($maskedPhone) . '</small>'
            . '</li>';
    }
}
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