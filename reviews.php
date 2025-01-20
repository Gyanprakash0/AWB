<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AWB</title>

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    
    <link rel="stylesheet" href="css/style.css">

    <style>
        .heading {
    position: relative;
}
.testimonials {
    background: url("https://hips.hearstapps.com/hmg-prod/images/alpe-di-siusi-sunrise-with-sassolungo-or-langkofel-royalty-free-image-1623254127.jpg") center center no-repeat;
    background-size: cover;
    background-attachment: fixed;
    padding: 130px 0 150px;
    color: #ffffff;
    position: relative;
}
.testimonials:before {
    content: "";
    background: rgba(0, 0, 0, 0.5);
    width: 100%;
    height: 100%;
    top: 0;
    position: absolute;
    left: 0;
}
.testimonials .heading h2 {
    font-size: 25px;
    font-weight: 700;
    color: #ffffff;
}

.testimonials .heading h2 span {
    color: #ff0000;
}

.testimonials p {
    font-size: 15px;
    font-weight: 400;
    line-height: 1.7;
    color: #d1e5e7;
    margin: 20px 0;
    padding: 0;
}

/* Image */
.testimonials .carousel-inner .carousel-item .team {
    width: 100px;
    height: 100px;
    border: 2px solid #ff0000;
    border-radius: 100%;
    padding: 5px;
    margin: 50px 0 15px;
}

.testimonials .carousel-inner .carousel-item h3 {
    font-size: 20px;
    color: #ffffff;
    font-weight: 600;
}

.testimonials .carousel-inner .carousel-item h4 {
    font-size: 14px;
    font-weight: 300;
    color: #e2e1e1;
    margin-bottom: 20px;
}

.testimonials .carousel-indicators {
    bottom: -30px;
}

.testimonials .carousel-indicators li {
    background-color: #b8b7b7;
    border-radius: 30px;
    height: 4px;
    width: 40px;
}

.testimonials .carousel-indicators .active {
    background-color: #ff0000;
}

.testimonials .control span {
    cursor: pointer;
}

.testimonials .icon {
    height: 40px;
    width: 40px;
    background-size: 100%, 100%;
    border-radius: 50%;
    font-size: 30px;
    background-image: none;
    color: #ffffff;
}

.star-rating {
    color: #FFD700; 
    font-size: 20px; 
    margin-top: 10px; 
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
<br><br><br><br><br><br>


<section class="testimonials">
        <div class="heading text-center">
            <h2>What's
                <span>Clients</span>
                Says?</h2>

        </div>
        <div class="container">
            <div class="row text-center">
                <div class="col-md-12">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <!-- Indicators-->
                        <ol class="carousel-indicators">
                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                        </ol>

                        <div class="carousel-inner">
                            <!-- Item-1 -->
                            <div class="carousel-item active text-center">
                                <img src="https://media.licdn.com/dms/image/D4D03AQEabszoZsIACw/profile-displayphoto-shrink_200_200/0/1702545934514?e=2147483647&v=beta&t=tmO8EpBGSDAj3U_nDG_nbhbShcuCmbuaElcOMTX_Aq4" alt="" class="center-block team">
                                <h3>Gyan Prakash Pandey</h3>
                                <h4>Customer at <span><b>AWB</b></span></h4>
                                <p>AWB made our family trip unforgettable! Every detail was taken care of, <br>
                                allowing us to relax and enjoy our time together</p>

                                <div class="star-rating">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
    </div>
                            </div>
                            <!-- Item-2 -->
                            <div class="carousel-item text-center">
                                <img src="https://www.hackerworld.net/Team2024/assets/imgTeam/shivam_lead-technical.jpg" alt="" class="center-block team">
                                <h3>Shivam Tripathi</h3>
                                <h4>Customer at <b>AWB </b></h4>
                                <p>The team at AWB exceeded our expectations! From booking to our last day,<br>
                                everything was seamless and stress-free.</p>

                                <div class="star-rating">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
    </div>
                            </div>
                            <!-- Item-3 -->
                            <div class="carousel-item text-center">
    <img src="https://www.hackerworld.net/assets/img/team/ansh.jpeg" alt="" class="center-block team">
    <h3>Ansh Shukla</h3>
    <h4>Customer at <b>AWB</b></h4>
    <p>An amazing experience with AWB! Their personalized itineraries helped us discover hidden gems,<br>
    we wouldn’t have found on our own.</p>
    <div class="star-rating">
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
        <i class="fas fa-star"></i>
    </div>
</div>

                        </div>
                        <a
                            class="carousel-control-prev control"
                            data-target="#carouselExampleIndicators"
                            role="button"
                            data-slide="prev">
                            <span class="fa fa-angle-left icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a
                            class="carousel-control-next control"
                            data-target="#carouselExampleIndicators"
                            role="button"
                            data-slide="next">
                            <span class="fa fa-angle-right icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>






<?php

include 'includes/subscribe.php';
?>






<?php

include 'includes/footer.php';
?>

<script>
    </script>













<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


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