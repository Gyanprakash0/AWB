<?php
session_start();
include('connection.php'); 
include('includes/header.php'); 

$userId = $_SESSION['user_id']; 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupName = $_POST['group_name'];

    if (!empty($groupName)) {
        $stmt = $conn->prepare("INSERT INTO tbl_group (group_name, created_by) VALUES (?, ?)");
        $stmt->bind_param("si", $groupName, $userId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<div class='alert success'>Group created successfully.</div>";
        } else {
            echo "<div class='alert error'>Failed to create group.</div>";
        }
    } else {
        echo "<div class='alert error'>Group name cannot be empty.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traveling Group</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <style>
        body {
            display: flex;
            flex-direction: column;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa; 
        }

        .container {
            display: flex;
            flex-wrap: wrap; 
            justify-content: space-between; 
            padding: 20px;
            gap: 20px; 
        }

        .create-group, .group-list {
            flex: 1;
            min-width: 300px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
            padding: 20px;
        }

        h2, h3 {
            color: #343a40;
        }

        form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 16px;
        }

        button {
            background-color: #007bff; 
            border: none;
            color: white;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3; 
        }

        #groupItems {
            list-style-type: none; 
            padding: 0;
        }

        .group-item {
            padding: 10px;
            border-bottom: 1px solid #e9ecef; 
            cursor: pointer;
            display: flex; 
            align-items: center; 
            transition: background-color 0.2s;
        }

        .group-item:hover {
            background-color: #e9ecef; 
        }

        .group-item i {
            margin-right: 10px; 
            color: #007bff; 
        }

        .alert {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda; 
            color: #155724; 
        }

        .error {
            background-color: #f8d7da; 
            color: #721c24; 
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column; 
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="create-group">
            <h2>Create Group</h2>
            <form method="POST">
                <input type="text" name="group_name" placeholder="Enter Group Name" required>
                <button type="submit">Create Group</button>
            </form>
        </div>

        <div class="group-list">
            <h3>Your Groups</h3>
            <h4>Created by You</h4>
            <ul id="createdGroupItems">
                <?php
               
                $stmt = $conn->prepare("SELECT group_id, group_name FROM tbl_group WHERE created_by = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    echo "<li class='group-item' data-group-id='{$row['group_id']}'><i class='fas fa-users'></i>{$row['group_name']} (Created by Me)</li>";
                }
                ?>
            </ul>

            <h4>Joined Groups</h4>
            <ul id="joinedGroupItems">
                <?php
                
                $stmt = $conn->prepare("
                    SELECT g.group_id, g.group_name, g.created_by
                    FROM tbl_group g
                    JOIN tbl_group_members gm ON g.group_id = gm.group_id
                    WHERE gm.user_id = ?
                ");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $creatorStmt = $conn->prepare("SELECT username FROM tbl_users WHERE id = ?");
                    $creatorStmt->bind_param("i", $row['created_by']);
                    $creatorStmt->execute();
                    $creatorResult = $creatorStmt->get_result();
                    $creatorRow = $creatorResult->fetch_assoc();

                    echo "<li class='group-item' data-group-id='{$row['group_id']}'><i class='fas fa-users'></i>{$row['group_name']} (Created by {$creatorRow['username']})</li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <script>
    document.querySelectorAll('.group-item').forEach(item => {
        item.addEventListener('dblclick', function() {
            const groupId = this.getAttribute('data-group-id');
            window.location.href = `group_interface.php?group_id=${groupId}`;
        });
    });
    </script>
</body>

<?php include('includes/footer.php'); ?>
</html>
