<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>AWB-USER Registration</title>

    <!-- Bootstrap v4.4.1 -->
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/bootstrap.min.css">

    <!-- favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.jpg">

    <style>
 body {
            background-image: url('https://images6.alphacoders.com/132/1322314.jpeg'); /* Update the path to your image */
            background-size: cover; 
            height:800px;/* Cover the entire background */
            background-repeat: no-repeat; /* Do not repeat the image */
            background-position: center; /* Center the image */
        }

    </style>
</head>
<?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>

<body class="bg-light">

<?php
include 'includes/loader.php'; 

renderLoader(); 


?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="text-center mt-2">
                    <img class="mb-2" src="logo.png" alt="" width="70" height="50">
                </div>
                <h1 class="text-center">Register</h1>
                <hr>
                <form id="register_form">
                    <p class="lead">You can register an account here to login.</p>
                    <div id="alert_error_message" class="alert alert-danger collapse" role="alert">
                        <i class="fa fa-exclamation-triangle"></i>
                        Please check in on some of the fields below.
                    </div>
                    <div id="alert_sucess_message" class="alert alert-success collapse" role="alert">
                        New user successfully created. <a href="login.php" class="alert-link">Click here to login.</a>
                    </div>
                    <div class="mb-3">
                        <label for="fullname">Full Name *</label>
                        <input type="text" class="form-control" id="fullname" name="fullname" maxlength="50"
                            placeholder="Enter full name">
                        <div id="fullname_error_message" class="text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="username">Username *</label>
                        <input type="text" class="form-control" id="username" name="username" maxlength="50"
                            placeholder="Enter username">
                        <div id="username_error_message" class="text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" maxlength="100"
                            placeholder="Enter email">
                        <div id="email_error_message" class="text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label>Gender *</label>
                        <select name="gender" id="gender" class="custom-select">
                            <option value="" hidden>Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                        </select>
                        <div id="gender_error_message" class="text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password">Password *</label>
                        <input type="password" class="form-control" id="password" name="password" maxlength="50"
                            placeholder="Enter password">
                        <div id="password_error_message" class="text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm-password">Confirm Password *</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            maxlength="50" placeholder="Enter confirm password">
                        <div id="confirm_password_error_message" class="text-danger"></div>
                    </div>
                    <hr class="mb-4">
                    <input type="hidden" name="action" id="action" value="register_user">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Register</button>
                    <div class="mt-2">
                        <p><a href="login.php">Already registered? Click here to login.</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JQuery-3.4.1 -->
    <script src="vendor/jquery-3.4.1.min.js"></script>

    <script>
        $(document).ready(function () {

            $(document).keypress(function (e) {
                if (e.which == 13) {
                    registerUser();
                }
            });

            $('#register_form').on('submit', function (event) {
                event.preventDefault();
                registerUser();
            });

            var error_fullname = false;
            var error_username = false;
            var error_email = false;
            var error_gender = false;
            var error_password = false;
            var error_confirm_password = false;

            $("#fullname").focusout(function () {
                check_fullname();
            });

            $("#username").focusout(function () {
                check_username();
            });

            $("#email").focusout(function () {
                check_email();
            });

            $("#gender").focusout(function () {
                check_gender();
            });

            $("#password").focusout(function () {
                check_password();
            });

            $("#confirm_password").focusout(function () {
                check_confirm_password();
            });

            function check_fullname() {
                if ($.trim($('#fullname').val()) == '') {
                    $("#fullname_error_message").html("Fullname is a required field.");
                    $("#fullname_error_message").show();
                    $("#fullname").addClass("is-invalid");
                    error_fullname = true;
                } else {
                    $("#fullname_error_message").hide();
                    $("#fullname").removeClass("is-invalid");
                }
            }

            function check_username() {
                if ($.trim($('#username').val()) == '') {
                    $("#username_error_message").html("Username is a required field.");
                    $("#username_error_message").show();
                    $("#username").addClass("is-invalid");
                    error_username = true;
                } else {
                    $("#username_error_message").hide();
                    $("#username").removeClass("is-invalid");
                }
            }

            function check_email() {
                var pattern = new RegExp(/^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i);
                var email_length = $("#email").val().length;

                if ($.trim($('#email').val()) == '') {
                    $("#email_error_message").html("Email is a required field.");
                    $("#email_error_message").show();
                    $("#email").addClass("is-invalid");
                } else if (!(pattern.test($("#email").val()))) {
                    $("#email_error_message").html("Invalid email address");
                    $("#email_error_message").show();
                    error_email = true;
                    $("#email").addClass("is-invalid");
                } else {
                    $("#email_error_message").hide();
                    $("#email").removeClass("is-invalid");
                }
            }

            function check_gender() {
                if ($.trim($('#gender').val()) == '') {
                    $("#gender_error_message").html("Gender is a required field.");
                    $("#gender_error_message").show();
                    $("#gender").addClass("is-invalid");
                    error_gender = true;
                } else {
                    $("#gender_error_message").hide();
                    $("#gender").removeClass("is-invalid");
                }
            }
            function check_password() {
                var password_length = $("#password").val().length;

                if ($.trim($('#password').val()) == '') {
                    $("#password_error_message").html("Password is a required field.");
                    $("#password_error_message").show();
                    $("#password").addClass("is-invalid");
                    error_password = true;
                } else if (password_length < 8) {
                    $("#password_error_message").html("Please enter at least 8 characters!");
                    $("#password_error_message").show();
                    error_password = true;
                    $("#password").addClass("is-invalid");
                } else {
                    $("#password_error_message").hide();
                    $("#password").removeClass("is-invalid");
                }
            }

            function check_confirm_password() {
                var password = $("#password").val();
                var confirm_password = $("#confirm_password").val();

                if ($.trim($('#confirm_password').val()) == '') {
                    $("#confirm_password_error_message").html("Confirm password is a required field.");
                    $("#confirm_password_error_message").show();
                    $("#confirm_password").addClass("is-invalid");
                    error_confirm_password = true;
                } else if (password != confirm_password) {
                    $("#confirm_password_error_message").html("Passwords do not match!");
                    $("#confirm_password_error_message").show();
                    error_confirm_password = true;
                    $("#confirm_password").addClass("is-invalid");
                } else {
                    $("#confirm_password_error_message").hide();
                    $("#confirm_password").removeClass("is-invalid");
                }
            }

            function registerUser() {

                error_fullname = false;
                error_username = false;
                error_email = false;
                error_gender = false;
                error_password = false;
                error_confirm_password = false;

                check_fullname();
                check_username();
                check_email();
                check_gender();
                check_password();
                check_confirm_password();

                if (error_fullname == false && error_username == false && error_email == false && error_gender == false && error_password == false && error_confirm_password == false) {

                    data = $('#register_form').serialize();
                    $.ajax({
                        type: "POST",
                        data: data,
                        url: "profile_action.php",
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 'success') {
                                $("#alert_sucess_message").show();
                                $("#alert_error_message").hide();
                                $('#register_form')[0].reset();
                            } else if (data.status == 'error') {
                                $("#username_error_message").html("Username already exists");
                                $("#username_error_message").show();
                            }
                        },
                        error: function () {
                            alert("Oops! Something went wrong.");
                        }
                    });
                    return false;
                } else {
                    $("#alert_sucess_message").hide();
                    $("#alert_error_message").show();
                    return false;
                }
            }
        });
    </script>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const loader = document.getElementById('loader');
        const mainContent = document.getElementById('main-content');
        const dots = document.querySelectorAll('.dot');
        
        // Show the loader for the duration of the animation (2 seconds)
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
            loader.style.display = 'none'; // Hide loader
            mainContent.classList.remove('hidden'); // Show main content
        }, 2000); // Change this duration if needed
    });
</script>
<script src="//code.tidio.co/j6lqhoaeshlexqdor89digjetxmd8drj.js" async></script>

</body>

</html>