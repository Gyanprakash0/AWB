<?php include('includes/header.php'); ?>
<?php include('includes/requirements.php'); ?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1 class="display-4">Welcome!</h1>
        <p class="lead text-primary font-weight-bold"><?php echo htmlspecialchars($_SESSION['fullname']); ?></p>
        <hr class="my-4">
        <h1>Holiday Packages</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="images/kedarnath.jpg" alt="" class="img-fluid">
                        <div class="icons">
                            <a href="#" class="fas fa-shopping-cart" onclick="openBookingModal('Kedarnath', 14000)"></a>
                            <a href="#" class="fas fa-eye" onclick="openInquiryModal('Kedarnath', 14000)"></a>
                            <a href="#" class="fas fa-share" onclick="shareLink('Kedarnath')"></a>
                        </div>
                    </div>
                    <div class="content">
                        <h3>Kedarnath</h3>
                        <div class="price">₹ 14000 - 6N/7D</div>
                        <p>* Valid For Single Person Only</p>
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="swiper-slide slide">
                    <div class="image">
                        <img src="images/ayodhya.jpeg" alt="" class="img-fluid">
                        <div class="icons">
                            <a href="#" class="fas fa-shopping-cart" onclick="openBookingModal('Ayodhya', 3000)"></a>
                            <a href="#" class="fas fa-eye" onclick="openInquiryModal('Ayodhya', 3000)"></a>
                            <a href="#" class="fas fa-share" onclick="shareLink('Ayodhya')"></a>
                        </div>
                    </div>
                    <div class="content">
                        <h3>Ayodhya</h3>
                        <div class="price">₹ 3000 - 2N/3D</div>
                        <p>* Valid For Single Person Only</p>
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
</div>

<!-- Inquiry Modal -->
<div id="inquiryModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="inquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Inquiry for <span id="inquiryPlace"></span></h5>
                <button type="button" class="close" onclick="closeModal('inquiryModal')" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="text" id="inquiryName" class="form-control" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="inquiryEmail" class="form-control" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="inquiryPhone" class="form-control" placeholder="Your Phone Number" required>
                    </div>
                    <div class="form-group">
                        <textarea id="inquiryQuery" class="form-control" placeholder="Your Query" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('inquiryModal')">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitInquiry()">Submit Inquiry</button>
            </div>
        </div>
    </div>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking for <span id="bookingPlace"></span></h5>
                <button type="button" class="close" onclick="closeModal('bookingModal')" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="text" id="bookingUserName" class="form-control" placeholder="Your Name" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="bookingIDCard" class="form-control" placeholder="Government ID Card" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="bookingEmail" class="form-control" placeholder="Your Email" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="bookingPhone" class="form-control" placeholder="Your Phone Number" required>
                    </div>
                    <h5>Price: ₹<span id="bookingPrice"></span></h5>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('bookingModal')">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitBooking()">Book Now</button>
            </div>
        </div>
    </div>
</div>

<script>
function openInquiryModal(place, price) {
    document.getElementById('inquiryPlace').innerText = place;
    $('#inquiryModal').modal('show');
}

function openBookingModal(place, price) {
    document.getElementById('bookingPlace').innerText = place;
    document.getElementById('bookingPrice').innerText = price;
    $('#bookingModal').modal('show');
}

function closeModal(modalId) {
    $('#' + modalId).modal('hide');
}

function submitInquiry() {
    const name = document.getElementById('inquiryName').value;
    const email = document.getElementById('inquiryEmail').value;
    const phone = document.getElementById('inquiryPhone').value;
    const query = document.getElementById('inquiryQuery').value;

    $.ajax({
        type: "POST",
        url: "your_backend_endpoint.php", // Replace with your actual endpoint
        data: { name, email, phone, query },
        success: function(response) {
            alert("Inquiry submitted!");
            closeModal('inquiryModal');
        },
        error: function() {
            alert("There was an error submitting your inquiry.");
        }
    });
}

function submitBooking() {
    const userName = document.getElementById('bookingUserName').value;
    const idCard = document.getElementById('bookingIDCard').value;
    const email = document.getElementById('bookingEmail').value;
    const phone = document.getElementById('bookingPhone').value;
    const price = document.getElementById('bookingPrice').innerText;

    $.ajax({
        type: "POST",
        url: "submit_inquiry.php", // Replace with your actual endpoint
        data: { userName, idCard, email, phone, price },
        success: function(response) {
            alert("Booking confirmed!");
            closeModal('bookingModal');
        },
        error: function() {
            alert("There was an error confirming your booking.");
        }
    });
}

<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>

function shareLink(place) {
    const url = `https://example.com/packages?trip=${place}`; // Update with your actual sharing URL
    navigator.clipboard.writeText(url).then(() => {
        alert("Link copied to clipboard: " + url);
    }, (err) => {
        console.error('Could not copy text: ', err);
    });
}
</script>

<style>
.row {
    display: flex;
    flex-wrap: wrap;
}

.swiper-slide {
    border: 1px solid #ddd;
    border-radius: 8px;
    margin: 10px;
    overflow: hidden;
    transition: transform 0.3s; 
    width: 100%; 
    max-width: 400px; 
}

.image {
    position: relative; 
}

.image img {
    width: 100%;
    height: auto;
}

.icons {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0; 
    transition: opacity 0.3s; 
}

.swiper-slide:hover .icons {
    opacity: 1; 
}

.icons a {
    color: gray;
    margin-left: 10px;
    font-size: 20px; 
}

.modal-content {
    border-radius: 8px; 
}

.modal-header {
    border-bottom: 1px solid #dee2e6; 
}

.modal-footer {
    border-top: 1px solid #dee2e6; 
}
</style>

<?php include('includes/footer.php'); ?>
