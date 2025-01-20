
<?php 
session_start(); 

include('Connection.php'); 
include('includes/header.php'); 

global $conn;

?>

<style>
body {
    background-color: #f8f9fa; 
}

.sidebar {
    border-right: 1px solid #dee2e6; 
}

.card {
    border: none; 
    border-radius: 10px; 
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); 
}

.card-title {
    font-weight: bold; 
}

.comment-list p {
    border-bottom: 1px solid #dee2e6; 
    padding: 5px 0; 
}

.btn-light {
    transition: background-color 0.3s, color 0.3s; 
}

.btn-light:hover {
    background-color: #007bff; 
    color: white; 
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 bg-light">
            <h3 class="mt-3 text-center">Navigation</h3>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Feeds</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="my_posts.php">My Posts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">My Videos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Travel</a>
                </li>
            </ul>
            <button class="btn btn-primary mt-4 btn-block" data-toggle="modal" data-target="#postModal">Create Post</button>
        </div>

        <div class="col-md-9">
            <div class="jumbotron text-center">
                <h1 class="display-4">Welcome!</h1>
                <p class="lead text-primary font-weight-bold"><?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
                <hr class="my-4">
                <h2>Feed</h2>

                <!-- Search Form -->
                <form id="searchForm" class="mt-3">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search posts..." required>
                    <button type="submit" class="btn btn-primary mt-2">Search</button>
                </form>
            </div>

            <div id="posts" class="mb-4">
                <?php
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
                    echo '<p>No posts available.</p>';
                }

                $stmt->close();

                function getLikeCount($post_id) {
                    global $conn; // Make $conn accessible within this function
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
                    $stmt->bind_param("i", $post_id);
                    $stmt->execute();
                    $stmt->bind_result($count);
                    $stmt->fetch();
                    return $count;
                }

                function displayComments($post_id) {
                    global $conn; // Make $conn accessible within this function
                    $stmt = $conn->prepare("SELECT c.comment, u.fullname FROM comments c JOIN tbl_users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at DESC");
                    $stmt->bind_param("i", $post_id);
                    $stmt->execute();
                    $stmt->bind_result($comment, $fullname);
                    
                    while ($stmt->fetch()) {
                        echo '<p><strong>' . htmlspecialchars($fullname) . ':</strong> ' . htmlspecialchars($comment) . '</p>';
                    }
                    
                    $stmt->close();
                }

                function displayPost($row) {
                    global $conn; // Make $conn accessible within this function
                    echo '<div class="card mb-3 p-3">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($row['date_time']) . ' - ' . htmlspecialchars($row['post_type']) . '</h6>';
                    echo '<p class="card-text">' . htmlspecialchars($row['description']) . '</p>';
                    echo '<p class="card-text"><small class="text-muted">' . htmlspecialchars($row['location']) . '</small></p>';

                    // Display media if available
                    if (!empty($row['media_url'])) {
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

                    // Like button
                    $post_id = $row['id'];
                    echo '<div class="mt-2">';
                    echo '<button class="btn btn-light btn-sm like-button" data-post-id="' . $post_id . '">Like</button>';
                    echo '<span class="like-count" id="like-count-' . $post_id . '">' . getLikeCount($post_id) . ' Likes</span>';
                    echo '</div>';

                    // Comment section
                    echo '<div class="comments-section" id="comments-' . $post_id . '">';
                    echo '<h6>Comments:</h6>';
                    echo '<form class="comment-form" data-post-id="' . $post_id . '">
                            <input type="text" class="form-control" name="comment" placeholder="Add a comment" required>
                            <button type="submit" class="btn btn-primary btn-sm mt-1">Comment</button>
                          </form>';
                    echo '<div class="comment-list" id="comment-list-' . $post_id . '">';
                    displayComments($post_id);
                    echo '</div></div>';

                    echo '</div></div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModalLabel">Create a Post</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="postForm" method="POST" action="submit_post.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" class="form-control" id="location" name="location">
                    </div>
                    <div class="form-group">
                        <label for="date_time">Date & Time:</label>
                        <input type="datetime-local" class="form-control" id="date_time" name="date_time" required>
                    </div>
                    <div class="form-group">
                        <label for="post_type">Post Type:</label>
                        <select class="form-control" id="post_type" name="post_type">
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="media_type">Media Type:</label>
                        <select class="form-control" id="media_type" name="media_type">
                            <option value="">None</option>
                            <option value="image">Image</option>
                            <option value="video">Video</option>
                            <option value="audio">Audio</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="media_file">Upload Media:</label>
                        <input type="file" class="form-control-file" id="media_file" name="media_file">
                    </div>
                    <button type="submit" class="btn btn-primary">Publish Post</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Handle like button click
    $('.like-button').click(function() {
        var postId = $(this).data('post-id');
        $.post('like.php', { post_id: postId }, function(data) {
            $('#like-count-' + postId).text(data + ' Likes');
        });
    });

    // Handle comment form submission
    $('.comment-form').submit(function(e) {
        e.preventDefault();
        var postId = $(this).data('post-id');
        var comment = $(this).find('input[name="comment"]').val();
        $.post('comment.php', { post_id: postId, comment: comment }, function(data) {
            $('#comment-list-' + postId).append(data);
            $('input[name="comment"]').val(''); // Clear input
        });
    });

    // Handle search form submission
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        var searchTerm = $('#searchInput').val();
        $.get('search_post.php', { search: searchTerm }, function(data) {
            $('#posts').html(data);
        });
    });
});
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
