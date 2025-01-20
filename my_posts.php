<?php session_start(); include('includes/header.php'); ?>
<?php include('Connection.php'); ?>
<?php include('includes/requirements.php'); ?>
<div class="container">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    
    <div id="notification" class="alert" style="display: none;"></div>

    <?php
    
    if (isset($_SESSION['message'])) {
        echo '<script>
                $(document).ready(function() {
                    $("#notification").text("' . $_SESSION['message'] . '").show();
                    $("#notification").addClass("alert-success").removeClass("alert-danger");
                });
              </script>';
        unset($_SESSION['message']);
    }

    // Fetch posts for the logged-in user
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM posts WHERE user_id = ? ORDER BY date_time DESC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="card mb-3">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
            echo '<h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($row['date_time']) . ' - ' . htmlspecialchars($row['post_type']) . '</h6>';
            echo '<p class="card-text">' . htmlspecialchars($row['description']) . '</p>';
            echo '<p class="card-text"><small class="text-muted">' . htmlspecialchars($row['location']) . '</small></p>';
    
            // Display media
            if ($row['media_type'] == 'image') {
                echo '<img src="' . htmlspecialchars($row['media_url']) . '" class="img-fluid" alt="Post Image">';
            } elseif ($row['media_type'] == 'video') {
                echo '<video controls class="img-fluid"><source src="' . htmlspecialchars($row['media_url']) . '" type="video/mp4">Your browser does not support the video tag.</video>';
            } elseif ($row['media_type'] == 'audio') {
                echo '<audio controls><source src="' . htmlspecialchars($row['media_url']) . '" type="audio/mpeg">Your browser does not support the audio tag.</audio>';
            } elseif ($row['media_type'] == 'pdf') {
                echo '<a href="' . htmlspecialchars($row['media_url']) . '" target="_blank">View PDF</a>';
            }
    
            // Buttons for Like, Comment, Edit, and Delete
            echo '<div class="mt-2">';
            echo '<button class="btn btn-light btn-sm">Like</button> ';
            echo '<button class="btn btn-light btn-sm">Comment</button> ';
            echo '<button class="btn btn-warning btn-sm edit-btn" data-id="' . $row['id'] . '" data-title="' . htmlspecialchars($row['title']) . '" data-description="' . htmlspecialchars($row['description']) . '" data-location="' . htmlspecialchars($row['location']) . '" data-media-url="' . htmlspecialchars($row['media_url']) . '" data-toggle="modal" data-target="#editModal"><i class="fas fa-edit"></i> Edit</button> ';
            echo '<button class="btn btn-danger btn-sm delete-btn" data-id="' . $row['id'] . '" data-toggle="modal" data-target="#deleteModal"><i class="fas fa-trash-alt"></i> Delete</button>';
            echo '</div>';
            echo '</div></div>';
        }
    } else {
        echo '<p>No posts available.</p>';
    }
    ?>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editForm" action="edit_post.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="post_id" id="post_id">
                        <div class="form-group">
                            <label for="post_title">Title</label>
                            <input type="text" class="form-control" id="post_title" name="post_title" required>
                        </div>
                        <div class="form-group">
                            <label for="post_description">Description</label>
                            <textarea class="form-control" id="post_description" name="post_description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="post_location">Location</label>
                            <input type="text" class="form-control" id="post_location" name="post_location" required>
                        </div>
                        <div class="form-group">
                            <label for="post_media">Change Media</label>
                            <input type="file" class="form-control" id="post_media" name="post_media" accept="image/*,video/*,audio/*,application/pdf">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this post?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                    <form id="deleteForm" action="delete_post.php" method="POST" style="display:inline;">
                        <input type="hidden" name="post_id" id="delete_post_id">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    // Edit button functionality
    $(document).on('click', '.edit-btn', function() {
        const postId = $(this).data('id');
        const postTitle = $(this).data('title');
        const postDescription = $(this).data('description');
        const postLocation = $(this).data('location');

        $('#post_id').val(postId);
        $('#post_title').val(postTitle);
        $('#post_description').val(postDescription);
        $('#post_location').val(postLocation);
    });

    // Delete button functionality
    $(document).on('click', '.delete-btn', function() {
        const postId = $(this).data('id');
        $('#delete_post_id').val(postId);
    });

    // Show notification message
    function showNotification(message, type) {
        $("#notification").text(message).show();
        if (type === 'success') {
            $("#notification").addClass("alert-success").removeClass("alert-danger");
        } else {
            $("#notification").addClass("alert-danger").removeClass("alert-success");
        }
        setTimeout(function() {
            $("#notification").fadeOut();
        }, 3000);
    }
</script>

</div>

<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>

<?php include('includes/footer.php'); ?>
