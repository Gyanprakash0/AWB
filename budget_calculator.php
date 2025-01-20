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
            background-color: #f0f4f8;
            overflow: hidden;
        }

        
        .icon-box {
            display: flex;
            justify-content: center;
            margin: 2rem 0;
            gap: 2rem;
        }
        .icon-box div {
            text-align: center;
            font-size: 2rem;
            cursor: pointer;
            transition: transform 0.3s;
            position: relative;
        }
        .icon-box div:hover {
            transform: scale(1.1);
        }
        .active {
            color: #00796b; 
        }
        .active::after {
            content: '';
            display: block;
            width: 100%;
            height: 4px;
            background-color: #00796b; 
            position: absolute;
            bottom: -10px;
            left: 0;
        }

        
        .calculator-box, .budget-estimation-box, .suggestion-box {
            display: none;
            margin: 2rem auto;
            width: 80%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        
        .calculator-display {
            font-size: 2rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: right;
            background-color: #e0f7fa;
            color: #00796b;
        }

        
        .calculator-buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 0.5rem;
        }
        .calculator-button {
            padding: 1rem;
            font-size: 1.5rem;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            background-color: #b2ebf2;
            transition: background-color 0.3s;
            border: none;
        }
        .calculator-button:hover {
            background-color: #80deea;
        }
        .submit-btn {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            background-color: #00796b;
            color: white;
            border: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #004d40;
        }

        
        .service-description {
            margin-top: 1rem;
            font-size: 1.2rem; 
            color: #555;
            display: none; 
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





















<div class="icon-box">
    <div id="calculator-icon" class="active">
        <i class="fas fa-calculator"></i>
        <p>Calculator</p>
    </div>
    <div id="budget-icon">
        <i class="fas fa-money-bill-wave"></i>
        <p>Budget Calculator</p>
    </div>
    <div id="suggestion-icon">
        <i class="fas fa-lightbulb"></i>
        <p>Best Suggestions</p>
    </div>
</div>

<div class="calculator-box" id="calculator-box">
    <h3>Calculator</h3>
    <input type="text" class="calculator-display" id="calculator-display" disabled>
    <div class="calculator-buttons">
        <div class="calculator-button" data-value="7">7</div>
        <div class="calculator-button" data-value="8">8</div>
        <div class="calculator-button" data-value="9">9</div>
        <div class="calculator-button" data-value="/">/</div>
        
        <div class="calculator-button" data-value="4">4</div>
        <div class="calculator-button" data-value="5">5</div>
        <div class="calculator-button" data-value="6">6</div>
        <div class="calculator-button" data-value="*">*</div>

        <div class="calculator-button" data-value="1">1</div>
        <div class="calculator-button" data-value="2">2</div>
        <div class="calculator-button" data-value="3">3</div>
        <div class="calculator-button" data-value="-">-</div>

        <div class="calculator-button" data-value="0">0</div>
        <div class="calculator-button" data-value="clear">C</div>
        <div class="calculator-button" data-value="=">=</div>
        <div class="calculator-button" data-value="+">+</div>
    </div>
</div>

<div class="budget-estimation-box" id="budget-estimation-box">
    <h3>Budget Estimation</h3>
    <input type="text" class="budget-input" placeholder="From" id="budget-from">
    <input type="text" class="budget-input" placeholder="To" id="budget-to">
    <input type="number" class="budget-input" placeholder="Adults" id="budget-adults">
    <input type="number" class="budget-input" placeholder="Children" id="budget-children">
    <select class="budget-input" id="budget-service">
        <option value="">Select Service</option>
        <option value="basic">Basic</option>
        <option value="plus">Plus</option>
        <option value="premium">Premium</option>
        <option value="premium-plus">Premium +</option>
    </select>
    <div class="service-description" id="service-description"></div>
    <button class="submit-btn" id="budget-submit">Submit</button>
</div>

<div class="suggestion-box" id="suggestion-box">
    <h3>Suggestions</h3>
    <input type="number" class="suggestion-input" placeholder="Enter your budget" id="suggestion-budget">
    <button class="submit-btn" id="suggestion-submit">Submit</button>
</div>

<div class="search-form">
    <div id="close-search" class="fas fa-times"></div>
    <form action="">
        <input type="search" name="" placeholder="search here..." id="search-box">
        <label for="search-box" class="fas fa-search"></label>
    </form>
</div>

<?php include 'includes/subscribe.php'; ?>
<?php include 'includes/footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const destinationList = document.getElementById('destination-list');
        const searchInput = document.getElementById('search');

        
        fetch('https://www.hackerworld.net/api/')
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
                    <h3>${destination.name}</h3>
                    <p>${destination.location}</p>
                    <div class="icons">
                        <div><i class="fas fa-money-bill-wave"></i> ${destination.estimated_budget}</div>
                        <div><i class="fas fa-utensils"></i> ${destination.famous_food}</div>
                    </div>
                    <p>Best Time to Visit: ${destination.best_time_to_visit}</p>
                `;
                destinationList.appendChild(item);
            });
        }

        
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
        }

        
        document.getElementById('calculator-icon').addEventListener('click', function() {
            hideAllBoxes();
            document.getElementById('calculator-box').style.display = 'block';
            setActiveIcon(this);
        });

        
        document.getElementById('budget-icon').addEventListener('click', function() {
            hideAllBoxes();
            document.getElementById('budget-estimation-box').style.display = 'block';
            setActiveIcon(this);
        });

        
        document.getElementById('suggestion-icon').addEventListener('click', function() {
            hideAllBoxes();
            document.getElementById('suggestion-box').style.display = 'block';
            setActiveIcon(this);
        });

        
        function hideAllBoxes() {
            document.getElementById('calculator-box').style.display = 'none';
            document.getElementById('budget-estimation-box').style.display = 'none';
            document.getElementById('suggestion-box').style.display = 'none';
            document.getElementById('service-description').style.display = 'none'; // Hide description
            resetActiveIcons();
        }

        // Function to reset active icons
        function resetActiveIcons() {
            document.querySelectorAll('.icon-box div').forEach(icon => {
                icon.classList.remove('active');
            });
        }

        // Function to set the active icon
        function setActiveIcon(icon) {
            icon.classList.add('active');
        }

        // Calculator functionality
        let currentInput = '';
        const display = document.getElementById('calculator-display');

        document.querySelectorAll('.calculator-button').forEach(button => {
            button.addEventListener('click', function() {
                const value = this.getAttribute('data-value');

                if (value === 'clear') {
                    currentInput = '';
                } else if (value === '=') {
                    try {
                        currentInput = eval(currentInput).toString();
                    } catch {
                        currentInput = 'Error';
                    }
                } else {
                    currentInput += value;
                }

                display.value = currentInput;
            });
        });

        // Budget submission
        document.getElementById('budget-submit').addEventListener('click', function() {
            const from = document.getElementById('budget-from').value;
            const to = document.getElementById('budget-to').value;
            const adults = document.getElementById('budget-adults').value;
            const children = document.getElementById('budget-children').value;
            const service = document.getElementById('budget-service').value;
            alert(`Budget Details:\nFrom: ${from}\nTo: ${to}\nAdults: ${adults}\nChildren: ${children}\nService: ${service}`);
        });

        // Suggestion submission
        document.getElementById('suggestion-submit').addEventListener('click', function() {
            const budget = document.getElementById('suggestion-budget').value;
            alert(`Your suggested budget is: ${budget}`);
        });

        // Show service description based on selection
        document.getElementById('budget-service').addEventListener('change', function() {
            const serviceDescription = document.getElementById('service-description');
            const value = this.value;

            let description = '';
            if (value === 'basic') {
                description = `
                    <strong>Basic:</strong>
                    <ul>
                        <li>Train sleeper ticket from source to destination.</li>
                        <li>Accommodation in 2-3 star hotels.</li>
                        <li>Food not included.</li>
                        <li>Local sightseeing recommendations.</li>
                        <li>Access to basic amenities.</li>
                        <li>Travel support via chat.</li>
                        <li>24/7 helpline for emergencies.</li>
                        <li>Customizable itinerary options.</li>
                    </ul>`;
            } else if (value === 'plus') {
                description = ` 
                    <strong>Plus:</strong>
                    <ul>
                        <li>3AC train ticket from source to destination.</li>
                        <li>Accommodation in 3-star hotels.</li>
                        <li>Meals included (breakfast).</li>
                        <li>Local guide for sightseeing.</li>
                        <li>Assistance with ticket bookings.</li>
                        <li>Travel insurance coverage.</li>
                        <li>Priority customer support.</li>
                        <li>Customizable travel packages.</li>
                    </ul>`;
            } else if (value === 'premium') {
                description = `
                    <strong>Premium:</strong>
                    <ul>
                        <li>4-star hotel accommodation.</li>
                        <li>1AC train ticket from source to destination.</li>
                        <li>All meals included.</li>
                        <li>Personal guide for the entire trip.</li>
                        <li>Travel insurance up to 1 crore.</li>
                        <li>Complimentary welcome kit.</li>
                        <li>VIP access to attractions.</li>
                        <li>24/7 personal travel assistant.</li>
                    </ul>`;
            } else if (value === 'premium-plus') {
                description = `
                    <strong>Premium +:</strong>
                    <ul>
                        <li>5-star hotel accommodation.</li>
                        <li>Flight ticket from source to destination.</li>
                        <li>All meals included.</li>
                        <li>Private car for local sightseeing.</li>
                        <li>Travel insurance up to 2 crores.</li>
                        <li>Personalized travel planning.</li>
                        <li>Complimentary spa session.</li>
                        <li>Exclusive discounts on future bookings.</li>
                    </ul>`;
            }

            serviceDescription.innerHTML = description;
            serviceDescription.style.display = value ? 'block' : 'none'; 
        });
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
<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>