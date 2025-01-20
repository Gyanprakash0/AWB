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
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>AWB - User Login</title>

 
  <link rel="stylesheet" type="text/css" href="vendor/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../CSS/style.css">

 
  <link rel="shortcut icon" type="image/x-icon" href="images/logo.png">
<style>
  body {
            background-image: url('https://images6.alphacoders.com/132/1322314.jpeg'); 
            background-size: cover; 
            height:800px;
            background-repeat: no-repeat; 
            background-position: center; 
        }

      </style>
</head>

<body class="bg-light">

<?php
include 'includes/loader.php'; 

renderLoader(); 


?>








  <div class="container">
    <div class="row">
      <div class="col-md-4 offset-md-4">
        <div class="text-center mt-5">
          <img class="mb-2" src="images/logo.png" alt="" width="70" height="50">
        </div>
        <h1 class="text-center"> Welcome Back , Login</h1>
        <hr>
        <form id="login_form">
          <span id="login_error_message"></span>
          <div class="mb-3">
            <label for="username">Username *</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username"
              maxlength="50">
            <div id="username_error_message" class="text-danger"></div>
          </div>
          <div class="mb-3">
            <label for="password">Password *</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password"
              maxlength="50">
            <div id="password_error_message" class="text-danger"></div>
          </div>
          <hr class="mb-4">
          <input type="hidden" name="action" id="action" value="login_user">
          <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
          <div class="mt-2">
            <p><a href="register.php">Do not have an account yet? Register.</a></p>
          </div>
        </form>
        <p class="mt-5 mb-3 text-muted text-center">&copy; 2024 <a target="blank"
            href="https://www.hackerworld.net">AWB</p>
      </div>
      <div class="col-sm-4"></div>
    </div>
  </div>

  <!-- JQuery-3.4.1 -->
  <script src="vendor/jquery-3.4.1.min.js"></script>

  <script>
    $(document).ready(function () {

      $(document).keypress(function (e) {
        if (e.which == 13) {
          loginUser();
        }
      });

      $('#login_form').on('submit', function (event) {
        event.preventDefault();
        loginUser();
      });

      var error_username = false;
      var error_password = false;

      $("#username").focusout(function () {
        check_username();
      });
      $("#password").focusout(function () {
        check_password();
      });

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

      function check_password() {

        if ($.trim($('#password').val()) == '') {
          $("#password_error_message").html("Password is a required field.");
          $("#password_error_message").show();
          $("#password").addClass("is-invalid");
          error_password = true;
        } else {
          $("#password_error_message").hide();
          $("#password").removeClass("is-invalid");
        }
      }

      function loginUser() {

        error_username = false;
        error_password = false;

        check_username();
        check_password();

        if (error_username == false && error_password == false) {
          data = $('#login_form').serialize();
          $.ajax({
            type: "POST",
            data: data,
            url: "profile_action.php",
            dataType: "json",
            success: function (data) {
              if (data.status == 'inactive') {
                $('#login_error_message').html('<div class="alert alert-danger">' + data.error + '</div>');
              } else if (data.status == 'success') {
                window.location = "dashboard.php";
              } else if (data.status == 'error') {
                $('#login_error_message').html('<div class="alert alert-danger">' + data.error + '</div>');
              }
            },
            error: function () {
              alert("Oops! Something went wrong.");
            }
          });
        } else {
          $('#login_error_message').html('<div class="alert alert-danger">Incorrect username or password.</div>');
        }
      }
    });
  </script>



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