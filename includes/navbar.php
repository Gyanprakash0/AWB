
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png" type="image/png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>AWB</title>
    <style>
        
        .dropdown, .language-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content, .language-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown:hover .dropdown-content,
        .language-dropdown:hover .language-content {
            display: block;
        }

        .dropdown-content a, .language-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover, .language-content a:hover {
            background-color: #f1f1f1;
        }

        #language-selector {
            margin-left: 15px;
        }
    </style>
</head>
<body>

<yha pr add kro navbar ke bahar>

<header class="header">
    <a href="#" class="logo"> 
        <img src="images/logo.png" alt="AWB" style="width: 30px; height: 20px;"> AWB
    </a>

    <nav class="navbar">
        <div id="nav-close" class="fas fa-times"></div>
        <a href="index"><i class="fas fa-home"></i> Home</a>
        <a href="connect.php"><i class="fas fa-link"></i> Connect +</a>
        <a href="booking.php"><i class="fas fa-ticket-alt"></i> Bookings</a>
        <a href="budget_calculator"><i class="fas fa-calculator"></i> Estimation</a>
        <a href="blog"><i class="fas fa-blog"></i> Blog</a>
        <a href="places"><i class="fas fa-map-marker-alt"></i> Places</a>
        
        <a href="Login.php"><i class="fas fa-user"></i>Login</a>
        

        <div class="dropdown">
            <a href="#"><i class="fas fa-info-circle"></i> More</a>
            <div class="dropdown-content">
                <a href="contact"><i class="fas fa-envelope"></i> Contact</a>
                <a href="about"><i class="fas fa-info-circle"></i> About</a>
                <a href="help"><i class="fas fa-question-circle"></i> Help</a>
                <a href="reviews"><i class="fas fa-star"></i> Reviews</a>

            </div>
        </div>

        <!-- <div id="language-selector" class="language-dropdown">
            <select id="language-dropdown">
                <option value="en">English</option>
                <option value="hi">Hindi</option>
                <option value="de">German</option>
                <option value="fr">French</option>
             
            </select>
        </div> -->
    </nav>

    <div class="icons">
        <div id="menu-btn" class="fas fa-bars"></div>
        <div id="search-btn" class="fas fa-search"></div>
    </div>
</header>
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

