<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["username"])) {
    header('Location: login.php'); 
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>AWB-Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/fontawesome/css/all.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/logo.png">
    <style>
    .navbar {
        transition: background-color 0.3s, padding 0.3s;
    }

    .navbar.scrolled {
        background-color: white;
        padding: 10px 0;
    }

    .navbar-brand h1 {
        font-size: 24px;
    }

    .mic-icon {
        cursor: pointer;
        font-size: 20px;
        /* Reduced size */
        margin-left: 15px;
        transition: transform 0.3s ease;
        /* Smooth transition */
    }

    .mic-active {
        color: green;
        /* Green for active */
        animation: mic-bounce 0.5s infinite;
        /* Add bounce animation */
    }

    .mic-inactive {
        color: red;
        /* Red for inactive */
    }

    @keyframes mic-bounce {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        /* Hidden by default */
        width: 250px;
        height: 100%;
        background: white;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
        transition: left 0.3s ease;
        z-index: 1000;
    }

    .sidebar.active {
        left: 0;
        /* Show sidebar */
    }

    body {
        padding-top: 70px;
    }

    @media (min-width: 769px) {
        .toggle-sidebar {
            display: none;
            /* Hide toggle button on larger screens */
        }
    }

    .dropdown-menu {
        min-width: 200px;
        /* Adjust as needed */
    }
    </style>
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="Dashboard.php">
                <h1>AWB <span style="font-size:7px;">Think, Travel, Explore</span></h1>
            </a>
            <button class="navbar-toggler toggle-sidebar" type="button" id="sidebarToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="Dashboard.php"><span class="fas fa-home"></span>
                            Home</a></li>


                           


                    <!-- AWB Connect++ Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="connectDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-link"></i> AWB Connect+
                        </a>
                        <div class="dropdown-menu" aria-labelledby="connectDropdown">
                            <a class="dropdown-item" href="connect+.php">
                                <i class="fa fa-plug"></i> Connect+
                            </a>
                            <a class="dropdown-item" href="chat.php">
                                <i class="fa fa-comments"></i> Chat
                            </a>
                            <a class="dropdown-item" href="Live_chat.php">
                                <i class="fa fa-users"></i> Community
                            </a>
                            <a class="dropdown-item" href="Status.php">
                                <i class="fa fa-info-circle"></i> Status
                            </a>
                            <a class="dropdown-item" href="open_to_trip.php">
                                <i class="fa fa-network-wired"></i> Open to Network
                            </a>
                        </div>
                    </li>

                    <!-- AWB Network + Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="networkDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-sitemap"></i> AWB Network +
                        </a>
                        <div class="dropdown-menu" aria-labelledby="networkDropdown">
                            <a class="dropdown-item" href="user_post_blade.php">
                                <i class="fa fa-thumbs-up"></i> Feeds
                            </a>
                            <a class="dropdown-item" href="my_posts.php">
                                <i class="fa fa-pencil-alt"></i> My Posts
                            </a>
                            <a class="dropdown-item" href="travel_clips.php">
                                <i class="fa fa-film"></i> Upload Clips
                            </a>
                            <a class="dropdown-item" href="my_reels.php">
                                <i class="fa fa-video"></i> My Clips
                            </a>
                            <a class="dropdown-item" href="all_reels.php">
                                <i class="fa fa-clone"></i> Clips
                            </a>
                        </div>
                    </li>



                    <!----AWB Other Service --->








                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="networkDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-map"></i> AWB Trip +
                        </a>
                        <div class="dropdown-menu" aria-labelledby="networkDropdown">
                            <a class="dropdown-item" href="user_post_blade.php">
                                <i class="fa fa-hotel"></i> Hotel Bookings
                            </a>
                            <a class="dropdown-item" href="my_posts.php">
                                <i class="fa fa-plane"></i> Flight Booking
                            </a>
                            <a class="dropdown-item" href="travel_clips.php">
                                <i class="fa fa-train"></i> Train Booking
                            </a>
                            <a class="dropdown-item" href="cab_booking.php">
                                <i class="fa fa-taxi"></i> Cab Booking
                            </a>
                            <a class="dropdown-item" href="bus_booking.php">
                                <i class="fa fa-bus"></i> Bus Booking
                            </a>
                            <a class="dropdown-item" href="car_rental.php">
                                <i class="fa fa-car"></i> Car Rental
                            </a>
                            <a class="dropdown-item" href="awb_prof.php">
                                <i class="fa fa-user-tie"></i> AWB Professional Booking
                            </a>
                            <a class="dropdown-item" href="awb_prem_trip_pkg.php">
                                <i class="fa fa-gift"></i> AWB Trip Package Booking
                            </a>
                        </div>
                    </li>




                    <!-- AWB Social Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="socialDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-users"></i> AWB Social
                        </a>
                        <div class="dropdown-menu" aria-labelledby="socialDropdown">
                            <a class="dropdown-item" href="user-blog.php">
                                <i class="fa fa-pencil-alt"></i> Blogging
                            </a>
                        </div>
                    </li>




                    <!---For AWB MEET , Expense Expliter-->

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="networkDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-comments"></i> AWB Discuss
                        </a>
                        <div class="dropdown-menu" aria-labelledby="networkDropdown">
                            <a class="dropdown-item" href="awb_mee.php">
                                <i class="fa fa-users"></i> AWB MEET.
                            </a>
                            <a class="dropdown-item" href="create_journey_group.php">
                                <i class="fa fa-share-alt"></i> Create journey Group.
                            </a>
                            <a class="dropdown-item" href="expense_spliter.php">
                                <i class="fa fa-plane"></i> Trip Expense.
                            </a>
                            <a class="dropdown-item" href="your_expenses.php">
                                <i class="fa fa-wallet"></i> Your Expense.
                            </a>

                            <a class="dropdown-item" href="Expense_Group.php">
                                <i class="fa fa-users"></i> Create Expense Group.
                            </a>


                            <a class="dropdown-item" href="budget_cal.php">
                                <i class="fa fa-calculator"></i> Budget Calculator and Estimator
                            </a>

                        </div>
                    </li>


                    <!-- Emergency Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="emergencyDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-exclamation-triangle"></i> Emergency
                        </a>
                        <div class="dropdown-menu" aria-labelledby="emergencyDropdown">
                            <a class="dropdown-item" href="medical_support.php">
                                <i class="fa fa-plus-circle"></i> Medical Emergency
                            </a>
                            <a class="dropdown-item" href="financial_emerergency.php">
                                <i class="fa fa-money-bill-wave"></i> Financial Emergency
                            </a>
                            <a class="dropdown-item" href="trip_emergecy.php">
                                <i class="fa fa-info-circle"></i> Other
                            </a>
                        </div>
                    </li>


                    <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="discussDropdown" role="button"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-comments"></i> AWB Core
    </a>
    <div class="dropdown-menu" aria-labelledby="discussDropdown">
        <a class="dropdown-item" href="forex_exchange.php">
            <i class="fa fa-users"></i> Forex Exchange.
        </a>
        
        <a class="dropdown-item" href="Travel_Gallery.php">
            <i class="fa fa-images"></i> Gallary
        </a>
        <a class="dropdown-item" href="Booking.php">
            <i class="fa fa-circle"></i> Trip Booking
        </a>
    </div>
</li>




                    <li class="nav-item"><a class="nav-link" href="profile.php"><span class="fas fa-user-circle"></span>
                            Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="about-us.php"><span class="fas fa-ellipsis-h"></span>
                            More</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><span class="fas fa-sign-out-alt"></span>
                            Logout</a></li>
                </ul>
                <span class="fas fa-microphone mic-icon mic-inactive" id="micButton"></span>
            </div>
        </div>
    </nav>

    <div class="sidebar" id="sidebar">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="Dashboard.php"><span class="fas fa-home"></span> Home</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="Status.php"><span class="fas fa-plane-departure"></span>
                    Status</a></li>
            <li class="nav-item"><a class="nav-link" href="user-blog.php"><span class="fas fa-pencil-alt"></span>
                    Blogging</a></li>
            <li class="nav-item"><a class="nav-link" href="booking.php"><span class="fas fa-ticket-alt"></span>
                    Booking</a></li>
            <li class="nav-item"><a class="nav-link" href="connect+.php"><span class="fas fa-link"></span> Connect +</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="user_post_blade.php"><span class="fas fa-paper-plane"></span>
                    Network +</a></li>
            <li class="nav-item"><a class="nav-link" href="chat.php"><span class="fas fa-comments"></span> Chat</a></li>
            <li class="nav-item"><a class="nav-link" href="Live_chat.php"><span class="fas fa-comments"></span>
                    Community</a></li>
            <li class="nav-item"><a class="nav-link" href="open_to_trip.php"><span class="fas fa-globe-americas"></span>
                    Open to Trip</a></li>
            <li class="nav-item"><a class="nav-link" href="package.php"><span class="fas fa-info-circle"></span>
                    Package</a></li>
            <li class="nav-item"><a class="nav-link" href="forex_exchange.php"><span class="fas fa-envelope"></span>
                    Forex Exchange</a></li>
            <li class="nav-item"><a class="nav-link" href="profile.php"><span class="fas fa-user-circle"></span>
                    Profile</a></li>
            <li class="nav-item"><a class="nav-link" href="about-us.php"><span class="fas fa-ellipsis-h"></span>
                    More</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php"><span class="fas fa-sign-out-alt"></span>
                    Logout</a></li>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script>
    $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
            $('.navbar').addClass('scrolled');
        } else {
            $('.navbar').removeClass('scrolled');
        }
    });

    // Sidebar toggle
    $('#sidebarToggle').on('click', function() {
        $('#sidebar').toggleClass('active');
    });

    // Speech Recognition
    document.addEventListener("DOMContentLoaded", function() {
        const micButton = document.getElementById('micButton');
        let recognition;
        let listening = false;

        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = true;
            recognition.interimResults = false;

            recognition.onresult = function(event) {
                const transcript = event.results[event.resultIndex][0].transcript.toLowerCase();
                console.log("Recognized: ", transcript);
                navigate(transcript);
            };

            recognition.onerror = function(event) {
                console.error("Error occurred in recognition: " + event.error);
            };

            micButton.addEventListener('click', function() {
                if (!listening) {
                    startRecognition();
                } else {
                    stopRecognition();
                }
            });

            recognition.onend = function() {
                micButton.classList.remove('mic-active');
                micButton.classList.add('mic-inactive');
                listening = false;
                localStorage.removeItem('micListening'); // Clear when stopped
            };

            // Check if the mic was active on page load
            if (localStorage.getItem('micListening') === 'true') {
                startRecognition();
            }
        } else {
            alert("Your browser does not support speech recognition. Please use Google Chrome.");
        }

        function startRecognition() {
            recognition.start();
            micButton.classList.remove('mic-inactive');
            micButton.classList.add('mic-active');
            console.log("Voice recognition started. Speak now.");
            listening = true;
            localStorage.setItem('micListening', 'true'); // Store the state
        }

        function stopRecognition() {
            recognition.stop();
            micButton.classList.remove('mic-active');
            micButton.classList.add('mic-inactive');
            console.log("Voice recognition stopped.");
            listening = false;
            localStorage.removeItem('micListening'); // Clear when stopped
        }

        function navigate(command) {
            const navLinks = {
    "open dashboard": "Dashboard.php",
    "dashboard kholo": "Dashboard.php",
    "Dashboard Kholo": "Dashboard.php",
    "status": "Status.php",
    "status kholo": "Status.php",
    "blog kholo": "user-blog.php",
    "open blog": "user-blog.php",
    "booking kholo": "booking.php",
    "open booking": "booking.php",
    "connect plus kholo": "connect+.php",
    "open connect plus": "connect+.php",
    "network": "user_post_blade.php",
    "network kholo": "user_post_blade.php",
    "chat kholo": "chat.php",
    "open chat": "chat.php",
    "live chat kholo": "Live_chat.php",
    "open live chat": "Live_chat.php",
    "trip kholo": "open_to_trip.php",
    "open to trip": "open_to_trip.php",
    "package": "package.php",
    "package kholo": "package.php",
    "forex exchange": "forex_exchange.php",
    "forex kholo": "forex_exchange.php",
    "open profile": "profile.php",
    "profile kholo": "profile.php",
    "about": "about-us.php",
    "about dekho": "about-us.php",
    "logout": "logout.php",
    "logout karo": "logout.php"
};


            const link = navLinks[command];
            if (link) {
                window.location.href = link;
            } else {
                alert("Sorry, I didn't understand that command.");
            }
        }
    });
    </script>
    <script src="//code.tidio.co/j6lqhoaeshlexqdor89digjetxmd8drj.js" async></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>
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