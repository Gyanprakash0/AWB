<?php

include 'connection.php';


$query = "SELECT b.id, b.title, b.theme, b.description, b.image, b.created_at, u.fullname 
          FROM blogs b
          JOIN tbl_users u ON b.user_id = u.id";


$titleFilter = isset($_GET['title']) ? mysqli_real_escape_string($conn, $_GET['title']) : '';
$themeFilter = isset($_GET['theme']) ? mysqli_real_escape_string($conn, $_GET['theme']) : '';


if ($titleFilter || $themeFilter) {
    $query .= " WHERE 1=1"; 
    
    if ($titleFilter) {
        $query .= " AND b.title LIKE '%$titleFilter%'";
    }
    
    if ($themeFilter) {
        $query .= " AND b.theme = '$themeFilter'";
    }
}

$query .= " ORDER BY b.created_at DESC";


$result = mysqli_query($conn, $query);
if (!$result) {
    die("Error: " . mysqli_error($conn));
}


$blogs = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWB Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/style.css">
    <style>
        
        .blog-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }
        .blog-item {
            background: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 10px;
            width: 30%;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
            margin-bottom: 20px;
        }
        .blog-item:hover {
            transform: translateY(-5px);
        }
        .blog-image img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .blog-content {
            padding: 15px;
        }
        .blog-title {
            font-size: 20px;
            font-weight: bold;
            margin: 0;
        }
        .blog-theme {
            font-size: 14px;
            color: #555;
        }
        .blog-description {
            font-size: 16px;
            margin: 10px 0;
            color: #333;
        }
        .read-more {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php include 'includes/loader.php'; renderLoader(); ?>
<?php include 'includes/navbar.php'; ?>

<form style="margin-top:90px; margin-left:400px;" method="GET" action="" class="filter-form">
    <label for="title">Search by Title: </label>
    <input type="text" name="title" id="title" placeholder="Enter blog title" value="<?= htmlspecialchars($titleFilter) ?>">

    <label for="theme">Filter by Theme: </label>
    <select class="form-control" id="theme" name="theme" required>
        <option value="">Select Theme</option>
        <option value="Travel" <?= $themeFilter == 'Travel' ? 'selected' : '' ?>>Travel</option>
        <option value="Adventures" <?= $themeFilter == 'Adventures' ? 'selected' : '' ?>>Adventures</option>
        <option value="Nature" <?= $themeFilter == 'Nature' ? 'selected' : '' ?>>Nature</option>
        <option value="Culture" <?= $themeFilter == 'Culture' ? 'selected' : '' ?>>Culture</option>
        <option value="Food" <?= $themeFilter == 'Food' ? 'selected' : '' ?>>Food</option>
        <option value="Lifestyle" <?= $themeFilter == 'Lifestyle' ? 'selected' : '' ?>>Lifestyle</option>
    </select>
    <button type="submit">Filter</button>
</form>


<div class="blog-list">
    <?php
    foreach ($blogs as $blog): ?>
        <div class="blog-item">
            <div class="blog-image">
                <img src="<?= htmlspecialchars($blog['image']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>">
            </div>
            <div class="blog-content">
                <p class="blog-title"><?= htmlspecialchars($blog['title']) ?></p>
                <p class="blog-theme"><?= htmlspecialchars($blog['theme']) ?></p>
                <p class="blog-description"><?= substr($blog['description'], 0, 100) ?>...</p>
                <a href="javascript:void(0);" class="read-more" onclick="openModal(<?= $blog['id'] ?>)">Read More</a>
            </div>
        </div>
    <?php endforeach; ?>
</div>


<div id="blog-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modal-title"></h2>
        <p id="modal-author"></p>
        <p id="modal-date"></p>
        <div id="modal-description"></div>
    </div>
</div>

<script>
    
    function openModal(blogId) {
        const blog = <?php echo json_encode($blogs); ?>.find(b => b.id == blogId);

        
        document.getElementById('modal-title').innerText = blog.title;
        document.getElementById('modal-author').innerText = "Posted by: " + blog.fullname;
        document.getElementById('modal-date').innerText = "Created on: " + new Date(blog.created_at).toLocaleDateString();
        document.getElementById('modal-description').innerHTML = blog.description;

       
        document.getElementById('blog-modal').style.display = 'block';
    }

    
    function closeModal() {
        document.getElementById('blog-modal').style.display = 'none';
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const loader = document.getElementById('loader');
        const mainContent = document.getElementById('main-content');
        const dots = document.querySelectorAll('.dot');
        
       
        let progress = 0;
        const interval = setInterval(() => {
            if (progress < 3) {
                dots[progress].style.opacity = 1;
                progress++;
            } else {
                clearInterval(interval);
            }
        }, 500);

        setTimeout(() => {
            loader.style.display = 'none'; 
            mainContent.classList.remove('hidden'); 
        }, 2000);
    });
</script>
<?php include 'includes/footer.php';?>

</body>
</html>

<?php

mysqli_close($conn);
?>
