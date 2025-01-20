<?php
session_start();
include('connection.php');


$query = "SELECT r.*, u.username, m.likes, m.views, m.comments FROM reels r 
          JOIN tbl_users u ON r.user_id = u.id 
          LEFT JOIN reels_meta_data m ON r.id = m.reel_id 
          ORDER BY RAND()";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Clips</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .video-card { margin-bottom: 20px; opacity: 0; animation: fadeIn 0.5s forwards; width: 80%; margin: 0 auto; }
        @keyframes fadeIn { to { opacity: 1; } }
        .video-frame { width: 100%; height: auto; max-height: 300px; }
        .comments-list { margin-top: 10px; }
        .comment-actions { display: flex; justify-content: space-between; align-items: center; }
        .liked { color: red; }
        .share-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
        .share-content { background: white; padding: 20px; margin: 100px auto; width: 300px; border-radius: 5px; }
        .start-watching { margin: 20px 0; }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container">
        <h2>All Clips</h2>
        <button id="startWatching" class="btn btn-primary start-watching">Start Watching</button>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card video-card">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= htmlspecialchars($row['title']); ?></h5>
                        <video class="video-frame" controls data-id="<?= $row['id']; ?>">
                            <source src="travel_clips/<?= htmlspecialchars($row['file_name']); ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <p>Uploaded by: <?= htmlspecialchars($row['username']); ?></p>
                        <p>Description: <?= htmlspecialchars($row['description']); ?></p>
                        <p>Location: <?= htmlspecialchars($row['location']); ?></p>
                        <p>Likes: <span id="likes-<?= $row['id']; ?>"><?= $row['likes']; ?></span></p>
                        <p>Views: <span id="views-<?= $row['id']; ?>"><?= $row['views']; ?></span></p>
                        <p>Comments: <span id="comments-<?= $row['id']; ?>"><?= htmlspecialchars($row['comments']); ?></span></p>

                        <div class="comment-actions">
                            <button id="like-button-<?= $row['id']; ?>" onclick="likeReel(<?= $row['id']; ?>)" class="btn btn-light btn-sm">
                                <i class="fa fa-heart" id="like-icon-<?= $row['id']; ?>"></i> Like
                            </button>
                            <button onclick="showShareModal('AWB/travel_clips/<?= htmlspecialchars($row['file_name']); ?>')" class="btn btn-light btn-sm">
                                <i class="fa fa-share"></i> Share
                            </button>
                        </div>

                        <div>
                            <textarea id="comment-<?= $row['id']; ?>" placeholder="Add a comment..." class="form-control" style="margin-top: 10px;"></textarea>
                            <button onclick="addComment(<?= $row['id']; ?>)" class="btn btn-light btn-sm" style="margin-top: 5px;">Comment</button>
                        </div>

                        <div class="comments-list" id="comments-list-<?= $row['id']; ?>">
                            <?php 
                                $comments = explode('|', $row['comments']);
                                foreach ($comments as $comment) {
                                    echo '<p>' . htmlspecialchars($comment) . '</p>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reels available.</p>
        <?php endif; ?>
    </div>

    <div class="share-modal" id="shareModal">
        <div class="share-content">
            <h5>Share this Reel</h5>
            <p id="shareLink"></p>
            <button id="copyButton" class="btn btn-light btn-sm"><i class="fa fa-copy"></i> Copy Link</button>
            <button onclick="shareViaEmail()" class="btn btn-light btn-sm"><i class="fa fa-envelope"></i> Share via Email</button>
            <button onclick="closeShareModal()" class="btn btn-light btn-sm" style="margin-top: 10px;">Close</button>
        </div>
    </div>

    <script>
        let userInteracted = false;

        
        document.getElementById('startWatching').addEventListener('click', () => {
            userInteracted = true;
            document.getElementById('startWatching').style.display = 'none'; 
        });

        const handlePlay = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && userInteracted) {
                    const video = entry.target;
                    video.play();
                    
                    document.querySelectorAll('.video-frame').forEach(v => {
                        if (v !== video) {
                            v.pause();
                        }
                    });
                } else {
                    entry.target.pause();
                }
            });
        };

        const observer = new IntersectionObserver(handlePlay, {
            threshold: 0.5 
        });

        document.querySelectorAll('.video-frame').forEach(video => {
            observer.observe(video);
        });

        function likeReel(reelId) {
            $.post("like_reel.php", { reel_id: reelId }, function(response) {
                if (response.success) {
                    let likesCount = parseInt($("#likes-" + reelId).text()) + 1;
                    $("#likes-" + reelId).text(likesCount);
                    $("#like-icon-" + reelId).addClass("liked"); 
                }
            }, "json");
        }

        $(".video-frame").on("play", function() {
            let reelId = $(this).data("id");
            viewReel(reelId);
        });

        function viewReel(reelId) {
            $.post("view_reel.php", { reel_id: reelId }, function(response) {
                if (response.success) {
                    let viewsCount = parseInt($("#views-" + reelId).text()) + 1;
                    $("#views-" + reelId).text(viewsCount);
                }
            }, "json");
        }

        function addComment(reelId) {
            let comment = $("#comment-" + reelId).val();
            $.post("add_comment.php", { reel_id: reelId, comment: comment }, function(response) {
                if (response.success) {
                    let commentsList = $("#comments-list-" + reelId);
                    commentsList.append('<p>' + comment + '</p>');
                    $("#comment-" + reelId).val(''); 
                }
            }, "json");
        }

        function showShareModal(videoPath) {
            const shareLink = window.location.origin + '/' + videoPath;
            $("#shareLink").text(shareLink);
            $("#shareModal").show();
        }

        function closeShareModal() {
            $("#shareModal").hide();
        }

        document.getElementById('copyButton').onclick = function() {
            const shareLink = document.getElementById('shareLink').innerText;
            navigator.clipboard.writeText(shareLink).then(() => {
                alert('Link copied to clipboard!');
            });
        };

        function shareViaEmail() {
            const shareLink = document.getElementById('shareLink').innerText;
            window.location.href = `mailto:?subject=Check out this reel&body=${shareLink}`;
        }
    </script>
</body>

<?php include('includes/footer.php'); ?>

</html>
