<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWB</title>
    <link rel="icon" href="logo.png" type="image/png">

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    
    <link rel="stylesheet" href="css/style.css">

<style>

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.6);
    animation: fadeIn 0.5s;
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 2px solid #007bff; /* Border color */
    border-radius: 15px; /* More rounded corners */
    width: 50%;
    transform: scale(0) translateY(-50%); /* Start scaled down and off-screen */
    animation: scaleIn 2s forwards; /* Scale-in animation */
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

@keyframes fadeIn {
    from {opacity: 0;}
    to {opacity: 1;}
}

@keyframes scaleIn {
    from {
        transform: scale(0) translateY(-50%); /* Start off-screen and scaled down */
        opacity: 0; /* Start invisible */
    }
    to {
        transform: scale(1) translateY(0); /* Final scale and position */
        opacity: 1; /* Fade in */
    }
}
.about {
    display: flex;
    align-items: center;
    padding: 20px;
}

.image {
    flex: 1;
}

.content {
    flex: 1;
    padding: 20px;
}

blockquote {
    font-style: italic;
    color: #555;
    margin: 15px 0;
}



</style>


</head>
<body>
<?php
include 'includes/loader.php'; 

renderLoader(); 


?>


<?php

include 'includes/navbar.php';


?>




<div class="search-form">

    <div id="close-search" class="fas fa-times"></div>

    <form action="">
        <input type="search" name="" placeholder="search here..." id="search-box">
        <label for="search-box" class="fas fa-search"></label>
    </form>
</div>



<section class="home" id="home">

    <div class="swiper home-slider">

        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <div class="box" style="background: url('https://images.unsplash.com/photo-1465220183275-1faa863377e3?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8bW91bnRhaW4lMjBzbm93fGVufDB8fDB8fHww') no-repeat;">
                    <div class="content">
                        <br>
                        <span>Never stop exploring the </span>
                        <h3 style="color:White;">World...</h3>
                        <p></p>
                        <a href="places.php" class="btn">get started</a>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="box second" style="background: url('https://www.bontravelindia.com/wp-content/uploads/2023/03/Best-Places-to-Visit-in-Ladakh.jpg') no-repeat;">
                    <div class="content">
                        <span style="color:white; ">make tour</span>
                        <h3 style="color:white;" >amazing</h3>
                        <p></p>
                        <a href="places.php" class="btn">get started</a>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="box" style="background: url('images/AWB.jpeg') no-repeat;">
                    <div class="content">
                        <span  style="color:white; ">explore the</span>
                        <h3 style="color:white;">new world</h3>
                        <p></p>
                        <a href="places.php" class="btn">get started</a>
                    </div>
                </div>
            </div>

        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

    </div>

</section>




<section class="category">
    <h1 class="heading">Top Places To Visit In India.</h1>
    <div class="box-container">
        <div class="box">
            <img src="https://www.maavaishnodevi.org/sites/default/files/2023-10/bhawan_img_cover.jpg" alt="">
            <h3>Vaishno Devi</h3>
            <h1 class="btn" onclick="openModal('vaishnodevi')">read more</h1>
        </div>

        <div class="box">
            <img src="images/kedarnath.jpg" alt="">
            <h3>Kedarnath</h3>
            <h1 class="btn" onclick="openModal('kedarnath')">read more</h1>
        </div>

        <div class="box">
            <img src="images/manali.jpg" alt="">
            <h3>Manali in Monsoon</h3>
            <h1 class="btn" onclick="openModal('manali')">read more</h1>
        </div>

        <div class="box">
            <img src="data:image/jpeg;base64,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" alt="">
            <h3>Banaras</h3>
            <h1 class="btn" onclick="openModal('banaras')">read more</h1>
        </div>

        <div class="box">
            <img src="images/kashmir.jpeg" alt="">
            <h3>Kashmir</h3>
            <h1 class="btn" onclick="openModal('kashmir')">read more</h1>
        </div>

        <div class="box">
            <img src="images/Nainital.jpeg" alt="">
            <h3>Nainital</h3>
            <h1 class="btn" onclick="openModal('nainital')">read more</h1>
        </div>

        <div class="box">
            <img src="data:image/jpeg;base64,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" alt="">
            <h3>Taj Mahal</h3>
            <h1 class="btn" onclick="openModal('tajmahal')">read more</h1>
        </div>

        <div class="box">
            <img src="images/ayodhya.jpeg" alt="">
            <h3>Ayodhya</h3>
            <h1 class="btn" onclick="openModal('ayodhya')">read more</h1>
        </div>
    </div>
</section>


<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modal-title"></h2>
        <ul id="modal-facts"></ul>
    </div>
</div>

<script>
    function openModal(place) {
        const modal = document.getElementById("myModal");
        const title = document.getElementById("modal-title");
        const facts = document.getElementById("modal-facts");
        
        const data = {
            vaishnodevi: {
                title: "Vaishno Devi",
                facts: [
                    "Located in Jammu & Kashmir.",
                    "Elevation: 5,200 feet.",
                    "Famous for its temple dedicated to Goddess Durga.",
                    "Attracts millions of pilgrims each year.",
                    "Best visited during the spring season."
                ]
            },
            kedarnath: {
                title: "Kedarnath",
                facts: [
                    "Located in Uttarakhand.",
                    "Elevation: 11,755 feet.",
                    "Part of the Char Dham pilgrimage.",
                    "Known for the Kedarnath Temple.",
                    "Accessible via a trek of 16 km from Gaurikund."
                ]
            },
            manali: {
                title: "Manali",
                facts: [
                    "Located in Himachal Pradesh.",
                    "Elevation: 6,726 feet.",
                    "Known for its scenic beauty and adventure sports.",
                    "Ideal for skiing in winter.",
                    "Popular honeymoon destination."
                ]
            },
            banaras: {
                title: "Banaras (Varanasi)",
                facts: [
                    "Located in Uttar Pradesh.",
                    "One of the oldest inhabited cities in the world.",
                    "Famous for its ghats and Ganga Aarti.",
                    "A major cultural and religious hub.",
                    "Best visited in winter."
                ]
            },
            kashmir: {
                title: "Kashmir",
                facts: [
                    "Known as 'Paradise on Earth'.",
                    "Elevation varies from 1,600 to 14,000 feet.",
                    "Famous for its gardens, houseboats, and dry fruits.",
                    "Ideal for trekking and skiing.",
                    "Best visited in summer and autumn."
                ]
            },
            nainital: {
                title: "Nainital",
                facts: [
                    "Located in Uttarakhand.",
                    "Elevation: 6,358 feet.",
                    "Famous for its beautiful lake.",
                    "A popular hill station for tourists.",
                    "Best visited during summer."
                ]
            },
            tajmahal: {
                title: "Taj Mahal",
                facts: [
                    "Located in Agra, Uttar Pradesh.",
                    "A UNESCO World Heritage Site.",
                    "Built by Mughal Emperor Shah Jahan in memory of his wife.",
                    "Known for its stunning white marble architecture.",
                    "Best visited in the early morning or late evening."
                ]
            },
            ayodhya: {
                title: "Ayodhya",
                facts: [
                    "Located in Uttar Pradesh.",
                    "The birthplace of Lord Rama.",
                    "Significant religious and cultural site.",
                    "Famous for its temples and historical sites.",
                    "Best visited during festivals."
                ]
            }
        };

        title.textContent = data[place].title;
        facts.innerHTML = '';
        data[place].facts.forEach(fact => {
            const li = document.createElement("li");
            li.textContent = fact;
            facts.appendChild(li);
        });

        modal.style.display = "block";
    }

    function closeModal() {
        document.getElementById("myModal").style.display = "none";
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById("myModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>





<section class="about" id="about">
    <div class="image">
        <img src="https://giffiles.alphacoders.com/136/13674.gif" alt="">
    </div>

    <div class="content">
        <h3>Memorable Outdoor Experiences</h3>
        <blockquote>
            "The journey of a thousand miles begins with one step." - Ansh Shukla
        </blockquote>
        <p id="additionalText" style="display: none;">
            At AWB, we believe that adventure awaits just beyond your doorstep. Whether it’s trekking through the mountains, camping under the stars, or exploring hidden waterfalls, every moment spent in nature is a memory in the making. Join us to create unforgettable stories and experiences that last a lifetime.
        </p>
        <a href="#" id="toggleText" class="btn">Read More</a>
    </div>
</section>
<script>
    document.getElementById("toggleText").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default anchor click behavior
        const additionalText = document.getElementById("additionalText");
        const button = document.getElementById("toggleText");

        if (additionalText.style.display === "none") {
            additionalText.style.display = "block";
            button.textContent = "Read Less";
        } else {
            additionalText.style.display = "none";
            button.textContent = "Read More";
        }
    });
</script>






<section class="shop" id="shop">

    <h1 class="heading">Offer of the Months  <span style="color:red;">Get 20% Discount </span></h1>

    <div class="swiper product-slider">

        <div class="swiper-wrapper">

            <div class="swiper-slide slide">
                <div class="image">
                    <img src="data:image/jpeg;base64,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" alt="">
                    <div class="icons">
                        <a href="Booking.php" class="fas fa-shopping-cart"></a>
                        <a href="Booking.php" class="fas fa-eye"></a>
                        <a href="Booking.php" class="fas fa-share"></a>
                    </div>
                </div>
                <div class="content">
                    <h3>Kedarnath - Uttrakhand</h3>
                    <div class="price fas fa-currency">₹ 8000 - 4N/5D </div>
                    <p>*Price for Single Person</p>

                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>

            <div class="swiper-slide slide">
                <div class="image">
                    <img src="data:image/jpeg;base64,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" alt="">
                    <div class="icons">
                        <a href="Booking.php" class="fas fa-shopping-cart"></a>
                        <a href="Booking.php" class="fas fa-eye"></a>
                        <a href="Booking.php" class="fas fa-share"></a>
                    </div>
                </div>
                <div class="content">
                    <h3>Vaishno Devi</h3>
                    <div class="price">₹ 7000 - 4N/5D </div>
                    <p>*Price For Single Person</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>

            <div class="swiper-slide slide">
                <div class="image">
                    <img src="data:image/jpeg;base64,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" alt="">
                    <div class="icons">
                        <a href="Booking.php" class="fas fa-shopping-cart"></a>
                        <a href="Booking.php" class="fas fa-eye"></a>
                        <a href="Booking.php" class="fas fa-share"></a>
                    </div>
                </div>
                <div class="content">
                    <h3>Leh-Ladakh</h3>
                    <div class="price">₹ 14000 - 6N/7D </div>
                    <p>* Valid For Single person Only</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>

            <div class="swiper-slide slide">
                <div class="image">
                    <img src="data:image/jpeg;base64,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" alt="">
                    <div class="icons">
                        <a href="Booking.php" class="fas fa-shopping-cart"></a>
                        <a href="Booking.php" class="fas fa-eye"></a>
                        <a href="Booking.php" class="fas fa-share"></a>
                    </div>
                </div>
                <div class="content">
                    <h3>Ayodhya</h3>
                    <div class="price">₹ 3000 - 2N/3D </div>
                    <p>Valid for Single person Only</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>

            
                </div>
            </div>

        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

    </div>

</section>




<?php
include('connection.php'); // Include your database connection

// Fetch recent blog posts from the database
$query = "SELECT * FROM blogs ORDER BY created_at DESC LIMIT 3"; // Adjust as needed
$result = $conn->query($query);
?>

<section class="packages" id="packages">
    <h1 class="heading">Recent Blogs</h1>

    <div class="box-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Extracting fields from the row
                $title = htmlspecialchars($row['title']);
                $description = htmlspecialchars($row['description']);
                $image = htmlspecialchars($row['image']);
                $created_at = htmlspecialchars($row['created_at']);

                // Displaying the blog post as a package
                echo '<div class="box">';
                echo '<div class="image">';
                echo '<img src="' . $image . '" alt="' . $title . '">';
                echo '</div>';
                echo '<div class="content">';
                echo '<h3>' . $title . '</h3>';
                echo '<p>' . $description . '</p>';
                echo '<div class="price">Posted on: ' . date('jS F, Y', strtotime($created_at)) . '</div>';
                
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No recent blogs found.</p>';
        }
        ?>
    </div>
</section>

<?php
$conn->close(); // Close the database connection
?>












<section class="services">

    <h1 class="heading"> WE Offer's</h1>

    <div class="box-container">

        <div class="box">
            <img src="images/serv.png" alt="">
            <h3>complete guide</h3>
            
        </div>

        <div class="box">
            <img src="images/serv2.png" alt="">
            <h3>Custom Package For Users</h3>
            
        </div>

        <div class="box">
            <img src="https://www.transparentpng.com/thumb/travel/bm9DxT-travel-tour-world-transparent.png" alt="">
            <h3>All Types of Trips Inside India / Outside India</h3>
            
        </div>

        <div class="box">
            <img src="https://www.transparentpng.com/download/airplane/clipart-airplane-transparent-15.png" alt="">
            <h3>Transport Arrangement </h3>
          
        </div>

        <div class="box">
            <img src="data:image/png;base64,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" alt="">
            <h3>Meeting + Chat and SOS Intregation </h3>
            
        </div>

        <div class="box">
            <img src="data:image/jpeg;base64,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" alt="">
            <h3>30+ Features</h3>
            
        </div>


       
        
    </div>

</section>



<?php
include('connection.php'); 


$query = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 6"; 
$result = $conn->query($query);
?>

<section class="blogs" id="blogs">
    <h1 class="heading"> Our Daily Posts </h1>

    <div class="swiper blogs-slider">
        <div class="swiper-wrapper">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Extracting fields from the row
                    $title = htmlspecialchars($row['title']);
                    $description = htmlspecialchars($row['description']);
                    $location = htmlspecialchars($row['location']);
                    $date_time = htmlspecialchars($row['date_time']);
                    $media_url = htmlspecialchars($row['media_url']);
                    $link = htmlspecialchars($row['link']);
                    
                    // Displaying the blog post
                    echo '<div class="swiper-slide slide">';
                    echo '<img src="' . $media_url . '" alt="">';
                    echo '<div class="icons">';
                    echo '<a href="#"> <i class="fas fa-calendar"></i> ' . date('jS F, Y', strtotime($date_time)) . ' </a>';
                    echo '<a href="#"> <i class="fas fa-user"></i> by admin </a>';
                    echo '</div>';
                    echo '<h3>' . $title . '</h3>';
                    echo '<p>' . $description . '</p>';
                   
                    echo '</div>';
                }
            } else {
                echo '<p>No posts found.</p>';
            }
            ?>
        </div>
    </div>
</section>

<?php
$conn->close(); // Close the database connection
?>






<?php
// Include the navbar
include 'includes/subscribe.php';
?>


<section class="clients">

    <div class="swiper clients-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide silde"><img src="data:image/jpeg;base64,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" alt=""></div>
            <div class="swiper-slide silde"><img src="https://www.hackerworld.net/gfg.png" alt=""></div>
            <div class="swiper-slide silde"><img src="images/logo.png" alt=""></div>
            <div class="swiper-slide silde"><img src="https://www.sunderdeep.ac.in/images/LOGO-GIF.gif" alt=""></div>
        </div>
    </div>

</section>



<?php
// Include the navbar
include 'includes/footer.php';
?>













<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>


<script src="js/script.js"></script>


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
<script src="//code.tidio.co/j6lqhoaeshlexqdor89digjetxmd8drj.js" async></script>
</body>
</html>
<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>