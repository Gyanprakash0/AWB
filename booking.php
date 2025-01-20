<?php
session_start();
include('connection.php');
include('includes/header.php');
include('includes/requirements.php'); 


$query = "SELECT * FROM packaged";  
$result = mysqli_query($conn, $query);  


if (mysqli_num_rows($result) > 0):
?>

<style>

.events-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); 
    gap: 2em;
    margin: 3em;
}


.event.card {
    width: 100%;
    max-width: 100%;  
    border-radius: 15px; 
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); 
    overflow: hidden;
    background-color: #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    font-family: "Roboto", sans-serif;
}


.event.card:hover {
    transform: translateY(-15px); 
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}


.image-wrapper {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover; 
    transition: transform 0.3s ease;
}


.image-wrapper:hover img {
    transform: scale(1.1); 
}


.event-title {
    padding: 1.5em;
    background-color: #f9fafb;
    text-align: center;
}

.event-title h2 {
    font-size: 1.75em;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5em;
}

.event-title p {
    font-size: 1.1em;
    color: #555;
}


.event-date, .event-time {
    text-align: center;
    padding: 0.8em 0;
    font-size: 1.2em;
}

.event-date time {
    font-weight: bold;
    color: #e74c3c; 
    text-decoration: line-through; 
}

.event-time {
    font-weight: 600;
    color: #27ae60; 
}


.new-price {
    font-size: 1.5em;
    color: #2ecc71; 
    font-weight: bold;
}


.buttons-container {
    display: flex;
    justify-content: space-between; 
    padding: 1em;
}


.event-details {
    background-color: #95a5a6; 
    color: #fff;
    padding: 0.8em 1.5em;
    border-radius: 5px;
    text-align: center;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.3s ease;
    flex: 1; 
    margin-right: 0.5em;
}

.event-details:hover {
    background-color: #7f8c8d; 
    cursor: pointer;
}

.event-details .link {
    color: #fff;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
}

.event-tickets {
    background-color: #3498db;
    color: #fff;
    padding: 0.8em 1.5em;
    border-radius: 5px;
    text-align: center;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.3s ease;
    flex: 1; 
}

.event-tickets:hover {
    background-color: #2980b9; 
    cursor: pointer;
}

.event-tickets .link {
    color: #fff;
    font-weight: bold;
    letter-spacing: 1px;
    text-transform: uppercase;
    text-decoration: none;
}


.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 2em;
    border-radius: 10px;
    width: 80%;
    max-width: 700px;
    overflow-y: auto;
}

.modal-header {
    font-size: 2em;
    margin-bottom: 1em;
}

.modal-body {
    margin-bottom: 1em;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
}

@media screen and (max-width: 800px) {
    .events-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media screen and (max-width: 600px) {
    .events-container {
        grid-template-columns: 1fr; 
    }
}
</style>

<div class="events-container">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <?php
       
        $originalPrice = $row['price'];
        $discount = $row['discount'];
        $newPrice = $originalPrice - ($originalPrice * ($discount / 100));
        ?>

        <section class="event card">
            <div class="event-title">
                <h2 class="title"><?php echo htmlspecialchars($row['name']); ?></h2>
                <p class="venue">
                    <a class="link" href="#" target="_blank" aria-label="Visit venue website"><?php echo htmlspecialchars($row['location']); ?></a>
                </p>
                <address class="address">
                    <p class="streetAddress"><?php echo htmlspecialchars($row['location']); ?>, India</p>
                    <span class="locality"><?php echo htmlspecialchars($row['description']); ?></span>
                </address>
            </div>

            <div class="image-wrapper">
                <img class="featured-image" src="https://png.pngtree.com/thumb_back/fh260/background/20230411/pngtree-nature-forest-sun-ecology-image_2256183.jpg" alt="<?php echo htmlspecialchars($row['name']); ?>" />
            </div>

            <div class="event-date">
                <time datetime="2018-10-16"><?php echo number_format($row['price'], 2); ?>/-</time>
            </div>

            <div class="event-time">
                <span><?php echo $row['discount']; ?>% off</span>
            </div>

            <div class="new-price">
                ₹<?php echo number_format($newPrice, 2); ?>/- 
            </div>

            <div class="buttons-container">
                <div class="event-details" 
                     
                     data-name="<?php echo htmlspecialchars($row['name']); ?>"  
                     data-location="<?php echo htmlspecialchars($row['location']); ?>" 
                     data-description="<?php echo htmlspecialchars($row['description']); ?>" 
                     data-price="<?php echo number_format($row['price'], 2); ?>" 
                     data-discount="<?php echo number_format($row['discount'], 2); ?>" 
                     data-new-price="<?php echo number_format($newPrice, 2); ?>">
                     DETAILS
                </div>

                <div class="event-tickets">
    <a class="link" href="trip_booking.php?name=<?php echo urlencode($row['name']); ?>&location=<?php echo urlencode($row['location']); ?>&description=<?php echo urlencode($row['description']); ?>&price=<?php echo urlencode(number_format($row['price'], 2)); ?>&discount=<?php echo urlencode(number_format($row['discount'], 2)); ?>&new-price=<?php echo urlencode(number_format($newPrice, 2)); ?>">Book Now</a>
</div>

            </div>
        </section>
    <?php endwhile; ?>
</div>


<div id="modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span id="modalTitle"></span>
        </div>
        <div class="modal-body">
            <p><strong>Location:</strong> <span id="modalLocation"></span></p>
            <p><strong>Description:</strong> <span id="modalDescription"></span></p>
            <p><strong>Price:</strong> ₹<span id="modalPrice"></span></p>
            <p><strong>Discount:</strong> <span id="modalDiscount"></span>%</p>
            <p><strong>New Price:</strong> ₹<span id="modalNewPrice"></span></p>
        </div>
        <div class="modal-footer">
            <button id="closeModal">Close</button>
        </div>
    </div>
</div>

<script>

const detailsButtons = document.querySelectorAll('.event-details');
const modal = document.getElementById('modal');
const closeModal = document.getElementById('closeModal');

detailsButtons.forEach(function(button) {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        
        var name = this.getAttribute('data-name');
        var location = this.getAttribute('data-location');
        var description = this.getAttribute('data-description');
        var price = this.getAttribute('data-price');
        var discount = this.getAttribute('data-discount');
        var newPrice = this.getAttribute('data-new-price');

        
        document.getElementById("modalTitle").innerText = name;
        document.getElementById("modalDescription").innerText = description;
        document.getElementById("modalLocation").innerText = location;
        document.getElementById("modalPrice").innerText = price;
        document.getElementById("modalDiscount").innerText = discount;
        document.getElementById("modalNewPrice").innerText = newPrice;

        
        modal.style.display = "flex";
    });
});


closeModal.addEventListener('click', function() {
    modal.style.display = "none";
});


window.addEventListener('click', function(e) {
    if (e.target === modal) {
        modal.style.display = "none";
    }
});
</script>
<script src="//code.tidio.co/j6lqhoaeshlexqdor89digjetxmd8drj.js" async></script>
<?php
else:
    echo "<p>No events available.</p>";
endif;

include('includes/footer.php');  
?>
