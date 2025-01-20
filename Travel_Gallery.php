<?php
session_start();
include('connection.php');
include('includes/header.php');  


if (!isset($_SESSION['user_id'])) {
    die("Please log in to access the gallery.");
}

$user_id = $_SESSION['user_id'];  


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_folder'])) {
    if (!empty($_POST['folder_name'])) {
        $folder_name = $_POST['folder_name'];

        
        $stmt = $conn->prepare("INSERT INTO tbl_folders (user_id, folder_name) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $folder_name); 
        $stmt->execute();

        echo "<p>Folder '$folder_name' created successfully!</p>";
    } else {
        echo "<p>Please enter a folder name.</p>";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['files'])) {
    $total_files = count($_FILES['files']['name']);
    $folder_id = isset($_POST['folder_id']) ? $_POST['folder_id'] : null; 

    
    for ($i = 0; $i < $total_files; $i++) {
        $file_name = $_FILES['files']['name'][$i];
        $file_tmp = $_FILES['files']['tmp_name'][$i];
        $file_type = $_FILES['files']['type'][$i];
        $file_size = $_FILES['files']['size'][$i];

        
        $upload_dir = 'Gallery/';
        $upload_path = $upload_dir . basename($file_name);

        
        if (move_uploaded_file($file_tmp, $upload_path)) {
           
            $stmt = $conn->prepare("INSERT INTO tbl_files (user_id, folder_id, file_name, file_type, file_size) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $user_id, $folder_id, $file_name, $file_type, $file_size);  
            $stmt->execute();

            echo "<p>File '$file_name' uploaded successfully!</p>";
        } else {
            echo "<p>Error uploading file '$file_name'.</p>";
        }
    }
}


$folder_id = isset($_GET['folder_id']) ? $_GET['folder_id'] : null;


$query = "SELECT * FROM tbl_folders WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$folders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


$files = [];
if ($folder_id) {
    $query = "SELECT * FROM tbl_files WHERE folder_id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $folder_id, $user_id);
    $stmt->execute();
    $files = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $query = "SELECT * FROM tbl_files WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $files = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}


$recent_files = [];
if ($folder_id) {
    $query = "SELECT * FROM tbl_files WHERE folder_id = ? AND user_id = ? ORDER BY file_id DESC LIMIT 3";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $folder_id, $user_id);
    $stmt->execute();
    $recent_files = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<style>

.folder-create-container {
    text-align: center;
    margin-bottom: 20px;
}

.folder-create-btn img {
    width: 20px;
    height:20px;
    cursor: pointer;
}

.folder-input-container {
    margin-top: 10px;
}

.folder-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.folder-card {
    width: 200px;
    margin: 10px;
    text-align: center;
    border: 1px solid #ddd;
    padding: 20px;
    border-radius: 8px;
    transition: 0.3s;
}

.folder-card:hover {
    background-color: #f1f1f1;
}

.folder-icon img {
    width: 100px;
    height: 100px;
}

/* File Display */
.file-list {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

.file-card {
    width: 200px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    transition: 0.3s;
}

.file-card:hover {
    background-color: #f1f1f1;
}

.file-thumbnail img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
}

.modal img {
    max-width: 100%;
    height: auto;
}


.spotlight {
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
}

.spotlight img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ddd;
}

.spotlight div {
    text-align: center;
}

.spotlight h4 {
    margin-top: 5px;
}
</style>

<!-- HTML content starts here -->
<div class="container">
    <h1 style="text-align:center;">Welcome to AWB Travel Gallery</h1>

    <!-- Folder creation form -->
    <div class="folder-create-container">
        <form method="POST" action="Travel_Gallery.php">
            <button type="button" class="folder-create-btn" onclick="document.getElementById('folder_name_input').style.display='block'">
                <img src="images/create-folder.png" alt="Create Folder">
            </button>
            <div id="folder_name_input" class="folder-input-container" style="display: none;">
                <input type="text" name="folder_name" placeholder="Enter folder name" required>
                <button type="submit" name="create_folder">Create Folder</button>
            </div>
        </form>
    </div>

    <!-- Display user's folders -->
    <h2>Trip Created</h2>
    <div class="folder-list">
        <?php foreach ($folders as $folder): ?>
            <div class="folder-card">
                <a href="Travel_Gallery.php?folder_id=<?= $folder['folder_id']; ?>">
                    <div class="folder-icon">
                        <img src="images/folder.png" alt="Folder Icon">
                    </div>
                    <h3><?= htmlspecialchars($folder['folder_name']); ?></h3>
                </a>
                <!-- Upload button for each folder -->
                <form method="POST" enctype="multipart/form-data" action="Travel_Gallery.php">
                    <input type="hidden" name="folder_id" value="<?= $folder['folder_id']; ?>">
                    <input type="file" name="files[]" multiple required>
                    <button type="submit">Upload Files</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- If a folder is selected, display recent photos as spotlight -->
    <?php if ($folder_id): ?>
        <h2>Spotlights of the Trip - <?= htmlspecialchars($folders[0]['folder_name']); ?></h2>
        <div class="spotlight">
            <?php foreach ($recent_files as $file): ?>
                <?php if (strpos($file['file_type'], 'image') !== false): ?>
                    <div>
                        <img src="Gallery/<?= htmlspecialchars($file['file_name']); ?>" alt="Image">
                        <h4><?= htmlspecialchars($file['file_name']); ?></h4>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Display files from the gallery or selected folder -->
    <h2>Your Files</h2>
    <div class="file-list">
        <?php foreach ($files as $file): ?>
            <div class="file-card">
                <div class="file-thumbnail">
                    <?php if (strpos($file['file_type'], 'image') !== false): ?>
                        <img src="Gallery/<?= htmlspecialchars($file['file_name']); ?>" alt="Image" data-toggle="modal" data-target="#imageModal<?= $file['file_id']; ?>">
                    <?php elseif (strpos($file['file_type'], 'video') !== false): ?>
                        <video width="100%" height="150" controls>
                            <source src="Gallery/<?= htmlspecialchars($file['file_name']); ?>" type="<?= htmlspecialchars($file['file_type']); ?>">
                        </video>
                    <?php endif; ?>
                </div>
                <h4><?= htmlspecialchars($file['file_name']); ?></h4>
                <p>Size: <?= round($file['file_size'] / 1024 / 1024, 2); ?> MB</p>
                <p>Type: <?= htmlspecialchars($file['file_type']); ?></p>

                <!-- Modal for image zoom -->
                <?php if (strpos($file['file_type'], 'image') !== false): ?>
                    <div class="modal" id="imageModal<?= $file['file_id']; ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <img src="Gallery/<?= htmlspecialchars($file['file_name']); ?>" alt="Image" class="img-fluid">
                                </div>
                                <div class="modal-footer">
                                    <a href="Gallery/<?= htmlspecialchars($file['file_name']); ?>" download class="btn btn-primary">Download</a>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include('includes/footer.php');   ?>
