<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['name'])) { 
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $query = $_POST['query'];

        
        $stmt = $conn->prepare("INSERT INTO inquiries (name, email, phone, query) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $phone, $query);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        
        $stmt->close();
    } elseif (isset($_POST['userName'])) { 
        $userName = $_POST['userName'];
        $idCard = $_POST['idCard'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $price = $_POST['price'];

   
        $stmt = $conn->prepare("INSERT INTO bookings (userName, idCard, email, phone, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $userName, $idCard, $email, $phone, $price);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }

        $stmt->close();
    }
}
$conn->close();
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