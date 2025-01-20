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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        header {
            text-align: center;
            margin: 0px 0;
        }

        #search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        #search {
            padding: 10px;
            width: 80%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #search-button {
            padding: 10px;
            margin-left: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        .destination-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .destination-item {
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px;
            padding: 15px;
            width: 280px;
            text-align: center;
            cursor: pointer;
            position: relative;
        }

        .destination-item h3 {
            margin: 10px 0;
            font-size: 1.2em;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .destination-image {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        h1 {
            margin-top: 80px;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.5s;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 70%;
            max-width: 500px;
            position: relative;
            animation: slideIn 0.5s;
        }

        .modal-image {
            width: 100px;
            height: auto;
        }

        .close {
            float: right;
            font-size: 1.5em;
            cursor: pointer;
        }

        .modal-content p {
            font-size: 1.1em;
        }

        .modal-video {
            width: 100%;
            height: 315px;
        }

        .precautions {
            margin-top: 20px;
            font-style: italic;
            color: #888;
        }

        .plane-icon {
            margin-left: 10px;
            animation: fly 0.5s forwards;
        }

        @keyframes fly {
            0% { transform: translateY(-20%); }
            100% { transform: translateY(0); }
        }

        @keyframes slideIn {
            from { transform: translateY(-50%); }
            to { transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>

<?php
include 'includes/loader.php'; 

renderLoader(); 


?>


    <?php include 'includes/navbar.php'; ?>
<br><br><br><br><br>
    <div class="search-form">
        <div id="close-search" class="fas fa-times"></div>
        <form action="">
            <div id="search-container">
                <input type="search" name="" placeholder="search here..." id="search-box">
                <button type="button" id="search-button" class="fas fa-search"></button>
            </div>
        </form>
    </div>

    <header>
        <h1>Top Travel Destinations in India</h1>
        <input type="text" id="search" placeholder="Search...">
    </header>

    <div id="destination-list" class="destination-list"></div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modal-name"></h2>
            <img id="modal-image" class="modal-image" src="" alt="">
            <p id="modal-location"></p>
            <p id="modal-budget"></p>
            <p id="modal-food"></p>
            <p id="modal-time"></p>
            <iframe id="modal-video" class="modal-video" src="" frameborder="0" allowfullscreen></iframe>
            <p class="precautions" id="modal-precautions"></p>
        </div>
    </div>

    <?php include 'includes/subscribe.php'; ?>
    <?php include 'includes/footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const destinationList = document.getElementById('destination-list');
            const searchInput = document.getElementById('search');

            // Fetch data from API
            fetch('api.php')
                .then(response => response.json())
                .then(data => {
                    renderDestinations(data);
                    setupSearch(data);
                })
                .catch(error => console.error('Error fetching data:', error));

            
            function renderDestinations(destinations) {
                destinationList.innerHTML = ''; 
                destinations.forEach(destination => {
                    const item = document.createElement('div');
                    item.className = 'destination-item';

                    const imageUrl = destination.image || 'path/to/default-image.jpg';

                    item.innerHTML = `
                        <img src="${imageUrl}" alt="${destination.name}" class="destination-image">
                        <h3>${destination.name} <i class="fas fa-plane plane-icon"></i></h3>
                    `;

                    item.addEventListener('click', () => {
                        openModal(destination);
                        animatePlane(item);
                    });

                    destinationList.appendChild(item);
                });
            }

            function openModal(destination) {
    document.getElementById('modal-name').textContent = destination.name;
    document.getElementById('modal-image').src = destination.image || 'path/to/default-image.jpg';
    document.getElementById('modal-location').textContent = `Location: ${destination.location}`;
    document.getElementById('modal-budget').textContent = `Estimated Budget: ${destination.estimated_budget}`;
    document.getElementById('modal-food').textContent = `Famous Food: ${destination.famous_food}`;
    document.getElementById('modal-time').textContent = `Best Time to Visit: ${destination.best_time_to_visit}`;

    // Update the video link
    if (destination.video_link) {
        const videoId = getYouTubeID(destination.video_link);
        document.getElementById('modal-video').src = videoId ? `https://www.youtube.com/embed/${videoId}` : '';
    } else {
        document.getElementById('modal-video').src = '';
    }

    document.getElementById('modal-precautions').textContent = destination.precautions || 'No precautions listed.';
    document.getElementById('modal').style.display = 'block';
}

// Function to extract YouTube ID from different URL formats
function getYouTubeID(url) {
    const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^&\n]{11})/;
    const matches = url.match(regex);
    return matches ? matches[1] : null;
}


            
            function animatePlane(item) {
                const planeIcon = item.querySelector('.plane-icon');
                planeIcon.style.animation = 'fly 0.5s forwards';
                setTimeout(() => {
                    planeIcon.style.animation = '';
                }, 500);
            }

            
            document.querySelector('.close').addEventListener('click', () => {
                document.getElementById('modal').style.display = 'none';
            });

            
            window.addEventListener('click', (event) => {
                const modal = document.getElementById('modal');
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });

            
            function setupSearch(data) {
                searchInput.addEventListener('input', (e) => {
                    const query = e.target.value.toLowerCase();
                    const filteredDestinations = data.filter(destination => 
                        destination.name.toLowerCase().includes(query) ||
                        destination.location.toLowerCase().includes(query) ||
                        destination.famous_food.toLowerCase().includes(query)
                    );
                    renderDestinations(filteredDestinations);
                });

                document.getElementById('search-button').addEventListener('click', () => {
                    const query = searchInput.value.toLowerCase();
                    const filteredDestinations = data.filter(destination => 
                        destination.name.toLowerCase().includes(query) ||
                        destination.location.toLowerCase().includes(query) ||
                        destination.famous_food.toLowerCase().includes(query)
                    );
                    renderDestinations(filteredDestinations);
                });
            }
        });
    </script>

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

</body>
</html>
