
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
session_start();
include('includes/header.php');
include('connection.php'); 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, query) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $query);


    $name = $_POST['name'];
    $email = $_POST['email'];
    $query = $_POST['query'];

    if ($stmt->execute()) {
        
        $successMessage = "Your message has been sent successfully!";
    } else {
       
        $errorMessage = "Error: " . $stmt->error;
    }

    
    $stmt->close();
}


$conn->close();
?>

<div class="jumbotron jumbotron-fluid bg-light text-center">
    <div class="container">
        <h1 class="display-4">Welcome!</h1>
        <p class="lead text-primary font-weight-bold"><?php echo $_SESSION['fullname']; ?></p>
        <hr class="my-4">

        
        <ul class="nav nav-tabs justify-content-center" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="about-tab" data-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="true">About Us</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Contact</a>
            </li>
        </ul>

        
        <div class="tab-content" id="myTabContent">
            
            <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="about-tab">
                <div class="card mt-3 p-3">
                    <h2>About AWB</h2>
                    <p>AWB is a premier travel company dedicated to providing a seamless travel experience for our customers. Our comprehensive platform offers services that cater to all your travel needs, including flight bookings, tour packages, and travel advice. Established in Ghaziabad, our mission is to simplify the travel process and make your journeys enjoyable and hassle-free.</p>
                    <p>With our user-friendly app, you can explore various destinations, book your flights, and find the best holiday packages tailored just for you. Our experienced team is committed to providing personalized customer support, assisting you at every step of your journey. Whether you're planning a family vacation or a solo adventure, we ensure that every trip is memorable. Join us and embark on your next adventure with AWB!</p>
                </div>
            </div>

            
            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <div class="card mt-3 p-3">
                    <h2>Contact Us</h2>

                    <?php if (isset($successMessage)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $successMessage; ?>
                        </div>
                    <?php elseif (isset($errorMessage)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $errorMessage; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <table class="table">
                            <tr>
                                <td><label for="name">Name:</label></td>
                                <td><input type="text" class="form-control" id="name" name="name" required></td>
                            </tr>
                            <tr>
                                <td><label for="email">Email:</label></td>
                                <td><input type="email" class="form-control" id="email" name="email" required></td>
                            </tr>
                            <tr>
                                <td><label for="query">Query:</label></td>
                                <td><textarea class="form-control" id="query" name="query" required></textarea></td>
                            </tr>
                        </table>
                        <button type="submit" class="btn btn-primary rounded">Submit</button>
                    </form>
                    <hr>
                    <h4>Contact Information</h4>
                    <p>Email: <a href="mailto:gyanprakash@awb.com">admin@awb.com</a></p>
                    <p>Contact Number: +91-1234567890</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"></script>

<style>
.card {
    margin-top: 20px;
}
</style>
<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>