<?php

    $servername="localhost";
    $username="root";
    $password="password";
    $dbname="awb_";

    
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    
    if(!$conn){
        die("Connection failed: ".mysqli_connect_error());
    }

 ?>
 <?php
/*
  ************************************************************
  *                        AWB                                 *
  *                       Copyright                              *
  *   Gyan Prakash Pandey   *   Ansh Shukla   *   Shiwan Tripathi   *
  *   Code is open source, but please do not remove this credit. *
  ************************************************************
*/?>