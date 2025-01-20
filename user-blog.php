<?php include('includes/header.php'); ?>
<?php 

include('Connection.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="display-4">Welcome!</h1>
        <p class="lead text-primary font-weight-bold"><?php echo $_SESSION['fullname']; ?></p>
        <hr class="my-4">
        <p>User Blog Starts Here</p>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" id="success-message">
                <?php echo $_SESSION['success_message']; ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" id="error-message">
                <?php echo $_SESSION['error_message']; ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="submit_blog.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Blog Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
                <button type="button" class="btn btn-secondary btn-sm" id="titleMicButton" style="margin-left: 10px;">
                    <i class="fas fa-microphone"></i>
                </button>
            </div>
            <div class="form-group">
                <label for="theme">Select Theme:</label>
                <select class="form-control" id="theme" name="theme" required>
                    <option value="Travel">Travel</option>
                    <option value="Adventures">Adventures</option>
                    <option value="Nature">Nature</option>
                    <option value="Culture">Culture</option>
                    <option value="Food">Food</option>
                    <option value="Lifestyle">Lifestyle</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="10" required></textarea>
                <small class="form-text text-muted">Write more than 2000 words if possible.</small>
                <button type="button" class="btn btn-secondary btn-sm" id="descriptionMicButton" style="margin-left: 10px;">
                    <i class="fas fa-microphone"></i>
                </button>
            </div>
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" class="form-control-file" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <hr class="my-4">
        <h2>Your Submitted Blogs</h2>
        
        <?php
        $user_id = $_SESSION['user_id'];
        $query = "SELECT * FROM blogs WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<table class="table"><thead><tr><th>Title</th><th>Theme</th><th>Actions</th></tr></thead><tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . htmlspecialchars($row['title']) . '</td>
                        <td>' . htmlspecialchars($row['theme']) . '</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modifyModal" 
                            data-id="' . $row['id'] . '" data-title="' . htmlspecialchars($row['title']) . '" 
                            data-theme="' . htmlspecialchars($row['theme']) . '" data-description="' . htmlspecialchars($row['description']) . '">Modify</button>
                            <a href="delete_blog.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" 
                            onclick="return confirm(\'Are you sure you want to delete this blog?\');">Delete</a>
                        </td>
                      </tr>';
            }
            echo '</tbody></table>';
        } else {
            echo '<p>No blogs submitted yet.</p>';
        }
        ?>
    </div>
</div>

<div class="modal fade" id="modifyModal" tabindex="-1" role="dialog" aria-labelledby="modifyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modifyModalLabel">Modify Blog</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color: #f8f9fa;">
                <form id="modifyForm" action="update_blog.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="modifyId" name="id">
                    <div class="form-group">
                        <label for="modifyTitle">Blog Title:</label>
                        <input type="text" class="form-control" id="modifyTitle" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="modifyTheme">Select Theme:</label>
                        <select class="form-control" id="modifyTheme" name="theme" required>
                            <option value="Travel">Travel</option>
                            <option value="Adventures">Adventures</option>
                            <option value="Nature">Nature</option>
                            <option value="Culture">Culture</option>
                            <option value="Food">Food</option>
                            <option value="Lifestyle">Lifestyle</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="modifyDescription">Description:</label>
                        <textarea class="form-control" id="modifyDescription" name="description" rows="10" required></textarea>
                        <small class="form-text text-muted">Write more than 2000 words if possible.</small>
                    </div>
                    <div class="form-group">
                        <label for="modifyImage">Upload Image:</label>
                        <input type="file" class="form-control-file" id="modifyImage" name="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/4.25.0-lts/standard/ckeditor.js"></script>

<script>
    CKEDITOR.replace('description');
    CKEDITOR.replace('modifyDescription');

    $('#modifyModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var title = button.data('title');
        var theme = button.data('theme');
        var description = button.data('description');

        var modal = $(this);
        modal.find('#modifyId').val(id);
        modal.find('#modifyTitle').val(title);
        modal.find('#modifyTheme').val(theme);
        modal.find('#modifyDescription').val(description);
    });

    // Speech Recognition
    let titleInput = document.getElementById('title');
    let descriptionInput = document.getElementById('description');

    if ('webkitSpeechRecognition' in window) {
        let recognition = new webkitSpeechRecognition();
        recognition.continuous = false; // Change to false to stop after one input
        recognition.interimResults = false; // No interim results

        let currentField = null;

        recognition.onresult = function(event) {
            let transcript = '';
            for (let i = event.resultIndex; i < event.results.length; i++) {
                transcript += event.results[i][0].transcript;
            }
            transcript = transcript.trim();

            if (currentField === 'title') {
                titleInput.value += (titleInput.value ? ' ' : '') + transcript; 
            } else if (currentField === 'description') {
                descriptionInput.value += (descriptionInput.value ? ' ' : '') + transcript; 
            }

            // Reset current field to allow for new input
            currentField = null;
        };

        recognition.onerror = function(event) {
            console.error("Speech recognition error: " + event.error);
        };

        const startRecognition = (inputField) => {
            currentField = inputField;
            recognition.stop(); // Ensure any previous recognition is stopped
            recognition.start(); // Start recognition
        };

        document.getElementById('titleMicButton').onclick = () => startRecognition('title');
        document.getElementById('descriptionMicButton').onclick = () => startRecognition('description');

    } else {
        alert("Your browser does not support speech recognition. Please use Google Chrome.");
    }

    setTimeout(function() {
        var message = document.getElementById('success-message');
        if (message) {
            message.style.display = 'none';
        }
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 5000);
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
*/ 
?>
